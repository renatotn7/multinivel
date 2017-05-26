<?php

class upgradeModel extends CI_Controller {

    public function db() {
        return parent::get_instance();
    }

    public static function RealizarUpgrade() {

    }

    public static function RealizarUpgradeAutomatico($idDistribuidor = 0) {

        if (empty($idDistribuidor)) {
            return false;
        }
        $planosAutomaticos =array();

        $distribuidor = get_user();
        //pegando o plano atual do distribudor
        $plano = PlanosModel::getPlanoDistribuidor($idDistribuidor);
        //Pegando proximo plano.
        $planoAdiquirir = self::getProximoPlano($idDistribuidor);
        //Verificar se o distribuidor tem saldo para upgrade do proximo plano.
        $saldo = SaldoVirtual::getSaldo($idDistribuidor);

        if(count($plano)==0){
            return false;
        }

        if(!in_array($plano->pa_id,$planosAutomaticos)){
           return false;
        }

        if (count($planoAdiquirir) == 0) {
            return false;
        }

        if ($saldo < $planoAdiquirir->pa_valor) {
            return false;
        }

        if (count($distribuidor) == 0) {
            return false;
        }


        //Plano upgrade.
        $plano_valores = self::db()->db->where('pug_id_plano', $plano->pa_id)
                ->where('pug_id_plano_upgrade', $planoAdiquirir->pa_id)
                ->get('planos_upgrades')
                ->row();

        $co_descricao = "Upgrade do plano {$plano->pa_descricao} para o plano {$planoAdiquirir->pa_descricao}";
        //Verificando se não tem nenhum upgrade pendente
       $upgradePendente= self::db()->db->where('co_id_distribuidor',$distribuidor->di_id)
                      ->where('co_pago',0)
                      ->where('co_eupgrade',1)->get('compras')->row();


        if(count($upgradePendente)>0){
            return false;
        }

        //gerando uma nova comprar do plano do distribuidor.
        self::db()->db->insert('compras', array(
            'co_tipo' => 1,
            'co_entrega' => 1,
            'co_id_distribuidor' => $distribuidor->di_id,
            'co_entrega_cidade' => $distribuidor->di_cidade,
            'co_entrega_uf' => $distribuidor->di_uf,
            'co_entrega_bairro' => $distribuidor->di_bairro,
            'co_entrega_cep' => $distribuidor->di_cep,
            'co_entrega_complemento' => $distribuidor->di_complemento,
            'co_entrega_numero' => $distribuidor->di_numero,
            'co_entrega_logradouro' => $distribuidor->di_endereco,
            'co_total_pontos' => $plano_valores->pug_pontos,
            'co_situacao' => 7,
            'co_id_plano' => $planoAdiquirir->pa_id,
            'co_eplano' => 1,
            'co_descricao' => $co_descricao,
            'co_pago' => 1,
            'co_eupgrade' => 1,
            'co_forma_pgt' => 1,
            'co_hash_boleto' => criar_hash_boleto(),
            'co_total_valor' => $plano_valores->pug_valor,
            'co_data_insert' => date('Y-m-d H:i:s')
        ));

        $id_compra = self::db()->db->insert_id();

        //Salvar o compra em produtos_comprados
        self::db()->db->insert('produtos_comprados', array(
            'pm_id_compra' => $id_compra,
            'pm_valor' => $plano_valores->pug_valor,
            'pm_quantidade' => 1,
            'pm_tipo' => 1,
            'pm_pontos' => $plano_valores->pug_pontos,
            'pm_id_produto' => $plano_valores->pug_produto,
            'pm_valor_total' => $plano_valores->pug_valor,
        ));

        //Salvando o novo registro do plano novo
        self::db()->db->insert('registro_planos_distribuidor', array(
            'ps_plano' => $planoAdiquirir->pa_id,
            'ps_distribuidor' => $distribuidor->di_id,
            'ps_compra' => $id_compra,
            'ps_valor' => $plano_valores->pug_valor,
        ));

        //Criando debitando o valor
        self::db()->db->insert('conta_bonus', array(
            'cb_tipo' => 270,
            'cb_descricao' => $co_descricao,
            'cb_debito' => $plano_valores->pug_valor,
            'cb_distribuidor' => $distribuidor->di_id,
            'cb_compra' => $id_compra,
        ));

        return true;
    }

    public static function getProximoPlano($idDistribuidor = 0) {

        if (empty($idDistribuidor)) {
            return array();
        }

        $planos = self::db()->db->where("pa_id > (SELECT ps_plano FROM registro_planos_distribuidor"
                                . "  WHERE ps_distribuidor = '{$idDistribuidor}' ORDER BY ps_plano desc limit 1)")
                        ->get('planos')->row();

        return $planos;
    }

}

?>