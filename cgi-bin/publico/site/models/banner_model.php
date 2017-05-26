<?php 
class Banner_model extends CI_Model{

public function get_banner(){
	return $this->db->order_by("coluna","ASC")->get("site_banner")->result();
	}	
}
?>