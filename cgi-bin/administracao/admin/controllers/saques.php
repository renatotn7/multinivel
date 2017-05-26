<?php
class Saques extends CI_Controller{

	public function index()
	{  
		$result = $this->db->where('field','administrar_data_saque')->get('config')->row();
		$data['saques']=$result;
		$data['pagina']='saques/administrar_saques';
		$this->load->view('home/index_view',$data);
	}
	
	public function salvar_sague()
	{
		$administrar_data_saque = $this->input->post('administrar_data_saque');
		$dados=array('valor'=> $administrar_data_saque);
		
		$this->db
		->where('field','administrar_data_saque')
		->update('config',$dados);
		
		set_notificacao(array(0=>array('tipo'=>1,'mensagem'=>"Atualizado com sucesso!")));
		redirect(base_url('index.php/saques'));
	}
}