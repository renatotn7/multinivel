<?php







define('VERSAO', "1.1.0");



// CONSTANTES

define("ENDERECO_BASE", "https://ecommerce.cielo.com.br");

define("ENDERECO", ENDERECO_BASE."/servicos/ecommwsec.do");



define("LOJA", "1044393901");

define("LOJA_CHAVE", "-");

define("CIELO", "-");

define("CIELO_CHAVE", "525a24934cdbe6fafe1001c652dad16fd98a7d630041a29e9e8551b5c00e8dc6");



define("ARQUIVO_LOG_CIELO",APPPATH.'logs/cielo.log');



require      FCPATH.APPPATH.'libraries/cielo/errorHandling.php';

require_once FCPATH.APPPATH.'libraries/cielo/pedido.php';

require_once FCPATH.APPPATH.'libraries/cielo/logger.php';





// Envia requisi��o

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

	//  informa a localiza��o do certificado para verifica��o com o peer

	curl_setopt($sessao_curl, CURLOPT_CAINFO, FCPATH.APPPATH.

			"libraries/cielo/ssl/VeriSignClass3PublicPrimaryCertificationAuthority-G5.crt");

	curl_setopt($sessao_curl, CURLOPT_SSLVERSION, 3);



	//  CURLOPT_CONNECTTIMEOUT

	//  o tempo em segundos de espera para obter uma conex�o

	curl_setopt($sessao_curl, CURLOPT_CONNECTTIMEOUT, 10);



	//  CURLOPT_TIMEOUT

	//  o tempo m�ximo em segundos de espera para a execu��o da requisi��o (curl_exec)

	curl_setopt($sessao_curl, CURLOPT_TIMEOUT, 40);



	//  CURLOPT_RETURNTRANSFER

	//  TRUE para curl_exec retornar uma string de resultado em caso de sucesso, ao

	//  inv�s de imprimir o resultado na tela. Retorna FALSE se h� problemas na requisi��o

	curl_setopt($sessao_curl, CURLOPT_RETURNTRANSFER, true);



	curl_setopt($sessao_curl, CURLOPT_POST, true);

	curl_setopt($sessao_curl, CURLOPT_POSTFIELDS, $paPost );



	$resultado = curl_exec($sessao_curl);

	

	curl_close($sessao_curl);



	if ($resultado)

	{

		return $resultado;

	}

	else

	{

		return curl_error($sessao_curl);

	}

	

	

	

}











?>