<?php
class Cd extends CI_Controller{



  function index(){
	   
	   permissao('cds','visualizar',get_user(),true);
	  
	 	if(isset($_GET['ex'])){
		permissao('cds','excluir',get_user(),true);
		
		$compra_cd = $this->db->where('co_id_cd',$_GET['ex'])->get('compras')->num_rows;
		if($compra_cd==0){	
		$ex = $this->db->where('cd_id',$_GET['ex'])->delete('cd');
		
		if($ex){
			set_notificacao(array(0=>array('tipo'=>1,'mensagem'=>"Produto excluido com sucesso.")));
			redirect(current_url());exit;
			}else{
				set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>"Erro ao excluir o cd, tente novamente")));
			    redirect(current_url());exit;
				}
		
		
		}else{
			set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>"Você não pode excluir esse cd, existe compras vinculadas a ele")));
			    
			}
		
		
		}
	  
	  
	  
	  $data['pagina'] = strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	  $this->load->view('home/index_view',$data);
	  }



  function adicionar_cd(){
	  
	  permissao('cds','adicionar',get_user(),true);
	  
	  if($this->input->post('cd_nome')){
		   unset($_POST['x']);unset($_POST['y']);
		   
		   $_POST['cd_pw'] = sha1($_POST['cd_pw']);
		   if($this->db->insert('cd',$_POST)){
			   set_notificacao(array(0=>array('tipo'=>1,'mensagem'=>"CD cadastrado com sucesso!")));
			   redirect(current_url());exit;
			   }else{
				   set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>"Erro ao gravar, tente novamente")));
			       redirect(current_url());exit;
				   }
		  }
	  
	  $data['pagina'] = strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	  $this->load->view('home/index_view',$data);
	  }


function editar_cd(){
	  
	  permissao('cds','editar',get_user(),true);
	  
	  if($this->input->post('cd_nome')){
		   unset($_POST['x']);unset($_POST['y']);
		   
		   if($_POST['cd_pw']!=''){
		   $_POST['cd_pw'] = sha1($_POST['cd_pw']);
		   }else{
			   unset($_POST['cd_pw']);
			   }
		   
		   if($this->db->where('cd_id',$this->uri->segment(3))->update('cd',$_POST)){
			   set_notificacao(array(0=>array('tipo'=>1,'mensagem'=>"CD atualizado com sucesso!")));
			   redirect(current_url());exit;
			   }else{
				   set_notificacao(array(0=>array('tipo'=>2,'mensagem'=>"Erro ao gravar, tente novamente")));
			       redirect(current_url());exit;
				   }
		  }
	
	  $data['pagina'] = strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	  $this->load->view('home/index_view',$data);	 
	}
	
}
