<?php

function enviar_notificao_nao_recebeu_produto($formulario = '', $escolha = '') {
    $voucher = '';
    $pin = '';

    if ($escolha == 1) {
        $pin = 'X';
    }

    if ($escolha == 2) {
        $voucher = 'X';
    }

    $msg = "
Confirme si hay recebido:<br/><br/>
 
({$pin}) Pin de su agencia<br/>
({$voucher}) Voucher <br/><br/>
 
Si por alguna razion, no hay recebido el Pin o Voucher escogido, por favor informe:<br/><br/>
{$formulario}
    <br/><br/>
 
El departamento de logistica va entar en contacto directamente con todos los empreendedores 
que por alguna razion no tenga recebido su Pin o Voucher de acuerdo con su escoja. La empresa
pida que todo y cualquier asunto relacionado con el tema sea tratado directamente con la empresa.
Sabemos que algunos pseudo lideres están se aprovechando de algunos pocos casos de problemas con 
logística o que es natural cuando si tiene una empresa real con producto real, para migrar la red 
a otros negocios.<br/>

--------------------------------------------------------------------------------------------------<br/>

Confirme se recebeu:<br/>
<br/>
({$pin}) Pin referente sua agência<br/>
({$voucher}) Voucher referente a sua agência<br/>
 <br/>
Se, por alguma razão, não recebeu Pin ou Voucher referente a sua agência escolhido, por favor informe:<br/><br/>
{$formulario}
 <br/><br/>
O departamento de logística irá contatar diretamente todos os Empreendedores por algum razão não receberam 
o PIN ou Voucher de acordo com sua escolha. A empresa pedi a todos que qualquer questão relacionada a logística 
deve ser tratada diretamente com a empresa. Sabemos que alguns pseudo líderes estão aproveitando alguns casos de 
problemas com logística e que é natural quando você tem uma empresa real com o produto real, para migrar a rede 
para outras empresas.
<br/>
----------------------------------------------------------------------------------------------<br/>

Confirm that you received:<br/>
 <br/>
  ({$pin}) Pin regarding your agency<br/>
  ({$voucher}) Voucher regarding your agency<br/>
 <br/>
  If, for some reason, did not receive Pin or voucher relating to your chosen agency, please inform:<br/><br/>
   {$formulario}
 <br/><br/>
  The logistics department will directly contact all entrepreneurs for some reason did not receive the 
  PIN or voucher according to their choice. The company asked everyone that any question regarding logistics 
  should be treated directly with the company. We know that some pseudo leaders are taking advantage of some 
  cases of logistic problems and that is natural when you have a real company with real product, to migrate 
  the network to other companies.<br/>";

    $body = "
	<html>
	<head></head>
	<body>
	{$msg}
	</body>
	</html>	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
    $envio = @mail(ConfigSingleton::getValue('email_todos_cadastro_brasil'), $assunto, utf8_decode($body), $headers);
    if ($envio)
        return true;
    else
        return false;
}

function enviar_notificacao($email, $assunto, $msg) {

    $body = "
	<html>
	<head></head>
	<body>
	{$msg}
	</body>
	</html>	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . $email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
    $envio = @mail($email, $assunto, utf8_decode($body), $headers);
    if ($envio)
        return true;
    else
        return false;
}

function layout_email($msg) {
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();


    return "
	 <html>
	<head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head>
	<body>
	
     {$msg}
	 
	 	
	  </body>
	  </html>
	 ";
}

function send_email_verificacao($assunto, $msg) {
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    $body = "
	<html>
	<head></head>
	<body>
	<strong>Administrador, você recebeu uma nova mensagem.</strong><br><br>
	<strong>" . get_user()->di_nome . " / " . get_user()->di_id . " </strong><br>
 	------------------------ Assunto -----------------------------------<br>
 	{$assunto} <br>
 	------------------------ Mensagem -----------------------------------<br>
 	{$msg} <br>
 	<br>
 	
	</body>
	</html>
	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . get_user()->di_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; //Cópia para sac
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
    $envio = @mail($fabrica->fa_endereco, $assunto, $body, $headers);

    if ($envio)
        return true;
    else
        return false;
}

function send_email($assunto, $msg) {
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    $body = "
	<html>
	<head></head>
	<body>
	<strong>Administrador, você recebeu uma nova mensagem.</strong><br><br>
	<strong>" . get_user()->di_nome . " / " . get_user()->di_id . " </strong><br>
	------------------------ Assunto -----------------------------------<br>
	{$assunto} <br>
	------------------------ Mensagem -----------------------------------<br>
	{$msg} <br>
	<br>
		
	</body>
	</html>
	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . get_user()->di_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; //Cópia para sac
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; //Cópia para sac
    $envio = @mail(EMAIL, $assunto, $body, $headers);
    if ($envio)
        return true;
    else
        return false;
}

