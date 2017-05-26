<?php 
class Configuracao_model extends CI_Model{
	
	function get(){
		$rs =  $this->db->get("site_configuracao")->result();
	    return $rs[0];
		}
		
	function atualizar($dados){
		$this->db->update('site_configuracao',$dados);
		}	
	
	}
?>