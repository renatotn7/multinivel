<?php

/**
 * Description of ModelCatalogPlan
 *
 * @author Ronyldo12
 */
class ModelCatalogPlanos extends Model {

    public function getTemPlanoDistribuidor($customer_id) {
        //VERIFICA SE O DISTRIBUIDOR TEM PLANO
        $planoDistribuidor = $this->db
                        ->query("SELECT * FROM registro_planos_distribuidor 
                           WHERE ps_distribuidor='" . $customer_id . "' ORDER BY ps_distribuidor DESC")->num_rows;

        if ($planoDistribuidor > 0) {
            return true;
        }
        return false;
    }

    public function getTemOpcoesProduct($product_id) {
        //Verificar se a opção para o produto
        $opcoes = $this->db->query("SELECT * FROM loja_product_option WHERE product_id={$product_id}")->row;
        if (count($opcoes) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getPlano($customer_id) {
        //VERIFICA SE O DISTRIBUIDOR TEM PLANO
        $planoDistribuidor = $this->db
                        ->query("SELECT * FROM registro_planos_distribuidor 
                           WHERE ps_distribuidor=" . $customer_id . " ORDER BY ps_distribuidor DESC LIMIT 1")->row;

        return $planoDistribuidor;
    }

    public function getStockPlanos($product_id) {
        $stock = $this->db->query("SELECT SUM(input-output) as total FROM loja_stock WHERE product_id={$product_id}")->row;
        return $stock;
    }

}
