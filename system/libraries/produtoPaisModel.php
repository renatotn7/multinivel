<?php

class produtoPaisModel extends CI_Controller {

    public function db() {
        return parent::get_instance();
    }

    public static function getProdutoPais($id_pais = 0) {
        if (!empty($id_pais)) {
            self::db()->db->where('ps_id', $id_pais);
        }
        $comboPais = self::db()->db->join('produtos_valores_pais', 'pr_id=prv_id_produto')
                                   ->join('pais', 'ps_id=prv_id_pais')
                                   ->get('produtos');

        if (!empty($id_pais)) {
            return $comboPais->row();
        }
        return $comboPais->result();
    }
    
     public static function addProduto($produtos=array()){
       return  self::db()->db->insert('produtos_valores_pais', 
                                      funcoesdb::valida_fields('produtos_valores_pais',$produtos));
     }
     
     public static function removerProduto($id_produto=0){
         if(empty($id_produto)){
             return false;
         }
         
         return self::db()->db->where('prv_id',$id_produto)->delete('produtos_valores_pais');
     }

}
