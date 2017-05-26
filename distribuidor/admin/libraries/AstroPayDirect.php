<?php

/**
 * Example of using the class:
 * require_once 'AstroPayDirect.class.php';
 * $apd = new AstroPayDirect();
 * $banks = $apd->get_banks_by_country('BR', 'json');
 *
 * Available functions:
 * - create($invoice, $amount, $iduser, $bank, $country, $currency, $description, $cpf, $sub_code, $return_url, $confirmation_url, $response_type = 'json') {befault: $response_type = 'json'}
 * - get_status($invoice)
 * - get_exchange($country = 'BR', $amount = 1) {befault: $country = BR | $amount = 1}
 * - get_banks_by_country($country, $type) {befault: $country = BR | $type = 'json'}
 *
 */

/**
 * Class of AstroPay Direct
 * 
 * @author Ivan L'olivier (ivan@astropay.com)
 * @version 1.0 
 *
 */
 
class AstroPayDirect{
	
	/**************************
	 * Merchant configuration *
	 **************************/
	
	private $x_login = 'KaiBiAwoIz';
	private $x_trans_key = 'bnwKGv2b34';
	
	private $x_login_for_webpaystatus = 'r8kDzwQb11';
	private $x_trans_key_for_webpaystatus = 'L0uHXAD5Eb';
	
	private $secret_key = '8AYxe7UIRUR2YyK3ndtJYFlhBEtJJmJPl';
	
	private $sandbox = true;
	
	/*********************************
	 * End of Merchant configuration *
	 *********************************/

	/*****************************************************
	 * ---- PLEASE DON'T CHANGE ANYTHING BELOW HERE ---- *
	 *****************************************************/
	
	
	
	private $url = array(
		'create' => '',
		'status' => '',
		'exchange' => '',
		'banks' => ''
	);
	private $errors = 0;
	
	public function __construct(){
		$this->errors = 0;
		
		$this->url['create'] = 'https://astropaycard.com/api_curl/apd/create';
		$this->url['status'] = 'https://astropaycard.com/apd/webpaystatus';
		$this->url['exchange'] = 'https://astropaycard.com/apd/webcurrencyexchange';
		$this->url['banks'] = 'https://astropaycard.com/api_curl/apd/get_banks_by_country';
		
		if ($this->sandbox){
			$this->url['create'] = 'http://sandbox.astropaycard.com/api_curl/apd/create';
			$this->url['status'] = 'http://sandbox.astropaycard.com/apd/webpaystatus';
			$this->url['exchange'] = 'http://sandbox.astropaycard.com/apd/webcurrencyexchange';
			$this->url['banks'] = 'http://sandbox.astropaycard.com/api_curl/apd/get_banks_by_country';
		}
	}
	
	public function create($invoice, $amount, $iduser, $bank, $country, $currency, $description, $cpf, $sub_code, $return_url, $confirmation_url, $response_type = 'json'){
		
		$params_array = array(
			//Mandatory
			'x_login' => $this->x_login,
			'x_trans_key' => $this->x_trans_key,
			'x_invoice' => $invoice,
			'x_amount' => $amount,
			'x_iduser' => $iduser,
			
			//Optional
			'x_bank' => $bank,
			'x_country' => $country,
			'x_currency' => $currency,
			'x_description' => $description,
			'x_cpf' => $cpf,
			'x_sub_code' => $sub_code,
			'x_return' => $return_url,
			'x_confirmation' => $confirmation_url,
			'type' => $response_type
		);
		
		$message_to_control = $invoice . 'D' . $amount . 'P' . $iduser . 'A';
		$control = strtoupper(hash_hmac('sha256', pack('A*', $message_to_control), pack('A*', $this->secret_key)));
		
		$params_array['control'] = $control;
		
		$response = $this->curl($this->url['create'], $params_array);
		
		return $response;
	}
	
	public function get_status($invoice){
		$params_array = array(
			//Mandatory
			'x_login' => $this->x_login_for_webpaystatus,
			'x_trans_key' => $this->x_trans_key_for_webpaystatus,
			'x_invoice' => $invoice
		);
		
		$response = $this->curl($this->url['status'], $params_array);
		return $response;
	}
	
	public function get_exchange($country = 'BR', $amount = 1){
		$params_array = array(
			//Mandatory
			'x_login' => $this->x_login_for_webpaystatus,
			'x_trans_key' => $this->x_trans_key_for_webpaystatus,
			'x_country' => $country,
			'x_amount' => $amount
		);
		
		$response = $this->curl($this->url['exchange'], $params_array);
		return $response;
	}
	
	public function get_banks_by_country($country = 'BR', $type = 'json'){
		$params_array = array(
			//Mandatory
			'x_login' => $this->x_login,
			'x_trans_key' => $this->x_trans_key,
			'country_code' => $country,
			'type' => $type
		);
		
		$response = $this->curl($this->url['banks'], $params_array);
		return $response;
	}
	
	
	/**
	 * END OF PUBLIC INTERFACE
	 */
	private function curl($url, $params_array) {
		$params = array();
		foreach ($params_array as $key => $value){
			$params[] = "{$key}={$value}";
		}
		$params = join('&', $params);
		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$response = curl_exec($ch);
		if (($error = curl_error($ch)) != false) {
			$this->errors++;
			
			if ($this->errors >= 5){
				die("Error in curl: $error");
			}
			
			sleep(1);
			$this->curl($url, $params_array);
		}
		curl_close($ch);
		
		$this->errors = 0;
		return $response;
	}
	
}