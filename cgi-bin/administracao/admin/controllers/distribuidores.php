<?php

class Distribuidores extends CI_Controller {

    public function index() {
        permissao('arede', 'visualizar', get_user(), true);

        $paises_permitidos = $this->db->where('rfp_id_responsavel_fabrica', get_user()->rf_id)
                        ->select("GROUP_CONCAT(rfp_id_pais) as paises", false)
                        ->get('responsaveis_fabrica_paises')->row();
        $this->db->start_cache();

        if (isset($_REQUEST['usuario']) && !empty($_REQUEST['usuario'])) {
            $this->db->where('di_usuario', get_parameter('usuario'));
        }

        if (isset($_REQUEST['cpf']) && !empty($_REQUEST['cpf'])) {
            $this->db->where('di_cpf', get_parameter('cpf'));
        }

        if (isset($_REQUEST['nome']) && !empty($_REQUEST['nome'])) {
            $this->db->like('di_nome', get_parameter('nome'));
        }

        if (isset($_REQUEST['uf']) && !empty($_REQUEST['uf'])) {
            $this->db->where('di_uf', get_parameter('uf'));
        }

        if (isset($_REQUEST['pais']) && !empty($_REQUEST['pais'])) {
            $this->db->where('ci_pais', get_parameter('pais'));
        }

        if (isset($_REQUEST['niv']) && !empty($_REQUEST['niv'])) {
            $this->db->where('di_niv', get_parameter('niv'));
        }

        if (isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {
            $this->db->where('di_email', get_parameter('email'));
        }

        if ($this->input->get('empresa')) {
            $this->db->where('di_empresa', $this->input->get('empresa'));
        }

        if (get_parameter('di_login_status')) {
            $this->db->where('di_login_status', (get_parameter('di_login_status') - 1));
        }

        if (get_parameter('conta_verificada') == 'sim') {
            $this->db->where('di_contrato', 1);
            $this->db->where('di_conta_verificada', 1);
        } else if (get_parameter('conta_verificada') == 'nao') {
            $this->db->where('di_contrato', '0 OR  di_conta_verificada = 0', false);
        }

        if (isset($_REQUEST['cidade']) && !empty($_REQUEST['cidade'])) {
            $this->db->where('di_cidade', get_parameter('cidade'));
        }

        if (get_parameter('planos')) {
            $this->db->where('pa_id', get_parameter('planos'));
        }

        $this->db->stop_cache();



        if ($paises_permitidos->paises != null) {
            $data['num_distribuidores'] = $distribuidores_rows = $this->db
                            ->where('ci_pais IN(' . $paises_permitidos->paises . ')')
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->join('planos', 'pa_id=co_id_plano')
                            ->join('registro_planos_distribuidor', 'ps_distribuidor=di_id')
                            ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                            ->order_by('di_id', 'DESC')
                            ->group_by('di_id')
                            ->get('distribuidores')->num_rows;
        } else {
            $data['num_distribuidores'] = $distribuidores_rows = $this->db
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->join('planos', 'pa_id=co_id_plano')
                            ->join('registro_planos_distribuidor', 'ps_distribuidor=di_id')
                            ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                            ->order_by('di_id', 'DESC')
                            ->group_by('di_id')
                            ->get('distribuidores')->num_rows;
        }

        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/distribuidores/index/');
        $config['total_rows'] = $distribuidores_rows;
        $config['per_page'] = 10;
        $config['num_links'] = 7;
        $config['last_link'] = 'Ultima';
        $config['first_link'] = 'Primeira';
        $config['suffix'] = '?pais=' . get_parameter('pais') . '&planos=' . get_parameter('planos') . '&usuario=' . get_parameter('usuario') . '&nome=' . get_parameter('nome') . '&cpf=' . get_parameter('cpf') . '&uf=' . get_parameter('uf') . '&conta_verificada=' . get_parameter('conta_verificada') . "&di_login_status=" . get_parameter('di_login_status');
        $config['suffix'].= '&empresa=' . get_parameter('empresa');

        
        $dados = array( 
                       'max(pa_id) as pa_id ',
                       'di_id',
                       'di_nome',
                       'di_usuario',
                       'di_contrato',
                       'co_forma_pgt_txt',
                       'di_data_cad',
                       'di_ni_patrocinador',
                       'di_conta_verificada',
                       'di_login_status',
                       'di_cidade',
                    );

        if ($paises_permitidos->paises != null) {
            $data['distribuidores'] = $this->db
                            ->select($dados)
                            ->where('ci_pais IN(' . $paises_permitidos->paises . ')')
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->join('planos', 'pa_id=co_id_plano')
                            ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                            ->order_by('di_id', 'DESC')
                            ->group_by('di_id')
                            ->get('distribuidores', $config['per_page'], $this->uri->segment(3))->result();
        } else {
            $data['distribuidores'] = $this->db
                            ->select($dados)
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->join('planos', 'pa_id=co_id_plano')
                            ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                            ->order_by('di_id', 'DESC')
                            ->group_by('di_id')
                            ->get('distribuidores', $config['per_page'], $this->uri->segment(3))->result();
        }

        $this->db->flush_cache();

        $this->pagination->initialize($config);

        $data['links'] = $this->pagination->create_links();



        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    /**
     * Relatório dos financiados.
     */
    public function financiados() {
        permissao('arede', 'visualizar', get_user(), true);

        $paises_permitidos = $this->db->where('rfp_id_responsavel_fabrica', get_user()->rf_id)
                        ->select("GROUP_CONCAT(rfp_id_pais) as paises", false)
                        ->get('responsaveis_fabrica_paises')->row();
        $this->db->start_cache();

        if (isset($_REQUEST['usuario']) && !empty($_REQUEST['usuario'])) {
            $this->db->where('di_usuario', get_parameter('usuario'));
        }

        if (isset($_REQUEST['cpf']) && !empty($_REQUEST['cpf'])) {
            $this->db->where('di_cpf', get_parameter('cpf'));
        }

        if (isset($_REQUEST['nome']) && !empty($_REQUEST['nome'])) {
            $this->db->like('di_nome', get_parameter('nome'));
        }

        if (isset($_REQUEST['uf']) && !empty($_REQUEST['uf'])) {
            $this->db->where('di_uf', get_parameter('uf'));
        }

        if (isset($_REQUEST['pais']) && !empty($_REQUEST['pais'])) {
            $this->db->where('ci_pais', get_parameter('pais'));
        }

        if (isset($_REQUEST['niv']) && !empty($_REQUEST['niv'])) {
            $this->db->where('di_niv', get_parameter('niv'));
        }

        if (isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {
            $this->db->where('di_email', get_parameter('email'));
        }

        if (isset($_REQUEST['situacao']) && !empty($_REQUEST['situacao'])) {

            $this->db->where('(select count(*) from compras_financiamento where cof_id_compra=co_id and cof_pago=' . (get_parameter('situacao') - 1) . ' ) <1 ');
        }

        $limit = 20;

        if (isset($_REQUEST['totalpagina']) && $_REQUEST['totalpagina'] == "todos") {
            $limit = null;
        } else if (isset($_REQUEST['totalpagina'])) {
            $limit = $_REQUEST['totalpagina'];
        }

        $this->db->stop_cache();



        if ($paises_permitidos->paises != null) {
            $data['num_distribuidores'] = $distribuidores_rows = $this->db
                            ->where('co_parcelado', 1)
                            ->where('co_pago', 1)
                            ->where('ci_pais IN(' . $paises_permitidos->paises . ')')
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->order_by('di_id', 'DESC')
                            ->group_by('di_id')
                            ->get('distribuidores')->num_rows;
        } else {
            $data['num_distribuidores'] = $distribuidores_rows = $this->db
                            ->where('co_parcelado', 1)
                            ->where('co_pago', 1)
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->order_by('di_id', 'DESC')
                            ->group_by('di_id')
                            ->get('distribuidores')->num_rows;
        }

        $this->load->library('pagination');

        $config['base_url'] = base_url('index.php/distribuidores/financiados');
        $config['total_rows'] = $distribuidores_rows;
        $config['per_page'] = $limit;
        $config['num_links'] = 7;
        $config['last_link'] = 'Ultima';
        $config['first_link'] = 'Primeira';
        $config['suffix'] = '?situacao=' . get_parameter('situacao') . ' pais=' . get_parameter('pais') . '&usuario=' . get_parameter('usuario') . '&nome=' . get_parameter('nome') . '&cpf=' . get_parameter('cpf') . '&uf=' . get_parameter('uf') . '&conta_verificada=' . get_parameter('conta_verificada') . "&di_login_status=" . get_parameter('di_login_status');



        if ($paises_permitidos->paises != null) {
            $data['distribuidores'] = $this->db
                            ->where('co_parcelado', 1)
                            ->where('ci_pais IN(' . $paises_permitidos->paises . ')')
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->order_by('di_id', 'DESC')
                            ->group_by('di_id')
                            ->get('distribuidores', $config['per_page'], $this->uri->segment(3))->result();
        } else {
            $data['distribuidores'] = $this->db
                            ->where('co_parcelado', 1)
                            ->join('cidades', 'ci_id=di_cidade')
                            ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                            ->join('compras', 'co_id_distribuidor=di_id')
                            ->order_by('di_id', 'DESC')
                            ->group_by('di_id')
                            ->get('distribuidores', $config['per_page'], $this->uri->segment(3))->result();
        }

        $this->db->flush_cache();

        $this->pagination->initialize($config);

        $data['links'] = $this->pagination->create_links();



        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function remover_distribuidor_com_compra() {
        autenticar();

        permissao('cadastro_pendente', 'excluir', get_user(), true);

        $id = 358;


        $dis = $this->db
                        ->where('di_id', $id)
                        ->get('distribuidores')->row();

        $noQueEstaInserido = $this->db
                        ->where('di_esquerda', $id)
                        ->or_where('di_direita', $id)
                        ->get('distribuidores')->row();


        if (isset($dis->di_id) && isset($noQueEstaInserido->di_id) && $dis->di_direita == 0 && $dis->di_esquerda == 0) {


            $compras = $this->db
                            ->where('co_id_distribuidor', $id)
                            ->where('co_situacao <>', -1)
                            ->get('compras')->result();

            $this->db->insert('distribuidores_excluido', array(
                'di_dados' => json_encode($dis),
                'di_compra' => json_encode($compras),
                'di_id' => $id
            ));

            $this->db->where('di_id', $id)->delete('distribuidores');
            $this->db->where('co_id_distribuidor', $id)->delete('compras');
            $this->db->where('li_id_distribuidor', $id)->delete('distribuidor_ligacao');

            $lado = 'di_esquerda';
            if ($noQueEstaInserido->di_esquerda == $id) {
                $lado = 'di_esquerda';
            } else {
                $lado = 'di_direita';
            }

            $this->db
                    ->where('di_id', $noQueEstaInserido->di_id)
                    ->update('distribuidores', array(
                        $lado => 0
            ));

            echo "Excluido com sucesso";
        } else {
            echo "Nao e possivel excluir";
        }
    }

    public function remover_distribuidor() {
        autenticar();

        permissao('cadastro_pendente', 'excluir', get_user(), true);

        $id = $this->uri->segment(3);

        $alocado = $this->db
                        ->select('li_id_distribuidor')
                        ->where('li_id_distribuidor', $id)
                        ->get('distribuidor_ligacao')->row();

        //Comprou e pagou
        $fezCompra = $this->db
                        ->where('co_id_distribuidor', $id)
                        ->where('co_pago', 1)
                        ->get('compras')->result();

        if (count($alocado) == 0 && count($fezCompra) == 0) {

            $dis = $this->db
                            ->where('di_id', $id)
                            ->get('distribuidores')->row();

            $this->db->insert('distribuidores_excluido', array(
                'di_dados' => json_encode($dis),
                'di_id' => $id
            ));

            //Removendo todo os sales.
            $compras = $this->db
                            ->where('co_id_distribuidor', $id)
                            ->get('compras')->result();

            foreach ($compras as $compra) {
                $this->db->where('sa_id_compra', $compra->co_id)->delete('compras_sales');
            }

            $this->db->where('di_id', $id)->delete('distribuidores');
            $this->db->where('co_id_distribuidor', $id)->delete('compras');
        }

        redirect(base_url('index.php/cadastros_pendentes'));
    }

    public function mudar_patrocinador() {

        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function rede() {

        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function buscar_distribuidor_ajax() {

        $valor = $_POST['chave'] + 0;
        ($_POST['chave'] === '' . $valor) ? $this->db->where('di_id', $_POST['chave']) : $this->db->like('di_nome', $_POST['chave']);

        $d = $this->db->get('distribuidores')->result();
        echo json_encode($d);
    }

    public function mudar_patrocinador_confirm() {

        $this->db->trans_start();

        $di_id = $_POST['di_id'];
        $ni_patrocinador = $_POST['ni_escolhido'];

        //Alterar Patrocinador
        $this->db->where('di_id', $di_id)->update('distribuidores', array(
            'di_ni_patrocinador' => $ni_patrocinador
        ));

        //Alterar ligações do distribuidor
        $this->db->where('li_id_distribuidor', $di_id)->delete('distribuidor_ligacao');

        $this->nos_ids = array();
        $this->caminho_distribuidor($di_id);
        $caminho = array_reverse($this->nos_ids);

        $obj_my = new stdClass;
        $obj_my->di_ni_patrocinador = $di_id;
        $caminho[] = $obj_my;
        foreach ($caminho as $k => $c) {
            $this->db->insert('distribuidor_ligacao', array(
                'li_id_distribuidor' => $di_id,
                'li_posicao' => ($k + 1),
                'li_no' => $c->di_ni_patrocinador
            ));
        }

        $this->atualizar_ligacao_rede($di_id);


        //Se todas as operações ocorrem como esperado
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        redirect(base_url('index.php/distribuidores'));
    }

    function atualizar_ligacao_rede($ni_pai) {

        $ci = & get_instance();

        $dis = $ci->db->select(array('di_id', 'di_nome', 'di_ativo'))
                ->where('di_ni_patrocinador', $ni_pai)->get('distribuidores')
                ->result();

        if (count($dis)) {

            foreach ($dis as $d) {

                //Alterar ligações do distribuidor
                $this->db->where('li_id_distribuidor', $d->di_id)->delete('distribuidor_ligacao');

                $this->nos_ids = array();
                $this->caminho_distribuidor($d->di_id);
                $caminho = array_reverse($this->nos_ids);

                $obj_my = new stdClass;
                $obj_my->di_ni_patrocinador = $d->di_id;
                $caminho[] = $obj_my;
                foreach ($caminho as $k => $c) {
                    $this->db->insert('distribuidor_ligacao', array(
                        'li_id_distribuidor' => $d->di_id,
                        'li_posicao' => ($k + 1),
                        'li_no' => $c->di_ni_patrocinador
                    ));
                }

                $this->atualizar_ligacao_rede($d->di_id);
            }
        }
    }

    function caminho_distribuidor($patrocinador) {
        $dis = $this->db->select(array('di_ni_patrocinador'))->where('di_id', $patrocinador)->get('distribuidores')->result();
        foreach ($dis as $d) {
            if ($d->di_ni_patrocinador != 0) {
                $this->nos_ids[] = $d;
                $this->caminho_distribuidor($d->di_ni_patrocinador);
            }
        }
    }

    ## END Mudar patrocinador			

    function distribuidor_info_ajax() {
        $now = time();
        $di = $this->db
                        ->join('cidades', 'ci_id = di_cidade')
                        ->join('distribuidor_qualificacao', 'dq_id=di_qualificacao')
                        ->where('di_id', $_POST['ni'])->get('distribuidores')->result();
        $di[0]->di_data_cad = date('d/m/Y H:i:s', strtotime($di[0]->di_data_cad));

##PONTOS
        $pontos = $this->db->where(array('pd_mes' => date('m'),
                            'pd_ano' => date('Y'), 'pd_distribuidor' => $_POST['ni']))
                        ->get('pontos_distribuidor')->result();

        $di[0]->pp = isset($pontos[0]->pd_pontos) ? number_format($pontos[0]->pd_pontos, 2, ',', '.') : 0;
        $di[0]->pg = isset($pontos[0]->pd_grupo) ? number_format($pontos[0]->pd_grupo, 2, ',', '.') : 0;


        $VFR = $this->db->query("
		SELECT SUM(co_total_valor) as co_total_valor FROM compras 
		WHERE co_pago=1 
		AND co_data_compra like '%" . date('Y-m-') . "%'
		AND co_id_distribuidor IN(SELECT di_id FROM `distribuidor_ligacao` 
		JOIN distribuidores ON di_id = `li_id_distribuidor`
		WHERE `li_no` = " . $_POST['ni'] . ")")->result();

        $di[0]->vfr = number_format($VFR[0]->co_total_valor, 2, ',', '.');



        echo json_encode($di[0]);



        $and_now = time() - $now;
        if ($and_now == 0) {
            usleep(200000);
        }
    }

    public function login_distribuidor() {

        //Login destribuidor		 
        $login = $this->db->where('di_id', $this->uri->segment(3))
                        ->join('cidades', 'di_cidade = ci_id')
                        ->join('distribuidor_qualificacao', 'dq_id=di_qualificacao', 'left')
                        ->get('distribuidores')->row();

        if (count($login) > 0) {

            $_SESSION['compra'] = 0;

            $this->db->insert('historico_acesso', array(
                'ha_distribuidor' => $this->uri->segment(3),
                'ha_time' => time()
            ));
            //Pagando o Binário do usuario
            $bonusBinario = new BonusBinario();
            $bonusBinario->pagar($this->uri->segment(3));

            $_SESSION['distribuidor_log'] = $login;
            redirect(APP_BASE_URL . APP_DISTRIBUIDOR);
        }

        //Fim Login destribuidor
    }

    public function editar_distribuidor() {
        autenticar();
        permissao('arede', 'editar', get_user(), true);

        //Se não passar o id 
        if ($this->uri->segment(3) == false) {
            set_notificacao(2, "<div>Não foi possível encontrar o distribuidor!</div>");
            redirect(base_url());
        }

        if (count($_POST) > 0) {
            $di_id = $this->uri->segment(3);

            if (!empty($_POST['di_senha'])) {
                $this->db->where('di_id', $this->uri->segment(3))->update('distribuidores', array(
                    'di_senha' => funcoesdb::is_sha1($_POST['di_senha']) ? $_POST['di_senha'] : sha1($_POST['di_senha'])
                ));
            }

            if (!empty($_POST['di_pw'])) {
                $this->db->where('di_id', $this->uri->segment(3))->update('distribuidores', array(
                    'di_pw' => funcoesdb::is_sha1($_POST['di_pw']) ? $_POST['di_pw'] : sha1($_POST['di_pw'])
                ));
            }

            $_POST['di_data_nascimento'] = data_usa($_POST['di_data_nascimento']);

            unset($_POST['di_pw']);
            unset($_POST['di_senha']);

            foreach ($_POST as $key => $value) {

                if ($key != 'di_senha' && $key != 'di_senha2') {
                    $dados[$key] = $value;
                }

                if ($key != 'di_pw' && $key != 'di_pw2') {
                    $dados[$key] = $value;
                }
            }

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
                    $dados['di_cidade'] = (int) $this->db->insert_id();
                }
            } else {
                $dados['di_cidade'] = (int) $cidade->ci_id;
            }

            //Altera os dados do distribuidor.
            if (isset($_POST['tipopessoa']) && $_POST['tipopessoa'] == 1) {
                $this->db->where('dpj_id_distribuidor', $di_id)->delete('distribuidor_pessoa_juridica');
                $_POST['dpj_id_distribuidor'] = $di_id;
                $this->db->insert('distribuidor_pessoa_juridica', valida_fields('distribuidor_pessoa_juridica', $_POST));
            }

            if (isset($_POST['tipopessoa']) && $_POST['tipopessoa'] == 0) {
                $this->db->where('dpj_id_distribuidor', $di_id)->delete('distribuidor_pessoa_juridica');
            }


            if ($this->db->where('di_id', $this->uri->segment(3))->update('distribuidores', valida_fields('distribuidores', $dados))) {

                $distribuidor = $this->db->where('di_id', $this->uri->segment(3))
                                ->get('distribuidores')->row();
                /**
                 * Pos atualiza o atualizar o usuário local
                 * atualiza ele também na atm.
                 */
                $atm = new atm();
                $res = $atm->atualiar_cadastro_ewallet($distribuidor);

                if ($res == false) {
//                     set_notificacao(2, "<div>Os dados foram atualizado no sistema local, mas não forão modificado na "
//                             . "Plataforma de Pagamento por algum motivo la tente mais tarde!</div>");
                }

                set_notificacao(1, "<div>Cadastro alterado com sucesso!</div>");
            } else {
                set_notificacao(2, "<div>Não foi possível atualizar o cadastro do Distribuidor, Tente novamente!</div>");
            }
            redirect(base_url('index.php/distribuidores/editar_distribuidor/' . $di_id));
            exit;
        }



        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function get_pais_ajax() {

        $ps_nome = $_REQUEST['ps_nome'];
        $pais = $this->db->where('ps_iso3', $ps_nome)->get('pais')->row();
        if (count($pais) > 0) {
            echo json_encode(array('status' => 1, 'data' => $pais->ps_id));
        } else {
            echo json_encode(array('status' => 1, 'data' => ''));
        }
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

    public function alterar_usuario() {
        autenticar();
        permissao('arede', 'editar', get_user(), true);


        if (get_user()->rf_tipo != 1) {
            set_notificacao(2, "Permissão negada");
            redirect(base_url('index.php/distribuidores/editar_distribuidor/' . $_POST['di_id']));
            exit;
        }
        if (trim($_POST['di_usuario']) == "") {
            set_notificacao(2, "Usuário invalido");
            redirect(base_url('index.php/distribuidores/editar_distribuidor/' . $_POST['di_id']));
            exit;
        }

        $distribuidorCadastrado = $this->db->where('di_usuario', $_POST['di_usuario'])->get('distribuidores')->row();

        if (count($distribuidorCadastrado) > 0) {
            set_notificacao(2, "Usuário já cadastrado");
            redirect(base_url('index.php/distribuidores/editar_distribuidor/' . $_POST['di_id']));
            exit;
        }


        if (isset($_POST['di_usuario']) && isset($_POST['di_usuario_ant'])) {

            //alterar nome de usuario
            $this->db->where('di_usuario', $_POST['di_usuario_ant'])->update('distribuidores', array('di_usuario' => $_POST['di_usuario']));

            //alterar patrocinadores
            $this->db->where('di_usuario_patrocinador', $_POST['di_usuario_ant'])->update('distribuidores', array('di_usuario_patrocinador' => $_POST['di_usuario']));

            //alterar distribuidor na descricao da conta bonus//alterar nome de usuario
            $this->db->query('UPDATE conta_bonus SET cb_descricao =  REPLACE (cb_descricao, "<b>' . $_POST['di_usuario_ant'] . '</b>", "<b>' . $_POST['di_usuario'] . '</b>");');

            $this->db->insert('auditoria_geral', array(
                'ag_id_responsavel' => get_user()->rf_id,
                'ag_data' => date('Y-m-d H:i:s'),
                'ag_tabela' => 'distribuidores',
                'ag_acao_realizada' => 'alterar_usuario',
                'ag_descricao' => "O administrador " . get_user()->rf_nome . "(" . get_user()->rf_id . "ind) alterou o login do usuário " . $_POST['di_usuario_ant'] . " para " . $_POST['di_usuario']
            ));
            set_notificacao(1, 'Alterado com sucesso');
        }


        redirect(base_url('index.php/distribuidores/editar_distribuidor/' . $_POST['di_id']));
    }

    /**
     * função de alteração de status de login.
     * bloquear e desbloquear usuário.
     */
    public function alterar_status_login() {
        /**
         * 1 bloqueia o usuário na tabela de distribuidores.
         *  {status =1 livre: status =0 bloqueado}
         * 2 alvar o resgistor do bloqueio na tabela de logins bloqueados.
         * 
         */
        if (isset($_POST['di_id']) && !empty($_POST['di_id'])) {
            /*
             * Verifica qual o status que ta no login então ai muda para
             * outro status.
             */

            $status_login = $this->db->where('di_id', $_POST['di_id'])
                    ->select('IF(di_login_status=1, 0, 1) as di_login_status ,di_nome', false)
                    ->get('distribuidores')
                    ->row();

            //Mudar o status do distribuidores.
            $this->db->where('di_id', $_POST['di_id'])->update('distribuidores', array(
                'di_login_status' => $status_login->di_login_status
            ));

            //Colocaando o dados para inserir no banco.
            $data = array('rdb_id_distribuidor' => $_POST['di_id']);

            //Se o administrador informar uma mensagem de bloqueio personalizada.
            if (isset($_POST['di_mensagem']) && !empty($_POST['di_mensagem'])) {
                $data = array_merge($data, array('rdb_mensagem' => $_POST['di_mensagem']));
            }

            //Salvando o registro de bloqueio de distribuidor.
            $this->db->insert('registro_distribuidor_bloqueio', $data);

            if ($status_login->di_login_status == 1) {
                set_notificacao(1, "Usuário: <i>{$status_login->di_nome}</i> foi Desbloqueado.");
            } else {
                set_notificacao(2, "Usuário: <i>{$status_login->di_nome}</i> foi Bloqueado.");
            }

            redirect(base_url('index.php/distribuidores'));
        }
    }

    /**
     * Histórico do bloqueio individual de usuário
     */
    public function historico_bloqueio() {
        $data['pagina'] = strtolower(__CLASS__)
                . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function bloquear_desbloquear_financeiro() {
        autenticar();
        $di_usuario = $this->uri->segment(3);
        $usuario = explode(',', conf()->grupo_usuarios);
        $usuario_existe = $this->db->where('di_usuario', $di_usuario)
                        ->get('distribuidores')->row();

        if (count($usuario_existe) == 0) {
            set_notificacao(2, "Erro: Usuário não existe.");
            redirect(base_url('index.php/distribuidores'));
            return false;
        }

        if (empty($usuario[0])) {
            unset($usuario[0]);
        }

        if (!in_array($di_usuario, $usuario)) {
            //Incluido o usuario se não existir
            $usuario = implode(',', array_merge($usuario, array($di_usuario)));
            $this->db->where('field', 'grupo_usuarios')
                    ->update('config', array('valor' => $usuario));

            set_notificacao(2, "Usuário: <i>{$usuario_existe->di_nome}</i> foi Bloqueado.");
        } else {
            $posicao = array_search($di_usuario, $usuario);
            unset($usuario[$posicao]);
            $this->db->where('field', 'grupo_usuarios')
                    ->update('config', array('valor' => implode(',', $usuario)));
            set_notificacao(1, "Usuário: <i>{$usuario_existe->di_nome}</i> foi Desbloqueado.");
        }

        redirect(base_url('index.php/distribuidores'));
    }

    public function status_financeiro($di_usuario) {

        if (!empty($di_usuario)) {

            if (in_array($di_usuario, explode(',', conf()->grupo_usuarios))) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * ajax do busca estado.
     */
    public function estados() {
        $c = $this->db->where('es_pais', $_POST['es_pais'])->get('estados')->result();
        echo json_encode($c);
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

}
