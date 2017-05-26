<?php

function set_cd($user) {
    $_SESSION['cd_log'] = $user;
    redirect(URL_CD);
    exit;
}

function set_fabrica($fab) {
    $_SESSION['admin'] = $fab;
    redirect(URL_ADMINISTRACAO);
    exit;
}

function set_distribuidor($user) {
    $_SESSION['distribuidor_log'] = $user;
    redirect(URL_DISTRIBUIDOR);
    exit;
}

class Entrar extends CI_Controller {

    public function setadmin() {
        $_SESSION['em_manutencao'] = false;
        redirect(base_url('index.php/entrar/login/'));
    }

    public function manutencao() {
        $this->load->view('entrar/manutencao_view');
    }

    public function index() {
        redirect(base_url('index.php/entrar/login/'));
    }

    public function gerar_cap() {

        $this->load->helper('captcha');
        $arrayLetras = array('a', 'c', 'h', 'b', '1', 'e', '7', 'p', 'm', 'f', 'x', '8', '4');

        $strWrod = '';
        for ($i = 0; $i < 8; $i++) {
            $numA = rand(0, 12);
            $strWrod .= $arrayLetras[$numA];
        }


        $_SESSION['captcha_word'] = $strWrod;

        @mkdir('public/captcha/');
        $vals = array(
            'word' => $strWrod,
            'img_path' => 'public/captcha/',
            'img_url' => base_url('public/captcha/') . '/',
            'img_width' => '140',
            'img_height' => '40',
            'expiration' => 7200
        );

        $cap = create_captcha($vals);
        $_SESSION['captcha_img'] = $cap['image'];

        redirect(base_url('index.php/entrar/login/?' . http_build_query(array_filter($_REQUEST))));
    }

    public function captchar_ajax() {

        $this->load->helper('captcha');
        $arrayLetras = array('a', 'c', 'h', 'b', '1', 'e', '7', 'p', 'm', 'f', 'x', '8', '4');

        $strWrod = '';
        for ($i = 0; $i < 8; $i++) {
            $numA = rand(0, 12);
            $strWrod .= $arrayLetras[$numA];
        }


        $_SESSION['captcha_word'] = $strWrod;

        @mkdir('public/captcha/');
        $vals = array(
            'word' => $strWrod,
            'img_path' => 'public/captcha/',
            'img_url' => base_url('public/captcha/') . '/',
            'img_width' => '140',
            'img_height' => '40',
            'expiration' => 7200
        );

        $cap = create_captcha($vals);
        echo $_SESSION['captcha_img'] = $cap['image'];
    }

    public function login($lang = APP_LINGUAGEM) {
        $_SESSION["lang"] = $lang;
        $this->config->set_item('language', $lang);
       /* if (!isset($_SESSION['captcha_word']) || $_SESSION['captcha_word'] == NULL) {
            redirect(base_url('index.php/entrar/gerar_cap'));
        }*/

        $data = array();

        $this->load->view(strtolower(__CLASS__) . "/index_view", $data);
    }

    public function autenticar() {
        $this->lang->load('publico/entrar/autenticar');

        if (isset($_POST['entrar1'])) {

            /*if ($_SESSION['captcha_word'] != $this->input->post('cap')) {
                $_SESSION['captcha_word'] = NULL;
                redirect(base_url('index.php/entrar/gerar_cap?msg=' . $this->lang->line('erro_codigo')));
            }*/

            if (substr($this->input->post('entrar1', true), -3) == "cdp") {
                $this->login_cdp($this->input->post('entrar1', true), $this->input->post('entrar2', true));
            }

            if (substr($this->input->post('entrar1'), -3) == "fun") {
                $this->login_cd_funcionario($this->input->post('entrar1', true), $this->input->post('entrar2', true));
            }

            if (substr($_POST['entrar1'], -3) == "ind") {
                $this->login_fab($this->input->post('entrar1', true), $this->input->post('entrar2', true));
            }

            $this->login_distribuidor($this->input->post('entrar1', true), $this->input->post('entrar2', true));
            redirect(base_url('index.php/entrar/gerar_cap?msg=' . $this->lang->line('erro_dados')));
        }
    }

