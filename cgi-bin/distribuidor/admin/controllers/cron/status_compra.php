<?php

/**
 * Atualiza todo o historico das compras e pagas com plataform pay 
 * 
 */
set_time_limit(0);

class status_compra extends CI_Controller {

    public function index() {

        $atm = new atm();

        $distribuidores = $this->db
                        ->select(array(
                            'co_id',
                            'sa_id_compra',
                            'sa_id',
                            'sa_numero','sa_status'
                        ))
                        ->join('compras', 'co_id_distribuidor=di_id')
                        ->join('compras_sales', 'sa_id_compra=co_id')
                        ->get('distribuidores')->result();

        foreach ($distribuidores as $key => $distribuidor_value) {
            if (!in_array($distribuidor_value->sa_status, array(3, 4, 6, ''))) {
                continue;
            }
            ComprasModel::logSalesTransacoes($distribuidor_value);
            sleep(2);
        }
    }

}
