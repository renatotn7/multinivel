<?php 
class Estados extends CI_Controller{

function cidades(){
	
	$c = $this->db->where('ci_estado',$_POST['es_id'])->get('cidades')->result();
	echo json_encode($c);
	}
	
public function filtrar_cd_ajax(){
	 
	$cd = $this->db->where('cd_uf',$_POST['estado'])->get('cd')->result();
	
	if(count($cd)==0){echo "<option value=''>Nenhum CD nesse estado</option>"; }
	
	foreach($cd as $c){
	 echo "<option value='".$c->cd_id."'>".$c->cd_nome." - ".$c->cd_responsavel_nome."</option>";
    }
	}
			
}

?>