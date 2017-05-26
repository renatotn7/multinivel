<?php 
class Estados extends CI_Controller{

function cidades(){
	
	$c = $this->db->where('ci_estado',$_POST['es_id'])->get('cidades')->result();
	echo json_encode($c);
	}	

function uf(){
	
	$c = $this->db->where('es_pais',$_POST['es_pais'])->get('estados')->result();
	echo json_encode($c);
	}	
}

?>