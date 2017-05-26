<?php

function __autoload($class){
 require_once DIR_SYSTEM.'library/openboleto/src/OpenBoleto/Agente.php';
 require_once DIR_SYSTEM.'library/openboleto/src/OpenBoleto/Banco/Bradesco.php';
 require_once DIR_SYSTEM.'library/openboleto/src/OpenBoleto/BoletoAbstract.php';
}


class ControllerPaymentBoletobradesco extends Controller {
	protected function index() {
		$this->language->load('payment/boleto_bradesco');
		
		$this->data['text_instruction'] = $this->language->get('text_instruction');
		$this->data['text_instruction2'] = $this->language->get('text_instruction2');
		$this->data['text_bank'] = $this->language->get('text_bank');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_linkboleto'] = $this->language->get('text_linkboleto');
		$this->data['text_linkboleto2'] = $this->language->get('text_linkboleto2');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		
			
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));

		
		$this->data['idboleto'] = $encryption->encrypt($this->session->data['order_id']);
		
		
		//
		$this->data['continue'] = $this->url->link('checkout/success');
		$this->data['back'] = $this->url->link('checkout/checkout', '', 'SSL');
		
		$this->id       = 'payment';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/boleto_bradesco.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/boleto_bradesco.tpl';
		} else {
			$this->template = 'default/template/payment/boleto_bradesco.tpl';
		}	
		
		$this->render(); 
	}
	
	public function confirm() {
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		$order_id = $encryption->encrypt($this->session->data['order_id']);
		
		$this->load->language('payment/boleto_bradesco');
		
		$this->load->model('checkout/order');
		
		$codigo_boleto = $order_id;
		
		$comment  = $this->language->get('text_instruction') . "\n\n";
		$comment .= sprintf($this->language->get('text_linkboleto'), $codigo_boleto). "\n\n";
		$comment .= $this->language->get('text_payment');
		
		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('boleto_bradesco_order_status_id'), $comment);
	}
	
	
	public function callback() {
		$this->load->library('encryption');
		
		$encryption = new Encryption($this->config->get('config_encryption'));
		$order_id = $encryption->decrypt(@$this->request->get['order_id']);
		
		
		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($order_id);
                if(!$order_info){
                 $order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);   
                }
		
if($order_info){
    
    if(strpos($this->config->get('boleto_bradesco_agencia'),'-')){
        $geralA = explode('-',$this->config->get('boleto_bradesco_agencia'));
        $agencia = $geralA[0];
        $agenciaDigito = $geralA[1];
    }
    else{
        $agencia = $this->config->get('boleto_bradesco_agencia');
        $agenciaDigito = '';
    }
    
    if(strpos($this->config->get('boleto_bradesco_conta'),'-')){
        $geralC = explode('-',$this->config->get('boleto_bradesco_conta'));
        $conta = $geralC[0];
        $contaDigito = $geralC[1];
    }
    else{
        $conta = $this->config->get('boleto_bradesco_conta');
        $contaDigito = '';
    }    
    

$sacado = new OpenBoleto\Agente($order_info['payment_firstname'], '', $order_info['payment_address_1'], $order_info['payment_postcode'], $order_info['payment_city'], $order_info['payment_zone_code']);
$cedente = new OpenBoleto\Agente($this->config->get('boleto_bradesco_identificacao'), $this->config->get('boleto_bradesco_cpf_cnpj'), $this->config->get('boleto_bradesco_endereco'), '', $this->config->get('boleto_bradesco_cidade_uf'), '');

$boleto = new OpenBoleto\Banco\Bradesco(array(
    // Parâmetros obrigatórios
    'dataVencimento' => new DateTime(date('Y-m-d', strtotime($order_info['date_added']. ' + '.$this->config->get('boleto_bradesco_dia_prazo_pg').' days'))),
    'valor' =>(float)$order_info['total'] + (float)$this->config->get('boleto_bradesco_taxa_boleto'),
    'sequencial' => $order_info['order_id']+1000000, // Até 11 dígitos
    'sacado' => $sacado,
    'cedente' => $cedente,
    'agencia' => $agencia, // Até 4 dígitos
    'carteira' => $this->config->get('boleto_bradesco_carteira'), // 3, 6 ou 9
    'conta' => $conta, // Até 7 dígitos

    // Parâmetros recomendáveis
    //'logoPath' => 'http://empresa.com.br/logo.jpg', // Logo da sua empresa
    'contaDv' => $contaDigito,
    'agenciaDv' => $agenciaDigito,
    'descricaoDemonstrativo' => array( // Até 5
        $this->config->get('boleto_bradesco_demonstrativo1'),
        $this->config->get('boleto_bradesco_demonstrativo2'),
    ),
    'instrucoes' => array( // Até 8
        $this->config->get('boleto_bradesco_instrucoes1'),
        $this->config->get('boleto_bradesco_instrucoes2'),
        $this->config->get('boleto_bradesco_instrucoes3'),
        $this->config->get('boleto_bradesco_instrucoes4'),        
    ),

    // Parâmetros opcionais
    //'resourcePath' => '../resources',
    //'cip' => '000', // Apenas para o Bradesco
    //'moeda' => Bradesco::MOEDA_REAL,
    //'dataDocumento' => new DateTime(),
    //'dataProcessamento' => new DateTime(),
    //'contraApresentacao' => true,
    //'pagamentoMinimo' => 23.00,
    //'aceite' => 'N',
    //'especieDoc' => 'ABC',
    //'numeroDocumento' => '123.456.789',
    //'usoBanco' => 'Uso banco',
    //'layout' => 'layout.phtml',
    //'logoPath' => 'http://boletophp.com.br/img/opensource-55x48-t.png',
    //'sacadorAvalista' => new Agente('Antônio da Silva', '02.123.123/0001-11'),
    //'descontosAbatimentos' => 123.12,
    //'moraMulta' => 123.12,
    //'outrasDeducoes' => 123.12,
    'outrosAcrescimos' => $this->config->get('boleto_bradesco_taxa_boleto'),
    //'valorCobrado' => 123.12,
    //'valorUnitario' => 123.12,
    //'quantidade' => 1,
));

echo $boleto->getOutput();
 
    
}else {
	//erro ao gera boleto
	$ouput = "<script>
       alert(\"Atencao!\\n \\nBoleto bancario nao encontrado!\\n \\nEntre em contato com nosso atendimento.\\n \\nVocê sera redirecionado para a Central do Cliente.\");
 window.location = 'index.php?route=information/contact';
 </script>";  
	
}
		$this->response->setOutput($ouput);
		
		}
	
}
?>