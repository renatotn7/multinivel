<?php
class Detalhes_binario extends CI_Controller{

 public function detalhes(){
	 
	 $data['pagina'] = 'detalhes_binario/detalhes';
	 $this->load->view('home/index_view',$data);
	 }	
 
 public function atualiza_data_binario(){
	   
	    $distribuidores = $this->db->query("
		  SELECT di_id, di_usuario,di_nome,di_esquerda,di_direita,di_fone1,di_fone2 FROM distribuidores
		  JOIN registro_distribuidor_binario ON db_distribuidor = di_id
		  WHERE di_id NOT IN(
		   SELECT bv_distribuidor FROM binario_verificado
		  )
		 ")->result();
		 
	   foreach($distribuidores as $distribuidor){
					  
				$dataBinarioDIREITA = $this->db->query("
				SELECT di_id, di_usuario, di_nome, co_data_compra FROM compras 
				JOIN distribuidores ON di_id = co_id_distribuidor
				JOIN  distribuidor_ligacao ON li_id_distribuidor = di_id 
				WHERE 
				li_no = ".$distribuidor->di_direita."
				AND co_pago = 1
				ORDER BY co_data_compra ASC
			   ")->row();
			   
			   
			
			   $dataBinarioESQUERDA = $this->db->query("
				SELECT di_id, di_usuario, di_nome, co_data_compra FROM compras 
				JOIN distribuidores ON di_id = co_id_distribuidor
				JOIN  distribuidor_ligacao ON li_id_distribuidor = di_id 
				WHERE 
				li_no = ".$distribuidor->di_esquerda."
				AND co_pago = 1
				ORDER BY co_data_compra ASC
			   ")->row();
			   
			   if(count($dataBinarioESQUERDA)==0 || count($dataBinarioDIREITA)==0){
				    
					$this->db->query("DELETE FROM registro_distribuidor_binario
					 WHERE db_distribuidor = ".$distribuidor->di_id.";");
				   }else{
			   
			   $dataUltimoIndicaco = $dataBinarioESQUERDA->co_data_compra > $dataBinarioDIREITA->co_data_compra?$dataBinarioESQUERDA->co_data_compra:$dataBinarioDIREITA->co_data_compra;
			
			   $dataBinario = $this->db->select('db_data')
			   ->where('db_distribuidor', $distribuidor->di_id)
			   ->order_by('db_data', 'ASC')
			   ->get('registro_distribuidor_binario')->row();
			 
				if($dataBinario != $dataUltimoIndicaco){
					echo "Atualizado ".$distribuidor->di_usuario." | ".$distribuidor->di_id."<br>";
					$this->db
					->where('db_distribuidor',$distribuidor->di_id)
					->update('registro_distribuidor_binario',array(
					 'db_data'=>$dataUltimoIndicaco
					)); 
				  }  
		   
		   $this->db->insert('binario_verificado',array(
		    'bv_distribuidor'=>$distribuidor->di_id,
			'bv_data'=>date('Y-m-d H:i:s')
		   ));
		 }
		  
	   }
	  
	 }
	
}