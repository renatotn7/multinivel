<?php
class Documentos extends CI_Controller{
   
   
   private $table;
   private $pk;
   private $module_descricao;
	
	
  function __construct(){
	  parent::__construct();
	  $this->table = 'documentos';
	  $this->pk = 'id_no';
	  $this->module_descricao = 'Documentos';
	  }	
	
	
	public function ver_arquivo(){
		
		$fileTemp = "public/tmpdoc/";
		if(!file_exists($fileTemp)){
			mkdir($fileTemp);
			}
		 
		 
		 
		 $data['arquivo'] = $arquivo = $this->db
		  ->where('do_id',$this->uri->segment(3))
		  ->get('documentos')
		  ->row();
		 
		 //Greando um nome para o arquivo
		 $fileName = 'tmp-'.sha1(rand(111,999).date('YmdHis').rand(111,999));
		 $fileExtensao = @explode('.',$arquivo->do_nome_arquivo);
		 $fileExtensao = end($fileExtensao);
		 $fileName = $fileName.'.'.$fileExtensao;
		 
		 
		 //File Origen
		 $fileOrigem = '/home/soffce/'.$arquivo->do_nome_arquivo;
		 //$fileOrigem = $arquivo->do_nome_arquivo;
		 $fileDestino = $fileTemp.$fileName;
		 
		 @copy($fileOrigem,$fileDestino);
		 
		 
		 $data['file'] =  $fileDestino;
		 $data['distribuidor'] = $this->db
		  ->join('cidades','ci_id=di_cidade')
		  ->where('di_id',$arquivo->do_distribuidor)
		  ->get('distribuidores')
		  ->row(); 
		  
		 
		 $this->load->view('documentos/ver_arquivo_view',$data);
		}
	
    public function download(){
	    
		 autenticar();
		 
	  	 $arquivo = $this->db
		  ->where('do_id',$this->uri->segment(3))
		  ->get('documentos')
		  ->row();
		  
		  if($arquivo){
			$file = ''.$arquivo->do_nome_arquivo;  


		   if(file_exists($file)){
				  redirect(base_url('download.php?f='.$file));				
			   }
		  }
		  
	  
	  }

	public function listar(){
		
             $paises_permitidos = $this->db->where('rfp_id_responsavel_fabrica',get_user()->rf_id)
                                        ->select("GROUP_CONCAT(rfp_id_pais) as paises",false)
                                        ->get('responsaveis_fabrica_paises')->row();
             
		if(get_parameter('de') && get_parameter('ate')){
			$this->db->where('do_data >= ',data_usa(get_parameter('de')).' 00:00:00')
			->where('do_data <= ',data_usa(get_parameter('ate')).' 23:59:59');
		}elseif(get_parameter('de') && !get_parameter('ate')){
			$this->db->where('do_data >= ',data_usa(get_parameter('de')).' 00:00:00');
		}elseif(!get_parameter('de') && get_parameter('ate')){
			$this->db->where('do_data <= ',data_usa(get_parameter('ate')).' 23:59:59');
		}
		if(get_parameter('usuario')){
			$this->db->like('di_usuario', get_parameter('usuario'));
		}
		if(get_parameter('nome')){
			$this->db->like('di_nome', get_parameter('nome'));
		}
              
                 if($paises_permitidos->paises !=null){
                  $dados = $this->db
                        ->where('ci_pais IN('.$paises_permitidos->paises.')')
                        ->join('distribuidores','di_id=do_distribuidor')
                        ->join('cidades','ci_id=di_cidade')
                        ->where('do_status',1)
                        ->group_by('di_id')
                        ->get('documentos')
                        ->result();
                 }else{
                  $dados = $this->db
                            ->join('distribuidores','di_id=do_distribuidor')
                            ->join('cidades','ci_id=di_cidade')
                            ->where('do_status',1)
                            ->group_by('di_id')
                            ->get('documentos')
                            ->result(); 
                 }
		
		$this->load->library('paginacao');
		$this->paginacao->por_pagina(10);
		$data['dados'] = $this->paginacao->rows($dados);
		$data['links'] = $this->paginacao->links();
		
		$data['modulo'] = 'documentos';
		$data['module_descricao'] = 'Documentação';
		$data['pk'] = 'do_id';
		$data['pagina'] = $this->table.'/listar';
		$this->load->view('home/index_view',$data);
		}
		
	
	 public function editar(){
		   permissao('verificar_conta','editar',get_user(),true);
		   $data['pagina'] = $this->table.'/editar';
		   $this->load->view('home/index_view',$data);
		 }
		
	public function salvar_dados(){		
	     $_POST['di_data_verificacao'] = date('Y-m-d H:i:s');
		 $this->db->where('di_id',$this->uri->segment(3))->update('distribuidores',$_POST);
		 redirect(base_url('index.php/documentos/editar/'.$this->uri->segment(3)));
		}	 

	public function salvar_status(){
		
	 
		 $dis = $this->db->where('di_id',$_POST['do_distribuidor'])->get('distribuidores')->row();
		 
		 $this->db->where('do_id',$_POST['do_id'])->update('documentos',array('do_status'=>$_POST['do_status']));
		 
		 if($_POST['do_status'] == 0){
			 
			 $assunto = "Envio de documento";
			 $mensagem = "Olá ".$dis->di_nome."<br><br>
			 O documento para verificação de conta foi reprovado pelo seguinte motivo:<br>
			 <strong>".$_POST['do_mensagem']."</strong><br><br>
			 Acesse seu escritório virtual e faça o reenvio dos documentos.";
			 
			 mailSend($dis->di_email,$mensagem,$assunto,$dis->di_email);
			 
			 set_notificacao(1,"Status modificado e notificação enviada com sucesso!");
		 }
		 
		  if($_POST['do_status'] == 1){
		 	$assunto = "Envio de documento";
		 	$mensagem = "Olá ".$dis->di_nome."<br><br>
			 A verificacao do documento está em análise,<br>
			 Acesse seu escritório virtual e faça o acompanhamento da verificação.";
		 	
		 	mailSend($dis->di_email,$mensagem,$assunto,$dis->di_email);
		 	set_notificacao(1,"Status modificado com sucesso!");
	       }
	       
	       if($_POST['do_status'] == 2){
	       	$assunto = "Envio de documento";
	       	$mensagem = "Olá ".$dis->di_nome."<br><br>
			  A verificação do documento foi aprovada.";
	       
	       	mailSend($dis->di_email,$mensagem,$assunto,$dis->di_email);
	       	set_notificacao(1,"Status modificado com sucesso!");
	       }
	       
		
		 redirect(base_url('index.php/documentos/editar/'.$this->uri->segment(3)));
		}	

		
	}
