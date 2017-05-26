<?php

class Distribuidor extends CI_Controller {

    public function teste() {
        $atm = new atm();
    }

    public function remover_distribuidor() {
        autenticar();
        $id = $this->uri->segment(3);

        $alocado = $this->db
                        ->select('li_id_distribuidor')
                        ->where('li_id_distribuidor', $id)
                        ->get('distribuidor_ligacao')->row();

        if (count($alocado) == 0) {

            $dis = $this->db
                            ->where('di_id', $id)
                            ->get('distribuidores')->row();

            $this->db->insert('distribuidores_excluido', array(
                'di_dados' => json_encode($dis),
                'di_id' => $id
            ));

            $this->db->where('di_id', $id)->delete('distribuidores');
            $this->db->where('co_id_distribuidor', $id)->delete('compras');
            $this->db->where('vt_distribuidor', $id)->delete('vitrines');
        }

        redirect(base_url('index.php/distribuidor/pendentes'));
    }

    public function verificar_token_pagamento_ajax() {
        try {
            if (!$this->input->post('token_camp1')) {
                throw new Exception('');
            }

            if (!$this->input->post('token_camp2')) {
                throw new Exception('');
            }

            if (!$this->input->post('token_camp3')) {
                throw new Exception('');
            }

            if (!$this->input->post('token_camp4')) {
                throw new Exception('');
            }

            if (!$this->input->post('token_camp5')) {
                throw new Exception('');
            }

            $token = '';
            $token.= $this->input->post('token_camp1');
            $token.='-' . $this->input->post('token_camp2');
            $token.='-' . $this->input->post('token_camp3');
            $token.='-' . $this->input->post('token_camp4');
            $token.='.' . $this->input->post('token_camp5');

            $dados = ComprasModel::validarTokenProdutoComprado($token);

            if (count($dados) == 0) {
                throw new Exception('Error');
            }

            echo 'ok';
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
    }

    public function enviar_token_pagamento() {
        $this->lang->load('distribuidor/home/meio_view');
        try {
            if (!$this->input->post('token_camp1')) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            if (!$this->input->post('token_camp2')) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            if (!$this->input->post('token_camp3')) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            if (!$this->input->post('token_camp4')) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            if (!$this->input->post('token_camp5')) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            $token = '';
            $token.= $this->input->post('token_camp1');
            $token.='-' . $this->input->post('token_camp2');
            $token.='-' . $this->input->post('token_camp3');
            $token.='-' . $this->input->post('token_camp4');
            $token.='.' . $this->input->post('token_camp5');


            if (count(ComprasModel::validarTokenProdutoComprado($token)) > 0) {
                
            }
        } catch (Exception $ex) {
            set_notificacao(2, $ex->getMessage());
            redirect(base_url());
        }
    }

    public function rede_linear() {
        $data['mobile'] = false;
        $this->load->library('user_agent');
        if ($this->agent->is_mobile()) {
            $data['mobile'] = true;
        }

        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    function cidades() {
        $c = $this->db->where('ci_estado', $_POST['es_id'])->get('cidades')->result();
        echo json_encode($c);
    }

    public function meus_dados() {
        $data['pagina'] = 'distribuidor/layout_distribuidor';
        $this->load->view('home/index_view', $data);
    }

    public function titulares() {
        $data['dados'] = 'titulares';
        $data['pagina'] = 'distribuidor/layout_distribuidor';
        $this->load->view('home/index_view', $data);
    }

    public function dados_bancarios() {
        $data['dados'] = 'dados_bancarios';
        $data['pagina'] = 'distribuidor/layout_distribuidor';
        $this->load->view('home/index_view', $data);
    }

    public function verificar_conta() {
        $data['dados'] = 'verificar_conta';
        $data['pagina'] = 'distribuidor/layout_distribuidor';
        $this->load->view('home/index_view', $data);
    }

    public function marketing() {
        $data['pagina'] = 'distribuidor/marketing';
        $this->load->view('home/index_view', $data);
    }

    public function download() {
        $data['pagina'] = 'distribuidor/download';
        $this->load->view('home/index_view', $data);
    }

    public function construcao() {
        $data['pagina'] = 'distribuidor/construcao';
        $this->load->view('home/index_view', $data);
    }

    public function enviar_documento() {

        if ($_POST['do_categoria'] == '') {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Selecione o tipo do documento!")));
            redirect(base_url('index.php/distribuidor/verificar_conta'));
            exit;
        }

        if ($_FILES['file']['name'] == '') {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Selecione o arquivo!")));
            redirect(base_url('index.php/distribuidor/verificar_conta'));
            exit;
        }
// 		$path = 'D:/';
        $path = "";
        $path.= '/home/soffce/';
        $path = $path . 'documentos/' . get_user()->di_usuario . '/';

        $path_file_relative = 'documentos/' . get_user()->di_usuario . '/';

        $arrayPontos = explode('.', $_FILES['file']['name']);
        $extensao = end($arrayPontos);
        $nome = $_FILES['file']['name'];
        $nome = str_ireplace('.' . $extensao, '', $nome);

        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|png|doc|docx|pdf';
        $config['max_size'] = '10000';
        $config['file_name'] = url_title($nome);


        if (!file_exists($config['upload_path'])) {
            mkdir($config['upload_path']);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file')) {
            set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Erro ao enviar: " . $this->upload->display_errors())));
        } else {

            $data = $this->upload->data();

            $this->db->insert('documentos', array(
                'do_distribuidor' => get_user()->di_id,
                'do_categoria' => $_POST['do_categoria'],
                'do_nome_arquivo' => $path_file_relative . $data['file_name']
            ));

            $html = "";
            $html.="Nome distribuidor: " . get_user()->di_nome;
            $html.="<br/>Usuário: " . get_user()->di_usuario;
            $html.="<br/>E-mail: " . get_user()->di_email;
            $html.="<br/>Telefone: " . get_user()->di_fone1;
            $html.="<br/><a href='" . conf()->url_administracao . "index.php/documentos/editar/" . get_user()->di_id . "'>Verificação de contas</a>";
            send_email_verificacao('Envio de documentação', $html);

            set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Enviado com sucesso")));
        }

        redirect(base_url('index.php/distribuidor/verificar_conta'));
    }

    public function ver_doc() {

        /**
         * Visualização de tocumentos.
         * 1 - Verificação da senha.
         * 2 - visualização do documento.
         */
        //Verificando a senha informada pelo usuário.
        $distrib = $this->db->where('di_pw', sha1($this->input->post('senha')))
                        ->where('di_usuario', get_user()->di_usuario)->get('distribuidores')->row();


        if (count($distrib)) {

            $fileTemp = "public/tmpdoc/";
            if (!file_exists($fileTemp)) {
                mkdir($fileTemp);
            }


            $data['arquivo'] = $arquivo = $this->db
                    ->where('do_id', $this->input->post('di_doc'))
                    ->get('documentos')
                    ->row();

            //Gerando um nome para o arquivo
            $fileName = 'tmp-' . sha1(rand(111, 999) . date('YmdHis') . rand(111, 999));
            $fileExtensao = @explode('.', $arquivo->do_nome_arquivo);
            $fileExtensao = end($fileExtensao);
            $fileName = $fileName . '.' . $fileExtensao;

            //File Origen
            $fileOrigem = '/home/escrit/' . $arquivo->do_nome_arquivo;
            //$fileOrigem = $arquivo->do_nome_arquivo;
            $fileDestino = $fileTemp . $fileName;

            @copy($fileOrigem, $fileDestino);

            redirect(base_url($fileDestino));
        } else {
            //Senha informada errada redireciona para a tela com a  mensagem de erro.
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Senha não encontrada.")));
            redirect(base_url('index.php/distribuidor/verificar_conta'));
        }
    }

    public function upgrade_plano() {
        $data['pagina'] = 'distribuidor/upgrade_plano';
        $this->load->view('home/index_view', $data);
    }

//Salva o upgrade de planos	
    public function salvar_upgrade_plano() {
        $this->lang->load('distribuidor/distribuidor/upgrade');
        /**
         * 1 Altera o plano do distribuidor + a difereça do plano novo.
         * 2 Incluir o plano antigo em histórico de planos.
         * 3 Finaliza o Upgrade.
         * 4 Plano Fast start terá prazo 60 dias para fazer upgrade
         */
        $data_dif = diffData(get_user()->di_data_cad);

        if (conf()->data_max_sessenta == 0) {
            $data_dif = 0;
        }

        if ($data_dif > 60 && DistribuidorDAO::getPlano(get_user()->di_id)->pa_id == 100) {
            set_notificacao(array(0 => array('tipo' => 2,
                    'mensagem' => $this->lang->line('error_praso_exp_plano_fast')
            )));

            redirect(base_url('/index.php/distribuidor/upgrade_plano'));
        }

        if ($this->input->post('plano')) {

            $plano = $this->db->where('pa_id', $this->input->post('plano'))->get('planos')->row();

            $compra = $this->db->where('co_total_valor != 0.00')
                            ->where('co_eplano', 1)
                            ->where('co_id_distribuidor', get_user()->di_id)
                            ->where('co_pago', 1)->get('compras')->row();

            //checando se ele ja vez uma compra para o mesmo plano.
            $co_plan = $this->db->where('co_id_distribuidor', get_user()->di_id)
                            ->where('co_eupgrade', 1)
                            ->where('co_id_plano', $this->input->post('plano'))
                            ->get('compras')->row();

            //pegando o id do plano
            $tipo_plano = $this->input->post('plano');


            //Sabendo qual pais o usuário pertence
            $pais = $this->db->where('ci_id', get_user()->di_cidade)
                    ->join('estados', 'ci_estado=es_id')
                    ->get('cidades')
                    ->row();

            //Calculando a diferênça do plano.
            $hash_boleto_new = criar_hash_boleto();
            $planoAtual = DistribuidorDAO::getPlano(get_user()->di_id);

            //Se não tem o plano para o usuário então cria um nova compra de um plano
            if (count($co_plan) == 0) {

                //Plano upgrade.
                $plano_upgrade = $this->db->where('pug_id_plano_upgrade', $plano->pa_id)
                        ->where('pug_id_plano', $planoAtual->pa_id)
                        ->get('planos_upgrades')
                        ->row();

                $co_descricao = "Upgrade para do plano {$planoAtual->pa_descricao} para o plano {$plano->pa_descricao}";
                //gerando uma nova comprar do plano do distribuidor.
                $this->db->insert('compras', array(
                    'co_tipo' => 1,
                    'co_entrega' => 1,
                    'co_id_distribuidor' => get_user()->di_id,
                    'co_entrega_cidade' => get_user()->di_cidade,
                    'co_entrega_uf' => get_user()->di_uf,
                    'co_entrega_bairro' => get_user()->di_bairro,
                    'co_entrega_cep' => get_user()->di_cep,
                    'co_entrega_complemento' => get_user()->di_complemento,
                    'co_entrega_numero' => get_user()->di_numero,
                    'co_entrega_logradouro' => get_user()->di_endereco,
                    'co_total_pontos' => $plano_upgrade->pug_pontos,
                    'co_situacao' => 7,
                    'co_id_plano' => $plano->pa_id,
                    'co_eplano' => 1,
                    'co_descricao' => $co_descricao,
                    'co_pago' => 0,
                    'co_eupgrade' => 1,
                    'co_tipo_plano' => $tipo_plano,
                    'co_forma_pgt' => 1,
                    'co_hash_boleto' => $hash_boleto_new,
                    'co_total_valor' => $plano_upgrade->pug_valor,
                    'co_data_insert' => date('Y-m-d H:i:s')
                ));

                $id_compra = $this->db->insert_id();

                //Salvar o compra em produtos_comprados
                $this->db->insert('produtos_comprados', array(
                    'pm_id_compra' => $id_compra,
                    'pm_valor' => $plano_upgrade->pug_valor,
                    'pm_quantidade' => 1,
                    'pm_tipo' => 1,
                    'pm_pontos' => $plano_upgrade->pug_pontos,
                    'pm_id_produto' => $plano_upgrade->pug_produto,
                    'pm_valor_total' => $plano_upgrade->pug_valor,
                ));
            } else {
                //Pega o id do plano
                $id_compra = $co_plan->co_id;
            }

            //Pegando o ultimo plano do usuário.
            $planoAtual = DistribuidorDAO::getPlano(get_user()->di_id);
            redirect(base_url('index.php/loja/pagar_transparente?c=' . $id_compra . '&paymentMethod=0'));
        }
    }

    public function mudar_senha() {


        if ($this->input->post('new_senha')) {
            $old_senha = $this->db->where('di_id', get_user()->di_id)
                            ->where('di_senha', sha1($this->input->post('senha')))
                            ->get('distribuidores')->num_rows;

            if ($old_senha > 0) {

                //Altera a senha	
                $new_senha = $this->db->where('di_id', get_user()->di_id)
                        ->update('distribuidores', array(
                    'di_senha' => sha1($this->input->post('new_senha'))));

                if ($new_senha) {
                    set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Senha atualizada com sucesso")));
                } else {
                    set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Erro ao atualizar senha, tente novamente")));
                }
            } else {
                set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Senha atual incorreta!")));
            }
        }

        $data['dados'] = 'mudar_senha';
        $data['pagina'] = 'distribuidor/layout_distribuidor';
        $this->load->view('home/index_view', $data);
    }

    public function mudar_senha2() {

        if ($this->input->post('new_senha')) {

            $senhaSeguranca = new SenhaSeguranca();

            $ja_definiu = $senhaSeguranca->jaDefiniuSenha();


            $old_senha = $this->db
                            ->where('di_id', get_user()->di_id)
                            ->where('di_pw', sha1($this->input->post('senha')))
                            ->get('distribuidores')->num_rows;


            if ($old_senha > 0 || $ja_definiu->di_pw == '') {
                if ($senhaSeguranca->eIgualSenhaDeLogin($this->input->post('new_senha'))) {
                    set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "A senha de segurança não pode ser igual a senha de login!")));
                } else {
                    //Altera a senha	
                    $new_senha = $this->db->where('di_id', get_user()->di_id)
                            ->update('distribuidores', array(
                        'di_pw' => sha1($this->input->post('new_senha'))));

                    if ($new_senha) {
                        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Senha atualizada com sucesso")));
                    } else {
                        set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Erro ao atualizar senha, tente novamente")));
                    }
                }
            } else {
                set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Senha atual incorreta!")));
            }
        }