function email_comprar_dolar($patrocinador, $msg) {
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    $body = "
	<html>
	<head></head>
	<body>
----------------- <strong>Solicitação para compra de dólar</strong>--------------<br>
<br>

<strong>Distribuidor/Usuário : " . get_user()->di_nome . "(" . get_user()->di_usuario . ") </strong><br>
<strong>Patrocinador : " . $patrocinador->di_nome . "(" . $patrocinador->di_usuario . ") </strong><br>
<strong>E-mail : " . get_user()->di_nome . " </strong><br>
<strong>Telefone : " . get_user()->di_fone1 . " </strong><br>
<strong>Celular : " . get_user()->di_fone2 . " </strong><br>
<br>
------------------------------------ Mensagem ------------------------------------<br>
{$msg}
<br><br>

		
	</body>
	</html>
	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . $fabrica->fa_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
    $envio = @mail($fabrica->fa_email, "Solicitação - Comprar dolar", $body, $headers);
    if ($envio)
        return true;
    else
        return false;
}

function email_comprar_cruzeiro($patrocinador, $msg) {
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    $body = "
	<html>
	<head></head>
	<body>
----------------- <strong>Solicitação para compra de cruzeiro marítimo com bônus</strong>--------------<br>
<br>

<strong>Distribuidor/Usuário : " . get_user()->di_nome . "(" . get_user()->di_usuario . ") </strong><br>
<strong>Patrocinador : " . $patrocinador->di_nome . "(" . $patrocinador->di_usuario . ") </strong><br>
<strong>E-mail : " . get_user()->di_nome . " </strong><br>
<strong>Telefone : " . get_user()->di_fone1 . " </strong><br>
<strong>Celular : " . get_user()->di_fone2 . " </strong><br>
<br>
------------------------------------ Mensagem ------------------------------------<br>
{$msg}
<br><br>	
	</body>
	</html>
	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . $fabrica->fa_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
    $envio = @mail($fabrica->fa_email, "Solicitação - Comprar cruzeiro marítimo com bônus", $body, $headers);
    if ($envio)
        return true;
    else
        return false;
}

function email_solicitacao_voucher($idCompra) {
    set_loja();
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    $body = "
		<html>
		<head></head>
		<body>
		<strong>Activation via voucher</strong><br><br>	
		---------------------------------------------------------------------<br>
		<strong>The User " . get_user()->di_nome . " request payment via a voucher franchisee upline.</strong><br>
		<strong>Voucher nº " . $idCompra . "</strong><br>
		<strong>sponsor:" . get_user()->di_usuario_patrocinador . "</strong><br>
		<strong>mail: " . get_user()->di_email . "</strong><br>
		<strong>Contact Phone: " . get_user()->di_fone1 . "</strong><br>
		<strong>mobile phone: " . get_user()->di_fone2 . "</strong><br>

		</body>
		</html>
	";
    $assunto = 'Solicitação de ativação via voucher';
    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente	 
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente

    $envio = @mail(get_loja()->fa_email, $assunto, $body, $headers);
    if ($envio)
        return true;
    else
        return false;
}

function email_primeiro_upline($idCompra, $emailPrimeiroUpline) {
    set_loja();
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    $body = "
		<html>
		<head></head>
		<body>
		<strong>Ativação via voucher</strong><br><br>	
		---------------------------------------------------------------------<br>
		<strong>O Usuário " . get_user()->di_nome . " solicita pagamento via voucher de um upline franqueado.</strong><br>
		<strong>Voucher nº " . $idCompra . "</strong><br>
		<strong>Patrocinador :" . get_user()->di_usuario_patrocinador . "</strong><br>
		<strong>E-mail : " . get_user()->di_email . "</strong><br>
		<strong>Telefone para contato : " . get_user()->di_fone1 . "</strong><br>
		<strong>Celular : " . get_user()->di_fone2 . "</strong><br>
		</body>
		</html>
	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
    $assunto = 'Solicitação de ativação via voucher';
    $envio = @mail($emailPrimeiroUpline, $assunto, $body, $headers);
    if ($envio)
        return true;
    else
        return false;
}

function email_segundo_upline($idCompra, $emailSegundoUpline) {
    set_loja();
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    $body = "
		<html>
		<head></head>
		<body>
		<strong>Activation via voucher</strong><br><br>	
		---------------------------------------------------------------------<br>
		<strong>The User " . get_user()->di_nome . " requests payment via a voucher franchisee upline.</strong><br>
		<strong>Voucher nº " . $idCompra . "</strong><br>
		<strong>sponsor:" . get_user()->di_usuario_patrocinador . "</strong><br>
		<strong>mail: " . get_user()->di_email . "</strong><br>
		<strong>contact Phone: " . get_user()->di_fone1 . "</strong><br>
		<strong>mobile Phone: " . get_user()->di_fone2 . "</strong><br>
		</body>
		</html>
	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; //Cópia para sac	
    $assunto = 'Activation request via voucher';
    $envio = @mail($emailSegundoUpline, $assunto, $body, $headers);
    if ($envio)
        return true;
    else
        return false;
}

