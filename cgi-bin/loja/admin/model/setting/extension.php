<?php
class ModelSettingExtension extends Model {
	public function getInstalled($type) {
		$extension_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "'");
		
		foreach ($query->rows as $result) {
			$extension_data[] = $result['code'];
		}
		
		return $extension_data;
	}
	
	public function install($type, $code) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $this->db->escape($type) . "', `code` = '" . $this->db->escape($code) . "'");
		
		$jaCadastrado = $this->db->query("
		SELECT * FROM ".DB_PREFIX."payment_method 
	        WHERE group_name = '".$this->db->escape($code)."'
		")->row;
		
		 $this->language->load('payment/'.$this->db->escape($code));
		 $description = $this->language->get('heading_title');

		
		if(!$jaCadastrado){
			        $this->language->load('payment/'.$this->db->escape($code));
				$this->db->query("INSERT INTO ".DB_PREFIX."payment_method(group_name,description,status) 
				 VALUES('".$this->db->escape($code)."','".$description ."',1)");
                                }else{
                                    $this->db->query("UPDATE loja_payment_method SET status = 1 WHERE `group_name` = '" . $this->db->escape($code) . "'");
                                }
		
	}
	
	public function uninstall($type, $code) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type` = '" . $this->db->escape($type) . "' AND `code` = '" . $this->db->escape($code) . "'");            
                $this->db->query("UPDATE loja_payment_method SET status = 0 WHERE `group_name` = '" . $this->db->escape($code) . "'");
	}
}
?>