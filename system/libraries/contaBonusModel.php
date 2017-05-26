<?php
class contaBonusModel{

    public  static function addSaldoUsuario($idDistribuidor=0,$cb_descricao='',$type='',$valor=0.00){
        
        if(empty($idDistribuidor)){
            return false;
        }
        
        if(empty($type)){
            return false;
        }
        
        if(empty($valor)){
            return false;
        }
       //Instanci o ci controle.  
        $ci = get_instance();
        
        $ci->db->insert('conta_bonus',array(
            'cb_distribuidor'=>$idDistribuidor,
            'cb_descricao'=>$cb_descricao,
            'cb_tipo'=>$type,
            'cb_credito'=>$valor,
        ));
        
        return true;
    }
    
    public  static function conta_bonus($distribuidor=array(),$type=''){
        $ci = get_instance();
         if(count($distribuidor) > 0){
          $ci->db->where('cb_distribuidor',$distribuidor->di_id);
         }
        
         if(empty($type)){
            $ci->db->where('cb_tipo',$type);
         }
            
        return $ci->db->get('conta_bonus')->result();
    }
    
    public static function saldo($distribuidor=array()){
        $ci = get_instance();
         if(count($distribuidor) > 0){
          $ci->db->where('cb_distribuidor',$distribuidor->di_id);
         }
        
        return $ci->db
                ->select('sum(cb_credito)- sum(cb_debito) as saldo')  
                ->get('conta_bonus')->row();
    }
  
}
