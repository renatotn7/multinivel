<?php
class Juridico extends CI_Controller{
	
	public function documentos(){
		 $data['pagina'] = 'juridico/documentos';
		 $this->load->view('home/index_view',$data);
		}
		
	public function ler_contrato(){
		$this->load->view('juridico/ler_contrato_view');
		}	
	
	}