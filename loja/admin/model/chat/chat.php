<?php
class ModelChatChat extends Model {
	public function conversas() {
	  return $this->db->query("SELECT * FROM ".DB_PREFIX."chat ORDER BY ch_status DESC, ch_id DESC LIMIT 30");		
	}

	public function get_conversa($id) {
	  return $this->db->query("SELECT * FROM ".DB_PREFIX."chat WHERE ch_id = ".$id);		
	}


	public function nova_conversa($id,$conversa) {	
	    return $this->db->query("UPDATE ".DB_PREFIX."chat SET ch_conversa = '".addslashes($conversa)."' WHERE ch_id = ".$id);		
	}
	
	
	public function logout_chat(){
		$this->db->query("UPDATE ".DB_PREFIX."setting SET value = '0'  WHERE ".DB_PREFIX."setting.key = 'chat_admin_online'");
		$this->db->query("UPDATE ".DB_PREFIX."setting SET value = '".mktime(0,0,0,01,01,1990)."'  WHERE ".DB_PREFIX."setting.key = 'chat_ping'");
		}
	
	public function adim_online(){
		
		$cadastrado = $this->db->query("SELECT * FROM ".DB_PREFIX."setting  WHERE ".DB_PREFIX."setting.group ='chat' AND ".DB_PREFIX."setting.key = 'chat_ping'")->num_rows;
		if($cadastrado==0){
			$this->db->query("INSERT INTO ".DB_PREFIX."setting VALUES(null,1,'chat','chat_ping','".time()."',0)");
			$this->db->query("INSERT INTO ".DB_PREFIX."setting VALUES(null,1,'chat','chat_admin_online','1',0)");
			}
			
		$this->db->query("UPDATE ".DB_PREFIX."setting SET value = '1'  WHERE ".DB_PREFIX."setting.key = 'chat_admin_online'");
		$this->db->query("UPDATE ".DB_PREFIX."setting SET value = '".time()."'  WHERE ".DB_PREFIX."setting.key = 'chat_ping'");
		}
	
	
	public function verifica_online(){
		 
		  $em_conversa = $this->db->query("SELECT * FROM ".DB_PREFIX."chat WHERE ch_status = 1")->rows;
		  foreach($em_conversa as $c){
			   $segundos = time()-$c['ch_ping']; 
			   $minutos = $segundos/60;
			   
			   if($minutos >=5){
				    $this->db->query("UPDATE ".DB_PREFIX."chat SET ch_status = 0 WHERE ch_id = ".$c['ch_id']);
				   }
			  }	
		  
		}
		
}
?>