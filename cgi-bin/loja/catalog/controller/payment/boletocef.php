<?php

class ControllerPaymentBoletocef extends Controller {
	public function index() {
		$this->language->load('payment/boletocef');
		
		$this->data['text_testmode'] = $this->language->get('text_testmode');		
 		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['testmode'] = $this->config->get('boletocef_test');
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['title_boleto'] = $this->language->get('text_title');
		
		$this->data['button_back'] = $this->language->get('button_back');
		//$this->data['print_bcef'] = $link = "index.php?route=payment/boletocef/callbackorder&order_id=$idboleto";
		
		if (!$this->config->get('boletocef_test')) {
    		$this->data['action'] = 'index.php?route=payment/boletocef_test/confirm';
  		} else {
			$this->data['action'] = 'index.php?route=payment/boletocef/confirm';
		}
		
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));

		$this->data['idboleto'] = $encryption->encrypt($this->session->data['order_id']);
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/boletocef.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/boletocef.tpl';
		} else {
			$this->template = 'default/template/payment/boletocef.tpl';
		}	
		
		$this->render(); 
	}
	
	public function confirmorder() {
		$this->load->library('encryption');
		$this->data['button_continue'] = "index.php?route/payment/boletocef/confirmorder";
		$encryption = new Encryption($this->config->get('config_encryption'));
		//$order_id = $encryption->encrypt($this->session->data['order_id']);
		
		$order_id = $encryption->decrypt(@$this->request->get['order_id']);
		
		$this->load->language('payment/boletocef');
		
		$this->load->model('checkout/order');
		
		
		$this->model_checkout_order->confirm($order_id, $this->config->get('boletocef_pending_status_id'), true);
		$this->redirect($this->url->link('checkout/success'));
		
	}
	
	public function callbackorder() {
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		$order_id = $encryption->decrypt(@$this->request->get['order_id']);
		
		$order_num = $this->request->get['order_id'];
		
		$this->load->model('checkout/order');
				
		$this->data['button_continue'] = "index.php?route/payment/boletocef/confirmorder";
	    $this->language->load('payment/boletocef');		
		$this->data['text_testmode'] = $this->language->get('text_testmode');		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		//Configurações da conta Admin
		$dadosconta = array(
		'private_key'   => $this->config->get('boletocef_private_key'), 
		'public_key'    => $this->config->get('boletocef_public_key'),
		'agencia_key'   => $this->config->get('boletocef_num_agencia_key'), 
		'conta_key' 	=> $this->config->get('boletocef_num_conta_key'), 
		'dig_key' 		=> $this->config->get('boletocef_num_conta_dig_key'), 
		'dias_pag_key'  => $this->config->get('boletocef_dias_pag_key'),
		'cod_ced_key'   => $this->config->get('boletocef_cod_ced_key'),
		
		'adress_key'    => $this->config->get('boletocef_adress_key'),
		'uf_key'    	=> $this->config->get('boletocef_uf_key'),
		'cnpj_key'   	=> $this->config->get('boletocef_cnpj_key'),
		'name_key'      => $this->config->get('boletocef_name_key'),
		
		'taxa_boleto'   => $this->config->get('boletocef_value_taxa'),
		
		'into1'     => $this->config->get('boletocef_instrucao1'),
		'into2'    	=> $this->config->get('boletocef_instrucao2'),
		'into3'   	=> $this->config->get('boletocef_instrucao3'),
		
		'demo1'     => $this->config->get('boletocef_demo1'),
		'demo2'    	=> $this->config->get('boletocef_demo2'),
		'demo3'   	=> $this->config->get('boletocef_demo3'),
		
		'logo_url'      => $this->config->get('logo_url'),
		'dig_ret'      => $this->config->get('dig_ret')
		
		);
		
		$this->load->model('checkout/order');
		$dadosboleto["print_url"] = "index.php?route=payment/boletocef/callbackorder&order_id=$order_num";
	
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		//var_dump($order_info);
		
		$dias_de_prazo_para_pagamento = $dadosconta['dias_pag_key'];
		 
		$taxa_boleto = $dadosconta['taxa_boleto'];
		$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
		$valor_cobrado = $order_info['total']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
		//$valor_cobrado = "0.10"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
		$valor_cobrado = str_replace(",", ".",$valor_cobrado);
		$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
		
		
		
		$dadosboleto["empresa_logo_url"] =  $dadosconta['logo_url'];
		
		// Composição Nosso Numero - CEF SIGCB
		$dadosboleto["nosso_numero1"] = sprintf("%03d", $order_info['customer_id']); // tamanho 3
		$dadosboleto["nosso_numero_const1"] = "2"; //constanto 1 , 1=registrada , 2=sem registro
		$dadosboleto["nosso_numero2"] =  $dadosconta['dig_ret'];; // tamanho 3 Colcoado Operação para rastreio
		$dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=emitido pelo proprio cliente
		$dadosboleto["nosso_numero3"] = sprintf("%09d", $order_info['order_id']); // tamanho 9
		
		
		$dadosboleto["numero_documento"] = "8". $order_info['customer_id'] . sprintf("%07d", $order_info['order_id']);	// Num do pedido ou do documento
		$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
		$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
		$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
		$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
		
		// DADOS DO SEU CLIENTE
		$dadosboleto["sacado"] = $order_info['firstname'];
		$dadosboleto["endereco1"] = $order_info['shipping_address_1'];
		$dadosboleto["endereco2"] = $order_info['shipping_address_2'];
		
		// INFORMACOES PARA O CLIENTE
		$dadosboleto["demonstrativo1"] = $dadosconta['demo1'];
		$dadosboleto["demonstrativo2"] = $dadosconta['demo2'];
		$dadosboleto["demonstrativo3"] = $dadosconta['demo3'];
		$dadosboleto["demonstrativo4"] = "- Taxa Bancaria: R$ " . $dadosconta['taxa_boleto'];
		
		// INSTRUÇÕES PARA O CAIXA
		$dadosboleto["instrucoes1"] = $dadosconta['into1'];
		$dadosboleto["instrucoes2"] = $dadosconta['into2'];
		$dadosboleto["instrucoes3"] = $dadosconta['into3'];
		$dadosboleto["instrucoes4"] = "- Emitido pelo sistema";
		
		// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
		$dadosboleto["quantidade"] = "";
		$dadosboleto["valor_unitario"] = "";
		$dadosboleto["aceite"] = "";		
		$dadosboleto["especie"] = "R$";
		$dadosboleto["especie_doc"] = "";
		
		
		// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
		
		// DADOS DA SUA CONTA - CEF
		$dadosboleto["agencia"] = $dadosconta['agencia_key']; // Num da agencia, sem digito
		$dadosboleto["conta"] = $dadosconta['conta_key']; 	// Num da conta, sem digito
		$dadosboleto["conta_dv"] = $dadosconta['dig_key'];	// Digito do Num da conta
		
		// DADOS PERSONALIZADOS - CEF
		$dadosboleto["conta_cedente"] = $dadosconta['cod_ced_key']; // Código Cedente do Cliente, com 6 digitos (Somente Números)
		$dadosboleto["carteira"] = "SR";  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
		
		// SEUS DADOS
		$dadosboleto["identificacao"] = $dadosconta['name_key'];
		$dadosboleto["cpf_cnpj"] = $dadosconta['cnpj_key'];
		$dadosboleto["endereco"] = $dadosconta['adress_key'];
		$dadosboleto["cidade_uf"] = $dadosconta['uf_key'];
		$dadosboleto["cedente"] = $dadosconta['name_key'];


	    if (!$this->config->get('boletocef_test')) {
	    	require_once 'bcef/include/re_funcoes_cef_sigcb.php';
	    	require_once 'bcef/include/re_layout_cef.php';
	    	
    		$this->data['action'] = 'bcef/boletocef.php';
  		} else {
			require_once 'bcef/test/include/re_funcoes_cef_sigcb.php';
			require_once 'bcef/test/include/re_layout_cef.php';
		}
		//$orerdun = $order_info['order_id'];
		//$link = "index.php?route/payment/boletocef/reprint&order_id=$orerdun";
		$this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('boletocef_pending_status_id'), false);
		//$this->render();
	}
	
	public function reprint(){
		
		$this->language->load('payment/boletocef');		
		$this->data['text_testmode'] = $this->language->get('text_testmode');		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		//Configurações da conta Admin
		$dadosconta = array(
		'private_key'   => $this->config->get('boletocef_private_key'), 
		'public_key'    => $this->config->get('boletocef_public_key'),
		'agencia_key'   => $this->config->get('boletocef_num_agencia_key'), 
		'conta_key' 	=> $this->config->get('boletocef_num_conta_key'), 
		'dig_key' 		=> $this->config->get('boletocef_num_conta_dig_key'), 
		'dias_pag_key'  => $this->config->get('boletocef_dias_pag_key'),
		'cod_ced_key'   => $this->config->get('boletocef_cod_ced_key'),
		
		'taxa_boleto'   => $this->config->get('boletocef_value_taxa'),
		
		'into1'     => $this->config->get('boletocef_instrucao1'),
		'into2'    	=> $this->config->get('boletocef_instrucao2'),
		'into3'   	=> $this->config->get('boletocef_instrucao3'),
		
		'demo1'     => $this->config->get('boletocef_demo1'),
		'demo2'    	=> $this->config->get('boletocef_demo2'),
		'demo3'   	=> $this->config->get('boletocef_demo3'),
		
		'adress_key'    => $this->config->get('boletocef_adress_key'),
		'uf_key'    	=> $this->config->get('boletocef_uf_key'),
		'cnpj_key'   	=> $this->config->get('boletocef_cnpj_key'),
		'name_key'      => $this->config->get('boletocef_name_key'),
		'logo_url'      => $this->config->get('logo_url'),
		'dig_ret'      => $this->config->get('dig_ret')
		);
		
		$order_id = $this->request->get['order_id'];
		$this->load->model('checkout/order');
		
	
		
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$dias_de_prazo_para_pagamento = $dadosconta['dias_pag_key'];
		$dadosboleto["empresa_logo_url"] =  $dadosconta['logo_url'];
		$taxa_boleto = $dadosconta['taxa_boleto'];
		$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
		$valor_cobrado = $order_info['total']; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
		$valor_cobrado = str_replace(",", ".",$valor_cobrado);
		$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
		// Composição Nosso Numero - CEF SIGCB
		$dadosboleto["nosso_numero1"] = sprintf("%03d", $order_info['customer_id']); // tamanho 3
		$dadosboleto["nosso_numero_const1"] = "2"; //constanto 1 , 1=registrada , 2=sem registro
		$dadosboleto["nosso_numero2"] = $dadosconta['dig_ret']; // tamanho 3 Colcoado Operação para rastreio
		$dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=emitido pelo proprio cliente
		$dadosboleto["nosso_numero3"] = sprintf("%09d", $order_info['order_id']); // tamanho 9
		
		
		$dadosboleto["numero_documento"] = "8". $order_info['customer_id'] . sprintf("%07d", $order_info['order_id']);	// Num do pedido ou do documento
		$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
		$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
		$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
		$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
		
		// DADOS DO SEU CLIENTE
		$dadosboleto["sacado"] = $order_info['firstname'];
		$dadosboleto["endereco1"] = $order_info['shipping_address_1'];
		$dadosboleto["endereco2"] = $order_info['shipping_address_2'];
		
		// INFORMACOES PARA O CLIENTE
		$dadosboleto["demonstrativo1"] = $dadosconta['demo1'];
		$dadosboleto["demonstrativo2"] = $dadosconta['demo2'];
		$dadosboleto["demonstrativo3"] = $dadosconta['demo3'];
		$dadosboleto["demonstrativo4"] = "- Taxa Bancaria: R$ " . $dadosconta['taxa_boleto'];
		
		// INSTRUÇÕES PARA O CAIXA
		$dadosboleto["instrucoes1"] = $dadosconta['into1'];
		$dadosboleto["instrucoes2"] = $dadosconta['into2'];
		$dadosboleto["instrucoes3"] = $dadosconta['into3'];
		$dadosboleto["instrucoes4"] = "- Emitido pelo sistema";
		
		// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
		$dadosboleto["quantidade"] = "";
		$dadosboleto["valor_unitario"] = "";
		$dadosboleto["aceite"] = "";		
		$dadosboleto["especie"] = "R$";
		$dadosboleto["especie_doc"] = "";
		
		
		// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
		
		// DADOS DA SUA CONTA - CEF
		$dadosboleto["agencia"] = $dadosconta['agencia_key']; // Num da agencia, sem digito
		$dadosboleto["conta"] = $dadosconta['conta_key']; 	// Num da conta, sem digito
		$dadosboleto["conta_dv"] = $dadosconta['dig_key'];	// Digito do Num da conta
		
		// DADOS PERSONALIZADOS - CEF
		$dadosboleto["conta_cedente"] = $dadosconta['cod_ced_key']; // Código Cedente do Cliente, com 6 digitos (Somente Números)
		$dadosboleto["carteira"] = "SR";  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)
		
		// SEUS DADOS
		$dadosboleto["identificacao"] = $dadosconta['name_key'];
		$dadosboleto["cpf_cnpj"] = $dadosconta['cnpj_key'];
		$dadosboleto["endereco"] = $dadosconta['adress_key'];
		$dadosboleto["cidade_uf"] = $dadosconta['uf_key'];
		$dadosboleto["cedente"] = $dadosconta['name_key'];


	if (!$this->config->get('boletocef_test')) {
	    	require_once 'bcef/include/re_funcoes_cef_sigcb.php';
	    	require_once 'bcef/include/re_layout_cef.php';
	    	
    		$this->data['action'] = 'bcef/boletocef.php';
  		} else {
			require_once 'bcef/test/include/re_funcoes_cef_sigcb.php';
			require_once 'bcef/test/include/re_layout_cef.php';
		}
				
	}
	
	
	
	private function prapareToken($text) {
		$token = base64_encode(sha1($text,true));
		return $token;
	
	}
	
	public function callback() {
		
	}
}
?>
