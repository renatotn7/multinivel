<?php

/**
 * Description of FormaPagamento
 *
 * @author Ronyldo12
 */
class FormaPagamento{
   
    private $db;
    
    public function __construct() {
        $this->db =& get_instance()->db;
    }
    
    public static function getFormasPagamentosAtivas(){
       $payments = get_instance()->db->where('status',1)->get('loja_payment_method')->result();
       return $payments;
    }
    
    
    public static function getFormaPagamento($payment_method_id){
       $payment = get_instance()->db->where('payment_method_id',$payment_method_id)->get('loja_payment_method')->row();
       return $payment; 
    }
    
    
}
