<?php

class ControllerPaymentBoletocef extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/boletocef');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('boletocef', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_logo_url'] = $this->language->get('logo_url');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
						
		$this->data['entry_num_conta_key'] = $this->language->get('entry_public_key');
		$this->data['entry_dias_pag_key'] = $this->language->get('entry_public_key');
		$this->data['entry_cod_ced_key'] = $this->language->get('entry_public_key');
		$this->data['entry_num_agencia_key'] = $this->language->get('entry_public_key');
		$this->data['entry_name_key'] = $this->language->get('entry_name_key');
		$this->data['entry_cnpj_key'] = $this->language->get('entry_cnpj_key');
		$this->data['entry_adress_key'] = $this->language->get('entry_adress_key');
		$this->data['entry_uf_key'] = $this->language->get('entry_uf_key');
		
		$this->data['entry_dig_ret'] = $this->language->get('entry_dig_ret');
				
		$this->data['entry_private_key'] = $this->language->get('entry_private_key');
		$this->data['entry_test'] = $this->language->get('entry_test'); 
		$this->data['entry_completed_status'] = $this->language->get('entry_completed_status'); 
		$this->data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$this->data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['entry_value_taxa'] = $this->language->get('entry_value_taxa');
		
		$this->data['text_boletocef_instrucao1'] = $this->language->get('text_boletocef_instrucao1');
		$this->data['text_boletocef_instrucao2'] = $this->language->get('text_boletocef_instrucao2');
		$this->data['text_boletocef_instrucao3'] = $this->language->get('text_boletocef_instrucao3');
		
		$this->data['text_boletocef_demo1'] = $this->language->get('text_boletocef_demo1');
		$this->data['text_boletocef_demo2'] = $this->language->get('text_boletocef_demo2');
		$this->data['text_boletocef_demo3'] = $this->language->get('text_boletocef_demo3');
		
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->error['entry_value_taxa'])) {
			$this->data['error_entry_value_taxa'] = $this->error['entry_value_taxa'];
		} else {
			$this->data['error_entry_value_taxa'] = '';
		}
		
		if (isset($this->error['entry_value_taxa'])) {
			$this->data['error_entry_value_taxa'] = $this->error['entry_value_taxa'];
		} else {
			$this->data['error_entry_value_taxa'] = '';
		}
		
		if (isset($this->error['name_key'])) {
			$this->data['error_name_key'] = $this->error['name_key'];
		} else {
			$this->data['error_name_key'] = '';
		}
		
		if (isset($this->error['adress_key'])) {
			$this->data['error_adress_key'] = $this->error['adress_key'];
		} else {
			$this->data['error_adress_key'] = '';
		}
		
		if (isset($this->error['uf_key'])) {
			$this->data['error_uf_key'] = $this->error['uf_key'];
		} else {
			$this->data['error_uf_key'] = '';
		}
		
		if (isset($this->error['logo_url'])) {
			$this->data['error_logo_url'] = $this->error['logo_url'];
		} else {
			$this->data['error_logo_url'] = '';
		}
		
		if (isset($this->error['cnpj_key'])) {
			$this->data['error_cnpj_key'] = $this->error['cnpj_key'];
		} else {
			$this->data['error_cnpj_key'] = '';
		}
		
		if (isset($this->error['num_conta_key'])) {
			$this->data['error_num_conta_key'] = $this->error['num_conta_key'];
		} else {
			$this->data['error_num_conta_key'] = '';
		}
		
		if (isset($this->error['num_conta_dig_key'])) {
			$this->data['error_num_conta_dig_key'] = $this->error['num_conta_dig_key'];
		} else {
			$this->data['error_num_conta_dig_key'] = '';
		}
		
		
		if (isset($this->error['num_agencia_key'])) {
			$this->data['error_num_agencia_key'] = $this->error['num_agencia_key'];
		} else {
			$this->data['error_num_agencia_key'] = '';
		}
		
		if (isset($this->error['cod_ced_key'])) {
			$this->data['error_cod_ced_key'] = $this->error['cod_ced_key'];
		} else {
			$this->data['error_cod_ced_key'] = '';
		}
		
		if (isset($this->error['dig_ret'])) {
			$this->data['error_dig_ret'] = $this->error['dig_ret'];
		} else {
			$this->data['error_dig_ret'] = '';
		}
		
		if (isset($this->error['dias_pag_key'])) {
			$this->data['error_dias_pag_key'] = $this->error['dias_pag_key'];
		} else {
			$this->data['error_dias_pag_key'] = '';
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
			'href'      => $this->url->link('payment/boletocef', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/boletocef', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['boletocef_demo1'])) {
			$this->data['boletocef_demo1'] = $this->request->post['boletocef_demo1'];
		} else {
			$this->data['boletocef_demo1'] = $this->config->get('boletocef_demo1');
		}
		
		if (isset($this->request->post['boletocef_demo2'])) {
			$this->data['boletocef_demo2'] = $this->request->post['boletocef_demo2'];
		} else {
			$this->data['boletocef_demo2'] = $this->config->get('boletocef_demo2');
		}
		
		if (isset($this->request->post['boletocef_demo3'])) {
			$this->data['boletocef_demo3'] = $this->request->post['boletocef_demo3'];
		} else {
			$this->data['boletocef_demo3'] = $this->config->get('boletocef_demo3');
		}
		
		if (isset($this->request->post['boletocef_name_key'])) {
			$this->data['boletocef_name_key'] = $this->request->post['boletocef_name_key'];
		} else {
			$this->data['boletocef_name_key'] = $this->config->get('boletocef_name_key');
		}
		
		if (isset($this->request->post['boletocef_instrucao3'])) {
			$this->data['boletocef_instrucao3'] = $this->request->post['boletocef_instrucao3'];
		} else {
			$this->data['boletocef_instrucao3'] = $this->config->get('boletocef_instrucao3');
		}
		
		if (isset($this->request->post['boletocef_instrucao2'])) {
			$this->data['boletocef_instrucao2'] = $this->request->post['boletocef_instrucao2'];
		} else {
			$this->data['boletocef_instrucao2'] = $this->config->get('boletocef_instrucao2');
		}
		
		if (isset($this->request->post['boletocef_instrucao1'])) {
			$this->data['boletocef_instrucao1'] = $this->request->post['boletocef_instrucao1'];
		} else {
			$this->data['boletocef_instrucao1'] = $this->config->get('boletocef_instrucao1');
		}
		
		if (isset($this->request->post['boletocef_adress_key'])) {
			$this->data['boletocef_adress_key'] = $this->request->post['boletocef_adress_key'];
		} else {
			$this->data['boletocef_adress_key'] = $this->config->get('boletocef_adress_key');
		}
			
		if (isset($this->request->post['logo_url'])) {
			$this->data['logo_url'] = $this->request->post['logo_url'];
		} else {
			$this->data['logo_url'] = $this->config->get('logo_url');
		}
		
		if (isset($this->request->post['boletocef_uf_key'])) {
			$this->data['boletocef_uf_key'] = $this->request->post['boletocef_uf_key'];
		} else {
			$this->data['boletocef_uf_key'] = $this->config->get('boletocef_uf_key');
		}
		
		if (isset($this->request->post['boletocef_cnpj_key'])) {
			$this->data['boletocef_cnpj_key'] = $this->request->post['boletocef_cnpj_key'];
		} else {
			$this->data['boletocef_cnpj_key'] = $this->config->get('boletocef_cnpj_key');
		}
		
		if (isset($this->request->post['boletocef_num_agencia_key'])) {
			$this->data['boletocef_num_agencia_key'] = $this->request->post['boletocef_num_agencia_key'];
		} else {
			$this->data['boletocef_num_agencia_key'] = $this->config->get('boletocef_num_agencia_key');
		}
		
		if (isset($this->request->post['boletocef_num_conta_key'])) {
			$this->data['boletocef_num_conta_key'] = $this->request->post['boletocef_num_conta_key'];
		} else {
			$this->data['boletocef_num_conta_key'] = $this->config->get('boletocef_num_conta_key');
		}
		
		if (isset($this->request->post['boletocef_value_taxa'])) {
			$this->data['boletocef_value_taxa'] = $this->request->post['boletocef_value_taxa'];
		} else {
			$this->data['boletocef_value_taxa'] = $this->config->get('boletocef_value_taxa');
		}
		
		if (isset($this->request->post['boletocef_num_conta_dig_key'])) {
			$this->data['boletocef_num_conta_dig_key'] = $this->request->post['boletocef_num_conta_dig_key'];
		} else {
			$this->data['boletocef_num_conta_dig_key'] = $this->config->get('boletocef_num_conta_dig_key');
		}
		
		if (isset($this->request->post['boletocef_cod_ced_key'])) {
			$this->data['boletocef_cod_ced_key'] = $this->request->post['boletocef_cod_ced_key'];
		} else {
			$this->data['boletocef_cod_ced_key'] = $this->config->get('boletocef_cod_ced_key');
		}
		
		if (isset($this->request->post['boletocef_dias_pag_key'])) {
			$this->data['boletocef_dias_pag_key'] = $this->request->post['boletocef_dias_pag_key'];
		} else {
			$this->data['boletocef_dias_pag_key'] = $this->config->get('boletocef_dias_pag_key');
		}
		
		if (isset($this->request->post['dig_ret'])) {
			$this->data['dig_ret'] = $this->request->post['dig_ret'];
		} else {
			$this->data['dig_ret'] = $this->config->get('dig_ret');
		}
						
		if (isset($this->request->post['boletocef_test'])) {
			$this->data['boletocef_test'] = $this->request->post['boletocef_test'];
		} else {
			$this->data['boletocef_test'] = $this->config->get('boletocef_test');
		}
				
		if (isset($this->request->post['boletocef_completed_status_id'])) {
			$this->data['boletocef_completed_status_id'] = $this->request->post['boletocef_completed_status_id'];
		} else {
			$this->data['boletocef_completed_status_id'] = $this->config->get('boletocef_completed_status_id');
		}	
		 
		if (isset($this->request->post['boletocef_failed_status_id'])) {
			$this->data['boletocef_failed_status_id'] = $this->request->post['boletocef_failed_status_id'];
		} else {
			$this->data['boletocef_failed_status_id'] = $this->config->get('boletocef_failed_status_id');
		}	
								
		if (isset($this->request->post['boletocef_pending_status_id'])) {
			$this->data['boletocef_pending_status_id'] = $this->request->post['boletocef_pending_status_id'];
		} else {
			$this->data['boletocef_pending_status_id'] = $this->config->get('boletocef_pending_status_id');
		}
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['boletocef_geo_zone_id'])) {
			$this->data['boletocef_geo_zone_id'] = $this->request->post['boletocef_geo_zone_id'];
		} else {
			$this->data['boletocef_geo_zone_id'] = $this->config->get('boletocef_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['boletocef_status'])) {
			$this->data['boletocef_status'] = $this->request->post['boletocef_status'];
		} else {
			$this->data['boletocef_status'] = $this->config->get('boletocef_status');
		}
		
		if (isset($this->request->post['boletocef_sort_order'])) {
			$this->data['boletocef_sort_order'] = $this->request->post['boletocef_sort_order'];
		} else {
			$this->data['boletocef_sort_order'] = $this->config->get('boletocef_sort_order');
		}

		$this->template = 'payment/boletocef.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/boletocef')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['boletocef_name_key']) {
			$this->error['name_key'] = $this->language->get('error_name_key');
		}
		
		if (!$this->request->post['boletocef_cnpj_key']) {
			$this->error['cnpj_key'] = $this->language->get('error_cnpj_key');
		}
		
		if (!$this->request->post['boletocef_num_agencia_key']) {
			$this->error['num_agencia_key'] = $this->language->get('error_num_agencia_key');
		}
		
		if (!$this->request->post['boletocef_num_conta_key']) {
			$this->error['num_conta_key'] = $this->language->get('error_num_conta_key');
		}
		
		if (!$this->request->post['boletocef_num_conta_dig_key']) {
			$this->error['num_conta_dig_key'] = $this->language->get('error_num_conta_dig_key');
		}
		
		if (!$this->request->post['boletocef_cod_ced_key']) {
			$this->error['cod_ced_key'] = $this->language->get('error_cod_ced_key');
		}
		
		if (!$this->request->post['boletocef_adress_key']) {
			$this->error['adress_key'] = $this->language->get('error_adress_key');
		}
		
		if (!$this->request->post['dig_ret']) {
			$this->error['dig_ret'] = $this->language->get('error_dig_ret');
		}
		
		if (!$this->request->post['boletocef_uf_key']) {
			$this->error['uf_key'] = $this->language->get('error_uf_key');
		}
		
		if (!$this->request->post['boletocef_dias_pag_key']) {
			$this->error['dias_pag_key'] = $this->language->get('error_dias_pag_key');
		}
		
		if (!$this->request->post['logo_url']) {
			$this->error['logo_url'] = $this->language->get('error_logo_url');
		}
				
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id  AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . "order` o";
		
		
		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}
		
		
		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_customer'])) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_total'])) {
			$sql .= " AND o.total = '" . (float)$data['filter_total'] . "'";
		}
		
		//$sql .= " AND payment_code LIKE 'boletocef'";
		$sql .= "AND payment_method LIKE 'Boleto Caixa'";
		
		
		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'o.date_modified',
			'o.total'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getlist(){
		
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = null;
		}
		
		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = null;
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}
											
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_return'),
				'href'      => $this->url->link('payment/boletocef/bcefreturn', 'token=' . $this->session->data['token'] . $url, 'SSL'),				
				'separator' => ' :: '
		);

		$this->data['invoice'] = $this->url->link('sale/order/invoice', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['insert'] = $this->url->link('sale/order/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('sale/order/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['orders'] = array();

		$data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_customer'	     => $filter_customer,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_total'           => $filter_total,
			'filter_date_added'      => $filter_date_added,
			'filter_date_modified'   => $filter_date_modified,
			'sort'                   => $sort,
			//'paymnent_code'			 => 'boletocef',
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$this->load->model('sale/order');
		$order_total = $this->model_sale_order->getTotalOrders($data);

		$results = $this->getOrders($data);
		
		  foreach ($results as $result) {
				$action = array();
							
				$action[] = array(
					'text' => $this->language->get('text_view'),
					'href' => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
				);
				
				if (strtotime($result['date_added']) > strtotime('-' . (int)$this->config->get('config_order_edit') . ' day')) {
					$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
					);
				}
				
				$this->data['orders'][] = array(
					'order_id'      => $result['order_id'],
					'customer'      => $result['customer'],
					'status'        => $result['status'],
					'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
					'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
					'selected'      => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
					'action'        => $action
				);
			}
			
	    $this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing'] = $this->language->get('text_missing');

		$this->data['column_order_id'] = $this->language->get('column_order_id');
    	$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_invoice'] = $this->language->get('button_invoice');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}
											
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.order_id' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_total'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.total' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&sort=o.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}
											
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('payment/boletocef/bcefreturn', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		$this->data['filter_total'] = $filter_total;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/order_status');

    	$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/order_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
		
		
	}
 	
 	public function getForm() {
		$this->load->model('sale/customer');
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		 
		$this->data['text_no_results'] = $this->language->get('text_no_results');  
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_wait'] = $this->language->get('text_wait');
		$this->data['text_product'] = $this->language->get('text_product');
		$this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_order'] = $this->language->get('text_order');
		
		$this->data['entry_store'] = $this->language->get('entry_store');
		$this->data['entry_customer'] = $this->language->get('entry_customer');
		$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_comment'] = $this->language->get('entry_comment');	
		$this->data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$this->data['entry_address'] = $this->language->get('entry_address');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
		$this->data['entry_zone_code'] = $this->language->get('entry_zone_code');
		$this->data['entry_country'] = $this->language->get('entry_country');		
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_to_name'] = $this->language->get('entry_to_name');
		$this->data['entry_to_email'] = $this->language->get('entry_to_email');
		$this->data['entry_from_name'] = $this->language->get('entry_from_name');
		$this->data['entry_from_email'] = $this->language->get('entry_from_email');
		$this->data['entry_theme'] = $this->language->get('entry_theme');	
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_amount'] = $this->language->get('entry_amount');
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_payment'] = $this->language->get('entry_payment');
		$this->data['entry_voucher'] = $this->language->get('entry_voucher');
		$this->data['entry_coupon'] = $this->language->get('entry_coupon');
		$this->data['entry_reward'] = $this->language->get('entry_reward');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
			
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_product'] = $this->language->get('button_add_product');
		$this->data['button_add_voucher'] = $this->language->get('button_add_voucher');
		$this->data['button_update_total'] = $this->language->get('button_update_total');
		$this->data['button_remove'] = $this->language->get('button_remove');
		$this->data['button_upload'] = $this->language->get('button_upload');

		$this->data['tab_order'] = $this->language->get('tab_order');
		$this->data['tab_customer'] = $this->language->get('tab_customer');
		$this->data['tab_payment'] = $this->language->get('tab_payment');
		$this->data['tab_shipping'] = $this->language->get('tab_shipping');
		$this->data['tab_product'] = $this->language->get('tab_product');
		$this->data['tab_voucher'] = $this->language->get('tab_voucher');
		$this->data['tab_total'] = $this->language->get('tab_total');

		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['order_id'])) {
			$this->data['order_id'] = $this->request->get['order_id'];
		} else {
			$this->data['order_id'] = 0;
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}

 		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
		
 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
 		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}

 		if (isset($this->error['shipping_firstname'])) {
			$this->data['error_shipping_firstname'] = $this->error['shipping_firstname'];
		} else {
			$this->data['error_shipping_firstname'] = '';
		}

 		if (isset($this->error['shipping_lastname'])) {
			$this->data['error_shipping_lastname'] = $this->error['shipping_lastname'];
		} else {
			$this->data['error_shipping_lastname'] = '';
		}
				
		if (isset($this->error['shipping_address_1'])) {
			$this->data['error_shipping_address_1'] = $this->error['shipping_address_1'];
		} else {
			$this->data['error_shipping_address_1'] = '';
		}
		
		if (isset($this->error['shipping_city'])) {
			$this->data['error_shipping_city'] = $this->error['shipping_city'];
		} else {
			$this->data['error_shipping_city'] = '';
		}
		
		if (isset($this->error['shipping_postcode'])) {
			$this->data['error_shipping_postcode'] = $this->error['shipping_postcode'];
		} else {
			$this->data['error_shipping_postcode'] = '';
		}
		
		if (isset($this->error['shipping_country'])) {
			$this->data['error_shipping_country'] = $this->error['shipping_country'];
		} else {
			$this->data['error_shipping_country'] = '';
		}
		
		if (isset($this->error['shipping_zone'])) {
			$this->data['error_shipping_zone'] = $this->error['shipping_zone'];
		} else {
			$this->data['error_shipping_zone'] = '';
		}
				
 		if (isset($this->error['payment_firstname'])) {
			$this->data['error_payment_firstname'] = $this->error['payment_firstname'];
		} else {
			$this->data['error_payment_firstname'] = '';
		}

 		if (isset($this->error['payment_lastname'])) {
			$this->data['error_payment_lastname'] = $this->error['payment_lastname'];
		} else {
			$this->data['error_payment_lastname'] = '';
		}
				
		if (isset($this->error['payment_address_1'])) {
			$this->data['error_payment_address_1'] = $this->error['payment_address_1'];
		} else {
			$this->data['error_payment_address_1'] = '';
		}
		
		if (isset($this->error['payment_city'])) {
			$this->data['error_payment_city'] = $this->error['payment_city'];
		} else {
			$this->data['error_payment_city'] = '';
		}
		
		if (isset($this->error['payment_postcode'])) {
			$this->data['error_payment_postcode'] = $this->error['payment_postcode'];
		} else {
			$this->data['error_payment_postcode'] = '';
		}
		
		if (isset($this->error['payment_country'])) {
			$this->data['error_payment_country'] = $this->error['payment_country'];
		} else {
			$this->data['error_payment_country'] = '';
		}
		
		if (isset($this->error['payment_zone'])) {
			$this->data['error_payment_zone'] = $this->error['payment_zone'];
		} else {
			$this->data['error_payment_zone'] = '';
		}
				
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . $this->request->get['filter_customer'];
		}
											
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
		
		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
		
		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL'),				
			'separator' => ' :: '
		);

		if (!isset($this->request->get['order_id'])) {
			$this->data['action'] = $this->url->link('sale/order/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/order/update', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . $url, 'SSL');
		}
		
		$this->data['cancel'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . $url, 'SSL');

    	if (isset($this->request->get['order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
    	}
			
    	if (isset($this->request->post['store_id'])) {
      		$this->data['store_id'] = $this->request->post['store_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['store_id'] = $order_info['store_id'];
		} else {
      		$this->data['store_id'] = '';
    	}
		
		$this->load->model('setting/store');
		
		$this->data['stores'] = $this->model_setting_store->getStores();
		
		$this->data['store_url'] = HTTP_CATALOG;
		
		if (isset($this->request->post['customer'])) {
			$this->data['customer'] = $this->request->post['customer'];
		} elseif (!empty($order_info)) {
			$this->data['customer'] = $order_info['customer'];
		} else {
			$this->data['customer'] = '';
		}
						
		if (isset($this->request->post['customer_id'])) {
			$this->data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($order_info)) {
			$this->data['customer_id'] = $order_info['customer_id'];
		} else {
			$this->data['customer_id'] = '';
		}
		
		if (isset($this->request->post['customer_group_id'])) {
			$this->data['customer_group_id'] = $this->request->post['customer_group_id'];
		} elseif (!empty($order_info)) {
			$this->data['customer_group_id'] = $order_info['customer_group_id'];
		} else {
			$this->data['customer_group_id'] = '';
		}
						
    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($order_info)) { 
			$this->data['firstname'] = $order_info['firstname'];
		} else {
      		$this->data['firstname'] = '';
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} elseif (!empty($order_info)) { 
			$this->data['lastname'] = $order_info['lastname'];
		} else {
      		$this->data['lastname'] = '';
    	}

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} elseif (!empty($order_info)) { 
			$this->data['email'] = $order_info['email'];
		} else {
      		$this->data['email'] = '';
    	}
				
    	if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} elseif (!empty($order_info)) { 
			$this->data['telephone'] = $order_info['telephone'];
		} else {
      		$this->data['telephone'] = '';
    	}
		
    	if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} elseif (!empty($order_info)) { 
			$this->data['fax'] = $order_info['fax'];
		} else {
      		$this->data['fax'] = '';
    	}	
		
		if (isset($this->request->post['affiliate_id'])) {
      		$this->data['affiliate_id'] = $this->request->post['affiliate_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['affiliate_id'] = $order_info['affiliate_id'];
		} else {
      		$this->data['affiliate_id'] = '';
    	}
		
		if (isset($this->request->post['affiliate'])) {
      		$this->data['affiliate'] = $this->request->post['affiliate'];
    	} elseif (!empty($order_info)) { 
			$this->data['affiliate'] = ($order_info['affiliate_id'] ? $order_info['affiliate_firstname'] . ' ' . $order_info['affiliate_lastname'] : '');
		} else {
      		$this->data['affiliate'] = '';
    	}
				
		if (isset($this->request->post['order_status_id'])) {
      		$this->data['order_status_id'] = $this->request->post['order_status_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['order_status_id'] = $order_info['order_status_id'];
		} else {
      		$this->data['order_status_id'] = '';
    	}
			
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();	
			
    	if (isset($this->request->post['comment'])) {
      		$this->data['comment'] = $this->request->post['comment'];
    	} elseif (!empty($order_info)) { 
			$this->data['comment'] = $order_info['comment'];
		} else {
      		$this->data['comment'] = '';
    	}	
		
		$this->load->model('sale/customer');

		if (isset($this->request->post['customer_id'])) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($this->request->post['customer_id']);
		} elseif (!empty($order_info)) {
			$this->data['addresses'] = $this->model_sale_customer->getAddresses($order_info['customer_id']);
		} else {
			$this->data['addresses'] = array();
		}
			
    	if (isset($this->request->post['shipping_firstname'])) {
      		$this->data['shipping_firstname'] = $this->request->post['shipping_firstname'];
		} elseif (!empty($order_info)) { 
			$this->data['shipping_firstname'] = $order_info['shipping_firstname'];
		} else {
      		$this->data['shipping_firstname'] = '';
    	}

    	if (isset($this->request->post['shipping_lastname'])) {
      		$this->data['shipping_lastname'] = $this->request->post['shipping_lastname'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_lastname'] = $order_info['shipping_lastname'];
		} else {
      		$this->data['shipping_lastname'] = '';
    	}

    	if (isset($this->request->post['shipping_company'])) {
      		$this->data['shipping_company'] = $this->request->post['shipping_company'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_company'] = $order_info['shipping_company'];
		} else {
      		$this->data['shipping_company'] = '';
    	}

    	if (isset($this->request->post['shipping_address_1'])) {
      		$this->data['shipping_address_1'] = $this->request->post['shipping_address_1'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_address_1'] = $order_info['shipping_address_1'];
		} else {
      		$this->data['shipping_address_1'] = '';
    	}

    	if (isset($this->request->post['shipping_address_2'])) {
      		$this->data['shipping_address_2'] = $this->request->post['shipping_address_2'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_address_2'] = $order_info['shipping_address_2'];
		} else {
      		$this->data['shipping_address_2'] = '';
    	}
		
    	if (isset($this->request->post['shipping_city'])) {
      		$this->data['shipping_city'] = $this->request->post['shipping_city'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_city'] = $order_info['shipping_city'];
		} else {
      		$this->data['shipping_city'] = '';
    	}
		
    	if (isset($this->request->post['shipping_postcode'])) {
      		$this->data['shipping_postcode'] = $this->request->post['shipping_postcode'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_postcode'] = $order_info['shipping_postcode'];
		} else {
      		$this->data['shipping_postcode'] = '';
    	}
				
    	if (isset($this->request->post['shipping_country_id'])) {
      		$this->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_country_id'] = $order_info['shipping_country_id'];
		} else {
      		$this->data['shipping_country_id'] = '';
    	}		
	    
		if (isset($this->request->post['shipping_zone_id'])) {
      		$this->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_zone_id'] = $order_info['shipping_zone_id'];
		} else {
      		$this->data['shipping_zone_id'] = '';
    	}	
				
    	if (isset($this->request->post['payment_firstname'])) {
      		$this->data['payment_firstname'] = $this->request->post['payment_firstname'];
		} elseif (!empty($order_info)) { 
			$this->data['payment_firstname'] = $order_info['payment_firstname'];
		} else {
      		$this->data['payment_firstname'] = '';
    	}

    	if (isset($this->request->post['payment_lastname'])) {
      		$this->data['payment_lastname'] = $this->request->post['payment_lastname'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_lastname'] = $order_info['payment_lastname'];
		} else {
      		$this->data['payment_lastname'] = '';
    	}

    	if (isset($this->request->post['payment_company'])) {
      		$this->data['payment_company'] = $this->request->post['payment_company'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_company'] = $order_info['payment_company'];
		} else {
      		$this->data['payment_company'] = '';
    	}

    	if (isset($this->request->post['payment_address_1'])) {
      		$this->data['payment_address_1'] = $this->request->post['payment_address_1'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_address_1'] = $order_info['payment_address_1'];
		} else {
      		$this->data['payment_address_1'] = '';
    	}

    	if (isset($this->request->post['payment_address_2'])) {
      		$this->data['payment_address_2'] = $this->request->post['payment_address_2'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_address_2'] = $order_info['payment_address_2'];
		} else {
      		$this->data['payment_address_2'] = '';
    	}
		
    	if (isset($this->request->post['payment_city'])) {
      		$this->data['payment_city'] = $this->request->post['payment_city'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_city'] = $order_info['payment_city'];
		} else {
      		$this->data['payment_city'] = '';
    	}

    	if (isset($this->request->post['payment_postcode'])) {
      		$this->data['payment_postcode'] = $this->request->post['payment_postcode'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_postcode'] = $order_info['payment_postcode'];
		} else {
      		$this->data['payment_postcode'] = '';
    	}
				
    	if (isset($this->request->post['payment_country_id'])) {
      		$this->data['payment_country_id'] = $this->request->post['payment_country_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_country_id'] = $order_info['payment_country_id'];
		} else {
      		$this->data['payment_country_id'] = '';
    	}		
	    
		if (isset($this->request->post['payment_zone_id'])) {
      		$this->data['payment_zone_id'] = $this->request->post['payment_zone_id'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_zone_id'] = $order_info['payment_zone_id'];
		} else {
      		$this->data['payment_zone_id'] = '';
    	}
		
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();															
		
    	if (isset($this->request->post['shipping_method'])) {
      		$this->data['shipping_method'] = $this->request->post['shipping_method'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_method'] = $order_info['shipping_method'];
		} else {
      		$this->data['shipping_method'] = '';
    	}	
		
    	if (isset($this->request->post['shipping_code'])) {
      		$this->data['shipping_code'] = $this->request->post['shipping_code'];
    	} elseif (!empty($order_info)) { 
			$this->data['shipping_code'] = $order_info['shipping_code'];
		} else {
      		$this->data['shipping_code'] = '';
    	}
						
    	if (isset($this->request->post['payment_method'])) {
      		$this->data['payment_method'] = $this->request->post['payment_method'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_method'] = $order_info['payment_method'];
		} else {
      		$this->data['payment_method'] = '';
    	}
		
    	if (isset($this->request->post['payment_code'])) {
      		$this->data['payment_code'] = $this->request->post['payment_code'];
    	} elseif (!empty($order_info)) { 
			$this->data['payment_code'] = $order_info['payment_code'];
		} else {
      		$this->data['payment_code'] = '';
    	}
	
		if (isset($this->request->post['order_product'])) {
			$order_products = $this->request->post['order_product'];
		} elseif (isset($this->request->get['order_id'])) {
			$order_products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);			
		} else {
			$order_products = array();
		}
		
		$this->load->model('catalog/product');
		
		$this->document->addScript('view/javascript/jquery/ajaxupload.js');
		
		$this->data['order_products'] = array();		
		
		foreach ($order_products as $order_product) {
			if (isset($order_product['order_option'])) {
				$order_option = $order_product['order_option'];
			} elseif (isset($this->request->get['order_id'])) {
				$order_option = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $order_product['order_product_id']);
			} else {
				$order_option = array();
			}

			if (isset($order_product['order_download'])) {
				$order_download = $order_product['order_download'];
			} elseif (isset($this->request->get['order_id'])) {
				$order_download = $this->model_sale_order->getOrderDownloads($this->request->get['order_id'], $order_product['order_product_id']);
			} else {
				$order_download = array();
			}
											
			$this->data['order_products'][] = array(
				'order_product_id' => $order_product['order_product_id'],
				'product_id'       => $order_product['product_id'],
				'name'             => $order_product['name'],
				'model'            => $order_product['model'],
				'option'           => $order_option,
				'download'         => $order_download,
				'quantity'         => $order_product['quantity'],
				'price'            => $order_product['price'],
				'total'            => $order_product['total'],
				'tax'              => $order_product['tax'],
				'reward'           => $order_product['reward']
			);
		}
		
		if (isset($this->request->post['order_voucher'])) {
			$this->data['order_vouchers'] = $this->request->post['order_voucher'];
		} elseif (isset($this->request->get['order_id'])) {
			$this->data['order_vouchers'] = $this->model_sale_order->getOrderVouchers($this->request->get['order_id']);			
		} else {
			$this->data['order_vouchers'] = array();
		}
       
		$this->load->model('sale/voucher_theme');
					
		$this->data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();
						
		if (isset($this->request->post['order_total'])) {
      		$this->data['order_totals'] = $this->request->post['order_total'];
    	} elseif (isset($this->request->get['order_id'])) { 
			$this->data['order_totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
		} else {
      		$this->data['order_totals'] = array();
    	}	
		
		$this->template = 'sale/order_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render());
  	}

}
?>
