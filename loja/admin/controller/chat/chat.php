<?php 
class ControllerChatChat extends Controller {
	public function index() {
		$this->data['title'] = $this->document->getTitle(); 
	
	   $this->load->model('chat/chat');
	   
	   $this->model_chat_chat->verifica_online();
	   
	   
	   $this->model_chat_chat->adim_online();
	   
	   $rs_conversa = $this->model_chat_chat->conversas();
	   $this->data['conversas'] = $rs_conversa->rows;
	   $this->data['num_rows'] = $rs_conversa->num_rows;
	   
	   
	   $this->children = array(
			'common/header',
			'common/footer'
		);
	   
	  $this->template = 'chat/chat.tpl'; 
	  $this->response->setOutput($this->render());
	
	}
	
	
	function logout_chat(){
		$this->load->model('chat/chat');
		$this->model_chat_chat->logout_chat();
		echo "<script type='text/javascript'>window.close()</script>";
		}
	
	
	public function conversa(){
		$this->load->model('chat/chat');
	    $rs_conversa = $this->model_chat_chat->get_conversa($this->request->get['ch_id']);
		$this->data['conversa'] = $rs_conversa->row;
		
		
		$this->template = 'chat/conversa.tpl';
		$this->response->setOutput($this->render());
		}
		

	public function so_conversa(){
		$this->load->model('chat/chat');
	    $rs_conversa = $this->model_chat_chat->get_conversa($this->request->get['ch_id']);
		echo $rs_conversa->row['ch_conversa'];
		}		

	public function nova_conversa(){
		$this->load->model('chat/chat');
		
		$rs_conversa = $this->model_chat_chat->get_conversa($this->request->get['ch_id']);
		
		
		$conversa = $rs_conversa->row['ch_conversa']."
		<div class=\"atendente\"><strong>".$this->user->getUserName()." <i>".date('H:i:s')."</i>: </strong> ".$this->request->post['msg']."</div>";
	    $this->model_chat_chat->nova_conversa($this->request->get['ch_id'],$conversa);
		echo $conversa;
		}	

		
}
?>