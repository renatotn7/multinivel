<?php

class Lib_bonus{
	
	private $ci;
	
	function __construct(){
		$this->ci =& get_instance();
		}
		
	public function debitar_bonus($id_distribuidor,$valor,$descricao='',$id_compra=0){
		 $this->ci->db->insert('conta_bonus',array(
		 'cb_distribuidor'=>$id_distribuidor,
		 'cb_debito'=>$valor,
		 'cb_credito'=>0.0,
		 'cb_descricao'=>$descricao,
		 'cb_compra'=>$id_compra,
		 'cb_data'=>date('Y-m-01')
		 ));	
		}
		
		
	public function depositar_cd($id_cd,$valor){
		}
		
	public function depositar_fabrica($valor,$id_distribuidor,$id_cd=0){ 
		}			
	
	}