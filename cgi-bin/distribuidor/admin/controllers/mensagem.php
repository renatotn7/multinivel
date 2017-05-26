<?php

class Mensagem extends CI_Controller{
	
	public function index(){
		
	 $this->load->library('paginacao');
	 $this->paginacao->por_pagina(13);
	 
	 $msg = $this->db
    ->where('me_receptor',get_user()->di_id)
	->join('distribuidores','di_id = me_emissor')
	->order_by('me_data','DESC')
	->get('mensagem')->result();
	
	$data['msg'] = $this->paginacao->rows($msg);
	$data['links'] = $this->paginacao->links();
	
	 
	 $data['pagina'] = strtolower(__CLASS__)
	 ."/mensagem_".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	 $this->load->view('home/index_view',$data);
		
		}
	
	public function ver(){
		 $data['pagina'] = strtolower(__CLASS__)
		 ."/mensagem_".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
		 $this->load->view('home/index_view',$data);
		}
	
	public function escrever(){
		$data['assunto'] = "";
		$data['mensagem'] = "";
		$data['receptor'] = 0;
		$data['resposta'] = 0;
		$data['name'] = '';
		if(isset($_GET['responder'])){
			$msg = $this->db
			->where('me_id',$_GET['responder'])
			->join('distribuidores','di_id = me_emissor')
			->get('mensagem')->result();
			
			$data['assunto'] = "RE: ".$msg[0]->me_assunto;
			$data['receptor'] = $msg[0]->di_id;
			$data['name'] = $msg[0]->di_nome;
			$data['resposta'] = $_GET['responder'];
			}
			
		if(isset($_GET['ni'])){
			 $msg = $this->db
			->where('di_id',$_GET['ni'])
			->get('distribuidores')->result();
			$data['receptor'] = $msg[0]->di_id;
			$data['name'] = $msg[0]->di_nome;
			}
		
		 $data['pagina'] = strtolower(__CLASS__)
		 ."/mensagem_".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
		 $this->load->view('home/index_view',$data);
		}
		
	public function enviar(){
		 
		 $mensagem = $this->input->post('mensagem');
		 
		 if($this->input->post('resposta')){
				 $msg = $this->db
				->where('me_id',$this->input->post('resposta'))
				->join('distribuidores','di_id = me_emissor')
				->get('mensagem')->result();
				
				$mensagem .= "
				<p></p>
				-------------".date('d/m/Y H:i:s',strtotime($msg[0]->me_data))."----------------
				<div><strong>".$msg[0]->di_nome."</strong></div>
				<div>".$msg[0]->me_mensagem."</div>
				";
				
			 }
		
		 $dados = array(
		 'me_emissor'=>get_user()->di_id,
		 'me_receptor'=>$this->input->post('para'),
		 'me_assunto'=>$this->input->post('assunto'),
		 'me_mensagem'=>$mensagem,
		 'me_resposta'=>$this->input->post('resposta')
		 );
		 
		 if($this->db->insert('mensagem',$dados)){
		 set_notificacao(array(0=>array('tipo'=>1,'mensagem'=>"Mensagem enviada com sucesso!")));
		 }
		 if(isset($_GET['ajax'])){
		 redirect(base_url('index.php/mensagem'));
		 }else{
			 echo "1";
			 }
		
		}	
	
	public function escolher_distribuidor(){
		$data['pagina'] = strtolower(__CLASS__)
		 ."/mensagem_".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
		 $this->load->view('home/index_view',$data);
		}
	
	}