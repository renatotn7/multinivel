<?php
class ControllerModuleNewslettersubscribe extends Controller {
	private $error = array(); 
	
	public function index() {   
	
		$this->load->language('module/newslettersubscribe');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			$this->model_setting_setting->editSetting('newslettersubscribe', $this->request->post);		
					 
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
		
		$this->data['entry_admin'] = $this->language->get('entry_admin');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_unsubscribe'] = $this->language->get('entry_unsubscribe');
		$this->data['entry_thickbox'] = $this->language->get('entry_thickbox');
		$this->data['entry_registered'] = $this->language->get('entry_registered');	
		$this->data['entry_mail'] = $this->language->get('entry_mail');
		$this->data['entry_options'] = $this->language->get('entry_options');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/newslettersubscribe', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/newslettersubscribe', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		
		if (isset($this->request->post['option_unsubscribe'])) {
			$this->data['option_unsubscribe'] = $this->request->post['option_unsubscribe'];
		} else {
			$this->data['option_unsubscribe'] = $this->config->get('option_unsubscribe');
		}
		
		if (isset($this->request->post['newslettersubscribe_registered'])) {
			$this->data['newslettersubscribe_registered'] = $this->request->post['newslettersubscribe_registered'];
		} else {
			$this->data['newslettersubscribe_registered'] = $this->config->get('newslettersubscribe_registered');
		}
		
		if (isset($this->request->post['newslettersubscribe_mail_status'])) {
			$this->data['newslettersubscribe_mail_status'] = $this->request->post['newslettersubscribe_mail_status'];
		} else {
			$this->data['newslettersubscribe_mail_status'] = $this->config->get('newslettersubscribe_mail_status');
		}
		
		if (isset($this->request->post['newslettersubscribe_thickbox'])) {
			$this->data['newslettersubscribe_thickbox'] = $this->request->post['newslettersubscribe_thickbox'];
		} else {
			$this->data['newslettersubscribe_thickbox'] = $this->config->get('newslettersubscribe_thickbox');
		}
		
		if (isset($this->request->post['newslettersubscribe_option_field'])) {
			$this->data['newslettersubscribe_option_field'] = $this->request->post['newslettersubscribe_option_field'];
		} else {
			$this->data['newslettersubscribe_option_field'] = $this->config->get('newslettersubscribe_option_field');
		}
		
		if (isset($this->request->post['newslettersubscribe_option_field1'])) {
			$this->data['newslettersubscribe_option_field1'] = $this->request->post['newslettersubscribe_option_field1'];
		} else {
			$this->data['newslettersubscribe_option_field1'] = $this->config->get('newslettersubscribe_option_field1');
		}
		
		if (isset($this->request->post['newslettersubscribe_option_field2'])) {
			$this->data['newslettersubscribe_option_field2'] = $this->request->post['newslettersubscribe_option_field2'];
		} else {
			$this->data['newslettersubscribe_option_field2'] = $this->config->get('newslettersubscribe_option_field2');
		}
		
		if (isset($this->request->post['newslettersubscribe_option_field3'])) {
			$this->data['newslettersubscribe_option_field3'] = $this->request->post['newslettersubscribe_option_field3'];
		} else {
			$this->data['newslettersubscribe_option_field3'] = $this->config->get('newslettersubscribe_option_field3');
		}
		
		if (isset($this->request->post['newslettersubscribe_option_field4'])) {
			$this->data['newslettersubscribe_option_field4'] = $this->request->post['newslettersubscribe_option_field4'];
		} else {
			$this->data['newslettersubscribe_option_field4'] = $this->config->get('newslettersubscribe_option_field4');
		}
		
		if (isset($this->request->post['newslettersubscribe_option_field5'])) {
			$this->data['newslettersubscribe_option_field5'] = $this->request->post['newslettersubscribe_option_field5'];
		} else {
			$this->data['newslettersubscribe_option_field5'] = $this->config->get('newslettersubscribe_option_field5');
		}
		
		if (isset($this->request->post['newslettersubscribe_option_field6'])) {
			$this->data['newslettersubscribe_option_field6'] = $this->request->post['newslettersubscribe_option_field6'];
		} else {
			$this->data['newslettersubscribe_option_field6'] = $this->config->get('newslettersubscribe_option_field6');
		}	
			
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();
		
		$this->data['modules'] = array();
				
		if (isset($this->request->post['newslettersubscribe_module'])) {
			$this->data['modules'] = $this->request->post['newslettersubscribe_module'];
		} elseif ($this->config->get('newslettersubscribe_module')) { 
			$this->data['modules'] = $this->config->get('newslettersubscribe_module');
		}	
		
		if (isset($this->request->post['newslettersubscribe_module'])) {
			$this->data['newslettersubscribe_module'] = $this->request->post['newslettersubscribe_module'];
		} else {
			$this->data['newslettersubscribe_module'] = $this->config->get('newslettersubscribe_module');
		}
				
		$this->template = 'module/newslettersubscribe.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/newslettersubscribe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>