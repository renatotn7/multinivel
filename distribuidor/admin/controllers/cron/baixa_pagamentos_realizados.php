<?php
/*
 * Cron de verificação de baixa de pagamento, 
 * essa CRON tem por medida, finalizar todos os pagamentos que por algum 
 * motivo ocasionou algum erro no meio do processo e não chegou a finalizar o 
 * pagamento, mas tem dado baixa na Plataforma de Pagamento.
 */
 class baixa_pagamentos_realizados extends CI_Controller{
     
     public function run()
     {
         set_time_limit(0);
       $compras = $this->db->where('co_pago', 0)
                          ->join('compras_sales','sa_id_compra=co_id')
                           ->get('compras')->result();
       
        foreach ($compras as $compra) {
            
            $atm = new atm();
            $resposta = json_decode($atm->estado_pagamento($compra));
            echo "<h3>compra id :{$compra->co_id} --- status: $resposta->status  </h3>";
            //Se status igual a 0 finaliza a compra

            if ($resposta->status == 0) {
                //Finalizar a compra
                $pagamento = new Pagamento();
                $pagamento->realizarPagamento(new PagamentoATM($compra));
            }
            
        }//Fim for
        
        
     }
 }