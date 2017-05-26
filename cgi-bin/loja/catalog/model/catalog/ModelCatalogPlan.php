<?php

/**
 * Description of ModelCatalogPlan
 *
 * @author Ronyldo12
 */
class ModelCatalogPlan extends Model {

    public function getTemPlanoDistribuidor($distribuidor_id) {
        //VERIFICA SE O DISTRIBUIDOR TEM PLANO
        $planoDistribuidor = $this->db
                        ->query("SELECT * FROM registro_planos_distribuidor 
                        WHERE ps_distribuidor=" . $distribuidor_id . "")->num_rows;

        if ($planoDistribuidor > 0) {
            return true;
        }

        return false;
    }

}
