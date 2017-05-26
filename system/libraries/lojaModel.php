<?php
class lojaModel extends CI_Controller{
     
   public function db(){
       return parent::get_instance();
   }
   
   public static function comprarProduto($pr_id=0){
      
    if(empty($pr_id)){
        return false;   
       }
       
    $produto = self::db()->db->where('pr_id',$pr_id)
                           ->get('produtos')->row();
    
     if(count($produto)==0){
     return false;     
     }
     
    self::db()->db->insert('compras',array(
        'co_id_distribuidor'=>  get_user()->di_id,
        'co_total_valor'=> $produto->pr_valor,
        'co_situacao'=> 5,
        'co_total_pontos'=>0
       ));
    
    $compra_id = self::db()->db->insert_id();
    
    self::db()->db->insert('produtos_comprados',array(
       'pm_id_compra'=>$compra_id,
       'pm_id_produto'=>$produto->pr_id,
       'pm_quantidade'=>1,
       'pm_pontos'=>0,
       'pm_valor'=> $produto->pr_valor,
       'pm_valor_total'=> $produto->pr_valor,
    ));
    
    return $compra_id;
   }
}