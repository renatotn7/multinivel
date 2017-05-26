<?php
class Custo_produto extends CI_Controller{
	 
	 public function __construct(){
		 parent::__construct();
		 }
	
	 public function index(){
		 
	 $data['percentual'] = $this->db->where('field', 'percentual_custo_produto')->get('config')->row();	 	 
	 $data['pagina'] = 'custo_produto/custo_produto';
	 $this->load->view('home/index_view',$data);
		 
    }
	
	public function salvar(){
	 
	 if(isset($_POST['percentual_custo_produto'])){
		 //Atualiza o percentual de custo do produto
		 $this->db
		      ->where('field', 'percentual_custo_produto')
			  ->set('valor', $this->input->post('percentual_custo_produto'))
			  ->update('config');	 
		 
		 
		 //Custo Produto		 
		 $custo = $this->db->get('custo_produto')->result();
		 $percentual = $this->db->where('field', 'percentual_custo_produto')->get('config')->row();
		 
		 
/*		 foreach($custo as $c){
						 
			$dados = array(
			 'cp_valor'=>$c->cp_valor*($percentual->valor)/
			 );
			 
			 $this->db->set($dados)->update('custo_produto');
			 			 
		   }*/

	   }	
	}

}
