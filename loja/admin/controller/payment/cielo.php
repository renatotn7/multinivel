<?php 
class ControllerPaymentCielo extends Controller {
 	private $error = array(); 
	//Esse array contem todos os campos do modulo
	private $array_campos = array('cielo_numero','cielo_order','cielo_chave','cielo_endereco_base','cielo_usar_visa','cielo_usar_visa_electron','cielo_usar_mastercard','cielo_usar_elo','cielo_parcelas','cielo_juros','cielo_parcelamento','cielo_captura','cielo_autorizacao','cielo_sort_order','cielo_status');
	
	public function index() {
		$this->load->language('payment/cielo');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('cielo', $this->request->post);				
		  	
			$this->session->data['success'] = $this->language->get('text_success');
		  	
			$this->redirect($this->url->link('payment/cielo', 'token=' . $this->session->data['token'], 'SSL'));			
		}
		
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		
		//O metodo get_all não existe na versão original;
		//O metodo foi criado na Class no diretorio system/library/language.php
		$linguagem = $this->language->get_all();
		foreach($linguagem as $index=> $value){
			$this->data[$index] = $value;
		}
		
		
		//Mensagem de erro
		if (isset($this->error['warning'])) {
		  $this->data['error_warning'] = $this->error['warning'];
		} else {
		  $this->data['error_warning'] = '';
		}
	
		
		
		
		//Links do caminho	
		$this->data['breadcrumbs'] = array();
		
		$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),  
				'text'      => $this->language->get('text_home'),
				'separator' => false
		);
		
		
		$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
				'text'      => $this->language->get('text_payment'),
				'separator' => ' :: '
		);
		
		$this->data['breadcrumbs'][] = array(
				'href'      => $this->url->link('payment/pagseguro', 'token=' . $this->session->data['token'], 'SSL'),
				'text'      => $this->language->get('heading_title'),
				'separator' => ' :: '
		);
		
		
		//Ações do furmulario			
		$this->data['action'] = $this->url->link('payment/cielo', 'token=' . $this->session->data['token'], 'SSL');
			
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		
		foreach($this->array_campos as $campo){
			//Para vir carregada as informações
			if (isset($this->request->post[$campo])) {
			  $this->data[$campo] = $this->request->post[$campo];
			} else {
			  $this->data[$campo] = $this->config->get($campo); 
			}
		
		}



		$this->template = 'payment/cielo.tpl';
		$this->children = array(
				'common/header',	
				'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	
	
	
	
	private function validate() {
	
		if (!$this->user->hasPermission('modify', 'payment/cielo')) {
		  $this->error['warning'] = $this->language->get('error_permission');
		}
			
		
		if (!$this->error) {
		  return TRUE;
		} else {
		  return FALSE;
		}	
	}
}
?>