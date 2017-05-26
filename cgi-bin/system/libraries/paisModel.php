<?php

class paisModel extends CI_Controller{
 
    public function db() {
        return parent::get_instance();
    }
    
     public static function getPais($ps_id = 0) {
        if (!empty($ps_id)) {
            self::db()->db->where('pr_id', $ps_id);
        }
        
        $paises = self::db()->db->group_by('ps_iso3')->get('pais');

        if (!empty($ps_id)) {
            return $paises->row();
        }
        return $paises->result();
    } 
    
    /**
     * Retorna o Pais do distribuidor
     * @param type $di_id
     * @return type
     */
    public static function getPaisDistribuidor($di_id=0){
        if(empty($di_id)){
            return array();
        }
        
       return  self::db()->db->where('di_id',$di_id)
                      ->join('cidades','ci_id=di_cidade')
                      ->join('pais','ps_id=ci_pais')
                      ->get('distribuidores')->row();
        
    }
    
    public static function taxa($id_pais=0){
        if(!empty($id_pais)){
            self::db()->db->where('camb_id_pais',$id_pais);
        }
        
      $taxas =  self::db()->db->join('moedas','moe_id=camb_id_moedas')
                     ->join('pais','ps_id=camb_id_pais')
                      ->get('moeda_cambio');
      
      if(!empty($id_pais)){
          return $taxas->row();
      }
      
      return $taxas->result();
    }
    
}