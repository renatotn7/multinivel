<?php
class Troca_usuario extends CI_Controller{
	
	public function trocar(){
		
		$de = "jbneres";
		$para = "jonasneres";
		$this->db->query("UPDATE distribuidores SET di_usuario = '".$para."' WHERE di_usuario = '".$de."'");
		$this->db->query("UPDATE distribuidores SET di_usuario_patrocinador = '".$para."' WHERE di_usuario_patrocinador = '".$de."'");
		
		}
	
	}