function email_primeiro_upline_help($descricao, $emailPrimeiroUpline) {
    set_loja();
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    $body = "
		<html>
		<head></head>
		<body>
		<strong>Ajudar</strong><br><br>
		---------------------------------------------------------------------<br>
		<strong>O Usuário " . get_user()->di_nome . " solicita ajuda.</strong><br>
		<strong>E-mail : " . get_user()->di_email . "</strong><br>
		<strong>Telefone para contato : " . get_user()->di_fone1 . "</strong><br>
		<strong>Celular : " . get_user()->di_fone2 . "</strong><br>
		---------------------------------------------------------------------<br>
		<div>Descrição : " . $descricao . "</div>
		</body>
		</html>";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente

    $assunto = 'Solicitação de Ajuda';
    $envio = @mail($emailPrimeiroUpline, $assunto, $body, $headers);

    if ($envio)
        return true;
    else
        return false;
}

function email_segundo_upline_help($descricao, $emailSegundoUpline) {
    set_loja();
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    $body = "
		<html>
		<head></head>
		<body>
		<strong>Ajudar</strong><br><br>
		--------------------------------------------------------------------<br>
		<strong>O Usuário " . get_user()->di_nome . "  solicita ajuda.</strong><br>
		<strong>E-mail : " . get_user()->di_email . "</strong><br>
		<strong>Telefone para contato : " . get_user()->di_fone1 . "</strong><br>
		<strong>Celular : " . get_user()->di_fone2 . "</strong><br>
		---------------------------------------------------------------------<br>
		<div>Descrição:" . $descricao . "</div>
		</body>
		</html>";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From:" . get_loja()->fa_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente

    $envio = @mail($emailSegundoUpline, $assunto, $body, $headers);

    if ($envio)
        return true;
    else
        return false;
}

function email_pagamento_voucher() {
    set_loja();
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();

    $body = "
		<html>
		<head></head>
		<body>
		<strong>Activation via voucher</strong><br><br>	
		---------------------------------------------------------------------<br>
		<strong>Hello User " . get_user()->di_nome . ".</strong><br>
		<strong>You asked for the activation of a franchisee upline.</strong><br>
		<strong>
                In a few minutes you will receive a call and /
                or email to confirm the data to purchase voucher.
                </strong><br>
		---------------------------------------------------------------------<br>
		<div>kind regards</div><br><br>
		<div>nossa empresa.</div><br>
		</body>
		</html>
	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . get_loja()->fa_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
    $assunto = 'Activation request via voucher';
    $envio = @mail(get_user()->di_email, $assunto, $body, $headers);

    if ($envio)
        return true;
    else
        return false;
}

//envia email para o patrocinador
function email_patrocinador_help_line($descricao, $patrocinador = array()) {

    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();

    $body = "
		<html>
		<head></head>
		<body>
		<strong>Pedido Realizado com Ativação via voucher</strong><br><br>
		---------------------------------------------------------------------<br>
		<strong>O Usuário " . get_user()->di_nome . "(" . get_user()->di_usuario . ")</strong><br>
		<strong>Patrocinador :" . get_user()->di_usuario_patrocinador . "</strong><br>
		---------------------------------------------------------------------<br>
		<div> Descrição : " . $descricao . "</div>
		</body>
		</html>
	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
    $assunto = 'Request Made with Activation via voucher';
    $envio = @mail($patrocinador->di_email, $assunto, $body, $headers);
    if ($envio)
        return true;
    else
        return false;
}

function email_patrocinador($idCompra = 0, $patrocinador = array()) {
    set_loja();
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    $body = "
		<html>
		<head></head>
		<body>
		<strong>Request Made with Activation via voucher</strong><br><br>	
		---------------------------------------------------------------------<br>
		<strong>The user " . get_user()->di_nome . "(" . get_user()->di_usuario . ")</strong><br>
		<strong>Voucher nº " . $idCompra . "</strong><br>
		<strong>sponsor:" . get_user()->di_usuario_patrocinador . "</strong><br>
		
		</body>
		</html>
	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente

    $assunto = 'Request Made with Activation via voucher';
    $envio = @mail($patrocinador->di_email, $assunto, $body, $headers);
    $copia = @mail(ConfigSingleton::getValue('email_todos_cadastro_brasil'), $assunto, $body, $headers);
    if ($envio)
        return true;
    else
        return false;
}

/**
 *
 *
 *
 * */
function enviar_email($de, $para, $assunto, $corpo) {
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();

    $body = "
	<html>
	
	<head>
	<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	</head>
	<body>
	{$corpo}
	<br>
	
	</body>
	</html>
	";

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Reply-To: {$de}\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . $fabrica->fa_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
    $envio = @mail($para, $assunto, $body, $headers);
    if ($envio)
        return true;
    else
        return false;
}

//envia e-mail com feedback do usuário sobre a entrega do produto
function email_compraentregue($assunto, $usuario, $mensagem) {
    $ci = & get_instance();
    $ci->load->library('email');

    $ci->email->from('logistica@Nossa Empresa.net', $usuario);
    $ci->email->to('logistica@Nossa Empresa.net'); //logistica@Nossa Empresa.net 

    $ci->email->subject($assunto);
    $ci->email->message($mensagem);
    $ci->email->send();
}
