<?php
class Noticias extends CI_Controller{
   
   
   private $table;
   private $pk;
   private $module_descricao;
	
	
  function __construct(){
	  parent::__construct();
	  $this->table = 'noticias';
	  $this->pk = 'id_no';
	  $this->module_descricao = 'Notícias';
	  }	
	

	public function listar(){
		
		//Verifica se há algum filtro no array
		if(isset($_SESSION['filtros'])&&count($_SESSION['filtros'])>0){
			 foreach($_SESSION['filtros'] as $filto){
				  
				  if($filto['type']=='pk'){
					   $this->db->where($filto['field'],$filto['val']);
					  }
					  
				  if($filto['type']=='varchar'){
					  $this->db->like($filto['field'],$filto['val']);
					  }
				  
				  if($filto['type']=='fk'){
					  $this->db->where($filto['field'],$filto['val']);
					  }	
				  	  	  
				 }
			}
			
		
		$dados = $this->db
		  ->order_by("ordem",'DESC')
		  ->order_by($this->pk,'DESC')
		  ->get($this->table)
		  ->result();
		
		$this->load->library('paginacao');
		$this->paginacao->por_pagina(10);
		$data['dados'] = $this->paginacao->rows($dados);
		$data['links'] = $this->paginacao->links();
		
		$data['modulo'] = $this->table;
		$data['module_descricao'] = $this->module_descricao;
		$data['pk'] = $this->pk;
		$data['pagina'] = $this->table.'/listar';
		$this->load->view('home/index_view',$data);
		}
		
	public function add(){
		
		$data['modulo'] = $this->table;
		$data['module_descricao'] = $this->module_descricao;
		$data['pk'] = $this->pk;
		$data['pagina'] = $this->table.'/add';
		$this->load->view('home/index_view',$data);
		}

	public function editar(){
		$data['modulo'] = $this->table;
		$data['module_descricao'] = $this->module_descricao;
		$data['pk'] = $this->pk;
		
		$data['d'] = $this->db->where($this->pk,$this->uri->segment(3))->get($this->table)->result();
		$data['d'] = $data['d'][0];
		
		$data['pagina'] = $this->table.'/editar';
		$this->load->view('home/index_view',$data);
		}	
	
	public function ordem(){
		
		$this->db->where($this->pk,$this->uri->segment(3))->update($this->table,array(
		'ordem'=>$_GET['direcao']
		));
		redirect(base_url('index.php/'.$this->table.'/listar'));
		}
	
	public function salvar_novo(){
		 $_POST['insert_data'] = date('Y-m-d H:i:s');
		 $_POST['update_data'] = date('Y-m-d H:i:s');

		 $this->db->insert($this->table,valida_fields($this->table,$_POST));
		 
		if(isset($_GET['aplicar'])&&$_GET['aplicar']=='sim'){
		 redirect(base_url('index.php/'.$this->table.'/add/'));
		 }else{
			 redirect(base_url('index.php/'.$this->table.'/listar'));
			 }
		 
		}

	public function salvar_update(){

		 $this->db->where($this->pk,$this->uri->segment(3))->update($this->table,valida_fields($this->table,$_POST));
		 
		 
		 redirect(base_url('index.php/'.$this->table.'/editar/'.$this->uri->segment(3)));
		 
		}	

	public function excluir(){ 
	    
	    
		 $this->db->where($this->pk,$this->uri->segment(3))->delete($this->table);
		 redirect(base_url('index.php/'.$this->table.'/listar'));
		}		

		
	}