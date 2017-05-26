<?php 

class ModelChatChat extends Model{



	public function get_conversa($id) {
	  return $this->db->query("SELECT * FROM ".DB_PREFIX."chat WHERE ch_id = ".$id);		
	}


	public function nova_conversa($id,$conversa) {	
	    return $this->db->query("UPDATE ".DB_PREFIX."chat SET ch_conversa = '".addslashes($conversa)."', ch_ping = ".time().",ch_status = 1 WHERE ch_id = ".$id);		
	}

   public function iniciar_nova_conversa($dados){
	   $conversa = "<div class=\"atendido\"><strong>".$dados['ch_nome'].": </strong> ".$dados['ch_conversa']."</div>";
	   $this->db->query("INSERT INTO ".DB_PREFIX."chat values(NULL,'{$dados['ch_nome']}','{$dados['ch_email']}','{$dados['ch_assunto']}','{$conversa}',CURRENT_TIMESTAMP,".time().",1)");
	   return $this->db->getLastId();
	   }

   public function sair_chat($id){
	   $this->db->query("UPDATE ".DB_PREFIX."chat SET ch_status = 0 WHERE ch_id = ".$id);
	   }

	public function chat_online(){
		 $ping = $this->db->query("SELECT value FROM ".DB_PREFIX."setting WHERE ".DB_PREFIX."setting.key = 'chat_ping'")->row;
		  
		   
		  
			   $segundos = time()-$ping['value']; 
			   $minutos = $segundos/60;
			   if($minutos >=5){
				    $this->db->query("UPDATE ".DB_PREFIX."setting SET value = '0'  WHERE ".DB_PREFIX."setting.key = 'chat_admin_online'");
				   return false;
				   }else{
					   return true;
					   }
			  
		
		}
		
}