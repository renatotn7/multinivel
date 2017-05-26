<?php
/*
* Módulo de Pagamento Boleto Bancário Banco Bradesco
* Feito sobre OpenCart 1.5.3.1
* Autor Valdeir S. <valdeirpsr@hotmail.com.br>
* Sob licença GPL.
**/
class ControllerPaymentBoletoBradesco extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/boleto_bradesco');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('boleto_bradesco', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');		
		
		$this->data['text_approved'] = $this->language->get('text_approved');
		$this->data['text_declined'] = $this->language->get('text_declined');
		$this->data['text_off'] = $this->language->get('text_off');
	
		$this->data['entry_logo'] = $this->language->get('entry_logo');
		$this->data['entry_identificacao'] = $this->language->get('entry_identificacao');
		$this->data['entry_cpf_cnpj'] = $this->language->get('entry_cpf_cnpj');
		$this->data['entry_endereco'] = $this->language->get('entry_endereco');
		$this->data['entry_cidade_uf'] = $this->language->get('entry_cidade_uf');
		$this->data['entry_cedente'] = $this->language->get('entry_cedente');
		$this->data['entry_agencia'] = $this->language->get('entry_agencia');
		$this->data['entry_conta'] = $this->language->get('entry_conta');
		$this->data['entry_conta_cedente'] = $this->language->get('entry_conta_cedente');
		
		$this->data['entry_formatacao_convenio'] = $this->language->get('entry_formatacao_convenio');
		$this->data['entry_aceite'] = $this->language->get('entry_aceite');
		
		$this->data['entry_carteira'] = $this->language->get('entry_carteira');
		$this->data['entry_dia_prazo_pg'] = $this->language->get('entry_dia_prazo_pg');
		$this->data['entry_taxa_boleto'] = $this->language->get('entry_taxa_boleto');
		$this->data['entry_nosso_numero'] = $this->language->get('entry_nosso_numero');
		
		$this->data['entry_demonstrativo1'] = $this->language->get('entry_demonstrativo1');
		$this->data['entry_demonstrativo2'] = $this->language->get('entry_demonstrativo2');
		$this->data['entry_demonstrativo3'] = $this->language->get('entry_demonstrativo3');
		$this->data['entry_instrucoes1'] = $this->language->get('entry_instrucoes1');
		$this->data['entry_instrucoes2'] = $this->language->get('entry_instrucoes2');
		$this->data['entry_instrucoes3'] = $this->language->get('entry_instrucoes3');
		$this->data['entry_instrucoes4'] = $this->language->get('entry_instrucoes4');
				
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');
		
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
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/boleto_bradesco', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/boleto_bradesco', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['boleto_bradesco_logo'])) {
			$this->data['boleto_bradesco_logo'] = $this->request->post['boleto_bradesco_logo'];
		} else {
			$this->data['boleto_bradesco_logo'] = $this->config->get('boleto_bradesco_logo');
		}
		
		if (isset($this->request->post['boleto_bradesco_identificacao'])) {
			$this->data['boleto_bradesco_identificacao'] = $this->request->post['boleto_bradesco_identificacao'];
		} else {
			$this->data['boleto_bradesco_identificacao'] = $this->config->get('boleto_bradesco_identificacao');
		}

		if (isset($this->request->post['boleto_bradesco_cpf_cnpj'])) {
			$this->data['boleto_bradesco_cpf_cnpj'] = $this->request->post['boleto_bradesco_cpf_cnpj'];
		} else {
			$this->data['boleto_bradesco_cpf_cnpj'] = $this->config->get('boleto_bradesco_cpf_cnpj');
		}

		if (isset($this->request->post['boleto_bradesco_endereco'])) {
			$this->data['boleto_bradesco_endereco'] = $this->request->post['boleto_bradesco_endereco'];
		} else {
			$this->data['boleto_bradesco_endereco'] = $this->config->get('boleto_bradesco_endereco');
		}

		if (isset($this->request->post['boleto_bradesco_cidade_uf'])) {
			$this->data['boleto_bradesco_cidade_uf'] = $this->request->post['boleto_bradesco_cidade_uf'];
		} else {
			$this->data['boleto_bradesco_cidade_uf'] = $this->config->get('boleto_bradesco_cidade_uf');
		}

		if (isset($this->request->post['boleto_bradesco_cedente'])) {
			$this->data['boleto_bradesco_cedente'] = $this->request->post['boleto_bradesco_cedente'];
		} else {
			$this->data['boleto_bradesco_cedente'] = $this->config->get('boleto_bradesco_cedente');
		}
		
		if (isset($this->request->post['boleto_bradesco_agencia'])) {
			$this->data['boleto_bradesco_agencia'] = $this->request->post['boleto_bradesco_agencia'];
		} else {
			$this->data['boleto_bradesco_agencia'] = $this->config->get('boleto_bradesco_agencia');
		}
		
		if (isset($this->request->post['boleto_bradesco_conta'])) {
			$this->data['boleto_bradesco_conta'] = $this->request->post['boleto_bradesco_conta'];
		} else {
			$this->data['boleto_bradesco_conta'] = $this->config->get('boleto_bradesco_conta');
		}

		if (isset($this->request->post['boleto_bradesco_conta_cedente'])) {
			$this->data['boleto_bradesco_conta_cedente'] = $this->request->post['boleto_bradesco_conta_cedente'];
		} else {
			$this->data['boleto_bradesco_conta_cedente'] = $this->config->get('boleto_bradesco_conta_cedente');
		}
		
		if (isset($this->request->post['boleto_bradesco_aceite'])) {
			$this->data['boleto_bradesco_aceite'] = $this->request->post['boleto_bradesco_aceite'];
		} else {
			$this->data['boleto_bradesco_aceite'] = $this->config->get('boleto_bradesco_aceite');
		}

		if (isset($this->request->post['boleto_bradesco_carteira'])) {
			$this->data['boleto_bradesco_carteira'] = $this->request->post['boleto_bradesco_carteira'];
		} else {
			$this->data['boleto_bradesco_carteira'] = $this->config->get('boleto_bradesco_carteira');
		}

		if (isset($this->request->post['boleto_bradesco_dia_prazo_pg'])) {
			$this->data['boleto_bradesco_dia_prazo_pg'] = $this->request->post['boleto_bradesco_dia_prazo_pg'];
		} else {
			$this->data['boleto_bradesco_dia_prazo_pg'] = $this->config->get('boleto_bradesco_dia_prazo_pg');
		}
			if (isset($this->request->post['boleto_bradesco_taxa_boleto'])) {
			$this->data['boleto_bradesco_taxa_boleto'] = $this->request->post['boleto_bradesco_taxa_boleto'];
		} else {
			$this->data['boleto_bradesco_taxa_boleto'] = $this->config->get('boleto_bradesco_taxa_boleto');
		}
			if (isset($this->request->post['boleto_bradesco_nosso_numero'])) {
			$this->data['boleto_bradesco_nosso_numero'] = $this->request->post['boleto_bradesco_nosso_numero'];
		} else {
			$this->data['boleto_bradesco_nosso_numero'] = $this->config->get('boleto_bradesco_nosso_numero');
		}

		if (isset($this->request->post['boleto_bradesco_demonstrativo1'])) {
			$this->data['boleto_bradesco_demonstrativo1'] = $this->request->post['boleto_bradesco_demonstrativo1'];
		} else {
			$this->data['boleto_bradesco_demonstrativo1'] = $this->config->get('boleto_bradesco_demonstrativo1');
		}
		if (isset($this->request->post['boleto_bradesco_demonstrativo2'])) {
			$this->data['boleto_bradesco_demonstrativo2'] = $this->request->post['boleto_bradesco_demonstrativo2'];
		} else {
			$this->data['boleto_bradesco_demonstrativo2'] = $this->config->get('boleto_bradesco_demonstrativo2');
		}
		if (isset($this->request->post['boleto_bradesco_demonstrativo3'])) {
			$this->data['boleto_bradesco_demonstrativo3'] = $this->request->post['boleto_bradesco_demonstrativo3'];
		} else {
			$this->data['boleto_bradesco_demonstrativo3'] = $this->config->get('boleto_bradesco_demonstrativo3');
		}
		if (isset($this->request->post['boleto_bradesco_instrucoes1'])) {
			$this->data['boleto_bradesco_instrucoes1'] = $this->request->post['boleto_bradesco_instrucoes1'];
		} else {
			$this->data['boleto_bradesco_instrucoes1'] = $this->config->get('boleto_bradesco_instrucoes1');
		}
		if (isset($this->request->post['boleto_bradesco_instrucoes2'])) {
			$this->data['boleto_bradesco_instrucoes2'] = $this->request->post['boleto_bradesco_instrucoes2'];
		} else {
			$this->data['boleto_bradesco_instrucoes2'] = $this->config->get('boleto_bradesco_instrucoes2');
		}
		if (isset($this->request->post['boleto_bradesco_instrucoes3'])) {
			$this->data['boleto_bradesco_instrucoes3'] = $this->request->post['boleto_bradesco_instrucoes3'];
		} else {
			$this->data['boleto_bradesco_instrucoes3'] = $this->config->get('boleto_bradesco_instrucoes3');
		}
		
		if (isset($this->request->post['boleto_bradesco_instrucoes4'])) {
			$this->data['boleto_bradesco_instrucoes4'] = $this->request->post['boleto_bradesco_instrucoes4'];
		} else {
			$this->data['boleto_bradesco_instrucoes4'] = $this->config->get('boleto_bradesco_instrucoes4');
		}		
		
		if (isset($this->request->post['boleto_bradesco_order_status_id'])) {
			$this->data['boleto_bradesco_order_status_id'] = $this->request->post['boleto_bradesco_order_status_id'];
		} else {
			$this->data['boleto_bradesco_order_status_id'] = $this->config->get('boleto_bradesco_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['boleto_bradesco_geo_zone_id'])) {
			$this->data['boleto_bradesco_geo_zone_id'] = $this->request->post['boleto_bradesco_geo_zone_id'];
		} else {
			$this->data['boleto_bradesco_geo_zone_id'] = $this->config->get('boleto_bradesco_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['boleto_bradesco_status'])) {
			$this->data['boleto_bradesco_status'] = $this->request->post['boleto_bradesco_status'];
		} else {
			$this->data['boleto_bradesco_status'] = $this->config->get('boleto_bradesco_status');
		}
		
		if (isset($this->request->post['boleto_bradesco_sort_order'])) {
			$this->data['boleto_bradesco_sort_order'] = $this->request->post['boleto_bradesco_sort_order'];
		} else {
			$this->data['boleto_bradesco_sort_order'] = $this->config->get('boleto_bradesco_sort_order');
		}

		$this->template = 'payment/boleto_bradesco.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/boleto_bradesco')) {
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