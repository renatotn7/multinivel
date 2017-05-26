<?php
class Valor_pl extends CI_Controller{
	 
	 public function __construct(){
		 parent::__construct();
		 permissao('valor_pl','visualizar',get_user(),true);
		 }
	
	 public function index(){
	 
	 $data['pl'] = $this->db->where('field' , 'percentual_pl')->get('config')->row();
		 
	 $data['pagina'] = 'valor_pl/valor_pl';
	 $this->load->view('home/index_view',$data);
		 
    }
	
	public function novo_pl(){
		
      $data['pl'] = $this->db
	                     ->where('field', $this->uri->segment(3))
						 ->get('config')->row();
	  	  
	  $data['pagina'] = 'valor_pl/adicionar_pl';
	  $this->load->view('home/index_view',$data);
	  	
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