        $data['dados'] = 'mudar_senha2';
        $data['pagina'] = 'distribuidor/layout_distribuidor';
        $this->load->view('home/index_view', $data);
    }

    public function resetar_senha_seguranca() {
        $token = $this->db
                        ->where('tk_token', $_GET['token'])
                        ->where('tk_usado', 0)
                        ->join('distribuidores', 'di_id=tk_ni')
                        ->get('token_senha')->row();

        if (count($token) > 0) {
            $this->db->where('di_id', $token->tk_ni)->update('distribuidores', array(
                'di_pw' => ''
            ));
        }
        autenticar();
        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "DEFINA UMA NOVA SENHA DE SEGURANÇA. DEIXE O CAMPO SENHA ATUAL EM BRANCO.")));
        redirect(base_url('index.php/distribuidor/mudar_senha2'));
    }

    public function pedir_senha_seguranca() {
        autenticar();

        $token = base64_encode(round(1111, 9999) . date('H:i:s') . get_user()->di_id);
        $this->db->insert('token_senha', array('tk_ni' => get_user()->di_id, 'tk_token' => $token));

        $body = layout_email("
	               <p>Olá <strong>" . get_user()->di_nome . "</strong><br>
					  <br>
					  Foi solicitado em nosso sistema um pedido de recuperação de senha de segurança.<br>
					  <br>
					  Caso queira realmente alterar sua senha no sistema clique no link abaixo<br>
					  <br>
					  <a href='" . base_url("index.php/distribuidor/resetar_senha_seguranca?token={$token}") . "'>
					  CLIQUE AQUI
					  </a>
					<br />
			
					<br />
					");

        enviar_email(EMAIL, get_user()->di_email, 'Recuperar senha de segurança', $body);

        $data['dados'] = 'pedir_senha_seguranca';
        $data['pagina'] = 'distribuidor/layout_distribuidor';
        $this->load->view('home/index_view', $data);
    }

    public function salvar_conta_empresa() {
        $dados = array('di_niv' => $_POST['di_niv'], 'di_email_atm' => $_POST['di_email_atm']);
        $this->db->where('di_id', get_user()->di_id)->update('distribuidores', $dados);
        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Dados alterados com sucesso.")));
        redirect(base_url('index.php/distribuidor/dados_bancarios'));
    }

    public function validar_conta_empresa() {
        $dados = array();
        //Atualizando os dados de pagamento.
        if (isset($_POST['di_email_atm']) && !empty($_POST['di_email_atm'])) {
            $atm = new atm();
            $dados = $atm->consulta_cadastro_ewallet($_POST['di_email_atm']);
        }
        echo json_encode($dados);
    }

    public function get_pais_ajax() {

        $ps_nome = $_REQUEST['ps_nome'];
        $pais = $this->db->where('ps_sigla', $ps_nome)->get('pais')->row();
        if (count($pais) > 0) {
            echo json_encode(array('status' => 1, 'data' => $pais->ps_id));
        } else {
            echo json_encode(array('status' => 1, 'data' => ''));
        }
    }

    /**
     * Pega as informações do usuário de acordo com os dados 
     * puchados n ewallty pay.
     */
    public function get_dados_plataform() {

        $distribuidor = array();
        if (isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {
            $email = $_REQUEST['email'];

            $atm = new atm();
            $distribuidor = $atm->consulta_cadastro_ewallet($email);
            $distribuidor['birthday'] = date('d/m/Y', strtotime($distribuidor['birthday']));
        }

        echo json_encode($distribuidor);
    }

    public function salvar_info_banco() {

        self::validar_senha_seguranca($_POST['senha_segurancao'], $_POST['url']);

        $cc_banco = get_user()->di_conta_banco;
        $cc_tipo = get_user()->di_conta_tipo;
        $di_numero = get_user()->di_conta_numero;
        $cc_agencia = get_user()->di_conta_agencia;
        $di_nome = get_user()->di_conta_nome;
        $cc_cpf = get_user()->di_conta_cpf;

        if (
                $cc_banco != $_POST['di_conta_banco'] || $cc_tipo != $_POST['di_conta_tipo'] || $cc_agencia != $_POST['di_conta_agencia'] || $di_numero != $_POST['di_conta_numero'] || $di_nome != $_POST['di_conta_nome'] || $cc_cpf != $_POST['di_conta_cpf']
        ) {
            $_POST['di_conta_verificada'] = '0';
        }



        $this->db->where('di_id', get_user()->di_id)->update('distribuidores', valida_fields('distribuidores', $_POST));
        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Dados alterados com sucesso.")));


        redirect($_POST['url']);
    }

    public function salvar_info_banco_alternativo() {

        self::validar_senha_seguranca($_POST['senha_segurancao'], $_POST['url']);


        $this->db->where('di_id', get_user()->di_id)->update('distribuidores', valida_fields('distribuidores', $_POST));
        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Dados alterados com sucesso.")));
        redirect($_POST['url']);
    }

    public function validar_senha_seguranca($senha, $retorno) {

        $senha_seguranca = $this->db
                        ->select('di_id')
                        ->where('di_pw', sha1($senha))
                        ->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->row();

        if (count($senha_seguranca) == 0) {
            set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Senha de segurança incorreta.")));
            redirect($retorno);
            exit;
        }
    }

    public function salvar_perna() {

        autenticar();


        $this->db->where('di_id', get_user()->di_id)->update('distribuidores', valida_fields('distribuidores', $_POST));


        $dis = $this->db
                        ->join('cidades', 'di_cidade = ci_id')
                        ->join('distribuidor_qualificacao', 'dq_id=di_qualificacao')
                        ->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->row();

        set_user($dis, false);

        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Perna preferencial alterada com sucesso.")));

        redirect($_POST['url']);
    }

    public function salvar_info() {
        autenticar();


//        $tela = self::valida_tela($_POST);
//
//        //Verifica se não há nenhum erro dos dados inseridos no formulário
//        if (count($tela) > 0) {
//            $_SESSION['form_cad_error'] = $tela;
//            redirect(base_url('index.php/distribuidor/meus_dados'));
//            exit;
//        } else {
//            if (isset($_SESSION['form_cad_error'])) {
//                unset($_SESSION['form_cad_error']);
//            }
//        }
//        if (isset($_POST['di_titular2_nascimento'])) {
//            $_POST['di_titular2_nascimento'] = data_to_usa($_POST['di_titular2_nascimento']);
//        }
//        $_POST['di_data_nascimento'] = data_to_usa($_POST['di_data_nascimento']);
        //pegando informações  para salvar informações do distribuidor
        $dados = $this->db->where('di_id', get_user()->di_id)->get('distribuidores')->row();
        $this->db->insert('distribuidores_log_user', array(
            'lu_distribuidor' => get_user()->di_id,
            'lu_infor' => json_encode($dados),
            'lu_data' => date('Y-m-d H:s:m')
        ));



//        if ($_POST['di_senha_seg'] != '') {
//            $password = $this->db->select('di_pw')->where('di_id', get_user()->di_id)->get('distribuidores')->row();
//            if (sha1($_POST['di_senha_seg']) != $password->di_pw) {
//                set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Senha de seguran�a incorreta.")));
//                redirect($_POST['url']);
//                exit;
//            }
//        }
        //Colocando a cidade.
        $cidade = $this->db->where('ci_uf', $_POST['di_uf'])->like('ci_nome', $_POST['di_cidade'])->get('cidades')->row();
        $estado = $this->db->where('es_id', $_POST['di_uf'])->get('estados')->row();
        if (count($cidade) == 0) {
            if (count($estado) > 0) {
                $this->db->insert('cidades', array(
                    'ci_nome' => $_POST['di_cidade'],
                    'ci_estado' => $estado->es_id,
                    'ci_uf' => $estado->es_uf,
                    'ci_pais' => $estado->es_pais
                ));
                $_POST['di_cidade'] = (int) $this->db->insert_id();
            }
        } else {
            $_POST['di_cidade'] = (int) $cidade->ci_id;
        }


        if (isset($_POST['di_senha_novo']) && !empty($_POST['di_senha_novo'])) {
            $_POST['di_senha'] = sha1($_POST['di_senha']);


            // confirmação de senha atual 
            $confirmacao_senha = $this->db->where('di_senha', $_POST['di_senha'])
                    ->get('distribuidores')
                    ->row();

            $_POST['di_senha'] = sha1($_POST['di_senha_novo']);

            if (count($confirmacao_senha) == 0) {
                set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Sua senha não foi alterada, erro na confirmação da senha.")));
                unset($_POST['di_senha']);
            }
        } else {
            unset($_POST['di_senha']);
        }

        //Altera os dados do distribuidor.
        if (isset($_POST['tipopessoa']) && $_POST['tipopessoa'] == 1) {
            $this->db->where('dpj_id_distribuidor', get_user()->di_id)->delete('distribuidor_pessoa_juridica');
            $_POST['dpj_id_distribuidor'] = get_user()->di_id;
            $this->db->insert('distribuidor_pessoa_juridica', valida_fields('distribuidor_pessoa_juridica', $_POST));
        }

        if (isset($_POST['tipopessoa']) && $_POST['tipopessoa'] == 0) {
            $this->db->where('dpj_id_distribuidor', get_user()->di_id)->delete('distribuidor_pessoa_juridica');
        }

        //alterando dados do distribuidor
        $this->db->where('di_id', get_user()->di_id)->update('distribuidores', valida_fields('distribuidores', $_POST));

        $distribuidor = $this->db->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->row();
        /**
         * Pos atualiza o atualizar o usuário local
         * atualiza ele também na atm.
         */
        $atm = new atm();
        $res = $atm->atualiar_cadastro_ewallet($distribuidor);



        $dis = $this->db
                        ->join('cidades', 'di_cidade = ci_id')
                        ->join('distribuidor_qualificacao', 'dq_id=di_qualificacao')
                        ->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->row();

        set_user($dis, false);

        set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => "Dados alterados com sucesso.")));

        redirect($_POST['url']);
    }

    /**
     * ajax do busca estado.
     */
    public function estados() {
        $c = $this->db->where('es_pais', $_POST['es_pais'])->get('estados')->result();
        echo json_encode($c);
    }

    /*
     *
     * Função de validação de dados
     *
     */

    private function validaCPF($cpf) {
        // Verifica se um número foi informado
        if (empty($cpf)) {
            return false;
        }


        // Verifica se o numero de digitos informados é igual a 11 
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' ||
                $cpf == '11111111111' ||
                $cpf == '22222222222' ||
                $cpf == '33333333333' ||
                $cpf == '44444444444' ||
                $cpf == '55555555555' ||
                $cpf == '66666666666' ||
                $cpf == '77777777777' ||
                $cpf == '88888888888' ||
                $cpf == '99999999999') {
            return false;
            // Calcula os digitos verificadores para verificar se o
            // CPF é válido
        } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    private function valida_tela($dados) {

        $mensagem_erro = array();
        #-- Validação do nome

        if ($dados['di_nome'] == '' || strlen($dados['di_nome']) < 3) {
            $mensagem_erro[] = 'Informe o seu nome completo';
        }

        if ($dados['di_nome_mae'] == '' || strlen($dados['di_nome']) < 3) {
            $mensagem_erro[] = 'Informe o nome completo da Mãe';
        }

        if ($dados['di_rg'] == '' || !preg_match('/^[0-9]*$/', $dados['di_rg'])) {
            $mensagem_erro[] = 'RG inválido';
        }

        if ($dados['di_cpf'] == '' || strlen($dados['di_cpf']) != 11 || !self::validaCPF($dados['di_cpf'])) {
            $mensagem_erro[] = 'Informe um CPF válido';
        }


        if ($dados['di_data_nascimento'] == '' || self::e_data_valida($dados['di_data_nascimento']) == false) {
            $mensagem_erro[] = 'Imforme uma data de nascimento válida no formato dd/mm/aaaa';
        }

        if ($dados['di_sexo'] != 'M' && $dados['di_sexo'] != 'F') {
            $mensagem_erro[] = 'Informe o sexo válido';
        }

        if (strlen($dados['di_cep']) != 9) {
            $mensagem_erro[] = 'Informe um CEP válido no formato 00000-000';
        }

        if ($dados['di_bairro'] == '') {
            $mensagem_erro[] = 'Informe um bairro';
        }

        if ($dados['di_endereco'] == '') {
            $mensagem_erro[] = 'Informe um Endereço';
        }

        if ($dados['di_complemento'] == '') {
            $mensagem_erro[] = 'Informe um complemento';
        }

        if ($dados['di_numero'] == '') {
            $mensagem_erro[] = 'Informe um número';
        }

        if ($dados['di_uf'] == '') {
            $mensagem_erro[] = 'Informe um Estado';
        }

        if ($dados['di_cidade'] == '') {
            $mensagem_erro[] = 'Informe uma Cidade';
        }

        if ($dados['di_fone2'] == '' || strlen($dados['di_fone1']) < 8) {
            $mensagem_erro[] = 'Informe um número de Celular Válido';
        }

        if ($dados['di_fone1'] == '' || strlen($dados['di_fone1']) < 8) {
            $mensagem_erro[] = 'Informe um número de Telefone Válido';
        }

        if ($dados['di_email'] == '') {
            $mensagem_erro[] = 'Informe um e-mail válido';
        }

        return $mensagem_erro;
    }

    private function e_data_valida($data) {
        if (strlen($data) != 10) {
            return false;
        }


        $data_array = @explode('/', $data);

        if (!isset($data_array[0]) || strlen($data_array[0]) != 2) {
            return false;
        }

        if (!isset($data_array[1]) || strlen($data_array[1]) != 2) {
            return false;
        }

        if (!isset($data_array[2]) || strlen($data_array[2]) != 4 || ($data_array[2] < (date('Y') - 120)) || ($data_array[2] > (date('Y') - 17))
        ) {
            return false;
        }

        return checkdate($data_array[1], $data_array[0], $data_array[2]);
    }

