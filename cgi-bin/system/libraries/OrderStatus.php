<?php


/**
 * Description of OrderStatus
 *
 * @author Ronyldo12
 */
class OrderStatus {
   
    public static function getStatus(){
        $status = get_instance()->db->get('loja_order_status')->result();
        return $status;
    }
    
    public static function getStatusAtual($order_status_id){
        if($order_status_id <> 0){
            $status = get_instance()->db->where('order_status_id', $order_status_id)->get('loja_order_status')->row()->name;
            return $status;
        }
        else{
            return 'Nova';
        }
    }    
}
