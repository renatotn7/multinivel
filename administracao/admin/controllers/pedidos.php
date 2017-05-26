<?php

class Pedidos extends CI_Controller{
	 
	 public function index(){
		
		permissao('cadastro_pendente','visualizar',get_user(),true);
			
		$this->load->library('paginacao');
	    $this->paginacao->por_pagina(30);
		
		

		if(get_parameter('situacao')){
			$this->db->where('co_situacao',get_parameter('situacao'));
			}		


		if(get_parameter('usuario')){
			$this->db->where('di_usuario',get_parameter('usuario'));
			}					

		if(get_parameter('nome')){
			$this->db->like('di_nome',get_parameter('nome'));
			}		

		if(get_parameter('cpf')){
			$this->db->where('di_cpf',get_parameter('cpf'));
			}
		
		$pedidos = $this->db
			   ->where('co_eplano',1)
			   ->where('co_pago',0)
			   ->order_by('co_id','DESC')
			   ->join('compra_situacao','co_situacao=st_id','left')
			   ->join('cidades','ci_id=co_entrega_cidade','left')
			   ->join('distribuidores','di_id=co_id_distribuidor')
			   ->get('compras')->result();
		 
		 $data['pedidos'] = $this->paginacao->rows($pedidos);
	     $data['links'] = $this->paginacao->links();
		 
		 $data['pagina'] = strtolower(__CLASS__).
		 "/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	     $this->load->view('home/index_view',$data);
		 }
	 
	 
	 public function pedido_imprimir(){
		 autenticar();
	     $this->load->view(strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__))."_view");
		 }
	 
	 
	 public function cancelar_pedido(){
		 $this->db->where('co_id',$this->uri->segment(3))->update('compras',array(
		 'co_situacao'=>'-1'
		 ));
		 redirect(base_url('index.php/pedidos'));
		 }
	 
	 //******************
	 //***
	 //********************
	 
	 public function editar_pedido(){
		 autenticar();
		 permissao('cadastro_pendente','editar',get_user(),true);
		 if($this->input->post('pago')){
			 
			$compra = $this->db->where('co_id',$this->uri->segment(3))->get('compras')->row(); 
			  
			 //ESTOQUE 
			if($this->input->post('pago')==1 && $compra->co_eplano==1){
				
			
				 
				$dados = array(
				 'co_forma_pgt'=>1,
				 'co_situacao'=>1,
				 'co_pago'=>1,
				 'co_pago_industria'=>1,
				 'co_forma_pgt_txt'=>'Pago pelo administrador'
				 );
				 
				 $this->db->where('co_id',$this->uri->segment(3))->update('compras',$dados);
				 
				

                #-- Lançar ativação da compra --#
				 $this->load->library('rede');
				 $this->rede->alocar($compra->co_id_distribuidor);
				 $this->load->library('planos');
				 //Não landa os beneficios
				 $this->planos->lancar($compra,false);

				#-- Lançar ativação da compra --#
				$this->load->library('ativacao');
				$this->ativacao->lancar_ativacao($compra);
				#-- Lançar ativação da compra --#			     
				 
				}

			  redirect(base_url('index.php/pedidos/'));
			 
			 }
		 
		 
		 $data['pagina'] = strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	     $this->load->view('home/index_view',$data);
		 }
	 
	

	
	 
	}

?>