//End função validação de dados


    public function primeiro_login() {
        echo "<h2>Você ainda não está ativo, ative-se para ter acesso as informações do seu escritório.</h1>";
    }

    public function imprimir_ficha_cadastral() {
        $this->load->view('distribuidor/imprimir_ficha_cadastral');
    }

    public function desempenho() {
        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function meu_titulo() {
        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    function pendentes() {



        $dis = $this->db->query("
         SELECT di_id,di_nome,di_usuario,di_email,di_fone1,di_lado_preferencial,di_preferencia_indicador 
	 FROM distribuidores
         LEFT JOIN distribuidor_ligacao ON li_id_distribuidor = di_id
	 WHERE `di_ni_patrocinador` = " . get_user()->di_id . "
         AND li_id_distribuidor IS NULL
	 GROUP BY di_id
	")->result();


        $data['dados'] = $dis;

        $data['pagina'] = 'distribuidor/pendentes';
        $this->load->view('home/index_view', $data);
    }

    public function perna_inserir_pendentes() {
        autenticar();
        self::validar_senha_seguranca($_POST['senha_segurancao'], $_POST['url']);

        $this->db->where('di_id', $_POST['di_id'])->update('distribuidores', array(
            'di_preferencia_indicador' => $_POST['perna']
        ));
        redirect(base_url('index.php/distribuidor/pendentes'));
    }

    public function mudar_endereco() {

        if ($_POST) {
            $this->db->where('di_id', get_user()->di_id)->update('distribuidores', $_POST);
            redirect(base_url("index.php"));
        }

        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function pendencias() {
        $pendencia = 0;
        if (get_user()->di_inss_pis == '') {
            echo "<p>-PIS</p>";
            $pendencia++;
        }

        if (get_user()->di_conta_banco == '') {
            echo "<p>-Conta Bancária</p>";
            $pendencia++;
        }
        if (get_user()->di_conta_numero == '') {
            echo "<p>-Número da conta</p>";
            $pendencia++;
        }
        if (get_user()->di_conta_agencia == '') {
            echo "<p>-Agência</p>";
            $pendencia++;
        }
        if (get_user()->di_conta_nome == '') {
            echo "<p>-Nome titular conta</p>";
            $pendencia++;
        }
        if (get_user()->di_conta_cpf == '') {
            echo "<p>-CPF titular conta</p>";
            $pendencia++;
        }
        if (get_user()->di_beneficiario1 == '') {
            echo "<p>-Beneficiarios</p>";
            $pendencia++;
        }
        if (get_user()->di_contrato == 0) {
            echo "<p>-Contrato assinado</p>";
            $pendencia++;
        }
        if ($pendencia) {
            echo "<a href='" . base_url() . "/index.php/distribuidor/mudar_endereco'>Informar pendências</a>";
            echo "<style>p{color:#f00}</style>";
        }
    }

    public function buscar_distribuidor_ajax() {

        $esta_ativo_now = false;


        $valor = $_POST['chave'] + 0;
        ($_POST['chave'] === '' . $valor) ? $this->db->where('di_id', $_POST['chave']) : $this->db->like('di_nome', $_POST['chave']);

        $d = $this->db->get('distribuidores')->result();
        echo json_encode($d);
    }

    public function verifica_ativo() {

        $esta_ativo_now = false;

        $ativacao_mensal = $this->db
                        ->where("cp_distribuidor", get_user()->di_id)
                        ->like("cp_data", date('Y-m-'))
                        ->get("credito_repasse")->result();



        if (count($ativacao_mensal) > 0) {
            $esta_ativo_now = true;
        }


        if ($esta_ativo_now == false) {



            $primeira_compra_esse_mes = $this->db
                            ->select('di_id')
                            ->join('produtos_comprados', 'pm_id_compra=co_id')
                            ->join('produtos', 'pm_id_produto=pr_id')
                            ->join('distribuidores', 'di_id=co_id_distribuidor')
                            ->where('pr_kit_tipo', 1)
                            ->where('co_pago', 1)
                            ->like('co_data_compra', date('Y-m-'))
                            ->where('di_id', get_user()->di_id)
                            ->group_by('di_id')
                            ->get('compras')->result();


            #se houver algum distribuidor que fez primeira compra nesse mês
            if (count($primeira_compra_esse_mes) > 0) {
                $esta_ativo_now = true;
            }//fim primeira ativação
        }

        if ($esta_ativo_now) {
            $this->db->where('di_id', get_user()->di_id)->update('distribuidores', array('di_ativo' => 1));
            echo json_encode(array('ativo' => 1));
        } else {
            $this->db->where('di_id', get_user()->di_id)->update('distribuidores', array('di_ativo' => 0));
            echo json_encode(array('ativo' => 0));
        }

        if (!isset($_GET['no_redirect'])) {
            if (isset($_GET['url']) && $_GET['url'] != '') {
                redirect($_GET['url']);
            } else {
                redirect(base_url());
            }
        }
    }

    public function sair() {
        session_destroy();
        sair_user();
    }

    public function buscar_na_rede() {


        $dis_key = $this->db
                        ->select('di_id')
                        ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                        ->where('li_no', get_user()->di_id)
                        ->where('di_usuario', get_parameter('chave_user'))
                        ->get('distribuidores')->row();

        if (count($dis_key) > 0) {
            redirect(base_url('index.php/distribuidor/minha_rede') . '?info_user=' . base64_encode($dis_key->di_id));
            exit;
        }

        $dis_key = $this->db->select('di_id')
                        ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                        ->where('li_no', get_user()->di_id)
                        ->like('di_nome', get_parameter('chave_user'))
                        ->get('distribuidores')->row();


        if ($dis_key) {
            redirect(base_url('index.php/distribuidor/minha_rede') . '?info_user=' . base64_encode($dis_key->di_id));
            exit;
        }

        set_notificacao(array(0 => array('tipo' => 2, 'mensagem' => "Usuário <strong>" . get_parameter('chave_user') . "</strong> não encontrado")));
        redirect(base_url('index.php/distribuidor/minha_rede') . '?info_user=' . base64_encode(get_user()->di_id));
    }

    public function minha_rede() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function distribuidor_info_ajax() {
        $now = time();
        $di = $this->db
                        ->select('di_id,di_nome,di_usuario,ci_nome,ci_uf,di_fone1,di_email,di_data_cad,dq_descricao,di_ativo')
                        ->join('cidades', 'ci_id = di_cidade')
                        ->join('distribuidor_qualificacao', 'dq_id=di_qualificacao')
                        ->where('di_id', $_POST['ni'])->get('distribuidores')->result();

        $compra = $this->db->where('co_pago', 1)->where('co_id_distribuidor', $di[0]->di_id)->where('co_eplano', 1)->order_by('co_data_compra', 'ASC')->get('compras', 1)->row();
        $di[0]->di_data_cad = date('d/m/Y H:i:s', strtotime($compra->co_data_compra));

        $ativo = $this->db
                        ->where('at_distribuidor', $di[0]->di_id)
                        ->where('at_data >', date('Y-m-01'))
                        ->get('registro_ativacao')->row();

        if (count($ativo) > 0) {
            $di[0]->di_ativo = 1;
        } else {
            $di[0]->di_ativo = 0;
        }

        echo json_encode($di[0]);

        $and_now = time() - $now;
        if ($and_now == 0) {
            usleep(200000);
        }
    }

    public function send_mensagem_ajax() {
        $msg = $_POST['mensagem'];
        $ni = $_POST['ni'];

        $dis_enviar = $this->db->where('di_id', $ni)->get('distribuidores')->result();

        $assunto = 'Mensagem de ' . get_user()->di_nome . ' - Nossa Empresa';
        $body = "
		 <p>Você recebeu uma mensagem de " . get_user()->di_nome . "</p>
		 <br>
		 ------------------------------------------------------------<br>
		 {$msg}<br>
		 ------------------------------------------------------------<br>
		 ";

        enviar_email(get_user()->di_email, $dis_enviar[0]->di_email, $assunto, $body);
    }

    public function enviar_token_produto() {
        error_reporting();
        autenticar();
        $this->lang->load('distribuidor/saque/solicitar_saques');
        try {

            $senha = $this->db
                            ->where('di_pw', sha1($this->input->post('senha')))
                            ->where('di_id', get_user()->di_id)
                            ->get('distribuidores')->num_rows;
            if (!$senha) {
                throw new Exception($this->lang->line('senha_nao_encontrada'));
            }

            $dados = ComprasModel::validarTokenProdutoComprado($this->input->post('token'));

            if (count($dados) == 0) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            $atm = new atm();
            $response = funcoesdb::arrayToObject($atm->solicitar_saque($dados, ($dados->pr_reebolso * 0.85)));

            switch ($response->status) {
                case 2:
                    throw new Exception($this->lang->line('error_usuario_nao_existe'));
                    break;
                case 3:
                    throw new Exception($this->lang->line('error_saldo_insuficientes'));
                    break;
                case 4:
                    throw new Exception($this->lang->line('error_invalido_api_key'));
                    break;
                case 5:
                    throw new Exception($this->lang->line('error_invalido_secret_key'));
                    break;
                case 100:
                    throw new Exception($this->lang->line('error_nao_conher_origem'));
                    break;
            }

            $response = funcoesdb::arrayToObject($atm->solicitar_saque($dados, ($dados->pr_reebolso * 0.15), 1));
            switch ($response->status) {
                case 2:
                    throw new Exception($this->lang->line('error_usuario_nao_existe'));
                    break;
                case 3:
                    throw new Exception($this->lang->line('error_saldo_insuficientes'));
                    break;
                case 4:
                    throw new Exception($this->lang->line('error_invalido_api_key'));
                    break;
                case 5:
                    throw new Exception($this->lang->line('error_invalido_secret_key'));
                    break;
                case 100:
                    throw new Exception($this->lang->line('error_nao_conher_origem'));
                    break;
            }

            //Alterando o status da token.
            ComprasModel::utilizarTokenProdutoComprado($this->input->post('token'));
            set_notificacao(1, "sucesso");
            redirect(base_url());
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url());
        }
    }

    public function confirmar_envio() {
        $this->lang->load('distribuidor/confirmar_envio/confirmar_envio');
        try {
            if (!$this->input->post('token_camp1')) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            if (!$this->input->post('token_camp2')) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            if (!$this->input->post('token_camp3')) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            if (!$this->input->post('token_camp4')) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            if (!$this->input->post('token_camp5')) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            $token = '';
            $token.= $this->input->post('token_camp1');
            $token.='-' . $this->input->post('token_camp2');
            $token.='-' . $this->input->post('token_camp3');
            $token.='-' . $this->input->post('token_camp4');
            $token.='.' . $this->input->post('token_camp5');

            $dados = ComprasModel::validarTokenProdutoComprado($token);

            if (count($dados) == 0) {
                throw new Exception($this->lang->line('error_token_invalida'));
            }

            $data['token'] = $dados;
            $data['pagina'] = "token_produto_envio/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
            $this->load->view('home/index_view', $data);
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url());
        }
    }

    /**
     * Ativar o distibuidor, faz parte do processo de ativação mensal
     * @throws Exception
     */
    public function reativar_distribuidor() {
        $this->lang->load('distribuidor/distribuidor/reativar_distribuidor');
        try {
            $AtivacaoMensal = new AtivacaoMensal();
            $AtivacaoMensal->setDistribuidor(get_user());

            if ($AtivacaoMensal->checarAtivacao()) {
                throw new Exception($this->lang->line('error_distribuidor_ativo'));
            }

            $compra_ativacao = ComprasModel::addCompraAtivacao(get_user()->di_id);

            if (count($compra_ativacao)==0) {
                throw new Exception($this->lang->line('error_inesperando_a_gerar_compra_ativacao'));
            }
            
            redirect(base_url('index.php/pedidos/confirmar_pagamento?id_pedido=' . $compra_ativacao->co_id));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url());
        }
    }

}
