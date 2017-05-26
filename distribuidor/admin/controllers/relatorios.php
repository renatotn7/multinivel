<?php 

class Relatorios extends CI_Controller{
	
	public function desempenho(){
		
	 $data['pagina'] = strtolower(__CLASS__).
	 "/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	 $this->load->view('home/index_view',$data);
		}
		
	public function de_rede(){
	   $data['pagina'] = strtolower(__CLASS__).
	   "/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	   $this->load->view('home/index_view',$data);
		}	
}