<?php

class Distribuidor extends CI_Controller {

    private $nos_ids;
    private $ids_rede = array();

    public function index() {
        redirect(base_url('escolher_patrocinador'));
    }

    public function cadastro_lang($lang = APP_LINGUAGEM) {
        $_SESSION["lang"] = $lang;
        $this->config->set_item('language', $lang);

        manutencao();

        if (!isset($_SESSION['captcha_word']) || $_SESSION['captcha_word'] == NULL) {
            redirect(base_url('index.php/entrar/gerar_cap'));
        }
        if (isset($_SESSION['pagina_atual']) && !empty($_SESSION['pagina_atual'])) {
            $data['pagina'] = base64_decode($_SESSION['pagina_atual']);
            redirect($data['pagina']);
        } else {
            $data['pagina'] = "distribuidor/distribuidor_cadastro";
            $this->load->view(strtolower(__CLASS__) . "/layout_view", $data);
        }
    }

    function buscar_cep() {

        $_REQUEST['cep'] = str_ireplace('-', '', $_REQUEST['cep']);
        $d = file_get_contents("http://cep.correiocontrol.com.br/{$_REQUEST['cep']}.json");
        $json = json_decode($d);

        if ($d && isset($json->bairro)) {
            echo $d;
        } else {
            echo json_encode(array());
        }
    }

    public function get_pais_ajax() {

        $ps_nome = $_REQUEST['ps_nome'];
        $pais = $this->db->where('ps_iso3', $ps_nome)->get('pais')->row();
        if (count($pais) > 0) {
            echo json_encode(array('status' => 1, 'data' => $pais->ps_id, 'ps_id' => $pais->ps_id));
        } else {
            echo json_encode(array('status' => 1, 'data' => '', 'ps_id' => ''));
        }
    }

    /**
     * Recarrega o plano para cotação da moeda
     * para cada país.
     */
    public function get_plano_cambio_ajax() {
        $data['id_pais'] = isset($_REQUEST['id_pais']) ? $_REQUEST['id_pais'] : false;
        $this->load->view('/distribuidor/distribuidor_cadastro_planos_view', $data);
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

            if (isset($distribuidor['birthday'])) {
                $distribuidor['birthday'] = date('d/m/Y', strtotime($distribuidor['birthday']));
            }
        }

        echo json_encode($distribuidor);
    }

    public function get_cartao_plataform() {
        $cartao = array("cartao" => 0);
        if (isset($_REQUEST['niv']) && !empty($_REQUEST['niv'])) {
            $niv = $_REQUEST['niv'];

            $total = $this->db
                            ->select("count(*) as total")
                            ->join("distribuidores", "di_id = co_id_distribuidor AND di_niv={$niv}")
                            ->where("co_tipo", 100)
                            ->get("compras")->row()->total;
            $cartao = array("cartao" => $total);
        }
        echo json_encode($cartao);
    }

    function cidade_by_name() {
        $cidades = array();
        $cis = $this->db->like('ci_nome', $_REQUEST['term'])
                        ->select(array('ci_nome', 'ci_id'))
                        ->get('cidades', 10)->result();
        foreach ($cis as $c) {
            $cidades[] = $c->ci_nome;
        }
        echo json_encode($cidades);
    }

    function cidades() {
        $c = $this->db->where('ci_estado', $_POST['es_id'])->get('cidades')->result();
        echo json_encode($c);
    }

    function estados() {
        $c = $this->db->where('es_pais', $_POST['es_pais'])->get('estados')->result();
        echo json_encode($c);
    }

    function usuario_disponivel() {
        $d = $this->db->select('di_nome')->where('di_usuario', url_title($this->uri->segment(3)))->get('distribuidores')->row();
        echo json_encode(array(
            'usuarios' => $d
        ));
    }

    function patrocinador_valido($patrocinador) {

        $d = $this->db->select(array(
                    'di_nome',
                    'di_id'
                ))->where('di_usuario', $patrocinador)->get('distribuidores')->row();


        if (count($d) == 0) {
            return false;
        } else {

            $seisMesesAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - 6, date('d'), date('Y')));

            $comprou = $this->db
