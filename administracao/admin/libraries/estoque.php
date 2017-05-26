<?php

class Estoque{
	
	private $ci;
	
	public function __construct(){
		$this->ci =& get_instance(); 
		}
	
	public function baixar($id_compra){
		 
		 $compra = $this->ci->db
		 ->where('co_id',$id_compra)
		 ->get('compras')
		 ->result();
		 
		 $produtos = $this->ci->db
		 ->where('pm_id_compra',$id_compra)
		 ->get('produtos_comprados')
		 ->result();
			  
			   foreach($produtos as $p){
					    $this->ci->db->query("
						 UPDATE produtos SET pr_estoque = pr_estoque - ".$p->pm_quantidade." 
						 WHERE pr_id = ".$p->pm_id_produto."
						");
					  }
			  
		 
		}
		
		

public function saida_cd($id_compra){
	
		  $compra = $this->ci->db->where('co_id',$id_compra)->get('compras')->result();
		  $prods = $this->ci->db->where('pm_id_compra',$compra[0]->co_id)
		                ->join('produtos','pr_id=pm_id_produto')
		                ->get('produtos_comprados')->result();
			
						
		 foreach($prods as $p){
			 #se for um KIT da baixa nos produtos do KIT
			 if($p->pr_kit==1){
				  $prods_kit = $this->ci->db
				  ->where('pk_kit_comprado',$p->pm_id)
				  ->get('produtos_kit_opcoes')->result();
				  
				  foreach($prods_kit as $pkit){
				  
				  #Baixa no produto comprado  no KIT
				  $this->ci->db->insert('produtos_do_cd',array(
				  'pc_id_produto'=>$pkit->pk_produto,
				  'pc_id_cd'=>$compra[0]->co_id_cd,
				  'pc_entrada'=>0,
				  'pc_saida'=>$p->pm_quantidade,
				  'pc_produto_comprado'=>$p->pm_id
				  ));
				  
				 }
			 }
			  
			  #Baixa no produto comprado 
			  $this->ci->db->insert('produtos_do_cd',array(
			  'pc_id_produto'=>$p->pm_id_produto,
			  'pc_id_cd'=>$compra[0]->co_id_cd,
			  'pc_entrada'=>0,
			  'pc_saida'=>$p->pm_quantidade,
			  'pc_produto_comprado'=>$p->pm_id
			  ));
			  
			 }	
			 
			 			
		}
		

	public function saida_fabrica($id_compra){
		
		  $compra = $this->ci->db->where('co_id',$id_compra)->get('compras')->result();
		  
		  $prods = $this->ci->db->where('pm_id_compra',$compra[0]->co_id)
		                ->join('produtos','pr_id=pm_id_produto')
		                ->get('produtos_comprados')->result();
		 foreach($prods as $p){
			 
			 //KIT
			  if($p->pr_kit==1){
				  $prods_kit = $this->ci->db
				  ->where('pk_kit_comprado',$p->pm_id)
				  ->get('produtos_kit_opcoes')->result();
				  foreach($prods_kit as $pkit){
				  $this->ci->db->query("UPDATE produtos SET pr_estoque = pr_estoque - 1 
						 WHERE pr_id = ".$pkit->pk_produto);
				 }
			 }
			 
			 
			  $this->ci->db->query("UPDATE produtos SET pr_estoque = pr_estoque - ".$p->pm_quantidade." 
						 WHERE pr_id = ".$p->pm_id_produto);
			 }				
		}

	
	}