    private function login_cdp($login, $senha) {


        //Login Cd	 
        $login_administrador = $this->db->where(array(
                    'cd_id' => str_ireplace(array("sup", "cdp"), array("", ""), $login), 'cd_pw' => sha1($senha)))->join('cidades', 'cd_cidade = ci_id')->get('cd')->result();

        $new_user = new StdClass;

        if (count($login_administrador)) {
            $new_user->nome = $login_administrador[0]->cd_nome;
            $new_user->cidade = $login_administrador[0]->cd_cidade;
            $new_user->uf = $login_administrador[0]->cd_uf;
            $new_user->id_cd = $login_administrador[0]->cd_id;
            $new_user->tipo = 1;
            $new_user->cd_suporte = $login_administrador[0]->cd_suporte;
            set_cd($new_user);
        }

        //Fim login CD 
    }

    private function login_fab($login, $senha) {
        //login da Fabrica
        $login = $this->db->where(array(
                    'rf_id' => str_ireplace("ind", "", $login),
                    'rf_pw' => sha1($senha)
                ))->join('responsaveis_fabrica', 'fabricas.fa_id = responsaveis_fabrica.rf_fabrica')->join('cidades', 'fabricas.fa_cidade = cidades.ci_id')->get('fabricas')->row();

        if ($login->rf_bloqueio == 1) {
            redirect(base_url('index.php/entrar/gerar_cap?msg=Sistema em manutenção'));
            exit;
        }

        if (count($login)) {
            set_fabrica($login);
        }

        //Fim login da Fabrica		
    }

    private function verificar_pagameto($idDistribvuidor) {

        $compra = $this->db->where('co_id_distribuidor', $idDistribvuidor)
                        ->where('co_pago', 0)
                        ->join('compras_sales', 'sa_id_compra=co_id')
                        ->get('compras')->result();

        if (count($compra) > 0) {
            //Pagamento com atm
            foreach ($compra as $c) {

                $atm = new atm();
                $resposta = json_decode($atm->estado_pagamento($c));

                //Se status igual a 0 finaliza a compra

                if ($resposta->status == 0) {
                    //Finalçizar a compra
                    $pagamento = new Pagamento();
                    $pagamento->realizarPagamento(new PagamentoATM($c));
                }
            }
        }
    }

    private function login_distribuidor($login, $senha) {

        $this->lang->load('publico/entrar/autenticar');
        //Login destribuidor		 
        $login = $this->db->where(array(
                            'di_usuario' => $login,
                            'di_senha' => sha1($senha)
                        ))->join('cidades', 'di_cidade = ci_id')
                        ->join('distribuidor_qualificacao', 'dq_id=di_qualificacao', 'left')
                        ->get('distribuidores')->row();

        //Verificar se o distribuidor já confirmou o cadastro.
        $cadastroConfirmado = $this->db
                        ->where('tk_confirmado', 0)
                        ->where('tk_distribuidor', $login->di_id)
                        ->get('token_confirmacao_cadastro')->row();

        if (count($cadastroConfirmado) > 0) {

            $parametros = http_build_query(array(
                'tipo' => 1,
                'msg' => $this->lang->line('erro_cadastro_nao_confirmado')
            ));

            redirect(base_url('index.php/entrar/gerar_cap') . '?' . $parametros);
            exit();
        }

        //Verificando se o distribuidor ta bloqueado
        if (count($login) > 0) {
            if ($login->di_login_status == 0) {
                redirect(base_url('index.php/entrar/gerar_cap?msg=' . $this->lang->line('erro_login_bloqueado')));
                exit();
            }

            //Verificando se o login está aberto ou fechado, pelo administador.
            if (conf()->ativar_login == 0) {
                redirect(base_url('index.php/entrar/gerar_cap?msg=' . $this->lang->line('erro_login_fechado')));
                exit();
            }
        }

        //Verifica se o distribuidor é americano.
        if (count($login) > 0 && $login->di_data_cad <= "2014-03-31 00:00:00") {
            redirect(base_url('index.php/entrar/gerar_cap?msg=Usuário não encontrado! '));
            exit();
        }

        if (count($login)) {
            //Solicitando cartão atm
            if (!empty($login->di_niv)) {
                $atm = new atm();
                $atm->solicitar_cartao($login);
            }
            //Verificando o estado do pagamento.
            $this->verificar_pagameto($login->di_id);

            //Verifica se o distribuidor é americano.

            if ($login->di_data_cad >= "2014-05-19 00:00:00") {
                //Verificando se o usuário já confirmou o nome.
                $distribuidor = $this->db->where('tk_distribuidor', $login->di_id)
                        ->where('tk_confirmado', 0)
                        ->get('token_confirmacao_cadastro')
                        ->row();

                $atm = new atm();
                $cartaoAtm = $atm->solicitar_cartao($login);
//                if (count($distribuidor) > 0) {
//                    redirect(base_url('index.php/entrar/gerar_cap?msg=' . $this->lang->line('erro_cadastro_nao_confirmado')));
//                    exit();
//                }
            }


            $this->db->insert('historico_acesso', array(
                'ha_distribuidor' => $login->di_id,
                'ha_time' => time()
            ));
            //Pagando o Binário do usuario
            $bonusBinario = new BonusBinario();
            $bonusBinario->pagar($login->di_id);

            //Pegando o ip do usuario caso o ip não tenha um ainda.
            $this->salvar_ip($login->di_id);
            set_distribuidor($login);
        }

        //Fim Login destribuidor
    }

