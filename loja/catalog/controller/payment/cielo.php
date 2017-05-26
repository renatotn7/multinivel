<?php
class ControllerPaymentCielo extends Controller{

	protected function index() {
		$this->data['button_continue'] = "Continuar";
		$this->data['button_back'] = "Cancelar";
		$this->data['dados_compra'] = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->data['parcelas'] = $this->config->get('cielo_parcelas');
		$this->data['cielo_juros'] = $this->config->get('cielo_juros');
		$this->data['cielo_usar_elo'] = $this->config->get('cielo_usar_elo');
		$this->data['cielo_usar_mastercard'] = $this->config->get('cielo_usar_mastercard');
		$this->data['cielo_usar_visa_electron'] = $this->config->get('cielo_usar_visa_electron');
		$this->data['cielo_usar_visa'] = $this->config->get('cielo_usar_visa');

		$this->data['action'] = HTTPS_SERVER. 'index.php?route=payment/cielo/transacao/';

		//
		$this->data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';

		$this->id     = 'payment';
		//$this->template = $this->config->get('config_template') . 'payment/boleto_cef_sigcb.tpl';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cielo.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/cielo.tpl';
		} else {
			$this->template = 'default/template/payment/cielo.tpl';
		}

		$this->render();
	}



	public function confirm() {
		$this->load->model('checkout/order');
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('cielo_order'));
	}

	function transacao(){

		self::confirm();
		$this->load->model('checkout/order');

		define('VERSAO', "1.1.0");

		// CONSTANTES
		define("ENDERECO_BASE", $this->config->get('cielo_endereco_base'));
		define("ENDERECO", ENDERECO_BASE."/servicos/ecommwsec.do");

		define("LOJA", $this->config->get('cielo_numero'));
		define("LOJA_CHAVE", $this->config->get('cielo_chave'));
		define("CIELO", "1040117276");
		define("CIELO_CHAVE", "c71eeb8f45d52f925095191d784dbb880e0ca6a7e017fc5db380962bf5cf0786");

		define("ARQUIVO_LOG_CIELO",'cielo/log/cielo.log');

		require      'cielo/errorHandling.php';
		require_once 'cielo/pedido.php';
		require_once 'cielo/logger.php';

		$compra = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		define("TIPO_PARCELAMENTO",$this->config->get('cielo_parcelamento')); //Loja
		define("CAPTURAR_AUT",$this->config->get('cielo_captura'));//Não
		define("AUTORIZACAO",$this->config->get('cielo_autorizacao'));//Autorizar transação autenticada e não-autenticada

		list($tipo,$bandeira,$foma_pagamento) = explode("/",$_POST['pg']);

		$Pedido = new Pedido();

		//Gravar os dados do Pedido

		// Lê dados do $_POST
		$Pedido->formaPagamentoBandeira = $bandeira;
		$Pedido->formaPagamentoProduto = $foma_pagamento;
		$Pedido->formaPagamentoParcelas = $foma_pagamento=="A"?1:$foma_pagamento;

		$Pedido->dadosEcNumero = CIELO;
		$Pedido->dadosEcChave = CIELO_CHAVE;

		$Pedido->capturar = CAPTURAR_AUT;
		$Pedido->autorizar = AUTORIZACAO;

		$Pedido->dadosPedidoNumero = $compra['order_id'];
		$Pedido->dadosPedidoValor  = str_replace(".","",number_format($compra['total'],2,'.',''));

		$Pedido->urlRetorno = HTTPS_SERVER. "index.php?route=payment/cielo/retorno/&compra=".$compra['order_id'];

		// ENVIA REQUISIÇÃO SITE CIELO
		$objResposta = $Pedido->RequisicaoTransacao(false);

		$Pedido->tid = $objResposta->tid;
		$Pedido->pan = $objResposta->pan;
		$Pedido->status = $objResposta->status;

		$urlAutenticacao = "url-autenticacao";
		$Pedido->urlAutenticacao = $objResposta->$urlAutenticacao;

		// Serializa Pedido e guarda na SESSION
		$_SESSION['xml_transacao'] = $StrPedido = $Pedido->ToString();

		$this->cart->clear();

		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['guest']);
		unset($this->session->data['comment']);
		unset($this->session->data['order_id']);
		unset($this->session->data['coupon']);
		unset($this->session->data['voucher']);
		unset($this->session->data['vouchers']);

		$this->redirect($Pedido->urlAutenticacao);
	}

	function retorno(){

	    define('VERSAO', "1.1.0");

		//Número cielo
		$numeroCielo = $this->config->get('cielo_numero');

		//Chave Cielo
		$chaveCielo = $this->config->get('cielo_chave');


		// CONSTANTES
		define("ENDERECO_BASE", $this->config->get('cielo_endereco_base'));
		define("ENDERECO", ENDERECO_BASE."/servicos/ecommwsec.do");

		define("LOJA", $this->config->get('cielo_numero'));
		define("LOJA_CHAVE", $this->config->get('cielo_chave'));

		define("CIELO", "{$numeroCielo}");
		define("CIELO_CHAVE", "{$chaveCielo}");

		define("ARQUIVO_LOG_CIELO",'cielo/log/cielo.log');

		require      'cielo/errorHandling.php';
		require_once 'cielo/pedido.php';
		require_once 'cielo/logger.php';

		$Pedido = new Pedido();
		$Pedido->FromString($_SESSION['xml_transacao']);

		$this->data['breadcrumbs'] = array();

		$this->data['objResultado'] = $objResultado = $Pedido->RequisicaoConsulta();
		$this->data['resultado'] = status_transacao($objResultado->status);


      	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/sucesso_cartao.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/sucesso_cartao.tpl';
		} else {
			$this->template = 'default/template/payment/sucesso_cartao.tpl';
		}

		$this->children = array(
			'common/footer',
			'common/header'
		);

 		$this->response->setOutput($this->render());

	}

}// Fim da classe

