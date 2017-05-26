<?php

/**
 *  Evoucher
 *
 * @author Ronildo Souza <ronyldo12@hotmail.com>
 */
class Evoucher {

    public static function lancar($compra){
    	    $valor=0;
        if ($compra->co_eplano == 1 ||  $compra->co_evoucher == 1){

            $voucher = get_instance()->db->where('vo_id_compra', $compra->co_id)->get('compras_voucher')->row();
            if (count($voucher) == 0) {
            	
               if($compra->co_evoucher ==0){
               	$planoDistribuidor="";
                $planoDistribuidor = get_instance()->db->where('pa_id', $compra->co_id_plano)->get('planos')->row();
                @$valor= $planoDistribuidor->pa_produto;
     
               }else{
               	$valor=$compra->co_total_valor;
               }
                
                if(count($valor)>0){
                
                $has = substr(base64_encode(uniqid()), 0, 16);
                $codeVouche = strtoupper(substr($has, 0, 4) . '-' . substr($has, 4, 4) . '-' . substr($has, 8, 4) . '-' . substr($has, 12, 4));
                get_instance()->db->insert('compras_voucher', array(
                    'vo_valor' => $valor,
                    'vo_id_compra' => $compra->co_id,
                    'vo_id_distribuidor' => $compra->co_id_distribuidor,
                    'vo_data' => date('Y-m-d H:i:s'),
                    'vo_codigo' => $codeVouche
                ));
                }
            }
        }
    }

}

