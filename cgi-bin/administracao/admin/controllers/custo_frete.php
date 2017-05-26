<?php
class Custo_frete extends CI_Controller{
	 
	 public function __construct(){
		 parent::__construct();
		 }
	
	 public function index(){

	 $data['custo_frete'] = $this->db->join('estados','estados.es_id=custo_produto.cp_uf')->get('custo_produto')->result();
		 
	 $data['pagina'] = 'custo_frete/custo_frete';
	 $this->load->view('home/index_view',$data);
		 
    }
	
	public function atualizar_frete(){
		
	 if(isset($_POST['cp_valor_frete'])){
	   $estado = $_POST['estado'];
	   $this->db
	        ->where('cp_id', $this->input->post('cp_id'))
			->set('cp_valor_frete', $this->input->post('cp_valor_frete'))
			->update('custo_produto');
				
			set_notificacao(array(1=>array('tipo'=>0,'mensagem'=>"Frete para o estado de {$estado} atualizado com sucesso!"))); 
			redirect(base_url('index.php/custo_frete'));
	    }
	  	
	}
	
	public function editar_pl(){
		
		if(isset($_POST['rpl_percentual'])){
		   
		   $this->db
		    ->where('field', $this->uri->segment(3))
			->set('valor',$this->input->post('rpl_percentual'))
			->update('config'); 
			
		  set_notificacao(array(0=>
						array('tipo'=>1,'mensagem'=>"Valor da PL atualizado com sucesso.")));
			redirect(base_url("index.php/valor_pl"));			
						  
	  }
	  
	}
 }
