<?php

if (!function_exists('uniqueAlfa')) {

    function uniqueAlfa($length = 16) {
        $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $len = strlen($salt);
        $pass = '';
        mt_srand(10000000 * (double) microtime());

        for ($i = 0; $i < $length; $i++) {
            $pass .= $salt[mt_rand(0, $len - 1)];
        }
        return strtoupper($pass);
    }

}

if (!function_exists('enviar_codigo_confirmacao')) {

    function enviar_codigo_confirmacao($idCompra = 0) {
        $ci = & get_instance();

        $compras = get_instance()->db->where('co_id', $idCompra)
                        ->join('distribuidores', 'di_id=co_id_distribuidor')
                        ->get('compras')->row();

        $codigo_confirmcao = uniqueAlfa(6);

        get_instance()->db->insert('tokem_validacao_email', array(
            'tkm_token' => $codigo_confirmcao,
            'tkm_id_distribuidor' => $compras->co_id_distribuidor,
        ));

        $html_pt = '<!doctype html>
        <html>
        <head>
        <meta charset="utf-8">
         <title>Security check nossa empresa</title>
        </head>
        <body>
        <p>Você solicitou um código de ativação para o cadastro na nossa empresa usando o e-mail <a href="mailto:'.ConfigSingleton::getValue('email_copia_oculta').'" onclick="return rcmail.command("compose","'.ConfigSingleton::getValue('email_copia_oculta').'",this)">'.ConfigSingleton::getValue('email_copia_oculta').'</a> </p>
        <p>Seu código de confirmação: <strong>' . $codigo_confirmcao . '</strong></p>
        <p>Favor inserir esse código em seu cadastro para continuar o procedimento.</p>
        <p>Se você não fez essa solicitação favor clicar aqui e abra um chamado reclamando:<a href="'.APP_BASE_URL.'/ticket/" target="_blank">'.APP_BASE_URL.'/ticket/</a></p>
        <p>Obrigado por usar a nossa empresa!<br>
          <br>
          Sinceramente,<br>
          <br>
        A equipe de Cadastro da nossa empresa.</p>
        </body>
     </html>';

        $html_es = '<!doctype html>
            <html>
            <head>
            <meta charset="utf-8">
             <title>Security check nossa empresa</title>
            </head>
            <body>
            <p><span lang="ES">Usted pidió un código de activación para registrar en nossa empresa por correo electrónico<a href="mailto:'.ConfigSingleton::getValue('email_copia_oculta').'" onclick="return rcmail.command("compose","'.ConfigSingleton::getValue('email_copia_oculta').'",this)">'.ConfigSingleton::getValue('email_copia_oculta').'</a></span></p>
            <p><span lang="ES"><br>
              Su código de confirmación:  <strong>' . $codigo_confirmcao . '</strong> <br>
              <br>
              Copiar este código en su registro para continuar con el procedimiento. <br>
              Si usted no ha solicitado este por favor haga click aquí y abrir una queja llamada:<a href="'.APP_BASE_URL.'/ticket/" target="_blank">'.APP_BASE_URL.'/ticket/</a></span></p>
            <p><span lang="ES"><br>
              Gracias por usar nossa empresa usted! <br>
              <br>
              Atentamente, <br>
              <br>
              Personal de Registro de nossa empresa.</span></p>
            </body>
            </html> ';

        $html_en = '<!doctype html>
                <html>
                <head>
                <meta charset="utf-8">
                   <title>Security check nossa empresa</title>
                </head>

                <body>
                <p><span lang="EN-US"> </span><span lang="EN">You asked for</span><span lang="EN"> an activation code to register in nossa empresa using email<a href="mailto:'.ConfigSingleton::getValue('email_copia_oculta').'" onclick="return rcmail.command("compose","'.ConfigSingleton::getValue('email_copia_oculta').'",this)">'.ConfigSingleton::getValue('email_copia_oculta').'</a></span></p>
                <p><span lang="EN">Your</span><span lang="EN"> confirmation code: <strong>' . $codigo_confirmcao . '</strong> <br>
                  <br>
                  Please enter this code in your registration to continue the procedure. <br>
                  If you did not request this please click here and open a call complaining:<a href="'.APP_BASE_URL.'/ticket/" target="_blank">'.APP_BASE_URL.'/ticket/</a></span></p>
                <p><span lang="EN">Thank you for using</span><span lang="EN"> nossa empresa! <br>
                  <br>
                  yours truly, <br>
                  <br>
                  Staff Registration of nossa empresa.</span></p>
                </body>
                </html>
                ';

//        $fabrica = get_instance()->db->get("fabricas")->row();
//        $config['protocol'] = 'sendmail';
//        $config['mailpath'] = '/usr/sbin/sendmail';
//        $config['charset'] = 'utf-8';
//        $config['smtp_user'] = $fabrica->fa_email;
//        $config['smtp_pass'] = $fabrica->fa_senha_email;
//        $config['smtp_host'] = 'mail.empresa.com';
//        $config['smtp_port'] = 587;
//        $config['wordwrap'] = TRUE;
//        $config['mailtype'] = 'html';
//
//        $ci->load->library('Email');
//        $ci->email->initialize($config);
//        $ci->email->from($fabrica->fa_email, $fabrica->fa_nome);
//        $ci->email->to($compras->di_email);
//        $ci->email->cc('system@empresa.com');
//        $ci->email->subject('Security check nossa empresa');
//        $ci->email->message($html_pt);
//        $ci->email->send();
////    
//        $ci->load->library('Email');
//        $ci->email->initialize($config);
//        $ci->email->from($fabrica->fa_email, $fabrica->fa_nome);
//        $ci->email->to($compras->di_email);
//        $ci->email->cc('system@empresa.com');
//        $ci->email->subject('Security check nossa empresa');
//        $ci->email->message($html_es);
//        $ci->email->send();
//
//        $ci->load->library('Email');
//        $ci->email->initialize($config);
//        $ci->email->from($fabrica->fa_email, $fabrica->fa_nome);
//        $ci->email->to($compras->di_email);
//        $ci->email->cc('system@empresa.com');
//        $ci->email->subject('Security check nossa empresa');
//        $ci->email->message($html_en);
//        $ci->email->send();
    }

}

