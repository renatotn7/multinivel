<?php

class PlanosModel extends CI_Controller{

    public function db() {
        return parent::get_instance();
    }
    
    /**
     * Retorna o plano ou planos.
     * @param type $idPlano
     * @return type
     */
    public static function getPlano($idPlano = 0) {
        if (!empty($idPlano)) {
            self::db()->db->where('pa_id', $idPlano);
        }
        $planos = self::db()->db->get('planos');

        if (!empty($idPlano)) {
            return $planos->row();
        }
        return $planos->result();
    }
    
     public static function getPlanoDistribuidor($idDistribuidor){
        $plano = self::db()->db
                ->select(array('sql_cache planos.*'),false)
                ->where('ps_distribuidor',$idDistribuidor)
                ->join('planos','pa_id=ps_plano')
                ->order_by('ps_plano','DESC')
                ->get('registro_planos_distribuidor',1)->row();
        return $plano;
    }
    
     public static function getPlanoDistribuidorNaoPago($idDistribuidor=0){
       
        $plano = self::db()->db
                ->query("select planos.* from distribuidores 
                            join compras on co_id_distribuidor  = di_id  and co_eplano=1
                            join planos  on co_id_plano = pa_id
                            where co_id_distribuidor={$idDistribuidor} and co_eupgrade =0")->row();
        
        return $plano;
    }

}
