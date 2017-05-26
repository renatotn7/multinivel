<?php
class Webservice extends CI_Controller{
	
	function download_txt(){
		
		 autenticar();
		 $this->load->helper('download');
		 
		 $dis = $this->db->get('distribuidores')->result();
		 $txt = '';
		 foreach($dis as $d){
		  $txt .= "{$d->di_nome};{$d->di_usuario};{$d->di_cpf};{$d->di_email}\n";
		 }
		 force_download('dis.txt',$txt);
		 
		}
	
	}