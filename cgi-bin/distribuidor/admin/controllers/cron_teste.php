<?php
set_time_limit(0);
class Cron_teste extends CI_Controller {
	
	public function index(){
		$distribuidores = $this->db->get('distribuidores')->result();
		
		/**
		 * pagamento das pernas 
		 * 
		 */
		foreach ($distribuidores as $distribuidor)
		{
			//Olhando a arvore de cada distribuidor.
			$pontos = new Pontos($distribuidor);
	        
			//Pegando o plano do distribuidor
			$plano  = $this->db
							->where('co_id_distribuidor',$distribuidor->di_id)
							->join('compras','co_id_plano=pa_id')
							->order_by('co_id','desc')
							->get('planos',1)->row();

			//Pegando a data da compra que o distribuidor adquiriu a compra.
			$compras = $this->db->where('co_id_distribuidor',$distribuidor->di_id)->get('compras')->row();

			//Vendo qual metodo será usado para calcular os pontos.
			if(strtotime ($compras->co_data_compra) > strtotime ("2014-03-27")){
				//calculando a percentual de acordo com o plano que ele adquiriu. Essa regra roda para as compras antes do dia 27
				$ponto_A_pagar = $pontos->get_pontos_perna_menor() - $pontos->get_pontos_pagos();
				$ponto_A_pagar = $ponto_A_pagar >0 ?($plano->pa_binario * $ponto_A_pagar)/100:0;
				
			}else{
				
				/**
				 * Verificando se já não foi incluido na tabela os pontos se foi
				 * calculado pega os pontos e soma com os novos.
				 * Assim com sita a tarefa de Número:1354 
				 */ 
				 $bonusCalculado = $this->db->where('bc_id_distribuidor',$distribuidor->di_id)
				 							->get('bonus_calculado')
				 							->row();
				 
				 if(count($bonusCalculado) > 0){
				 	
				   $ponto_A_pag  = ($pontos->get_pontos_perna_maior() - $pontos->get_pontos_pagos())/3;
				   
				   //Mantem o registro de bônus
				   $this->db->insert('bonus_calculado',array(
				   	'bc_id_distribuidor'=>$distribuidor->di_id,
				   	'bc_pontos'=>floor($ponto_A_pag),
				   	'bc_data'=>date('Y-m-d H:i:s')
				   ));
				   
				 }
			}

			//Pagando a perna mernor
           if($ponto_A_pagar >0){
           	
           	  $this->db->insert('conta_bonus',array(
           	  	'cb_distribuidor'=>$distribuidor->di_id,
           	    'cb_descricao'=>'Bônus Binário ',
           	    'cb_credito'=>$ponto_A_pagar,
           	  	'cb_data_hora'=>date('Y-m-d H:i:s'),
           	    'cb_tipo'=>2           	  		
           	  ));
           	  
           	  //Inserindo o registro de bonus indiretos no banco
           	  $this->db->insert('registro_bonus_indireto_pagos',array(
           	  		'pg_distribuidor'=>$distribuidor->di_id,
           	  		'pg_pontos'=>$pontos->get_pontos_perna_menor() - $pontos->get_pontos_pagos(),
           	  		'pg_data'=>date('Y-m-d'),
           	  		'pg_atualizacao'=>date('Y-m-d H:i:s')           	  
           	      ));
           	  
			   //quandando os usuários que foi pagos
			   $this->db->insert('pontos_pagos',
			   		array(
			   	'pg_distribuidor'=>$distribuidor->di_id,
			   	'pg_pontos'=>$ponto_A_pagar,
			    'pg_data'=>date('Y-m-d'),
			   	'pg_atualizacao'=>date('Y-m-d H:i:s')
			   ));
	           }
		}
		
	}
}