<?php

class Pedidos_cd extends CI_Controller{
	 
	 public function index(){
		 
		 permissao('vendas','visuzalizar',get_user(),true);
		 
		   $data['situacao'] = isset($_GET['situacao'])&&$_GET['situacao']!=''?$_GET['situacao']:FALSE;
		 
		   if($data['situacao']){$this->db->where('cr_situacao', $data['situacao']);}
		 #Paginação
		    $this->load->library('pagination');

			$config['base_url'] = base_url('index.php/pedidos/index/');
			$config['total_rows'] = $this->db->where('cr_situacao <>',-1)->get('compras_fabrica')->num_rows;
			$config['per_page'] = $data['per_page']= 15; 
			$config['suffix'] = "?".$_SERVER['QUERY_STRING'];
			
			$this->pagination->initialize($config); 
			
			$data['links'] = $this->pagination->create_links();
		 
		 
		 $data['pagina'] = strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	     $this->load->view('home/index_view',$data);
		 }
	 
	 
	 public function pedido_imprimir(){
		 autenticar();
	     $this->load->view(strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__))."_view");
		 }
	 
	 
	 
	 public function cancelar_pedido(){
		 $this->db->where('cr_id',$this->uri->segment(3))->update('compras_fabrica',array(
		 'cr_situacao'=>'-1'
		 ));
		 redirect(base_url('index.php/pedidos_cd'));
		 }
	 
	 public function editar_pedido(){
		 
		 permissao('vendas','editar',get_user(),true);
		 
		 $this->load->library('estoque');
		 
		 //Verifica o POST
		 if($this->input->post('situacao')){
			 
			 ##Inicia uma transação
		     $this->db->trans_start();
			 
			 //Obtem a compra
			 $compra = $this->db->where('cr_id',$this->uri->segment(3))
			 ->get("compras_fabrica")->result();
			 
			 //Atualiza a compra
			 $this->db
			 ->where('cr_id',$this->uri->segment(3))
			 ->update('compras_fabrica',array(
			 'cr_situacao'=>$this->input->post('situacao')
			 ));
			 
			 //Verifica se é crédito de repasse
			 if($this->input->post('pago')==1&&$compra[0]->cr_credito_repasse==1
			 &&$compra[0]->cr_estocado==0){
				 
				 
				 
				 //Se for crédito de repassa passa o valor para o CD
				 $this->db->insert('credito_repasse',array(
				 'cp_cd'=>$compra[0]->cr_id_cd,
				 'cp_credito'=>$compra[0]->cr_total_valor,
				 'cp_time'=>time()
				 ));
				 
				 //Altera a compra, crédito repassado
				  $this->db->where('cr_id',$this->uri->segment(3))
			     ->update("compras_fabrica",array('cr_estocado'=>1));
				 
				 
				 
				
				 
				 }
			
			//ESTOQUE 
			if($this->input->post('pago')==1){
				 //Compra como estocada
				  $this->db->where('cr_id',$this->uri->segment(3))
			     ->update("compras_fabrica",array('cr_pago'=>1));
				 
				 //Obtem todos os produtos da compra
				 $produtos_compra = $this->db
				 ->where('pc_id_compra',$compra[0]->cr_id)
				 ->get('produtos_comprados_fabrica')
				 ->result();
				 
				 foreach($produtos_compra as $pc){
				  //Atualiza o estoque dos produtos	 
				  $this->db->query("UPDATE produtos SET pr_estoque = pr_estoque - ".$pc->pc_quantidade." 
						 WHERE pr_id = ".$pc->pc_id_produto);
				 }
				 
				 
				}
			
			//Se todas as operações ocorrem como esperado
			 if ($this->db->trans_status() === FALSE)
					{
						$this->db->trans_rollback();
					}
					else
					{
						$this->db->trans_commit();
					}
			 
			 
			 }
		 
		 
		 $data['pagina'] = strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
	     $this->load->view('home/index_view',$data);
		 }
	 
	 
	}

?>	