    private function salvar_ip($idDistribuidor = 0) {
        if ($idDistribuidor > 0) {
            $ip = $this->db->where('di_id', $idDistribuidor)
                    ->where('di_ip_cadastro is not null')
                    ->get('distribuidores')
                    ->row();

            //Salvando o ip do distribuidor caso não tenha
            if (count($ip) == 0) {

                $this->db->where('di_id', $idDistribuidor)
                        ->update('distribuidores', array(
                            'di_ip_cadastro' => $_SERVER['REMOTE_ADDR']
                ));
            }
        }
    }

    private function login_cd_funcionario($login, $senha) {

        //Login funcionario		 
        $login_funcionario = $this->db->where(array(
                    'cf_id' => str_ireplace("FUN", "", $login),
                    'cf_pw' => sha1($senha)
                ))->join('cd', 'cd_id = cf_cd')->get('cd_funcionarios')->result();

        if (count($login_funcionario)) {
            $new_user->nome = $login_funcionario[0]->cf_nome;
            $new_user->id_cd = $login_funcionario[0]->cf_cd;
            $new_user->cidade = $login_funcionario[0]->cd_cidade;
            $new_user->uf = $login_funcionario[0]->cd_uf;
            $new_user->tipo = 2;
            set_cd($new_user);
        }
    }

    public function alterar_senha() {
        $token = $this->db->where('tk_token', $_GET['token'])->where('tk_usado', 0)->get('token_senha')->result();
        $data = array();
        //var_dump($token);exit;
        if ($this->input->post('senha') == $this->input->post('senha2')) {

            if (strlen($this->input->post('senha')) >= 6) {
                if ($token[0]->tk_tipo == 1) {
                    $this->db->where('di_id', $token[0]->tk_ni)->update('distribuidores', array(
                        'di_senha' => sha1($this->input->post('senha'))
                    ));
                }
                if ($token[0]->tk_tipo == 2) {
                    $this->db->where('rf_id', $token[0]->tk_ni)->update('responsaveis_fabrica', array(
                        'rf_pw' => sha1($this->input->post('senha'))
                    ));
                }
                $this->db->where('tk_ni', $token[0]->tk_ni)->update('token_senha', array(
                    'tk_usado' => 1
                ));

                redirect(base_url() . "index.php/entrar/alterar_senha_mensagem");
            } else {
                $data['error'] = $this->lang->line('erro_altera_senha');
            }
        }

        if (count($token)) {
            $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view", $data);
        } else {
            redirect(base_url() . "index.php/entrar/");
        }
    }

    public function alterar_senha_mensagem() {
        $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
    }