//                              ->where('at_data >', $seisMesesAtras)
                            ->where('at_distribuidor', $d->di_id)
                            ->get('registro_ativacao', 1)->row();

            if (count($comprou) == 0) {
                return false;
            }
        }

        return true;
    }

    function patrocinador_existe() {
        $d = $this->db
                        ->select("di_nome,di_id,di_usuario,di_data_cad")
                        ->where('di_login_status', 1)
                        ->where('di_usuario', url_title($this->uri->segment(3)))
                        ->get('distribuidores')->row();

        if (count($d) > 0) {

            $seisMesesAtras = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - 6, date('d'), date('Y')));

//            if ($d->di_data_cad <= "2014-03-31 00:00:00") {
//                echo json_encode(array(
//                    'usuarios' => 'inativado'
//                ));
//                return false;
//            }

            $plano_distribuidor = $this->db
                            ->where("ps_distribuidor", $d->di_id)
                            ->order_by("ps_plano", "DESC")
                            ->get("registro_planos_distribuidor")->row();

            //Plano membership
//            if($plano_distribuidor->ps_plano == 99){
//
//                echo json_encode(array(
//                    'usuarios' => 'inativado'
//                ));
//                return false;
//            }


            $d->plano = (is_object($plano_distribuidor)) ? $plano_distribuidor->ps_plano : 0;

            $comprou = $this->db
