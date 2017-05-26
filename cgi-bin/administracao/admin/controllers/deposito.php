<?php

class Deposito extends CI_Controller{
	 
	 public function status(){
		   $this->db->where('cdp_id',$_GET['cdp'])->update('conta_deposito',array(
		    'cdp_status'=>$_GET['s']
		   ));
		   
		   redirect(base_url('index.php/relatorios/deposito_unico/'.$_GET['cdp']));
		 }
		 
	public function extorno(){
		 	autenticar();
		   /**
		    * Operação de extorno de deposito Fluxo.
		    * 1 - Verifica o login do usuário solicitante
		    * 2 - Registra o extorno.
		    * 3 - Realiza o extorno.
		    * Obs.: Operação de extorno id 235
		    */
		 	
		 	//verificando a senha do usuário se é valida.
//		 	$admin   = $this->db->where('rf_id',get_user()->rf_id)
//		 	                    ->where('rf_pw',sha1($this->input->post('senha')))->get('responsaveis_fabrica')->row();
//
//		 	if(count($admin)>0){
		 		
		 	$this->db->trans_begin();
		 	//Dados do Saque
		 	$saque   = $this->db->where('cdp_id',$this->input->post('cdp_id'))->get('conta_deposito')->row();
		 	
		 	if(count($saque) > 0){
		 		$distrib = $this->db->where('di_id',$saque->cdp_distribuidor)->get('distribuidores')->row();
		 		//Registrando o Extorno do valor.
		 		$this->db->insert('conta_extorno',array(
		 			'ex_id_conta_bonus'=>$saque->cdp_id,
		 			'ex_id_distribuidor'=>$saque->cdp_distribuidor,
		 			'ex_descricao'=>'Extorno do saque '.$saque->cdp_id.' para Usuario: '.$distrib->di_nome." #Data do saque: ".date('d/m/Y H:i:s',strtotime($saque->cdp_data)),
		 		        'ex_credito'=>$saque->cdp_valor
		 		));
		 				 
		 	   //Extornando o valor.
		 		$this->db->insert('conta_bonus',array(
		 			 'cb_distribuidor'=>$saque->cdp_distribuidor,
		 			 'cb_descricao'=>'Extorno de saque #'.$saque->cdp_id,
		 			 'cb_credito'=>$saque->cdp_valor,
		 			 'cb_tipo'=>235
		 		));
		 	}
		 	
		 	//validando toda Operação bancária, realizadda para o extorno.
		 	if ($this->db->trans_status() === FALSE)
		 	   {
		 		$this->db->trans_rollback();
		 		//extorno não realizado com sucesso.
		 		set_notificacao(1,"Erro ao extornar o saque #".$saque->cdp_id."!");
		 		redirect(base_url('index.php/relatorios/depositos/'));
		 		
		 	  }else{
		 		$this->db->trans_commit();
		 		//extorno finalizado com sucesso.
		 		set_notificacao(1,"Extorno do saque #".$saque->cdp_id." efetuado com sucesso!");
		 		redirect(base_url('index.php/relatorios/depositos/'));
		 	  }
		    
//		 	}else{
//		 		//extorno não finalizado, pois a senha do usuário não é valida.
//		 		set_notificacao(2,"Senha informada não é valida!");
//		 		redirect(base_url('index.php/relatorios/depositos/'));
//		 	}		
		 }
	 
	 
	 
	}

?>	