function enviar_confirmacao_cadastro($idCompra, $token) {

    $ci = & get_instance();

    $compras = get_instance()->db->where('co_id', $idCompra)
                    ->join('distribuidores', 'di_id=co_id_distribuidor')
                    ->get('compras')->row();

    $fabrica = get_instance()->db->get("fabricas")->row();

    $valor_formatado = number_format($compras->co_total_valor, 2, ",", "");

    $link = base_url('index.php/distribuidor/confirmar_cadastro?token=' . $token);


    $corpo_pt = <<<EOD
            <!doctype html>
            <html>
            <head>
            <meta charset="utf-8">
            <title>Confirmation email</title>
            </head>

            <body>
            <table width="70%" border="0">
              
              <tr>
                <td><h4>Prezado : {$compras->di_nome}</h4></td>
              </tr>
              <tr>
                <td><p align="justify"> Texto de Boas Vindas !!!!!
                </P><p align="justify">                
                

                </td>
              </tr>
                 <tr>
                <td>
                    <p>Obrigado,<br>
                       Divisão nova conta                    
                    <p>Por favor, este link para confirmar o e-mail. <a href="{$link}">(Clique aqui para confirmação)</a></p>
                </td>
              </tr>
            </table>
            </body>
            </html>
EOD;
    $corpo_en = <<<EOD
             
EOD;
    $corpo_esp = <<<EOD
             
EOD;

    $config['protocol'] = 'sendmail';
    $config['mailpath'] = '/usr/sbin/sendmail';
    $config['charset'] = 'utf-8';
    $config['smtp_user'] = $fabrica->fa_email;
    $config['smtp_pass'] = $fabrica->fa_senha_email;
    $config['smtp_host'] = $fabrica->fa_server_email;
    $config['smtp_port'] = $fabrica->fa_porta_email;
    $config['wordwrap'] = TRUE;
    $config['mailtype'] = 'html';

    $ci->load->library('Email');
    $ci->email->initialize($config);
    $ci->email->from($fabrica->fa_email, $fabrica->fa_nome);
    $ci->email->to($compras->di_email);
    $ci->email->cc(ConfigSingleton::getValue('email_ativacao_estados_unidos'));
    $ci->email->subject('Confirmation email');
    $ci->email->message($corpo_pt . "<br>" . $corpo_esp . "<br>" . $corpo_en);
    $ci->email->send();
//    
//    $ci->load->library('Email');
//    $ci->email->initialize($config);
//    $ci->email->from($fabrica->fa_email, $fabrica->fa_nome);
//    $ci->email->to($compras->di_email);
//    $ci->email->cc(ConfigSingleton::getValue('email_ativacao_estados_unidos'));
//    $ci->email->subject('Confirmation email');
//    $ci->email->message($corpo_esp);
//    $ci->email->send();
//
//    $ci->load->library('Email');
//    $ci->email->initialize($config);
//    $ci->email->from($fabrica->fa_email, $fabrica->fa_nome);
//    $ci->email->to($compras->di_email);
//    $ci->email->cc(ConfigSingleton::getValue('email_ativacao_estados_unidos'));
//    $ci->email->subject('Confirmation email');
//    $ci->email->message($corpo_en);
//    $ci->email->send();
//$cliente = new SoapClient(null,array(
//        'location'=>'http://newNossa Empresa.com/confirmacao-emails/mail/examples/test_smtp_basic.php',
//	'uri'=>'http://newNossa Empresa.com/confirmacao-emails/mail/examples/'
//    ));
    // enviar($to,$msg,$from,$senha,$usuario)
//    $reponse =$cliente->enviar($compras->di_emai,$body,$fabrica->fa_email,$fabrica->fa_senha_email,$fabrica->fa_nome);
//    @mail($compras->di_email, 'Confirmation email', $corpo, $headers);
}