//                            ->where('at_data >', $seisMesesAtras)
                            ->where('at_distribuidor', $d->di_id)
                            ->get('registro_ativacao', 1)->row();

            $d->di_nome = mask_name($d->di_usuario, $d->di_nome);

            if (count($comprou) == 0) {
                $d = 'inativo';
            }
        } else {
            $d = false;
        }

        echo json_encode(array(
            'usuarios' => $d,
        ));
    }

    function cadastro() {
        manutencao();
        $data['pagina'] = get_view(__CLASS__, __METHOD__);
        $this->load->view("distribuidor/layout_view", $data);
    }

    function cadastro_Br() {
        manutencao();
        $data['pagina'] = get_view(__CLASS__, __METHOD__);
        $this->load->view("distribuidor/layout_view", $data);
    }

    function cadastro_Us() {
        manutencao();
        $data['pagina'] = get_view(__CLASS__, __METHOD__);
        $this->load->view("distribuidor/layout_view", $data);
    }

    function caminho_distribuidor($patrocinador) {
        $dis = $this->db->select(array(
                    'di_id'
                ))->where('di_direita', $patrocinador)->or_where('di_esquerda', $patrocinador)->get('distribuidores')->row();

        if (count($dis) > 0) {
            $this->nos_ids[] = $dis->di_id;
            $this->caminho_distribuidor($dis->di_id);
        }
    }

    private function usuario_existe($user) {
        $existe = $this->db->where('di_usuario', $user)->get('distribuidores')->row();
        if (count($existe) == 0) {
            return false;
        } else {
            return true;
        }
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

        if (!isset($data_array[2]) || strlen($data_array[2]) != 4 || ($data_array[2] < (date('Y') - 120)) || ($data_array[2] > (date('Y') - 17))) {
            return false;
        }

        return checkdate($data_array[1], $data_array[0], $data_array[2]);
    }

    private function cpf_patrocinador_proibido($cpf, $patrocinador) {

        $registrosComOCPF = $this->db->select(array(
                    'di_id',
                    'di_nome'
                ))->where('di_cpf', $cpf)->get('distribuidores')->num_rows;

        if ($registrosComOCPF > 0) {

            $patrocidador = $this->db->select(array(
                        'di_id',
                        'di_nome',
                        'di_cpf'
                    ))->where('di_usuario', $patrocinador)->get('distribuidores')->row();

            if (count($patrocidador) == 0) {
                return true;
            }

            if ($patrocidador->di_cpf != $cpf) {
                return true;
            }
        }

        return false;
    }

    private function verifica_se_cpf_cadastrado($cpf) {
        $dis = $this->db->where('di_cpf', $cpf)->get('distribuidores')->row();

        if (count($dis) > 0) {
            return true;
        } else {
            return false;
        }
    }

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
        else if ($cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
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
        $this->lang->load('publico/distribuidor/valida_tela');

        $mensagem_erro = array();
        #-- Validação do nome

        if ($dados['di_nome'] == '' || strlen($dados['di_nome']) < 3) {
            $mensagem_erro[] = $this->lang->line('erro_nome');
        }
        if (!isset($dados['li']) && $dados['li'] == '') {
            $mensagem_erro[] = $this->lang->line('erro_termo');
        }

//        if ($dados['di_rg'] == '' || strlen($dados['di_rg']) < 3) {
//            $mensagem_erro[] = $this->lang->line('erro_numero_documento');
//        }

        if ($dados['di_pais'] == 1) {
            if ($dados['di_rg'] == '' || strlen($dados['di_rg']) != 11 || !self::validaCPF($dados['di_rg'])) {
                $mensagem_erro[] = 'Em número documento, informe uma CPF Válido';
            }
        }


        if ($dados['di_uf']) {
            if (empty($dados['di_uf'])) {
                $mensagem_erro[] = "Selecione um estado";
            }
        } else {

            if (empty($dados['di_uf2'])) {
                $mensagem_erro[] = "Selecione um estado";
            }
        }



        if ($dados['di_data_nascimento'] == '' || self::e_data_valida($dados['di_data_nascimento']) == false) {
            $mensagem_erro[] = $this->lang->line('erro_data_nasc');
        }

        if ($dados['di_sexo'] != 'M' && $dados['di_sexo'] != 'F') {
            $mensagem_erro[] = $this->lang->line('erro_sexo');
        }

        if ($dados['di_usuario_patrocinador'] == '' || !self::patrocinador_valido($dados['di_usuario_patrocinador'])) {
            $mensagem_erro[] = $this->lang->line('erro_patrocinador');
        }

        if ($dados['di_bairro'] == '') {
            $mensagem_erro[] = $this->lang->line('erro_bairro');
        }

        if ($dados['di_endereco'] == '') {
            $mensagem_erro[] = $this->lang->line('erro_endereco');
        }

        if ($dados['di_uf']) {
            if ($dados['di_uf'] == '') {
                $mensagem_erro[] = $this->lang->line('erro_estado');
            }
        } else {

            if ($dados['di_uf2'] == '') {
                $mensagem_erro[] = $this->lang->line('erro_estado');
            }
        }

        if ($dados['di_cidade'] == '') {
            $mensagem_erro[] = $this->lang->line('erro_cidade');
        }

        if ($dados['di_fone2'] == '' || strlen($dados['di_fone1']) < 8) {
            $mensagem_erro[] = $this->lang->line('erro_numero_celular');
        }

        if ($dados['di_fone1'] == '' || strlen($dados['di_fone1']) < 8) {
            $mensagem_erro[] = $this->lang->line('erro_numero_telefone');
        }

        if ($dados['di_email'] == '') {
            $mensagem_erro[] = $this->lang->line('erro_email');
        }

        if (!preg_match('/^[A-Za-z0-9]*$/', $dados['di_usuario'])) {
            $mensagem_erro[] = $this->lang->line('erro_usuario_formato');
        }

        if ($dados['di_usuario'] == '' || strlen($dados['di_usuario']) <= 3 || self::usuario_existe($dados['di_usuario'])) {
            $mensagem_erro[] = $this->lang->line('erro_usuario');
        }

        if (strlen($dados['senha']) < 8) {
            $mensagem_erro[] = $this->lang->line('erro_senha');
        }

        if (strlen($dados['senha_finaceira']) < 8) {
            $mensagem_erro[] = $this->lang->line('erro_senha');
        }

        if ($dados['senha'] == $dados['senha_finaceira']) {
            $mensagem_erro[] = $this->lang->line('erro_senhas_iguais');
        }

        if (!isset($dados['plano']) && empty($dados['plano'])) {
            $mensagem_erro[] = $this->lang->line('erro_selecione_um_plano');
        }

        if (!isset($dados['di_cartao_membership']) && empty($dados['di_cartao_membership'])) {
            $mensagem_erro[] = $this->lang->line('erro_selecione_cartao');
        }

        /*
         * Regra do cartão de acordo com o país de origem do
         * distribuidor.
         */
        if (isset($dados['di_cartao_membership']) && !empty($dados['di_cartao_membership'])) {
            if ($dados['di_cartao_membership'] == 1 && $dados['di_pais'] != 1) {
                $mensagem_erro[] = $this->lang->line('erro_cartao_atm_visa');
            }

        //if ($dados['di_cartao_membership'] == 2 && $dados['di_pais'] != 1) {
        //    $mensagem_erro[] = $this->lang->line('erro_cartao_union_pay');
        //}

            if ($dados['di_cartao_membership'] == 3) {
                //se BRASIL, ou EUA, ou INDIA ou COREIA DO SUL ou FILIPINAS
                if ($dados['di_pais'] == 1 or $dados['di_pais'] == 2 or $dados['di_pais'] == 100 or $dados['di_pais'] == 114 or $dados['di_pais'] == 169) {
                    $mensagem_erro[] = $this->lang->line('erro_cartao_atm_master');
                }
            }
        }

        //Verifica o maximo de distribuidor cadastrado por niv
        $total_ditribuidor_por_niv = $this->db->where('di_email', $dados['di_email'])
                ->where('di_excluido !=1')
                ->select(array('*'))
                ->get('distribuidores')
                ->row();

//            if (count($total_ditribuidor_por_niv) >= 1) {
//                 $mensagem_erro[] = $this->lang->line('erro_cadastro_por_niv_ultrapassado');
//               }

        /**
         * Nova regra validar usuário.
         * Não permitir cadastro repedido
         * de usuário mesmo país, mesmo nome e sobre nome, e número documento.
         */
        $usuario_existe = $this->db->where('di_nome', $dados['di_nome'])
                        ->where('di_ultimo_nome', $dados['di_ultimo_nome'])
                        ->where('di_rg', $dados['di_rg'])
                        ->where('di_pais_nascimento', $dados['di_pais_nascimento'])
//                                       ->where('ci_pais',$_POST['di_pais'])
                        ->join('cidades', 'di_cidade = ci_id')
                        ->join('pais', 'ps_id=ci_pais')
                        ->get('distribuidores')->row();

//            if(count($usuario_existe)>0){
//                 $mensagem_erro[] = $this->lang->line('erro_usuario_ja_cadastrado').
//                                    ' '.$usuario_existe->ps_nome;
//            }

        return $mensagem_erro;
    }

    /**
     * Verifica se o usuário ja ta cadastrado no
     * sistema, verificando com varios dados.
     */
    public function verificar_usuario_cadastrado_ajax() {
        $this->lang->load('publico/distribuidor/valida_tela');

        /**
         * Nova regra validar usuário.
         * Não permitir cadastro repedido
         * de usuário mesmo país, mesmo nome e sobre nome, e número documento.
         */
        $dados = array();
        $dados = $_POST;

        $usuario_existe = $this->db->where('di_nome', $dados['di_nome'])
                        ->where('di_ultimo_nome', $dados['di_ultimo_nome'])
                        ->where('di_rg', $dados['di_rg'])
                        ->where('di_pais_nascimento', $dados['di_pais_nascimento'])
//                                       ->where('ci_pais',$_POST['di_pais'])
                        ->join('cidades', 'di_cidade = ci_id')
                        ->join('pais', 'ps_id=ci_pais')
                        ->get('distribuidores')->row();


        if (count($usuario_existe) > 0) {
            echo json_encode(array(
                'response' => 'error',
                'information' => $this->lang->line('erro_usuario_ja_cadastrado') .
                ' ' . $usuario_existe->ps_nome
            ));
        } else {
            echo json_encode(array(
                'response' => 'ok',
                'information' => $this->lang->line('novo_usuario_liberado')
            ));
        }
    }

    /**
     * Confirmação de cadastro.
     */
    public function confirmar_cadastro() {

        if (isset($_REQUEST['token']) && !empty($_REQUEST['token'])) {
            //Verificando o código de acesso ser é valido.
            $token = $this->db->where('tk_token', $_REQUEST['token'])
                            ->get('token_confirmacao_cadastro')->row();

            if (count($token) > 0) {
                //Pega as informaçães do distribuidor.
                $distribuidor = $this->db->where('di_id', $token->tk_distribuidor)
                                ->get('distribuidores')->row();

                //se retornou distribuidor
                if (is_object($distribuidor)) {

                    $token_verificacao = $this->db->where('tk_confirmado', 0)
                                    ->where('tk_token', $_REQUEST['token'])
                                    ->get('token_confirmacao_cadastro')->row();

                    if (count($token_verificacao) > 0) {

                        $this->db->where('tk_distribuidor', $distribuidor->di_id)
                                ->update('token_confirmacao_cadastro', array(
                                    'tk_confirmado' => '1'
                        ));

                        //Enviando a solicitação do cartão atm
                        $atm = new atm();
                        //Verificando se o cadastro do usuário ta completo na atm
                        $cadastro = $atm->consulta_cadastro_ewallet($distribuidor->di_email);
                        //Verificando se os dados ta completo.
                        $atm->atualiar_cadastro_ewallet($distribuidor);


                        //Solicitar cartão.
                        /**
                         * se escolheru Mastercard EDIZ confirma nesse momento
                         * se não, essa solicitação será feita no momento do
                         * confirmação do pagamento.
                         */
                        $compra = $this->db->where('co_id', $token->tk_compra)
                                ->get('compras')
                                ->row();

                        if ($compra->co_id_cartao == 3) {
                            $cartaoAtm = $atm->solicitar_cartao($distribuidor);
                        }
                        $data['sucess'] = '1';
                    } else {
                        $data['sucess'] = '2';
                    }

                    $data['distribuidor'] = $distribuidor;
                    $data['pagina'] = get_view(__CLASS__, __METHOD__);
                    $this->load->view("distribuidor/layout_view", $data);
                } else {
                    $data['distribuidor'] = '0';
                    $data['sucess'] = '0';
                    $data['pagina'] = get_view(__CLASS__, __METHOD__);
                    $this->load->view("distribuidor/layout_view", $data);
                }
            }
        } else {
            $data['distribuidor'] = '0';
            $data['sucess'] = '0';
            $data['pagina'] = get_view(__CLASS__, __METHOD__);
            $this->load->view("distribuidor/layout_view", $data);
        }
    }

    public function salvar_distribuidor() {

        manutencao();
        if (isset($_POST['di_uf'])) {
            $uf = $_POST['di_uf'];
        } else {
            $uf = $_POST['di_uf2'];
        }


        $this->lang->load('publico/distribuidor/valida_tela');



        //Inicia uma transação
        $this->db->trans_begin();

        $_POST['di_usuario_patrocinador'] = str_ireplace(' ', '', $_POST['di_usuario_patrocinador']);

        //Salvando cidades
        $cidade = $this->db->where('ci_uf', $uf)->like('ci_nome', $_POST['di_cidade'])->get('cidades')->row();
        $estado = $this->db->where('es_id', $uf)->get('estados')->row();

        $tela = self::valida_tela($_POST);

        if (count($cidade) == 0) {
            if (count($estado) > 0) {
                $this->db->insert('cidades', array(
                    'ci_nome' => $_POST['di_cidade'],
                    'ci_estado' => $estado->es_id,
                    'ci_uf' => $estado->es_uf,
                    'ci_pais' => $estado->es_pais
                ));
                $_POST['di_cidade'] = $this->db->insert_id();
            }
        } else {
            $_POST['di_cidade'] = $cidade->ci_id;
        }

        //Salvando cidades
        $endcidade = $this->db->where('ci_pais', $_POST['end_pais'])->like('ci_nome', $_POST['end_cidade'])->get('cidades')->row();
        $endestado = $this->db->where('es_pais', $_POST['end_pais'])->get('estados')->row();

        if (count($cidade) == 0) {
            if (count($estado) > 0) {
                $this->db->insert('cidades', array(
                    'ci_nome' => $_POST['end_cidade'],
                    'ci_estado' => $estado->es_id,
                    'ci_uf' => $estado->es_uf,
                    'ci_pais' => $estado->es_pais
                ));
                $_POST['end_cidade'] = $this->db->insert_id();
            }
        } else {
            $_POST['end_cidade'] = $cidade->ci_id;
        }

        if (count($tela) > 0) {
            $_SESSION['form_cad'] = $_POST;
            $_SESSION['form_cad_error'] = $tela;
            redirect(base_url('index.php/distribuidor/cadastro/'));
            exit;
        }

        //Verifica se não há nenhum erro dos dados inseridos no formulário
        if (count($tela) > 0) {
            $_SESSION['form_cad'] = $_POST;
            $_SESSION['form_cad_error'] = $tela;
            redirect(base_url('index.php/distribuidor/cadastro/'));
            exit;
        } else {
            if (isset($_SESSION['form_cad'])) {
                unset($_SESSION['form_cad']);
            }
            if (isset($_SESSION['form_cad_error'])) {
                unset($_SESSION['form_cad_error']);
            }
        }

        //pegando o id do plano e pais
        $id_pais = $_POST['di_pais'];
        $id_plano = $_POST['plano'];

        $codigoPromocional = false;
        //Verificar se fui passando o codigo Promocional
        if ($this->input->post('codigo_promocional') != "") {
            $codigoPromocional = true;
            $id_plano = 99;
        }


        unset($_POST['plano']);
        unset($_POST['di_pais']);

        $senha = $_POST['senha'];

        $_POST['di_senha'] = sha1($_POST['senha']);
        $_POST['di_pw'] = sha1($_POST['senha_finaceira']);

        $_POST['di_data_nascimento'] = data_to_usa($_POST['di_data_nascimento']);
        $_POST['di_ni_patrocinador'] = 0;
        $_POST['di_email'] = strtolower($_POST['di_email']);
        $_POST['di_email_atm'] = strtolower($_POST['di_email']);
        $_POST['di_usuario'] = strtolower($_POST['di_usuario']);
        $_POST['di_ip_cadastro'] = $this->input->ip_address();

        //é o patrocinador do usuário que está cadastrando
        $pat = $this->db->join('cidades', 'ci_id = di_cidade')
                        ->where('di_usuario', $_POST['di_usuario_patrocinador'])
                        ->get('distribuidores')->row();

        if (count($pat) == 0) {
            redirect(base_url('index.php/distribuidor/cadastro/'));
        }

        unset($_POST['senha']);
        unset($_POST['li']);
        unset($_POST['senha_finaceira']);
        unset($_POST['recebimento_plano']);


        foreach ($_POST as $indice => $valor) {
            $_POST[$indice] = $this->input->post($indice, true);
        }

        //Pegando dados do endereço do cadastro caso seja Norte Americano

        if ($this->db->insert('distribuidores', valida_fields('distribuidores', $_POST))) {
            $idDistribuidor = $this->db->insert_id();

            $_POST['dpj_id_distribuidor'] = $idDistribuidor;
            if (isset($_POST['tipopessoa']) && $_POST['tipopessoa'] == 1) {
                //Inserido pessoa juridica.
                $this->db->insert('distribuidor_pessoa_juridica', valida_fields('distribuidor_pessoa_juridica', $_POST));
            }


            //Se for estado unidos salva dados do local.
            if ($id_pais == 225) {

                $endereco = array(
                    'end_endereco' => $_POST['end_endereco'],
                    'end_numero' => $_POST['end_numero'],
                    'end_complemento' => $_POST['end_complemento'],
                    'end_cep' => $_POST['end_cep'],
                    'end_bairro' => $_POST['end_bairro'],
                    'end_cidade' => $_POST['end_cidade'],
                    'end_uf' => $uf,
                    'end_pais' => $_POST['end_pais'],
                    'end_estado' => $_POST['end_estado'],
                );

                //Unidos os arrays;
                $endereco = array_merge($endereco, array('end_id_distribuidor' => $idDistribuidor));
                //inseindo o endereço de entrega.
                $this->db->insert('distribuidores_endereco', $endereco);
            }

            //Inserindo o endereço de entrega
            $_POST['end_id_distribuidor'] = $idDistribuidor;
            $this->db->insert('distribuidores_endereco', funcoesdb::valida_fields('distribuidores_endereco', $_POST));

            //Obter os dados do distribuidor recem cadastrado
            $distribuidor = $this->db->join('cidades', 'ci_id = di_cidade', 'left')
                            ->join('distribuidor_qualificacao', 'dq_id=di_qualificacao', 'left')
                            ->where('di_id', $idDistribuidor)
                            ->get('distribuidores')->row();

            //Atualizar os dados de patrocinador
            $this->db->where('di_id', $idDistribuidor)->update('distribuidores', array(
                'di_ni_patrocinador' => $pat->di_id,
                'di_usuario_patrocinador' => $pat->di_usuario
            ));
        }


        $codigo_tipo = $this->db->where("REPLACE(REPLACE(prk_token,'.',''),'-','')  = REPLACE(REPLACE('{$this->input->post('codigo_promocional')}','.',''),'-','')")
                ->get('produto_token_ativacao')
                ->row();

        if (!empty($codigo_tipo->prk_perna_derramamento) && $codigo_tipo->prk_perna_derramamento == 1) {
            if ($codigoPromocional) {

                //Colocando o lando do indicador
                $sql_ = "select (select IF(!isnull(di_ni_patrocinador),1,0) from distribuidores where di_esquerda = {$pat->di_id})  as esquerda,";
                $sql_.= "(select IF(!isnull(di_ni_patrocinador),1,0) from distribuidores where di_direita= {$pat->di_id})  as direita";

                $di_lado_patrocinador = $this->db->query($sql_)->row();

                if ($di_lado_patrocinador->direita == 1) {
                    $this->db->where('di_id', $distribuidor->di_id)
                            ->update('distribuidores', array('di_preferencia_indicador' => 2));
                }

                if ($di_lado_patrocinador->esquerda == 1) {
                    $this->db->where('di_id', $distribuidor->di_id)
                            ->update('distribuidores', array('di_preferencia_indicador' => 1));
                }
            }
        }

        //Gerando a compra.
        $objeto = ComprasModel::addCompraAgencia($distribuidor, $id_plano, $this->input->post('di_cartao_membership'), $codigoPromocional);

        //Inutializar o a token.
        $this->db->where("REPLACE(REPLACE(prk_token,'.',''),'-','')  = REPLACE(REPLACE('{$this->input->post('codigo_promocional')}','.',''),'-','')")
                ->update('produto_token_ativacao', array('prk_situacao' => 1));


        if (count($objeto) == 0) {
            $_SESSION['form_cad_error'] = 'error Inesperado';
            redirect(base_url('index.php/distribuidor/cadastro/'));
        }

        //Se der algun
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            redirect(base_url() . "index.php/distribuidor/cadastro");
        } else {

            //CADASTRA O DISTRIBUIDOR DO empresa
            $mensagem_erro = array();
            $dis = $this->db->where('di_id', $idDistribuidor)->get('distribuidores')->row();
            $atm = new atm();
            $niv = $atm->cadastro_ewallet($dis, $senha);
            $niv = substr($niv, 0, 10);

            /**
             * Validando o callback
             */
            $erro = false;
            //TaxID já cadastado no sistema
            if ($niv == "0900000000") {
                $erro = true;
                $mensagem_erro[] = $this->lang->line('erro_cadastro_usuario_taxId_cadastro');
            }
            //Erro não indetificado
            if ($niv == "0800000000") {
                $erro = true;
                $mensagem_erro[] = $this->lang->line('erro_cadastro_no_indentificado');
            }
            //CPF Inválido
            if ($niv == "0700000000") {
                $erro = true;
                $mensagem_erro[] = $this->lang->line('erro_numero_cpf_invalido');
            }
            //CPF já cadastrado
            if ($niv == "0600000000") {
                $erro = true;
                $mensagem_erro[] = $this->lang->line('erro_cadastro_cpf_ja_cadastrado');
            }
            /**
             * Email cadastrado, Buscar a informação de acordo com email
             * fornecido.
             */
            if ($niv == "0300000000") {
                $dados_atm = $atm->consulta_cadastro_ewallet($_POST['di_email']);
                if ($dados_atm['status'] == 2) {
                    $niv = $dados_atm['niv'];
                }
            }

            //Campo obrigatório não preechido.
            if ($niv == "0200000000") {
                $erro = true;
                $mensagem_erro[] = $this->lang->line('erro_cadastro_campo_obriatorio_n_preechido');
            }

            $tx_niv = strlen($niv) >= 8 ? substr($niv, 4, strlen($niv)) : $niv;

            if (true) {
                $dis = $this->db->where('di_id', $idDistribuidor)
                        ->update('distribuidores', array(
                    'di_niv' => ( strlen($niv) >= 8 ? substr($niv, 4, strlen($niv)) : $niv),
                    'di_senha_e_wallet' => $senha
                ));

                //finaliza o cadastro
                $this->db->trans_commit();
                //Atualiza o cadastro do usuário na ATM.
                $atm->atualiar_cadastro_ewallet($distribuidor);

                //Pegando a tolken na E-WalltePay...
                $token = $atm->getToken($distribuidor->di_email);
                //Enviando token de pagamento para o usuário se o usuário comprou ruby.
                if ($id_plano == 103) {
                    notificacao_token($distribuidor, $token);
                }
                //Enviando o e-mail de cadastro
                notificacao_cadastro($distribuidor, $pat, $senha);
                //Enviando email para a confirmação de cadastro.
                enviar_confirmacao_cadastro($objeto->compra->co_id, $objeto->token);
                //Enviando email de confirmação de email
                enviar_codigo_confirmacao($objeto->compra->co_id);
                //Enviar notificação para o SAC
                notificacao_sac($distribuidor, $pat);

                $_SESSION['distribuidor_log'] = $distribuidor;
                redirect(base_url('index.php/distribuidor/cadastro_sucesso/' . $objeto->compra->co_id));
                //redirect(URL_DISTRIBUIDOR . "index.php/loja/pagamento?c={$objeto->compra->co_id}");
            } else {

                //Se der algum erro na Virtual Pag
                $this->db->trans_rollback();
                $_SESSION['form_cad_error'] = $mensagem_erro;
                redirect(base_url('index.php/distribuidor/cadastro/'));
            }
        }
    }

    public function cadastro_sucesso() {
        $data['compra'] = $compra = $this->db->where('co_id', $this->uri->segment(3))->get('compras')->row();
        $data['d'] = $this->db->join('cidades', 'ci_id = di_cidade')
                ->where('di_id', $compra->co_id_distribuidor)
                ->get('distribuidores')
                ->result();

        $data['pagina'] = get_view(__CLASS__, __METHOD__);
        $this->load->view("distribuidor/layout_view", $data);
    }

    public function escolher_patrocinador() {
        $data['pagina'] = get_view(__CLASS__, __METHOD__);
        $this->load->view("distribuidor/layout_view", $data);
    }

    public function numero_cpf($cpf) {

        //Verifica se o cpf não tem permissão para realizar inumeros cadastros
        $cpfs = $this->db->where('field', 'cpf_cadastros_ilimitados')->get('config')->row();

        if (count($cpfs) > 0 && $cpfs->valor != '') {

            $cpfs = @explode(',', $cpfs->valor);

            if (in_array($cpf, $cpfs)) {
                return true;
            }
        }

        //Retorna o número de distribuidores encontrados pelo cpf
        $disCpf = $this->db
                        ->select('di_id')
                        ->where('di_cpf', $cpf)
                        ->get('distribuidores')
                ->num_rows;

        $mensagem_erro = array();
        $cadastro_por = $this->db->where('field', 'numero_cadastro_por_cpf')->get('config')->row();
        if ($disCpf > ($cadastro_por->valor - 1)) {
            return false;
        } else {
            return true;
        }
    }

    public function cpf_cad_ajax() {
        $this->lang->load('publico/distribuidor/cpf_cad_ajax');

        $registrosComOCPF = $this->db->select(array(
                    'di_id',
                    'di_nome'
                ))->where('di_cpf', $_POST['cpf'])->get('distribuidores')->num_rows;

        if ($registrosComOCPF > 0) {

            $patrocidador = $this->db->select(array(
                        'di_id',
                        'di_nome',
                        'di_cpf'
                    ))->where('di_usuario', $_POST['patrocinador'])->get('distribuidores')->row();

            if (count($patrocidador) == 0) {
                echo json_encode(array(
                    "error" => $this->lang->line('erro1')
                ));
                exit;
            }

            if ($patrocidador->di_cpf != $_POST['cpf']) {
                echo json_encode(array(
                    "error" => $this->lang->line('erro2_1') . $_POST['cpf'] . $this->lang->line('erro2_2')
                ));
                exit;
            }
        }
    }

    public function verifica_distribuidor_ajax() {
        $_POST['ni'] = strtolower($_POST['ni']);
        $di = $this->db->select(array(
                    'di_id',
                    'di_nome'
                ))->where('di_id', $_POST['ni'])->get('distribuidores')->result();
        echo json_encode($di);
    }

    public function verificar_membership_ajax() {
        $_POST['email'] = strtolower($_POST['email']);
        $memberShip = $this->db->select(array('count(di_id) as total'))->where('di_email', $_POST['email'])
                        ->join('distribuidores', 'di_id=txm_id_distribuidor')
                        ->get('registro_member_ships')->row();
        echo json_encode(array('resposta' => $memberShip->total));
    }

    public function verificar_codigo_promocional_ajax() {
        try {

            if (!$this->input->post('codigo_promocional')) {
                throw new Exception('1');
            }

            $reposta = $this->db->where("REPLACE(REPLACE(prk_token,'.',''),'-','')  = REPLACE(REPLACE('{$this->input->post('codigo_promocional')}','.',''),'-','')")
                    ->where('prk_situacao', 0)
                    ->join('distribuidores', 'di_id=prk_distribuidor_patrocinador')
                    ->get('produto_token_ativacao')
                    ->row();

            if (count($reposta) == 0) {
                throw new Exception('error');
            }

            echo json_encode(array('resposta' => 'ok', 'usuario' => $reposta->di_usuario, 'plano' => $reposta->prk_plano));
        } catch (Exception $exc) {
            $excep = $exc->getMessage();
            if (!empty($excep)) {
                echo json_encode(array('resposta' => 'error'));
            }
        }
    }

    /*
      | -------------------------------------------------------------------------
      | FIM DO CONTROLLER
      | -------------------------------------------------------------------------
     */
}

?>
