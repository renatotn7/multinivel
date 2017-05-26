<?php 
class Pacotes extends CI_Controller{
	
	public function index(){
		 $data['pagina'] = 'pacotes/pacotes';
	     $this->load->view('home/index_view',$data);
		}
		
	public function detalhes(){
		 $data['pagina'] = 'pacotes/detalhes';
	     $this->load->view('home/index_view',$data);
		
		}	
		
	}

?>