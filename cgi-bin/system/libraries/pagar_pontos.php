<?php 
class Pagar_pontos{
	
	private $ci;
	
	public function __construct(){
		 $this->ci =& get_instance();
		}
	
	public function inserir($compra,$tipo){
		 
		 if($compra->co_total_pontos > 0){
			 
		  $this->ci->db->insert('pontos_distribuidor',array(
			 'pd_distribuidor'=>$compra->co_id_distribuidor,
			 'pd_compra'=>$compra->co_id,
			 'pd_pontos'=>$compra->co_total_pontos,
			 'pd_tipo'=>$tipo,
			 'pd_data'=>date('Y-m-d')
			 ));
			 
		 }
		 
		}
	
	}