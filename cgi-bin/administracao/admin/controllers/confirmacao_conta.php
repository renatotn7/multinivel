<?php
class Confirmacao extends CI_Controller{
	 
	 public function __construct(){
		 parent::__construct();
		 permissao('usuario','visualizar',get_user(),true);
		 }
	
	 public function index(){
		 
		 $data['usuarios'] = $this->db->where('rf_tipo',2)->get('responsaveis_fabrica')->result();
		 $data['pagina'] = 'usuario/usuario';
		 $this->load->view('home/index_view',$data);
		 }
		 
	public function add(){
		$data['pagina'] = 'usuario/add';
		$this->load->view('home/index_view',$data);
		}	 
	
	public function editar(){
		
		autenticar();
		$data['user'] = $this->db->where('rf_id',$this->uri->segment(3))
		->get('responsaveis_fabrica')->row();
		
		$data['pagina'] = 'usuario/editar';
		$this->load->view('home/index_view',$data);
		}	
		
	public function salvar_usuario(){
		 autenticar();
		 $dados = array(
		 'rf_nome'=>$this->input->post('rf_nome'),
		 'rf_fabrica'=>1,
		 'rf_email'=>$this->input->post('rf_email'),
		 'rf_pw'=>sha1($this->input->post('senha')),
		 'rf_tipo'=>2,
		 'rf_permissao'=>(isset($_POST['permissao'])?json_encode($_POST['permissao']):'')
		 );
		 
		 $this->db->insert('responsaveis_fabrica',$dados);
		 $id_user = $this->db->insert_id();
		 redirect(base_url('index.php/usuario/editar/'.$id_user));
		 
		}	


	public function editar_usuario(){
		 autenticar();
		
		 $dados = array(
		 'rf_nome'=>$this->input->post('rf_nome'),
		 'rf_email'=>$this->input->post('rf_email'),
		 'rf_tipo'=>2,
		 'rf_fabrica'=>1,
		 'rf_permissao'=>(isset($_POST['permissao'])?json_encode($_POST['permissao']):'')
		 );
		 
		 if($this->input->post('senha')){
			 $dados['rf_pw'] = sha1($this->input->post('senha'));
			 }
		 
		 $this->db
		 ->where('rf_id',$this->input->post('rf_id'))
		 ->update('responsaveis_fabrica',$dados);
		 redirect(base_url('index.php/usuario/editar/'.$this->input->post('rf_id')));
		 
		}	
	
	public function remover(){
		 $this->db
		 ->where('rf_id',$this->uri->segment(3))
		 ->delete('responsaveis_fabrica');
		 redirect(base_url('index.php/usuario/'));
		}
	
	}