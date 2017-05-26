<?php  
class ControllerModuleNewslettersubscribe extends Controller{
  	private $error = array();
	
	public function index(){
	   $this->loadmodule();
	   
	   $this->load->model('account/newslettersubscribe');
	   //check db
	   $this->model_account_newslettersubscribe->check_db();
	   
	}
	
	public function subscribe(){
		
	
	if($this->config->get('newslettersubscribe_thickbox')){
	  $prefix_eval = "";
	}else{
	  $prefix_eval = "";
	}
	  
	  $this->language->load('module/newslettersubscribe');
	  
	  $this->load->model('account/newslettersubscribe');
	  
	  if(isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'],FILTER_VALIDATE_EMAIL)){
           
		   if($this->config->get('newslettersubscribe_registered') and $this->model_account_newslettersubscribe->checkRegisteredUser($this->request->post)){
			   
			   
			    $this->model_account_newslettersubscribe->UpdateRegisterUsers($this->request->post,1);
				
			echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('subscribe').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			   
		    
		   }else if(!$this->model_account_newslettersubscribe->checkmailid($this->request->post)){
			 
			 $this->model_account_newslettersubscribe->subscribe($this->request->post);
		     echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('subscribe').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			 
			 
				 if($this->config->get('newslettersubscribe_mail_status')){
			   
			    $subject = $this->language->get('mail_subject');	
				
				$message = '<table width="60%" cellpadding="2"  cellspacing="1" border="0"> 
				  	         <tr>
							   <td> Email Id </td>
							   <td> '.$this->request->post['subscribe_email'].' </td>
							 </tr>
				  	         <tr>
							   <td> Name  </td>
							   <td> '.$this->request->post['subscribe_name'].' </td>
							 </tr>';
				if(isset($this->request->post['option1'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field1').'</td> <td>'.$this->request->post['option1'].'</td> </tr>';  
				}
				if(isset($this->request->post['option2'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field2').'</td> <td>'.$this->request->post['option2'].'</td> </tr>';  
				}
				if(isset($this->request->post['option3'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field3').'</td> <td>'.$this->request->post['option3'].'</td> </tr>';  
				}
				if(isset($this->request->post['option4'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field4').'</td> <td>'.$this->request->post['option4'].'</td> </tr>';  
				}
				if(isset($this->request->post['option5'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field5').'</td> <td>'.$this->request->post['option5'].'</td> </tr>';  
				}
				if(isset($this->request->post['option6'])) {
				   $message .= '<tr> <td>'.$this->config->get('newslettersubscribe_option_field6').'</td> <td>'.$this->request->post['option6'].'</td> </tr>';  
				} 
				 $message .= '</table>';
	 
				$mail = new Mail();
				$mail->protocol = $this->config->get('config_mail_protocol');
				$mail->parameter = $this->config->get('config_mail_parameter');
				$mail->hostname = $this->config->get('config_smtp_host');
				$mail->username = $this->config->get('config_smtp_username');
				$mail->password = $this->config->get('config_smtp_password');
				$mail->port = $this->config->get('config_smtp_port');
				$mail->timeout = $this->config->get('config_smtp_timeout');				
				$mail->setTo($this->config->get('config_email'));
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($subject);
				$mail->setHtml($message);
				$mail->send();
			}
			 
		   }else{
	           echo('$("'.$prefix_eval.' #subscribe_result").html("<label class=\"label error-news\">'.$this->language->get('alreadyexist').'</label>");$("'.$prefix_eval.' #subscribe")[0].reset();');	 
		   }
		   
	  }else{
	           echo('$("'.$prefix_eval.' #subscribe_result").html("<span class=\"label error-news\">'.$this->language->get('error_invalid').'</span>")');
	       }
	}

	public function unsubscribe(){
	  
	  if($this->config->get('newslettersubscribe_thickbox')){
		  $prefix_eval = "#TB_ajaxContent ";
	  }else{
	      $prefix_eval = "";
	  }
	  
	  $this->language->load('module/newslettersubscribe');
	  
	  $this->load->model('account/newslettersubscribe');
	  
	  if(isset($this->request->post['subscribe_email']) and filter_var($this->request->post['subscribe_email'],FILTER_VALIDATE_EMAIL)){
            
		    if($this->config->get('newslettersubscribe_registered') and $this->model_account_newslettersubscribe->checkRegisteredUser($this->request->post)){
			   
			    $this->model_account_newslettersubscribe->UpdateRegisterUsers($this->request->post,0);
				
			echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('unsubscribe').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			   
		    
		   }else if(!$this->model_account_newslettersubscribe->checkmailid($this->request->post)){
			 
		     echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('notexist').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			 
		   }else{
			   
			  if($this->config->get('option_unsubscribe')) {
				 $this->model_account_newslettersubscribe->unsubscribe($this->request->post);
				 echo('$("'.$prefix_eval.' #subscribe_result").html("'.$this->language->get('unsubscribe').'");$("'.$prefix_eval.' #subscribe")[0].reset();');
			  }
		   }
		   
	  }else{
	    echo('$("'.$prefix_eval.' #subscribe_result").html("<span class=\"error\">'.$this->language->get('error_invalid').'</span>")');
	  }
	}

	protected function loadmodule() {
		$this->language->load('module/newslettersubscribe');

      	$this->data['heading_title'] = $this->language->get('heading_title');	
		
      	$this->data['entry_name'] = $this->language->get('entry_name');	
      	$this->data['entry_email'] = $this->language->get('entry_email');	
		
      	$this->data['entry_button'] = $this->language->get('entry_button');	
		
      	$this->data['entry_unbutton'] = $this->language->get('entry_unbutton');	
		
      	$this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');	
		
      	$this->data['option_fields'] = $this->config->get('newslettersubscribe_option_field');	
		
		$this->data['option_fields1'] = $this->config->get('newslettersubscribe_option_field1');	
		$this->data['option_fields2'] = $this->config->get('newslettersubscribe_option_field2');	
		$this->data['option_fields3'] = $this->config->get('newslettersubscribe_option_field3');	
		$this->data['option_fields4'] = $this->config->get('newslettersubscribe_option_field4');	
		$this->data['option_fields5'] = $this->config->get('newslettersubscribe_option_field5');	
		$this->data['option_fields6'] = $this->config->get('newslettersubscribe_option_field6');	
		
		$this->data['thickbox'] = $this->config->get('newslettersubscribe_thickbox');	
		
		$this->data['text_subscribe'] = $this->language->get('text_subscribe');	
		
		
		$this->id = 'newslettersubscribe';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newslettersubscribe.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/newslettersubscribe.tpl';
		} else {
			$this->template = 'default/template/module/newslettersubscribe.tpl';
		}
		
		$this->render();
	}
}
?>