    public function solicitar_alterar_senha() {
        $this->lang->load('publico/entrar/solicitar_alterar_senha');
        $adm = array();
        $data = array();
        if (isset($_POST['ni'])) {
            $di = $this->db->where('di_usuario', $_POST['ni'])->get('distribuidores')->result();

            if (count($di) > 0) {

                $this->load->helper('my_email');

                $token = base64_encode(round(1111, 9999) . date('H:i:s') . $di[0]->di_id);

                $this->db->insert('token_senha', array(
                    'tk_ni' => $di[0]->di_id,
                    'tk_token' => $token,
                    'tk_tipo' => 1
                ));

                $body = layout_email("
	
					<p>" . $this->lang->line('email_ola') . "<strong>" . $di[0]->di_nome . "</strong><br>
					  <br>
					  " . $this->lang->line('email_line1') . ".<br>
					  <br>
					  " . $this->lang->line('email_line2') . ".<br>
					  <br>
					  <a href='" . base_url("index.php/entrar/alterar_senha?token={$token}") . "'>
					  " . base_url("index.php/entrar/alterar_senha?token={$token}") . "
					  </a>
					<br />
					<br />
					");

                enviar_notificacao($di[0]->di_email, utf8_decode($this->lang->line('email_assunto') . get_loja()->fa_nome), $body);

                redirect(base_url() . "index.php/entrar/mudar_senha_mensagem/{$di[0]->di_id}/1");
            }

            if (preg_match_all('/([0-9]{4})([a-z]{3})/i', $_POST['ni'], $id_adm)) {
                $adm = $this->db->where('rf_id', $id_adm[1][0])->get('responsaveis_fabrica')->result();
            }

            //Se o distribuidor estiver bloqueado.
            if (count($adm) > 0) {
                if ($adm[0]->rf_bloqueio == 1) {
                    redirect(base_url('index.php/entrar/login/?msg=Sistema%20em%20manutenção'));
                    exit;
                }
            }

            if (count($adm) > 0) {

                $this->load->helper('my_email');

                $token = base64_encode(round(1111, 9999) . date('H:i:s') . $adm[0]->rf_id);

                $this->db->insert('token_senha', array(
                    'tk_ni' => $adm[0]->rf_id,
                    'tk_token' => $token,
                    'tk_tipo' => 2
                ));

                $body = layout_email("
	
					<p>" . $this->lang->line('email_ola') . "<strong>" . $adm[0]->rf_nome . "</strong><br>
					  <br>
					  " . $this->lang->line('email_line1') . ".<br>
					  <br>
					  " . $this->lang->line('email_line2') . ".<br>
					  <br>
					  <a href='" . base_url("index.php/entrar/alterar_senha?token={$token}") . "'>
					  " . base_url("index.php/entrar/alterar_senha?token={$token}") . "
					  </a>
					<br />
					<br />
					");

                enviar_notificacao($adm[0]->rf_email, utf8_decode($this->lang->line('email_assunto') . get_loja()->fa_nome), $body);

                redirect(base_url() . "index.php/entrar/mudar_senha_mensagem/{$adm[0]->rf_id}/2");
            }

            $data['error'] = $this->lang->line('erro_usuario');
        }


        $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view", $data);
    }

    public function mudar_senha_mensagem() {
        $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
    }

    public function nova_solicitacao() {
        $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view");
    }

    public function confirmar_solicitacao() {

        try {
            IF (!$this->input->post('usuario')) {
                throw new Exception('Usuário não existe');
            }

            $distribuidor = $this->db->where('di_usuario', $this->input->post('usuario'))
                    ->get('distribuidores')
                    ->row();

            if (count($distribuidor) == 0) {
                throw new Exception('Usuário não existe');
            }

            //Distribuidor...
            $data['distribuidor'] = $distribuidor;
            $this->load->view(strtolower(__CLASS__) . "/" .
                    strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view", $data);
        } catch (Exception $exc) {
            redirect(base_url('index.php/entrar/nova_solicitacao/?msg=' . $exc->getMessage()));
        }
    }

    public function nova_solicitacao_emial() {
        $this->lang->load('publico/entrar/nova_solicitacao_email'); 
        $paramentro = http_build_query(array(
            'msg' =>  str_replace('{email}',$this->input->post('email'),$this->lang->line('notificacao_confirmacao_de_envio'))
        ));

        //pegando o emial do usuário.
        if ($this->input->post('usuario')) {
            $distribuidor = $this->db->where('di_usuario', $this->input->post('usuario'))
                    ->get('distribuidores')
                    ->row();

            //Alterando o email para o novo email do usuário se foi alterado
            if ($distribuidor->di_email != $this->input->post('email')) {
                $this->db->where('di_id', $distribuidor->di_id)->update('distribuidores', array(
                    'di_email' => $this->input->post('email')
                ));
                //Evitando de realizar uma nova consulta
                $distribuidor->di_email = $this->input->post('email');
            }


            //pegando a compra do usuário.
            $compra = ComprasModel::compra($distribuidor, true, false, false, true);
            //Pegando a token de confirmação.
            $tokenConfirmacao = $this->db->where('tk_distribuidor', $distribuidor->di_id)
                            ->where('tk_compra', $compra[0]->co_id)
                            ->get('token_confirmacao_cadastro')->row();

            enviar_confirmacao_cadastro($compra[0]->co_id, $tokenConfirmacao->tk_token);
            redirect(base_url('index.php/entrar/login/?'.$paramentro));
        }
    }

}
