<?php 


class Ativacao extends CI_Controller{
	    
		/*
         * Mostrar para o usuário se escolheu a auto-ativação ou não.
         * Dever ter a possibilidade de acitar a auto ativação ou recusar.
         * Deve mostrar os produto que selecionou para a auto-ativação
         * e opção de alterar
         */
		 
	public function index(){
		redirect(base_url('index.php/ativacao/auto_ativacao'));
	}	 
	
	// CRON de ativação mensal que esta na System/Library 	
	public function ativacao_ciclo(){
		$this->load->library('ciclos');
		$this->ciclos->lanca_ativacao_ciclo();
	}
	
	public function auto_ativacao(){
		
		//Busca todos os produtos escolhidos
		
		 $data['pagina'] = strtolower(__CLASS__).
		 "/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
		 $this->load->view('home/index_view',$data);
		}
	
	/*
	*
	* Desativar auto-ativação
	*/
	public function desativar_auto_ativacao(){
		if(!$this->input->post('registroAtivacao')){
			
			set_notificacao(array(0=>array('tipo'=>0,'mensagem'=>"Erro, registro da auto ativacao nao encontrado!")));
			redirect(base_url('index.php/ativacao/auto_ativacao/'));
			exit;
		}
		   
		   
		    //Deletar a auto ativação
			$this->db->where('auto_atv_id', $this->input->post('registroAtivacao'), false)->delete('registro_auto_ativacao');
			
			//Deletar os produtos da Auto-Ativação
			$this->db->where('id_auto_atv', $this->input->post('registroAtivacao'), false)->delete('produtos_auto_ativacao');
			
			set_notificacao(array(0=>array('tipo'=>1,'mensagem'=>"Auto-Ativação desativada")));
			redirect(base_url('index.php/ativacao/auto_ativacao/'));
		
	}
	
	//excluir os produtos escolhidos
	//inserir os novos produtos selecionados
	public function ativar_auto_ativacao(){
		
		//Quantidade de produtos selecionados		
		$combosSelecionados = 0;
		foreach($this->input->post('combo') as $combo){
			if($combo != ''){
				$combosSelecionados++;
			}
		}
		
		//Se não tiver selecionado a quantidade que deveria
		if($combosSelecionados < get_user()->distribuidor->getPlano()->getQuantidadeComboMensal()){
			set_notificacao(array(0=>array('tipo'=>1,'mensagem'=>"Erro, a quantidade de combos mensais para ativacao nao foi alcancada!")));
			redirect(base_url('index.php/ativacao/auto_ativacao/'));
			exit;
		}
		
		$tipoCompra = 1; //valor default para o tipo da compra 1 = Fabrica
		$idCd = 0; //Nenhum CD
		
		//Local da Compra CD ou fabrica
		if($this->input->post('co_tipo')){
			$tipoCompra = $this->input->post('co_tipo');
		}
		
		//id do cd
		if($this->input->post('co_id_cd') && $tipoCompra ==2){
			$idCd = $this->input->post('co_id_cd');
		}
		
		//Busca o registro de auto-ativação do mês atual
		$ativacaoMensal = $this->db
		->like('auto_atv_data',date('Y-m-'))
		->get('registro_auto_ativacao')->row();
		
	
		
		//Se já esxistir um registro de auto-ativação desse mês
		if(count($ativacaoMensal)>0){
			
			$idAutoAtivacao = $ativacaoMensal->auto_atv_id;
			
			//Excluir os produtos selecionados anteriormente
			$this->db->where('id_auto_atv',$idAutoAtivacao, false)->delete('produtos_auto_ativacao');
			
			//Atualizar o registro da Auto-Ativacao
			$this->db->where('auto_atv_id',$idAutoAtivacao)->update('registro_auto_ativacao',array(
			  'auto_atv_co_tipo'=>$tipoCompra,
			  'auto_atv_cd_id'=>$idCd
			));
		
		//Se ainda não tiver o registro da auto-ativação	
		}else{
			//Inserindo o registro
			 $this->db->insert('registro_auto_ativacao',array(
			  'distribuidor_auto'=>get_user()->di_id,
			  'auto_atv_co_tipo'=>$tipoCompra,
			  'auto_atv_cd_id'=>$idCd
			 ));
			 
			 $idAutoAtivacao  = $this->db->insert_id();
			 
			}
		
 		
		
		
		foreach($this->input->post('combo') as $combo){
			$this->db->set('id_produto', $combo, false); 
			$this->db->set('id_auto_atv', $idAutoAtivacao, false); 
			$this->db->insert('produtos_auto_ativacao');	
		}
		
		redirect(base_url('index.php/ativacao/auto_ativacao/'));		
	}
	
	/* Mudar o status se deseja ou não se auto-ativar */	
	public function mudar_auto_ativacao(){
		redirect(base_url('index.php/ativacao/auto_ativacao'));
		}	
	
	/*
         * Escolher os combos/kit que vai compor sua auto-ativação
         * apenas produtos que a coluna pr_kit for igual a 1
         * deve estar disponivel para seleçã. 
         * Deve ter pelo menos 1 produto no estoque
         * Similar: loja/compra_servico
         */
	public function escolher_produto(){
		$data['pagina'] = strtolower(__CLASS__).
		 "/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
		 $this->load->view('home/index_view',$data);
	}
	
	
}
?>