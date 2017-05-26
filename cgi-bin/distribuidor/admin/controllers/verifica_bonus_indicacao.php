<?php
class verifica_bonus_indicacao extends CI_Controller{
	
	private $valorVendasVolume;
	
	public function indicacao(){
		
		 $ativacoes = $this->db->query("SELECT * FROM registro_ativacao")->result();
		 
		 foreach($ativacoes as $ativacao){
			 
			 $this->info_dis_bonus_volume = $distribuidor;			  
	   
			 $percentualDisponivelParaIndicacao = $this->percentual(1500,10);
			 $this->valorVendasVolume = $percentualDisponivelParaIndicacao;
			 
			 $this->pagar_bonus_venda_volume();
			 }
		 
		
		}
		

private function pagar_bonus_venda_volume($dis,$linha,$chegou_no1){
	 
	 
	 
	 if($linha <= 10){
		
	 
	  ##-- Obtenho o dado do sistribuidor
	  $dis = $this->db
		  ->select(array('di_id','di_usuario','di_ni_patrocinador','di_ativo'))
		  ->where('di_id',$dis->di_ni_patrocinador)
		  ->get('distribuidores')->row();
	 
	if(count($dis) > 0 ){
	 
	 #-- Valor que deve ser depositado
	 $valor_pagar = $this->valorVendasVolume/10;
	 
	 ##-- Verifica se vai inderir o crédito para um distribuidor ou para a industria;
	 if($chegou_no1===false){
				   
				  /*
					  ##-- Inserir Bônus para o distribuidor 		  
					  $this->db->insert('conta_bonus',array(
					  'cb_distribuidor'=>$dis->di_id,
					  'cb_descricao'=>"Vendas em Volume <b>".$this->info_dis_bonus_volume->di_usuario."</b>",
					  'cb_credito'=>$valor_pagar,
					  'cb_debito'=>0,
					  'cb_tipo'=>107
					  ));
					  
					  ##-- Inserir registro do pagamento de bonus
					  $this->db->insert("bonus_venda_volume_pagos",array(
						'bp_distribuidor'=>$this->info_dis_bonus_volume->di_id,
						'bp_distribuidor_recebeu'=>$dis->di_id,
						'bp_posicao'=>$linha,
						'bp_data'=>date('Y-m-d')
						)); 
			*/
			 
		}
		
		
	 if($dis->di_id == 1){
	    $chegou_no1 = true;
	  }
	  
  
			
	  $linha++;
	  self::pagar_bonus_venda_volume($dis,$linha,$chegou_no1); 
	
	
	 }//Fim verifica distribuidor existe

	 
	 }
		 
	}

  
  private function percentual($valor,$percentual){
	  return $valor*$percentual/100;
	  }	 		
		
	
	}