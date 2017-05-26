<?php

/**
 * Description of AtivacaoMensalAutomatica
 *
 * @author Ronyldo12
 */
class AtivacaoMensalAutomatica {

    private $db;

    public function __construct() {
        set_time_limit(0);
        error_reporting(1);
        $this->db = get_instance()->db;
    }

    public function run() {

        $seisMesesAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 180, date('Y')));
        $trintaDiasAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 30, date('Y')));

        $distribuidores = $this->db
                        ->select('di_id,di_usuario,di_nome,co_data_compra, di_cidade, di_uf, di_data_cad,di_niv,di_cidade')
                        ->join('(SELECT DISTINCT co_id_distribuidor,co_id,co_data_compra FROM compras WHERE co_pago = 1 AND co_eplano = 1 ORDER BY co_data_compra ASC) as primeira_compra', 'di_id = co_id_distribuidor')
                        ->where('co_data_compra >= ', $seisMesesAtras)
                        ->where('co_data_compra <= ', $trintaDiasAtras)
                        ->order_by("co_data_compra", 'asc')
                        ->get('distribuidores')->result();

        echo count($distribuidores) . '<br>';

        foreach ($distribuidores as $distribuidor) {

            $valorAtivacao = (float) ConfigSingleton::getValue("valor_ativacao_mensal");

            //Não pode deixar o saldo negativo.
            if ((contaBonusModel::saldo($distribuidor)->saldo - $valorAtivacao) > 0) {
                $this->ativacao_mensal_confirmar($distribuidor);
                continue;
            }

            $pais = DistribuidorDAO::getPais($distribuidor->di_cidade);
            if ($pais->ps_id == 1) {
                continue;
            }

            $saldo_atm = (float) atm::consultarSaldo($distribuidor);
            if ($saldo_atm == false) {
                continue;
            }

            $AtivacaoMensal = new AtivacaoMensal();
            $AtivacaoMensal->setDistribuidor($distribuidor);

            if ($AtivacaoMensal->checarAtivacao() == true) {
                continue;
            }

            if ((float) converte_moeda($saldo_atm) >= (float) number_format($valorAtivacao, 2)) {

                $compraAtivacaoMensal = ComprasModel::addCompraAtivacao($distribuidor->di_id);
                $status = 1;
                $post = array(
                    'paymentMethod' => '0',
                    'debitCardAccessCode' => '',
                    'c' => $compraAtivacaoMensal->co_id,);

                $sale = atm::builder_pamento_transparente($compraAtivacaoMensal, base_url('index.php/atm_pagamento/get_sale'), $post, '', array(), 'curl');

                $compras = $this->db->where('sa_id_compra', $compraAtivacaoMensal->co_id)
                        ->get('compras_sales')
                        ->result();

                $status = '';
                foreach ($compras as $compra_value) {

                    $atm = new atm();
                    $situacao = json_decode($atm->estado_pagamento($compra_value));

                    if ($situacao != false) {
                        if ($situacao->status < $status) {
                            $status = $situacao->status;
                        }
                    } else {
                        $status = '';
                        continue;
                    }
                }

                //se o status tiver tudo ok ai pode ativar
                if ($status == 0) {
                    $this->ativacao_mensal_confirmar($distribuidor, true);
                }
            }
        }
    }

    private function ativacao_mensal_confirmar($distribuidor, $ewallet = false) {

        echo "<p>Ativando $distribuidor->di_usuario : $distribuidor->di_id</p>";

        $AtivacaoMensal = new AtivacaoMensal();
        $AtivacaoMensal->setDistribuidor($distribuidor);

        if ($AtivacaoMensal->checarAtivacao() == true) {
            echo "<p> -- $distribuidor->di_id já esta ativo z</p>";
            var_dump($AtivacaoMensal->checarAtivacao());
            return false;
        }

        $valorAtivacao = (float) ConfigSingleton::getValue("valor_ativacao_mensal");

        if ($valorAtivacao == 0) {
            echo "<p> -- Valor da ativação zerado</p>";
            return false;
        }

        $ativacaoPendente = ComprasModel::addCompraAtivacao($distribuidor->di_id);

        echo "<p> -- Gerando a compra: $ativacaoPendente->co_id</p>";
        $idContaBonus = 0;

        //Se foi inserido pela e-wallepay
        if (!$ewallet) {

            $this->db->insert('conta_bonus', array(
                'cb_distribuidor' => $distribuidor->di_id,
                'cb_compra' => $ativacaoPendente->co_id,
                'cb_descricao' => 'Pagamento de ativação Nº ' . $ativacaoPendente->co_id,
                'cb_credito' => 0,
                'cb_debito' => $valorAtivacao,
                'cb_tipo' => 6,
                'cb_data_hora' => date('Y-m-d H:i:s')
            ));

            $idContaBonus = $this->db->insert_id();
            echo "<p> -- Pagamento realizado com saldo BackOffice</p>";
        } else {
            echo "<p> -- Pagamento realizado com saldo Plataforma de Pagamento</p>";
        }


        $this->db->where('co_id', $ativacaoPendente->co_id)->update('compras', array(
            'co_forma_pgt_txt' => ($ewallet ==false ? 'Bônus':'Saldo '. ConfigSingleton::getValue("name_plataforma_pagamento") .' (EwC ou EWC voucher)'),
            'co_forma_pgt' => ($ewallet ==false ? 3:17),
            'co_pago' => 1,
            'co_situacao' => 7,
            'co_data_compra' => date('Y-m-d H:i:s')
        ));

        $this->db->insert('registro_ativacao_mensal', array(
            'am_distribuidor' => $distribuidor->di_id,
            'am_compra' => $ativacaoPendente->co_id,
            'am_id_conta_bonus' => $idContaBonus,
            'am_data' => date('Y-m-d H:i:s')
        ));

        echo "<p> -- Lançando a ativação</p>";


        $ativacao = new Ativacao();
        $ativacao->lancar_ativacao($ativacaoPendente);
        echo "<p> -- Finalizando</p>";
    }

}
