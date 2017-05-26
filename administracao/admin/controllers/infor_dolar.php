<?php
class Infor_dolar extends CI_Controller{
	 
	 public function __construct(){
		 parent::__construct();
		 permissao('infor_dolar','visualizar',get_user(),true);
		 }
	
	 public function index(){
		 
		 if(isset($_POST['valor_dolar'])){
		   $this->db
		        ->where('field', 'cotacao_dolar') 
				->set('valor', $_POST['valor_dolar'])
				->update('config');
	      }
		 
		 $data['pagina'] = 'infor_dolar/infor_dolar';
		 $this->load->view('home/index_view',$data);
		 }

	
	}