<?php

class Relatorio_financeiro extends CI_Controller{
		
	public function relatorio(){ 
		autenticar();
		$custoProduto = $this->db->where('field', 'custo_base_produto')->get('config')->row();
                
                $de  = date('01/m/Y');
                $ate = date('d/m/Y');
                        
                if(isset($_REQUEST['de'])){			 
		 $de = get_parameter('de');
                }
                
               if(isset($_REQUEST['ate'])){	
	         $ate = get_parameter('ate');
                }
		 
		$faturamento = $this->db->query("
		 SELECT 
                 SUM( pm_quantidade ) AS lingotes,  SUM(pm_valor), ( SUM( pm_quantidade ) * ( SELECT valor FROM config WHERE field = 'custo_base_produto' ) ) + SUM( pm_quantidade ) * cp_valor_frete AS totalComFrete, 
                 SUM(pm_valor_total) AS faturamentoTotal 
                 FROM produtos_comprados 
                 JOIN compras ON co_id = pm_id_compra 
                 LEFT JOIN custo_produto ON cp_uf = co_entrega_uf 
                 WHERE co_pago =1 AND co_data_compra >= '".data_to_usa($de)." 00:00:00' AND co_data_compra <= '".data_to_usa($ate)." 23:59:59'	
                 GROUP BY co_entrega_uf
		 ")->result();
		
	        $totalComFrete    = 0;
		$faturamentoTotal = 0;
		$num_lingotes = 0;
		$f=array();
		foreach($faturamento as $f){
		 $totalComFrete    += $f->totalComFrete;
		 $faturamentoTotal += $f->faturamentoTotal;
		 $num_lingotes     += $f->lingotes;
                 continue;
		}
				
		 /*Despesa Produto*/
		 $data['despesaProduto'] = $totalComFrete;
		 
		 /*Total Faturamento*/
		 $data['totalFaturamento'] = $faturamentoTotal; 
		 $data['lingotes'] = $num_lingotes;  
		  	
		 /*Reserva Financeira(10% do faturamento)*/
                $reserva = $this->db->where('field', 'reserva_financeira')->get('config')->row();
                $data['reservaFinaceira'] = $faturamentoTotal*$reserva->valor;
                /*End*/
 
		 /*Despesas Operacionais(5% do faturamento)*/
		  $despesaOp = $this->db->where('field', 'despesas_operacionais')->get('config')->row();
		  $data['despesaOperacional'] = $faturamentoTotal*$despesaOp->valor; 
		 /*End*/
 
		 /*Despesas Qualifica��es(5% do faturamento)*/
		  $despesaQ = $this->db->where('field', 'despesas_qualificacao')->get('config')->row();
		  $data['despesaQualificacao'] = $faturamentoTotal*$despesaQ->valor;
		 /*End*/
 
		 /*Despesas Publicidade(5% do faturamento)*/
		  $despesaP = $this->db->where('field', 'despesas_publicidade')->get('config')->row();
		  $data['despesaPublicidade'] = $faturamentoTotal*$despesaP->valor;
		 /*End*/
		 
		 /*Custo Financeiro(6% do faturamento)*/
		  $custoF = $this->db->where('field', 'custo_financeiro')->get('config')->row();
		  $data['custoFinanceiro'] = $faturamentoTotal*$custoF->valor;
		 /*End*/
		 
		 /*Despesas com B�nus*/
		  
		  $data['bonusGerado'] = $this->db
		                              ->query("
           SELECT SUM(cb_credito) as total FROM conta_bonus		   
           WHERE cb_tipo IN(SELECT tb_id FROM bonus_tipo)
		   AND cb_data_hora >= '".data_to_usa($de)." 00:00:00'
		   AND cb_data_hora <= '".data_to_usa($ate)." 23:59:59'"
            )->row();

		 /*End*/	
		
                $data['f'] = $f;
	 	$data['pagina'] = "relatorio_financeiro/index";
		$this->load->view('home/index_view',$data);
	}
	
	public function conversao(){
	 $custo      = $this->db->get('custo_produto')->result();
	 $valorDolar = $this->db->where('field', 'cotacao_dolar')->get('config')->row();
	 $valorDolar = str_ireplace('R$','',$valorDolar->valor);
	 $valorDolar = str_ireplace(' ','',$valorDolar);
	 $valorDolar = str_ireplace(',','.',$valorDolar);

	 foreach($custo as $c){	
	   $this->query_conversao($c->cp_id, 500);
     }	
   	
	}
	
	
	public function query_conversao($id,$campo){
	    $this->db
		     ->where('cp_id', $id)
		     ->set('cp_valor', $campo)
			 ->update('custo_produto');	
	}	
	

	 
	public function sistema(){
		 autenticar();
		 $this->load->library('CalculaPontosPagos');
		 $data['pontos'] = $this->calculapontospagos;
		 $this->load->view('relatorio_financeiro/sistema_view');
		} 
		
	public function  devolver_binario(){
		 $this->load->view('relatorio_financeiro/devolver_binario_view');
		}

	public function  estorno(){
		 $this->load->view('relatorio_financeiro/estornado_binario_view');
		}
	 
}

?>	