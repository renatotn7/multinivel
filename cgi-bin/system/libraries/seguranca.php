<?php

class seguranca{
    
    public static function validar_senha_seguranca($pw ='',$distribuidor=array()){
        $ci= get_instance();
        if(empty($pw)){
            return false;
        }
        
        if(count($distribuidor)==0){
            return false;
        }
        
     $confirmacao = $ci->db->where('di_pw',sha1($pw))
                           ->where('di_id',$distribuidor->di_id)
                           ->get('distribuidores')
                           ->row();

      if(count($confirmacao)>0){
         return true;    
        }

        return false;
    }
}
