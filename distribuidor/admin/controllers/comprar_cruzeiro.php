<?php

class Comprar_cruzeiro extends CI_Controller{
  
  public function index(){
  	 verificar_permissao_acesso();
	 $this->load->view('comprar_cruzeiro/index_view');  
   }
   
  public function enviar(){
 	
	  $pat = $this->db->where('di_id', get_user()->di_ni_patrocinador)->get('distribuidores')->row();
	  $msg = $this->input->post('msg');
	  email_comprar_cruzeiro($pat,$msg);
	  		set_notificacao(array(0=>
						array('tipo'=>1,'mensagem'=>"Solicitação enviada com sucesso!")));
						echo "<script>alert('".utf8_decode('Solicitação')." enviada com sucesso!');window.close();</script>";
	    
   }
}