function enviar_notificacao($email, $assunto, $corpo) {

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . get_loja()->fa_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente


    @mail($email, $assunto, $corpo, $headers);
}

function enviar_suporte($email, $assunto, $corpo) {

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . get_loja()->fa_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente


    if (@mail($email, $assunto, $corpo, $headers)) {
        return true;
    } else {
        return false;
    }
}

function layout_email($msg) {
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    return "
		<html>
			<head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head>
				<body>
					{$msg}
					<br>
					<div>" . $fabrica->fa_endereco . "</div>
				</body>
		</html>";
}

function boleto_plano_cadastro($distribuidor, $link_boleto) {
    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . get_loja()->fa_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente

    $body = layout_email("
	
	<p>Olá <strong>" . $distribuidor->di_nome . "</strong><br>
	  <br>
	 Segue abaixo o link de acesso ao seu boleto bancário. 
	 <br>
	 Somente após o pagamento do boleto seu cadastro será confirmado. 
	 Se o pagamento já foi realizado, desconsidere este e-mail.
	 <br>
	 <a href='{$link_boleto}'>Gerar Boleto Bancário</a>
	<br>
	<br>
	");

    @mail($distribuidor->di_email, 'PAGAMENTO DE PLANO  - ' . get_loja()->fa_nome, $body, $headers);
}

function notificacao_token($distribuidor, $token) {
//
//    $headers = "MIME-Version: 1.1\r\n";
//    $headers .= "Content-type: text/html; charset=utf-8\r\n";
//    $headers .= "From: " . get_loja()->fa_email . "\r\n"; // remetente
////	$headers .= "Cc: system@empresa.com\r\n";//Cópia para sac
//
//
//    $body = layout_email('
//	<div>Seja bem vindo a NewNossa Empresa, por favor verifique a imagem em anexo.</div>			
//         <img src="' . base_url('public/imagem/email_cadastro.jpg') . '">
//	');
//
//    @mail($distribuidor->di_email, 'CODIGO PARA PAGAMENTO - ' . get_loja()->fa_nome, $body, $headers);
}

function notificacao_cadastro($distribuidor, $patrocinador, $senha) {

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . get_loja()->fa_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente
//$body = layout_email($body);
//	@mail($distribuidor->di_email, 'CONFIRMAÇÃO DE CADASTRO - '.get_loja()->fa_nome, $body, $headers);
}

function notificacao_sac($distribuidor, $patrocinador) {
    $ci = & get_instance();
    $fabrica = $ci->db->get('fabricas')->row();
    
    if(count($fabrica)==0){
        return false;
    }

    $headers = "MIME-Version: 1.1\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "From: " . $fabrica->fa_email . "\r\n"; // remetente
    $headers .= "Cc: " . ConfigSingleton::getValue('email_todos_cadastro_brasil') . "\r\n"; // remetente
    $headers .= "Cco: " . ConfigSingleton::getValue('email_copia_oculta') . "\r\n"; // remetente

    $body = layout_email("
	<html>
		<head></head>
		<body>
		<strong>Novo cadastro realizado - {$fabrica->fa_nome}</strong><br><br>	
		---------------------------------------------------------------------<br>
		<strong>Nome : " . $distribuidor->di_nome . "(" . $distribuidor->di_usuario . ")</strong><br>
		<strong>Tipo de Agencia : " .PlanosModel::getPlanoDistribuidorNaoPago($distribuidor->di_id)->pa_descricao . "</strong><br>
		<strong>País : " .DistribuidorDAO::getPais($distribuidor->di_cidade)->ps_nome . "</strong><br>
		<strong>Cidade : " .DistribuidorDAO::getCidade($distribuidor->di_cidade)->ci_nome . "</strong><br>
		<strong>Nome do Patrocinador :" . $patrocinador->di_nome . "(" . $patrocinador->di_usuario . ")</strong><br>
		<strong>Data Cadastro :" .date('d/m/Y'). ")</strong><br>
		---------------------------------------------------------------------<br>
		</body>
    </html>
	");

    @mail($fabrica->fa_email, 'NOVO CADASTRO - ' . $fabrica->fa_nome, $body, $headers);
}
