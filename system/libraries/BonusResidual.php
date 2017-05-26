<?php

class BonusResidual {

    private $ci;
    private $limiteDiario;

    function __construct() {
        $this->ci = & get_instance();
        $limit = $this->ci->db->where('field', 'valor_maximo_diario')->get('config')->row();
        $this->limiteDiario = (float) $limit->valor;
    }

    public function pagar_bonus($compra, $plano, $id_ativacao) {

        $distribuidorDAO = new DistribuidorDAO();

        $objDistribuidor = $distribuidorDAO->getById($compra->co_id_distribuidor);

        $objPatrocinadorAtual = $distribuidorDAO->getById($objDistribuidor->getPatrocinador()->getId());



        $geracao = 1;
        while ($geracao <= 10) {

            //Verifica para quem vai pagar o Bônus, distribuidor ou industria
            if ($objPatrocinadorAtual->getAtivo() == 1 && $objPatrocinadorAtual->getId() != 0) {

                $valorBonusAPagar = $this->valor_bonus($plano->pa_mensal, $objPatrocinadorAtual->getPlano()->getValorResidual());

                //Registrando o pagamento do bônus residual
                $this->ci->db->insert('registro_bonus_residual_pago', array(
                    'rb_distribuidor' => $objDistribuidor->getId(),
                    'rb_receptor' => $objPatrocinadorAtual->getId(),
                    'rb_ativacao' => $id_ativacao,
                    'rb_compra' => $compra->co_id,
                    'rb_posicao' => $geracao,
                    'rb_valor' => $valorBonusAPagar,
                    'rb_data' => date('Y-m-d H:i:s')
                ));

                $valorBonusResidual = LimiteGanho::paraCPF($objDistribuidor->getCpf(), $valorBonusAPagar, $this->limiteDiario);

                //Montar a descrição do bônus
                if ($valorBonusAPagar != $valorBonusResidual) {
                    $descicaoBonus = '
					   Bônus Residual <b>' . $objDistribuidor->getUsuario() . '</b><br>
					   Você atingiu o limite de <b>US$ ' . number_format($this->limiteDiario, 2, ',', '.') . '</b> de bonificação diária.
					  ';
                } else {
                    $descicaoBonus = 'Bônus Residual <b>' . $objDistribuidor->getUsuario() . '</b>';
                }

                //Registrando o bônus na conta do distribuidor  
                $this->ci->db->insert('conta_bonus', array(
                    'cb_distribuidor' => $objPatrocinadorAtual->getId(),
                    'cb_descricao' => $descicaoBonus,
                    'cb_credito' => $valorBonusResidual,
                    'cb_tipo' => 2
                ));

                $idContaBonus = $this->db->insert_id();

                //Se tiver bonus exedente registra
                if ($valorBonusAPagar != $valorBonusResidual) {

                    $this->db->insert('registro_ganho_limite_diario', array(
                        'gl_distribuidor' => $objPatrocinadorAtual->getId(),
                        'gl_id_conta_bonus' => $idContaBonus,
                        'gl_descricao' => "Ganhos de Bônus Residual excedente.",
                        'gl_valor' => ($valorBonusAPagar - $valorBonusResidual),
                        'gl_data' => date('Y-m-d H:i:s')
                    ));
                }


                //Incrementando a geração
                $geracao++;
            }

            //O proximo patrocinador atual
            //Muda o patrocinador atual
            if ($objPatrocinadorAtual->getId() != 0) {
                $objPatrocinadorAtual = $distribuidorDAO->getById($objPatrocinadorAtual->getPatrocinador()->getId());
            } else {
                $geracao = 7;
            }
        }
    }

    private function valor_bonus($valor_plano, $percentual) {
        return $valor_plano * $percentual / 100;
    }

}