//FORA DA CLASSE

function status_transacao($st){
	$status;

	switch($st)
	{
		case "0": $status = "Criada";
				break;
		case "1": $status = "Em andamento";
				break;
		case "2": $status = "Autenticada";
				break;
		case "3": $status = "Não autenticada";
				break;
		case "4": $status = "Autorizada";
				break;
		case "5": $status = "Não autorizada";
				break;
		case "6": $status = "Capturada";
				break;
		case "8": $status = "Não capturada";
				break;
		case "9": $status = "Cancelada";
				break;
		case "10": $status = "Em autenticação";
				break;
		default: $status = "n/a";
				break;
	}

	return $status;
}

// Envia requisição
function httprequest($paEndereco, $paPost){

	$sessao_curl = curl_init();
	curl_setopt($sessao_curl, CURLOPT_URL, $paEndereco);

	curl_setopt($sessao_curl, CURLOPT_FAILONERROR, true);

	//  CURLOPT_SSL_VERIFYPEER
	//  verifica a validade do certificado
	curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYPEER, true);
	//  CURLOPPT_SSL_VERIFYHOST
	//  verifica se a identidade do servidor bate com aquela informada no certificado
	curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYHOST, 2);

	//  CURLOPT_SSL_CAINFO
	//  informa a localização do certificado para verificação com o peer
	curl_setopt($sessao_curl, CURLOPT_CAINFO, "cielo/ssl/VeriSignClass3PublicPrimaryCertificationAuthority-G5.crt");
	curl_setopt($sessao_curl, CURLOPT_SSLVERSION, 3);

	//  CURLOPT_CONNECTTIMEOUT
	//  o tempo em segundos de espera para obter uma conexão
	curl_setopt($sessao_curl, CURLOPT_CONNECTTIMEOUT, 10);

	//  CURLOPT_TIMEOUT
	//  o tempo máximo em segundos de espera para a execução da requisição (curl_exec)
	curl_setopt($sessao_curl, CURLOPT_TIMEOUT, 40);

	//  CURLOPT_RETURNTRANSFER
	//  TRUE para curl_exec retornar uma string de resultado em caso de sucesso, ao
	//  invés de imprimir o resultado na tela. Retorna FALSE se há problemas na requisição
	curl_setopt($sessao_curl, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($sessao_curl, CURLOPT_POST, true);
	curl_setopt($sessao_curl, CURLOPT_POSTFIELDS, $paPost );

	$resultado = curl_exec($sessao_curl);

	curl_close($sessao_curl);

	if ($resultado){
		return $resultado;
	}else{
		return curl_error($sessao_curl);
	}

}