<?php

class Lib_bonus{
	
	private $ci;
	
	function __construct(){
		$this->ci =& get_instance();
		}
		
	public function debitar_bonus($id_distribuidor,$valor,$descricao='',$id_compra=0,$tipo){
		 $this->ci->db->insert('conta_bonus',array(
		 'cb_distribuidor'=>$id_distribuidor,
		 'cb_debito'=>$valor,
		 'cb_credito'=>0.0,
		 'cb_tipo'=>$tipo,
		 'cb_descricao'=>$descricao,
		 'cb_compra'=>$id_compra
		 ));	
		}
		
		
	public function depositar_cd($id_cd,$valor,$descricao='',$venda=0){
		 $this->ci->db->insert('conta_cd',array(
			'cc_venda'=>$venda,
			'cc_compra'=>0,
			'cc_descricao'=>$descricao,
			'cc_credito'=>$valor,
			'cc_cd'=>$id_cd
		 ));
		}


	public function depositar_fabrica($id_di,$valor,$descricao='',$venda=0){
		 $this->ci->db->insert('conta_fabrica',array(
			'ct_venda'=>$venda,
			'ct_id_distribuidor'=>$id_di,
			'ct_data'=>date('Y-m-d'),
			'ct_descricao'=>$descricao,
			'ct_credito'=>$valor,
			'ct_fabrica'=>1
		 ));
		}

					
	
	}