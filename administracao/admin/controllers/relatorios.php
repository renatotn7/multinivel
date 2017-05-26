<?php

class Relatorios extends CI_Controller {

    public function __construct() {
        parent::__construct();
        autenticar();
    }

    public function index() {
        $data['pagina'] = strtolower(__CLASS__) . "/relatorios_" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $daco_id_produto_escolha_entregata);
    }

    public function relatorio_email_ativos() {
        $email_ativos = $this->db
                        ->select('di_email')
                        ->join('compras', 'co_id_distribuidor=di_id')
                        ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                        ->order_by('di_id', 'DESC')
                        ->group_by('di_email')
                        ->get('distribuidores')->result();
        $relatorio = '';
        foreach ($email_ativos as $email_ativo) {
            $relatorio.= "{$email_ativo->di_email}\r\n";
        }

        header('Content-type: application/notepad');
        header('Content-Disposition: attachment; filename="email_ativos_' . date('d_m_y') . '.txt"');
        echo $relatorio;
    }

    public function relatorio_email_pendente() {
        $email_pendes = $this->db
                        ->join('compras', 'co_id_distribuidor=di_id')
                        ->where('di_id NOT IN', '(SELECT li_id_distribuidor FROM distribuidor_ligacao)', false)
                        ->where('di_id NOT IN', '(SELECT co_id_distribuidor FROM compras WHERE co_pago = 1)', false)
                        ->where('di_excluido', 0)
                        ->order_by('di_data_cad', 'desc')
                        ->group_by('di_email')
                        ->get('distribuidores')->result();

        $relatorio = '';
        foreach ($email_pendes as $email_pende) {
            $relatorio.= "{$email_pende->di_email}\r\n";
        }

        header('Content-type: application/notepad');
        header('Content-Disposition: attachment; filename="email_pendentes_' . date('d_m_y') . '.txt"');
        echo $relatorio;
    }

    public function relatorio_cadastros_ativos() {
        error_reporting(0);
        $cadastros = $this->db
                        ->select('di_nome, di_usuario, di_data_cad, di_email, ps_nome, pa_descricao, dq_descricao')
                        ->join('cidades', 'di_cidade=ci_id')
                        ->join('pais', 'ps_id=ci_pais')
                        ->join('registro_planos_distribuidor', 'di_id=ps_distribuidor')
                        ->join('planos', 'pa_id=ps_plano')
                        ->join('historico_qualificacao', 'di_id=hi_distribuidor')
                        ->join('distribuidor_qualificacao', 'dq_id=hi_qualificacao')
                        ->where('di_id IN (SELECT at_distribuidor FROM registro_ativacao)')
                        ->group_by('di_id')
                        ->order_by('hi_data', 'desc')
                        ->get('distribuidores')->result();

        ob_start();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header("Content-Disposition: attachment; filename='relatorio_cadastros_ativos_" . date('d_m_Y_H_i') . ".xls");
        header("Content-Description: PHP Generated Data");

        echo "<table>";
        echo "<tr>";
        echo "<th>Nome</th>";
        echo "<th>Login</th>";
        echo "<th>Data de Cadastro</th>";
        echo "<th>E-mail</th>";
        echo utf8_decode("<th>País</th>");
        echo "<th>Agencia Atual</th>";
        echo utf8_decode("<th>Graduação Plano de Carreira</th>");
        echo "</tr>";

        foreach ($cadastros as $cadastro) {
            echo "<tr>";
            echo "<td>" . $cadastro->di_nome . "</td>";
            echo "<td>" . $cadastro->di_usuario . "</td>";
            echo "<td>" . $cadastro->di_data_cad . "</td>";
            echo "<td>" . $cadastro->di_email . "</td>";
            echo "<td>" . $cadastro->ps_nome . "</td>";
            echo "<td>" . $cadastro->pa_descricao . "</td>";
            echo "<td>" . $cadastro->dq_descricao . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ob_flush();
    }

    public function relatorio_plano_carreira() {
        error_reporting(0);
        $cadastros = $this->db
                        ->select('di_nome, di_usuario, dq_descricao')
                        ->join('historico_qualificacao', 'di_id=hi_distribuidor')
                        ->join('distribuidor_qualificacao', 'dq_id=hi_qualificacao')
                        ->group_by('di_id')
                        ->order_by('hi_data', 'desc')
                        ->get('distribuidores')->result();

        ob_start();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header("Content-Disposition: attachment; filename='relatorio_plano_de_carreira" . date('d_m_Y_H_i') . ".xls");
        header("Content-Description: PHP Generated Data");

        echo "<table>";
        echo "<tr>";
        echo "<th>Login</th>";
        echo "<th>Nome</th>";
        echo utf8_decode("<th>Graduação Plano de Carreira</th>");
        echo "</tr>";

        foreach ($cadastros as $cadastro) {
            echo "<tr>";
            echo "<td>" . $cadastro->di_usuario . "</td>";
            echo "<td>" . $cadastro->di_nome . "</td>";
            echo "<td>" . $cadastro->dq_descricao . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ob_flush();
    }

    public function relatorio_cadastros_inativos() {
        error_reporting(0);
        $cadastros = $this->db
                        ->select('di_nome, di_usuario, di_data_cad, di_email, ps_nome, pa_descricao')
                        ->join('cidades', 'di_cidade=ci_id')
                        ->join('pais', 'ps_id=ci_pais')
                        ->join('compras', 'di_id = co_id_distribuidor')
                        ->join('planos', 'pa_id=co_id_plano')
                        ->where('di_id NOT IN (SELECT at_distribuidor FROM registro_ativacao)')
                        ->group_by('di_id')
                        ->get('distribuidores')->result();

        ob_start();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header("Content-Disposition: attachment; filename='relatorio_cadastros_inativos_" . date('d_m_Y_H_i') . ".xls");
        header("Content-Description: PHP Generated Data");

        echo "<table>";
        echo "<tr>";
        echo "<th>Nome</th>";
        echo "<th>Login</th>";
        echo "<th>Data de Cadastro</th>";
        echo "<th>E-mail</th>";
        echo utf8_decode("<th>País</th>");
        echo "<th>Agencia Escolhida</th>";
        echo "</tr>";

        foreach ($cadastros as $cadastro) {
            echo "<tr>";
            echo "<td>" . $cadastro->di_nome . "</td>";
            echo "<td>" . $cadastro->di_usuario . "</td>";
            echo "<td>" . $cadastro->di_data_cad . "</td>";
            echo "<td>" . $cadastro->di_email . "</td>";
            echo "<td>" . $cadastro->ps_nome . "</td>";
            echo "<td>" . $cadastro->pa_descricao . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ob_flush();
    }

    public function relatorio_escolhas() {
        error_reporting(0);
        $escolhas = $this->db
                        ->join('distribuidores', 'di_id=co_id_distribuidor')
                        ->join('registro_ativacao', 'di_id=at_distribuidor', 'left')
                        ->join('produtos_escolha_entrega', 'pe_id=co_id_produto_escolha_entrega')
                        ->select('at_data, compras.*, distribuidores.*')
                        ->group_by("di_id")
                        ->get('compras')->result();
        ob_start();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header("Content-Disposition: attachment; filename='relatorio_escolhas_." . date('d_m_Y_H_i') . ".xls");
        header("Content-Description: PHP Generated Data");


        echo "<table>";
        echo "<tr>";
        echo "<td>login</td>";
        echo "<td>Niv</td>";
        echo "<td>Nome completo</td>";
        echo "<td>Agencia adquirida</td>";
        echo "<td>agencia atual</td>";
        echo "<td>Escolha</td>";
        echo "<td>Upgrades</td>";
        echo utf8_decode("<td>Data da ativação</td>");
        echo utf8_decode("<td>Data da escolha</td>");
        echo "</tr>";

        foreach ($escolhas as $escolha) {
            if (DistribuidorDAO::getAgenciaAdquirida($escolha) <> null && DistribuidorDAO::getAgenciaAdquirida($escolha) <> '') {
                $agencia_adiquirida = DistribuidorDAO::getAgenciaAdquirida($escolha);
            } else {
                $agencia_adiquirida = DistribuidorDAO::getAgenciaAdquiridaNaoAtivo($escolha);
            }
            $agencia_atual = DistribuidorDAO::getPlano($escolha->di_id);
            $esco = ComprasModel::fez_escolha_recebimento($escolha);
            $upgrades = DistribuidorDAO::getUpgrades($escolha);

            echo "<tr>";
            echo "<td>" . $escolha->di_usuario . "</td>";
            echo "<td>" . $escolha->di_niv . "</td>";
            echo utf8_decode("<td>" . $escolha->di_nome . ' ' . $escolha->di_ultimo_nome . "</td>");
            echo "<td>" . (count($agencia_adiquirida) > 0 ? $agencia_adiquirida->pa_descricao : '') . "</td>";
            echo "<td>" . (count($agencia_atual) > 0 ? $agencia_atual->pa_descricao : '') . "</td>";
            echo "<td>" . (count($esco) > 0 ? utf8_decode($esco->pe_descricao) : '') . "</td>";
            echo "<td>";
            echo "<table>";
            if (count($upgrades) > 0) {
                foreach ($upgrades as $upgrade) {
                    echo "<tr>";
                    echo "<td>{$upgrade->pa_descricao}</td>";
                    echo "</tr>";
                }
            } else {
                echo utf8_decode('Não fez');
            }
            echo "</table>";
            echo "</td>";
            echo "<td>" . (!empty($escolha->at_data) ? date("d/m/Y H:i:s", strtotime($escolha->at_data)) : '' ) . "</td>";
            echo "<td>" . (count($esco) > 0 ? utf8_decode($esco->pe_descricao) : '') . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        ob_flush();
    }

    /**
     * Relatório de transações
     * realizado de acordo com a tarefa 783
     */
    public function transacoes() {

        $this->load->library('ordenacao');

        $this->db->start_cache();

        if (!empty($_REQUEST['name']) && !empty($_REQUEST['name']) && !isset($_REQUEST['di_usuario'])) {
            $this->db->where('di_usuario', $_REQUEST['name']);
        }

        if (!empty($_REQUEST['cb_tipo'])) {
            $this->db->where('cb_tipo', $_REQUEST['cb_tipo']);
        }

        if (!empty($_REQUEST['di_usuario']) && $_REQUEST['di_usuario'] == 1) {
            $this->db->where("li_no = (select di_id from distribuidores where di_usuario='{$_REQUEST['name']}')");
        }

        if (!empty($_REQUEST['de']) && !empty($_REQUEST['ate'])) {
            $de = data_usa($_REQUEST['de']);
            $ate = data_usa($_REQUEST['ate']);
        } else {
            $de = date('2014-01-01');
            $ate = date('Y-m-d');
        }

        $data['de'] = $de;
        $data['ate'] = $ate;

        $this->db->where('cb_data_hora >=', $de . " 00:00:00");
        $this->db->where('cb_data_hora <', $ate . " 59:59:59");

        //carregando a paginação
        $this->load->library('paginacao');
        $this->paginacao->por_pagina(isset($_REQUEST['totalpagina']) ? $_REQUEST['totalpagina'] : 20);

        $page = '';
        $page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;

        if (!isset($_REQUEST['totalpagina'])) {

            $limit = 20;
        }

        if (isset($_REQUEST['totalpagina']) && $_REQUEST['totalpagina'] == "todos") {
            $limit = null;
        } else if (isset($_REQUEST['totalpagina'])) {
            $limit = $_REQUEST['totalpagina'];
        }


        if (isset($_REQUEST['valor'])) {
            $this->db->order_by('valor', $_REQUEST['valor']['ordDsc'] == 1 ? 'Desc' : 'ASC');
        }

        if (isset($_REQUEST['data'])) {
            $this->db->order_by('cb_data_hora', $_REQUEST['data']['ordDsc'] == 1 ? 'Desc' : 'ASC');
        }
        if (isset($_REQUEST['dataCad'])) {
            $this->db->order_by('di_data_cad', $_REQUEST['dataCad']['ordDsc'] == 1 ? 'Desc' : 'ASC');
        }


        $this->db->stop_cache();

        if (isset($_REQUEST['di_usuario']) && $_REQUEST['di_usuario'] == 1) {

            $quantidade = $this->db
                            ->select(array('count(*) as valor'))
                            ->join('distribuidores', 'di_id=cb_distribuidor')
                            ->join('distribuidor_ligacao', 'di_id = li_id_distribuidor')
                            ->get('conta_bonus')->row();

            $data['totalCreditos'] = $this->db->select(array('SUM(cb_credito) as valor'))
                            ->join('distribuidores', 'di_id=cb_distribuidor')
                            ->join('distribuidor_ligacao', 'di_id = li_id_distribuidor')
                            ->get('conta_bonus')->row();

            $data['totalDebitos'] = $this->db->select(array('SUM(cb_debito) as valor'))
                            ->join('distribuidores', 'di_id=cb_distribuidor')
                            ->join('distribuidor_ligacao', 'di_id = li_id_distribuidor')
                            ->get('conta_bonus')->row();
            $this->db->select(array('cb_id', 'di_data_cad', 'di_usuario', 'cb_descricao', "if(cb_credito=0,cb_debito,cb_credito) as valor", "if(cb_credito=0,'- US$ ','+ US$ ') as R", 'cb_data_hora', "if(cb_credito=0,'label label-important','label label-success') as class"));

            $data['contaBonus'] = $this->db
                            ->join('distribuidores', 'di_id=cb_distribuidor')
                            ->join('distribuidor_ligacao', 'di_id = li_id_distribuidor')
                            ->get('conta_bonus', $limit, $page)->result();
        } else {

            $quantidade = $this->db
                            ->select(array('count(*) as valor'))
                            ->join('distribuidores', 'di_id=cb_distribuidor')
                            ->get('conta_bonus')->row();

            $data['totalCreditos'] = $this->db->select(array('SUM(cb_credito) as valor'))
                            ->join('distribuidores', 'di_id=cb_distribuidor')
                            ->get('conta_bonus')->row();

            $data['totalDebitos'] = $this->db->select(array('SUM(cb_debito) as valor'))
                            ->join('distribuidores', 'di_id=cb_distribuidor')
                            ->get('conta_bonus')->row();

            $this->db->select(array('cb_id', 'di_data_cad', 'di_usuario', 'cb_descricao', "IF(if(cb_credito=0,cb_debito,cb_credito)!=0,if(cb_credito=0,cb_debito,cb_credito),cb_debito_EWC) as valor", "if(cb_credito=0,'- US$ ','+ US$ ') as R", 'cb_data_hora', "if(cb_credito=0,'label label-important','label label-success') as class"));

            $data['contaBonus'] = $this->db
                            ->join('distribuidores', 'di_id=cb_distribuidor')
                            ->get('conta_bonus', $limit, $page)->result();
        }





        $this->db->flush_cache();
        //Paginação da lista de noticia
        $this->load->library('pagination');


        $this->pagination->initialize(array(
            'base_url' => base_url('index.php/relatorios/transacoes'),
            'total_rows' => $quantidade->valor,
            'per_page' => $limit,
            'full_tag_open' => '<div  class="pagination pagination-centered" ><ul>',
            'full_tag_close' => '</ul></div>',
            'prev_link' => '&laquo;',
            'prev_tag_open' => '<li>',
            'prev_tag_close' => '</li>',
            'last_tag_open' => '<li>',
            'last_tag_close' => '</li>',
            'first_tag_open' => '<li>',
            'first_tag_close' => '</li>',
            'next_link' => '&raquo;',
            'last_link' => 'Ultima',
            'first_link' => 'Primeira',
            'next_tag_open' => '<li>',
            'next_tag_close' => '</li>',
            'cur_tag_open' => '<li class="active"><a href="#">',
            'cur_tag_close' => '</a></li>',
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>',
            'suffix' => '?' . urldecode(http_build_query(array_filter($_REQUEST)))
        ));

        if ($_REQUEST) {
            $uri = "index.php/relatorios/transacoes?" . http_build_query(array_filter($_REQUEST));
        } else {
            $uri = "index.php/relatorios/transacoes?";
        }

        $data['ordenarDescData'] = $this->ordenacao->ordenarDesc('data');
        $data['ordenarAscData'] = $this->ordenacao->ordenarAsc('data');

        $data['ordenarDescDataCad'] = $this->ordenacao->ordenarDesc('dataCad');
        $data['ordenarAscDataCad'] = $this->ordenacao->ordenarAsc('dataCad');

        $data['activDescData'] = $this->ordenacao->activeDesc('data') ? 'icon-white' : '';
        $data['activAscData'] = $this->ordenacao->activASC('data') ? 'icon-white' : '';

        $data['activDescDataCad'] = $this->ordenacao->activeDesc('data') ? 'icon-white' : '';
        $data['activAscDataCad'] = $this->ordenacao->activASC('data') ? 'icon-white' : '';

        $data['ordenarDescValor'] = $this->ordenacao->ordenarDesc('valor');
        $data['ordenarAscValor'] = $this->ordenacao->ordenarAsc('valor');

        $data['activDescValor'] = $this->ordenacao->activeDesc('valor') ? 'icon-white' : '';
        $data['activAscValor'] = $this->ordenacao->activASC('valor') ? 'icon-white' : '';

        $data['paginacao'] = $this->pagination->create_links();
        $data['total_Registro'] = $quantidade->valor;
        $data['link'] = http_build_query(array_filter($_REQUEST));
        $data['linkOndenacao'] = base_url($uri);
        $data['tipos'] = $this->db->get('conta_bonus_tipo')->result();
        $data['pagina'] = strtolower(__CLASS__) . "/relatorios_" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function transaoesGerarXls() {


        $this->db->start_cache();
        if (!empty($_REQUEST['name']) && !empty($_REQUEST['name'])) {
            $this->db->where('di_usuario', $_REQUEST['name']);
        }

        if (!empty($_REQUEST['cb_tipo'])) {
            $this->db->where('cb_tipo', $_REQUEST['cb_tipo']);
        }

        if (!empty($_REQUEST['de']) && !empty($_REQUEST['ate'])) {
            $de = data_usa($_REQUEST['de']);
            $ate = data_usa($_REQUEST['ate']);
        } else {
            $de = date('2014-01-01');
            $ate = date('Y-m-d');
        }

        $data['de'] = $de;
        $data['ate'] = $ate;

        $this->db->where('cb_data_hora >=', $de . " 00:00:00");
        $this->db->where('cb_data_hora <', $ate . " 59:59:59");


        $this->db->stop_cache();
        $quantidade = $this->db
                        ->select(array('count(*) as valor'))
                        ->join('distribuidores', 'di_id=cb_distribuidor')
                        ->get('conta_bonus')->row();

        $totalCreditos = $this->db->select(array('SUM(cb_credito) as valor'))
                        ->join('distribuidores', 'di_id=cb_distribuidor')
                        ->get('conta_bonus')->row();

        $totalDebitos = $this->db->select(array('SUM(cb_debito) as valor'))
                        ->join('distribuidores', 'di_id=cb_distribuidor')
                        ->get('conta_bonus')->row();

        $this->db->select(array('cb_id', 'di_usuario', 'cb_descricao', "if(cb_credito=0,cb_debito,cb_credito) as valor", "if(cb_credito=0,'- US$ ','+ US$ ') as R", 'cb_data_hora', "if(cb_credito=0,'label label-important','label label-success') as class"));

        $contaBonus = $this->db
                        ->join('distribuidores', 'di_id=cb_distribuidor')
                        ->get('conta_bonus')->result();

        $this->db->select(array('cb_id', 'di_usuario', 'cb_descricao', "if(cb_credito=0,cb_debito,cb_credito) as valor", "if(cb_credito=0,'- US$ ','+ US$ ') as R", 'cb_data_hora', "if(cb_credito=0,'label label-important','label label-success') as class"));



        $this->db->flush_cache();

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-Disposition: attachment; filename='relatorio_transacoes_." . date('d_m_Y_H_i') . ".xls");
        header("Content-Description: PHP Generated Data");
        echo '<table >
            <thead>
            <tr>
            <th>Nº:</th>
            <th>Usuario:</th>
            <th>Data: </th>
            <th>Descrição:</th>
            <th>Moeda:</th>
            <th>Valor: </tr>
            </thead>';
        foreach ($contaBonus as $contaB) {
            echo '
                <tr>
                    <td>' . $contaB->cb_id . '</td>
                    <td>' . $contaB->di_usuario . '</td>
                     <td>' . date('d/m/Y H:s:i', strtotime($contaB->cb_data_hora)) . '</td>
                    <td>' . $contaB->cb_descricao . '</td>
                 <td>' . $contaB->R . '</td>
                <td><span class="' . $contaB->class . '">' . number_format($contaB->valor, 2, ',', '.') . '</span></td>
                </tr>  ';
        }
        echo '</table>';
        exit;
    }

    /*
     *
     * Relat�rio Logins Tarefa 
     * 
     */

    public function relatorio_login() {

        $data['distribuidores'] = $this->db
                        ->join('conta_deposito', 'di_id=cdp_distribuidor')
                        ->group_by('di_id')
                        ->get('distribuidores', 10)->result();

        $this->load->view('relatorios/relatorio_login_view', $data);
    }

    public function qualificacoes() {

        $data['pagina'] = strtolower(__CLASS__) . "/qualificacoes/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

// Implimir todos um relat�rio completo de distribuidores	
    public function relatorios_distribuidor() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function bonus_pago() {

        if (get_parameter("mes") && get_parameter("ano")) {
            $this->db->like("cb_data", get_parameter("ano") . "-" . get_parameter("mes") . "-");
        } else {
            $this->db->like("cb_data", date("Y") . "-" . date("m") . "-");
        }

        $data['bonus'] = $this->db->join("distribuidores", 'cb_distribuidor=di_id')->where('cb_debito', 0)->get("conta_bonus")->result();

        $data['encontrados'] = count($data['bonus']);

        $this->load->view('relatorios/bonus/bonus_pago_view', $data);
    }

    public function repasse_bonus_montar() {

        $data['pagina'] = strtolower(__CLASS__) . "/repasse/relatorios_" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function desempenho() {

        $data['pagina'] = strtolower(__CLASS__) . "/distribuidor/desempenho";

        $this->load->view('home/index_view', $data);
    }

    public function montar_vendas_produtos() {

        $data['pagina'] = strtolower(__CLASS__) . "/produtos/relatorios_" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function montar_vendas() {

        $data['pagina'] = strtolower(__CLASS__) . "/produtos/relatorios_" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function montar_produto_kit() {

        $data['pagina'] = strtolower(__CLASS__) . "/kit/relatorios_" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function ativacao_montar() {
        $data['pagina'] = strtolower(__CLASS__) . "/ativacao/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function ativacao() {

        autenticar();

        $this->load->library('paginacao');

        $this->paginacao->por_pagina(50);


        $proximo_mes = mktime(0, 0, 0, get_parameter('mes') + 1, 01, get_parameter('ano'));

        $ativacao = $this->db->query("
SELECT * FROM credito_repasse JOIN distribuidores ON di_id = cp_distribuidor 
WHERE cp_data > '" . get_parameter('ano') . "-" . get_parameter('mes') . "-01 00:00:00'
AND cp_data < '" . date('Y', $proximo_mes) . "-" . date('m', $proximo_mes) . "-01 00:00:00'
AND cp_cd = 0
")->result();

        $data['num_ativacoes'] = count($ativacao);

        $data['ativacao'] = $this->paginacao->rows($ativacao);
        $data['links'] = $this->paginacao->links();


        $this->load->view('relatorios/ativacao/ativacao_view', $data);
    }

    public function bonus_apagar_detalhes() {

        $this->load->view('relatorios/bonus/bonus_apagar_detalhes_view');
    }

    public function bonus_apagar() {

        autenticar();

        $this->load->library('paginacao');

        $this->paginacao->por_pagina(40);


        /* 	$creditos = $this->db
          ->join('distribuidores','bt_distribuidor=di_id')
          ->get('bonus_temp')->result(); */

        $creditos = $this->db->query("
SELECT * FROM conta_bonus
JOIN distribuidores ON distribuidores.di_id=conta_bonus.cb_distribuidor
JOIN bonus_tipo ON bonus_tipo.tb_id=conta_bonus.cb_tipo
WHERE cb_tipo=tb_id
")->result();

        $data['creditos'] = $this->paginacao->rows($creditos);
        $data['links'] = $this->paginacao->links();

        $data['encontrados'] = count($creditos);

        $this->load->view('relatorios/bonus/bonus_apagar_view', $data);
    }

    public function detalhes_distribuidor_bonus_apagar() {
        autenticar();

        $data['c'] = $this->db->query("
SELECT * FROM conta_bonus
JOIN distribuidores ON distribuidores.di_id=conta_bonus.cb_distribuidor
JOIN cidades ON cidades.ci_id=distribuidores.di_cidade
JOIN bonus_tipo ON bonus_tipo.tb_id=conta_bonus.cb_tipo
WHERE cb_tipo=tb_id
AND di_id = " . $this->uri->segment(3) . "
")->row();

        $this->load->view('relatorios/bonus/detalhes_distribuidor_bonus_apagar_view', $data);
    }

    public function produto_kit() {

        autenticar();

        $this->load->library('paginacao');

        $this->paginacao->por_pagina($_GET['por_pagina']);


        if (get_parameter('tipo') == 2) {

            $this->db->where('co_data_compra >=', data_usa(get_parameter('de')) . " 00:00:00");
            $this->db->where('co_data_compra <=', data_usa(get_parameter('ate')) . " 23:59:59");

            $produtos = $this->db->join('produtos_comprados', 'pk_kit_comprado=pm_id')->join('produtos', 'pk_produto=pr_id')->join('compras', 'co_id=pm_id_compra')->select(array(
                        'pr_id',
                        'pr_nome',
                        'pm_quantidade',
                        'pr_codigo',
                        'COUNT(pk_produto) as quantidade'
                    ))->where('co_pago', 1)->where('co_entrega', 1)->where('co_id_cd', 0)->order_by('quantidade', $_GET['ordem'])->group_by('pk_produto')->get('produtos_kit_opcoes')->result();

            $data['produtos_encontrados'] = count($produtos);

            $data['produtos'] = $this->paginacao->rows($produtos);
            $data['links'] = $this->paginacao->links();

            $this->load->view('relatorios/kit/produto_kit_view', $data);
        } else if (get_parameter('tipo') == 1) {

            $this->db->where('cr_data_compra >=', data_usa(get_parameter('de')) . " 00:00:00");
            $this->db->where('cr_data_compra <=', data_usa(get_parameter('ate')) . " 23:59:59");

            $produtos = $this->db->join('produtos', 'pr_id=pc_id_produto')->join('compras_fabrica', 'cr_id=pc_id_compra')->select(array(
                        'pr_id',
                        'pr_nome',
                        'pr_codigo',
                        'SUM(pc_quantidade) as quantidade'
                    ))->where('cr_pago', 1)->where('pc_kit <>', 0)->group_by('pc_id_produto')->order_by('quantidade', $_GET['ordem'])->get('produtos_comprados_fabrica')->result();

            $data['produtos_encontrados'] = count($produtos);

            $data['produtos'] = $this->paginacao->rows($produtos);
            $data['links'] = $this->paginacao->links();
            $this->load->view('relatorios/kit/produto_kit2_view', $data);
        }
    }

    public function repasse_bonus() {
        $this->load->view('relatorios/repasse/repasse_bonus_view');
    }

    public function relatorio_produto() {

        autenticar();

        $this->load->library('paginacao');

        $this->paginacao->por_pagina($_GET['por_pagina']);


        if (get_parameter('tipo') == 1) {

            $this->db->where('cr_data_compra >=', data_usa(get_parameter('de')) . " 00:00:00");
            $this->db->where('cr_data_compra <=', data_usa(get_parameter('ate')) . " 23:59:59");

            if (get_parameter('forma_pagamento')) {
                $this->db->where('cr_forma_pgt', $_GET['forma_pagamento']);
            }

            $produtos = $this->db->join('produtos', 'pc_id_produto=pr_id')->join('compras_fabrica', 'cr_id=pc_id_compra')->join('formas_pagamento', 'fp_id=cr_forma_pgt')->select(array(
                        'pr_id',
                        'pr_nome',
                        'pc_kit',
                        'pc_quantidade',
                        'pr_codigo',
                        'fp_descricao'
                    ))->select_sum('pc_valor')->select_sum('pc_quantidade')->where('cr_pago', 1)->order_by('pc_quantidade', $_GET['ordem'])->group_by('pr_id')->get('produtos_comprados_fabrica')->result();
            $view = 'relatorios/produtos/relatorio_produto_view';
        } else {

            $this->db->start_cache();

            $this->db->where('co_data_compra >=', data_usa(get_parameter('de')) . " 00:00:00");
            $this->db->where('co_data_compra <=', data_usa(get_parameter('ate')) . " 23:59:59");

            if (get_parameter('forma_pagamento')) {
                $this->db->where('co_forma_pgt', $_GET['forma_pagamento']);
            }

            $produtos = $this->db->select(array(
                        'pm_data_compra',
                        'pm_valor',
                        'pr_id',
                        'pr_nome',
                        'pm_quantidade',
                        'pr_codigo',
                        'fp_descricao',
                        'co_forma_pgt'
                    ))->join('produtos', 'pm_id_produto=pr_id')->join('compras', 'co_id=pm_id_compra')->join('formas_pagamento', 'fp_id=co_forma_pgt')->where('co_pago', 1)->where('co_id_cd', 0)->where('co_entrega', 1)->order_by('pm_quantidade', $_GET['ordem'])->get('produtos_comprados')->result();

            $data['valorTotal'] = $this->db->join('produtos', 'pm_id_produto=pr_id')->join('compras', 'co_id=pm_id_compra')->join('formas_pagamento', 'fp_id=co_forma_pgt')->select_sum('pm_valor')->where('co_pago', 1)->where('co_id_cd', 0)->where('co_entrega', 1)->order_by('pm_quantidade', $_GET['ordem'])->get('produtos_comprados')->result();

            $this->db->flush_cache();

            $view = 'relatorios/produtos/relatorio_produto2_view';
        }

        $data['produtos_encontrados'] = count($produtos);

        $data['produtos'] = $this->paginacao->rows($produtos);
        $data['links'] = $this->paginacao->links();


        $this->load->view($view, $data);
    }

    /*
     * Gera relat�rio de distribuidores por data.
     */

    public function relatorio_distribuidores() {

        $this->load->library('/pdf/wkhtmltopdf');

//recebe do formulario o periodo de tempo
        $de = $this->input->post('de');
        $ate = $this->input->post('ate');
        $this->db->select("*")->from('distribuidores');
        $this->db->join('cidades', 'cidades.ci_id=distribuidores.di_cidade');
        $this->db->join('estados', 'estados.es_id=cidades.ci_estado');
        $this->db->where('di_data_cad >=', data_usa($de) . " 00:00:00");
        $this->db->where('di_data_cad <=', data_usa($ate) . " 23:59:59");
        $results = $this->db->get()->result();

//adiciona o valor na variavel de retorno;
        $data['distribuidores'] = $results;
        $view = 'relatorios/relatorio_distribuidores_to_pdf_view';

// 			ob_start();
        $this->load->view($view, $data);

// 			$content = ob_get_clean();
// 			$wkhtmltopdf->to_pdf(array(
// 			'url'=>base_url().'tmp.html',
// 			'name'=>'distribuidores',
// 			'html'=>$content
// 					)
// 			);
// 			try
// 			{
// 				$html2pdf = new HTML2PDF('P', 'A4', 'fr');
// 				//$html2pdf->setModeDebug();
// 				$html2pdf->setDefaultFont('Arial');
// 				$html2pdf->writeHTML($content, false);
// 				$html2pdf->Output('contrato.pdf');
// 			}
// 			catch(HTML2PDF_exception $e) {
// 				echo $e;
// 				exit;
// 			}
    }

    public function relatorio_vendas() {

        autenticar();

        $this->load->library('paginacao');

        $this->paginacao->por_pagina($_GET['por_pagina']);


        if (get_parameter('tipo') == 1) {

            $this->db->where('cr_data_compra >=', data_usa(get_parameter('de')) . " 00:00:00");
            $this->db->where('cr_data_compra <=', data_usa(get_parameter('ate')) . " 23:59:59");

            if (get_parameter('forma_pagamento')) {
                $this->db->where('cr_forma_pgt', $_GET['forma_pagamento']);
            }

            $this->db->select(array(
                'cd_nome',
                'cr_id',
                'cr_data_compra',
                'cr_total_valor',
                'fp_descricao'
            ));

            if (get_parameter('agrupar')) {
                $this->db->select_sum('cr_total_valor');
                $this->db->group_by('cr_id_cd');
            }

            if (get_parameter('ni')) {
                $this->db->where('cr_id_distribuidor', get_parameter('ni'));
            }

            $produtos = $this->db->join('formas_pagamento', 'fp_id=cr_forma_pgt')->join('cd', 'cd_id=cr_id_cd')->order_by('cr_data_compra')->where('cr_pago', 1)->get('compras_fabrica')->result();


            $data['produtos_encontrados'] = count($produtos);

            $data['produtos'] = $this->paginacao->rows($produtos);
            $data['links'] = $this->paginacao->links();


            $this->load->view('relatorios/produtos/relatorio_venda_view', $data);
        } else if (get_parameter('tipo') == 2) {

            $this->db->where('co_data_compra >=', data_usa(get_parameter('de')) . " 00:00:00");
            $this->db->where('co_data_compra <=', data_usa(get_parameter('ate')) . " 23:59:59");

            if (get_parameter('forma_pagamento')) {
                $this->db->where('co_forma_pgt', $_GET['forma_pagamento']);
            }

            $this->db->select(array(
                'di_nome',
                'di_usuario',
                'co_id',
                'co_data_compra',
                'co_total_valor',
                'fp_descricao'
            ));


            if (get_parameter('agrupar')) {
                $this->db->select_sum('co_total_valor');
                $this->db->group_by('co_id_cd');
            }

            if (get_parameter('ni')) {
                $this->db->where('co_id_distribuidor', get_parameter('ni'));
            }

            $produtos = $this->db->join('formas_pagamento', 'fp_id=co_forma_pgt')->join('distribuidores', 'di_id=co_id_distribuidor')->order_by('co_data_compra')->where('co_pago', 1)->where('co_entrega', 1)->get('compras')->result();


            $data['produtos_encontrados'] = count($produtos);

            $data['produtos'] = $this->paginacao->rows($produtos);
            $data['links'] = $this->paginacao->links();


            $this->load->view('relatorios/produtos/relatorio_venda2_view', $data);
        }
    }

    public function ver_relatorio_vendas() {

//Vendas distribuidor
        $data['c'] = $this->db->join('distribuidores', 'di_id=co_id_distribuidor')->join('cidades', 'ci_id=di_cidade')->join('estados', 'es_id=di_uf')->join('compra_situacao', 'st_id=co_situacao')->join('formas_pagamento', 'fp_id=co_forma_pgt')->order_by('co_data_compra')->where('co_pago', 1)->where('co_entrega', 1)->where('co_id', $this->uri->segment(3))->get('compras')->row();

        $this->load->view('relatorios/produtos/ver_relatorio_venda_view', $data);
    }

    public function creditos_vendido() {
        $data['pagina'] = strtolower(__CLASS__) . "/relatorios_" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function depositos() {

        $data['pagina'] = strtolower(__CLASS__) . "/relatorios_" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function deposito_unico() {
        $this->load->view('relatorios/relatorios_deposito_unico_view');
    }

    public function relatorio_celular() {

        $data['celular'] = $this->db->get('distribuidores')->result();
        $this->load->view('relatorios/relatorios_celulares_view', $data);
    }

    public function relatorio_email() {

        $data['email'] = $this->db->where('di_excluido !=1')
                ->join('distribuidor_ligacao', 'li_id_distribuidor=di_id')
                ->group_by('di_email')
                ->get('distribuidores')
                ->result();

        $this->load->view('relatorios/relatorios_email_view', $data);
    }

    public function relatorio_folha_pagamento() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function mostrar_relatorio_folha_pagamento() {

        $data['distribuidores'] = '';

//PARTE FIXA DA QUERY
        $this->db->select("di_id, di_usuario, di_nome,di_conta_nome,di_conta_cpf,di_conta_titular2,di_conta_cpf2, cpd_conta_distribuidor, di_cpf, cdp_data, ba_nome, di_conta_agencia, di_conta_agencia2, di_conta_numero, di_conta_numero2, di_conta_operacao, di_conta_operacao2, di_fone1, di_fone2, cdp_valor", 'false')
                ->join('conta_deposito', 'di_id = cdp_distribuidor', 'left')
                ->join('bancos', 'di_conta_banco = ba_id', 'left')
                ->where('cdp_id NOT IN(select ex_id_conta_bonus from conta_extorno)')
                ->where('cdp_status', 0);
//APARTIR DO DIA X
        if ($this->input->post('de')) {
            $this->db->where('cdp_data >=', data_usa($this->input->post('de')));
        }
//ATE O DIA Y
        if ($this->input->post('ate')) {
            $this->db->where('cdp_data <=', data_usa($this->input->post('ate')));
        }
//DO USUARIO FULANO
        if ($this->input->post('di_usuario')) {
            $this->db->like('di_usuario', $this->input->post('di_usuario'));
        }
//DO BANCO BELTRANO
        if ($this->input->post('ba_id')) {
            $this->db->where('ba_id', $this->input->post('ba_id'));
        }
//FINALIZA A QUERY
        $depositos = $this->db->get('distribuidores')->result();
        $data['depositos'] = $depositos;
        $this->load->view(strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__)) . "_view", $data);
    }

    public function relatorio_usuario_rede() {
        $data['distribuidores'] = array();
        $data['paginacao'] = '';
        $data['paginacao'] = '';
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function relatorio_usuario_rede_buscar() {

        $data['distribuidores'] = array();
        if (get_parameter('name')) {

            $distribuidor = $this->db->select(array('di_id'))
                            ->where('di_usuario', get_parameter('name'))
                            ->get('distribuidores', 1)->row();

            if (count($distribuidor)) {

                $distribuidores = $this->db
                                ->query("
SELECT sql_cache di_id,di_nome,di_usuario,di_fone1,di_fone2,
di_cidade,di_email,di_usuario_patrocinador,
(select (sum(cb_credito) - sum(cb_debito))
from conta_bonus where cb_distribuidor=di_id) as di_saldo 
FROM distribuidor_ligacao 
JOIN distribuidores ON di_id = li_id_distribuidor
WHERE li_no = {$distribuidor->di_id}
GROUP BY di_id
")->result();

                $data['distribuidores'] = $distribuidores;
            }
        }

        $data['pagina'] = strtolower(__CLASS__) . "/relatorio_usuario_rede";
        $this->load->view('home/index_view', $data);
    }

    /**
     * Relatório de quem comprou o voucher.
     */
    public function venda_voucher() {
//Inicio do filtros 
        $this->db->start_cache();

//Usuário cadastrado no sistema.
        if (get_parameter('di_usuario')) {
            $this->db->where('di_usuario', get_parameter('di_usuario'));
        }

//CPF cadastrado no sistema.
        if (get_parameter('di_cpf')) {
            $this->db->where('di_cpf', get_parameter('di_cpf'));
        }

//Se o a compra do voucher ta pago
        if (get_parameter('co_pago') !== false) {
            $this->db->where('co_pago', get_parameter('co_pago'));
        }

//Se o voucher foi usuado (SE ARRUME PORQUE HOVER VOU-LHER USAR :p KK)
        if (get_parameter('status')) {
            $this->db->where('status', get_parameter('status'));
        }

//Data de inicial.
        if (get_parameter('de')) {
            $this->db->where('co_data_compra >=', get_parameter('de') . " 00:00:00");
        }

//Data de final. 
        if (get_parameter('ate')) {
            $this->db->where('co_data_compra <', get_parameter('ate') . " 59:59:59");
        }

        $this->db->stop_cache();


        $voucher = $this->db
                        ->where('co_evoucher', 1)
                        ->join('distribuidores', 'co_id_distribuidor= di_id')
                        ->join('compras_voucher', 'vo_id_compra=co_id', 'left')
                        ->get('compras')->result();

        $this->db->flush_cache();
        $data['vouchers'] = $voucher;
        $data['pagina'] = strtolower(__CLASS__)
                . "/relatorios_" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function relatorio_distribuidor_ponto_negativos() {
        set_time_limit(0);

        $this->db->query("DELETE  FROM registro_bonus_indireto_pagos WHERE pg_pontos < 0");

        /**
         * Relatório dos distribuidores com 
         * a pontuação negativa.
         */
        $data = array();
        $dis = array();

//pegando todos os distribuidores 
        $distribuidores = $this->db
                ->join('distribuidor_ligacao', 'di_id=li_id_distribuidor')
                ->join('registro_bonus_indireto_pagos', 'pg_distribuidor=di_id')
                ->group_by('di_id')
                ->get('distribuidores')
                ->result();

        $pontos = new PontosBonusBinario();
        foreach ($distribuidores as $key => $distribuidor) {
            $pontos->setDistribuidor($distribuidor);
            $prontosNegativos = 0;

            $pagos = $pontos->pontosPagos();
            $pontosDireita = ($pontos->direita() - $pagos);
            $pontosEsquerda = ($pontos->esquerda() - $pagos);
            $prontosNegativosDireita = 0;
            $prontosNegativosEsquerda = 0;

            echo $pontosEsquerda;
            echo "<br>";
            echo $pontosDireita;
            echo "<br>";
            echo $pagos;
            echo "<br>";
            echo $distribuidor->di_id;
            echo "----------------------------";

            $pontosNegativosALancar = 0;
            if ($pontosDireita <= $pontosEsquerda) {
                echo "<h4>Pontos Direita:{$pontosDireita} </h4>";
//                $dis[$key] = array('di_usuario' => $distribuidor->di_usuario, 'pontos' => $pontosDireita, 'd' => 1, 'e' => 0);
                $pontosNegativosALancar = $pontosDireita;
            } else {
                $pontosNegativosALancar = $pontosEsquerda;
            }

            if ($pontosNegativosALancar < 0) {
                echo "<br>" . $pontosNegativosALancar . " negativos";
                $this->db->insert('registro_bonus_indireto_pagos', array(
                    'pg_pontos' => $pontosNegativosALancar,
                    'pg_distribuidor' => $distribuidor->di_id,
                    'pg_data' => date('Y-m-d')
                ));
            } else {
                $APagar = $pontos->pontosAPagar();
                if ($APagar > 0) {
                    $this->db->insert('registro_bonus_indireto_pagos', array(
                        'pg_pontos' => $APagar,
                        'pg_distribuidor' => $distribuidor->di_id,
                        'pg_data' => date('Y-m-d')
                    ));
                }
            }
        }

//
//        $data['distribuidores'] = $dis;
//        $data['pagina'] = strtolower(__CLASS__) . "/relatorio_distribuidor_ponto_negativos";
//        $this->load->view('home/index_view', $data);
    }

    function get_num_rows($table) {
        $query = $this->db->get($table)->num_rows();
        return $query;
    }

    private function validateURL($URL) {
        $pattern = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

        if (preg_match($pattern, $URL)) {
            return true;
        } else {
            return false;
        }
    }

    public function salvar_logistica() {
        try {
            $url = count($_REQUEST) > 0 ? '?' . http_build_query(array_filter($_REQUEST)) : '';

            if (!$this->uri->segment(3)) {
                throw new Exception('Erro: Informe uma compra');
            }
            if (!$this->input->post('co_frete_codigo')) {
                throw new Exception('Erro: Informe o Código de Rastreio');
            }

            if (!$this->input->post('co_frete_transportadora')) {
                throw new Exception('Erro: Informe o nome da transportadora');
            }

            if (!$this->input->post('co_frete_link_transportadora')) {
                throw new Exception('Erro: Informe o link da transportadora');
            }
            if (!$this->validateURL($this->input->post('co_frete_link_transportadora'))) {
                throw new Exception('Erro: link informado é inválido');
            }

            $logistica = array(
                'co_id' => $this->uri->segment(3),
                'co_frete_codigo' => $this->input->post('co_frete_codigo'),
                'co_frete_transportadora' => $this->input->post('co_frete_transportadora'),
                'co_frete_link_transportadora' => $this->input->post('co_frete_link_transportadora')
            );

            //Salvando dados da logistica
            ComprasModel::salvarLogistica($logistica);
            set_notificacao(1, "Dados do transporte salvo com sucesso.");
            redirect(base_url('index.php/relatorios/relatorio_despachante' . $url));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/relatorios/relatorio_despachante' . $url));
        }
    }

    public function relatorio_despachante() {
        error_reporting(0);
        $data['pagina'] = strtolower(__CLASS__)
                . "/relatorios_" .
                strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->db->start_cache();

        //Situação do pedido
        if (get_parameter('co_id')) {
            $this->db->where('co_id', get_parameter('co_id'));
        }
        //Situação do pedido
        if (get_parameter('co_situacao')) {
            $this->db->where('co_situacao', get_parameter('co_situacao'));
        }
        //Situação do pedido
        if (get_parameter('co_id_produto_escolha_entrega')) {
            $this->db->where('co_id_produto_escolha_entrega', (get_parameter('co_id_produto_escolha_entrega') - 1));
        }

        //Usuário
        if (get_parameter('di_usuario')) {
            $this->db->where('di_usuario', get_parameter('di_usuario'));
        }

        if ($this->input->get('empresa')) {
            $this->db->where('di_empresa', $this->input->get('empresa'));
        }

        //Produto 
        if (get_parameter('pr_id')) {
            $this->db->where('pr_id', get_parameter('pr_id'));
        }

        //Agências.
        if (get_parameter('pa_id')) {
            $this->db->where('pa_id', get_parameter('pa_id'));
        }

        //Data de inicial.
        if (get_parameter('de')) {
            $this->db->where('co_data_compra >=', data_to_usa(get_parameter('de')) . " 00:00:00");
        } else {
            if (!get_parameter('co_id')) {
                $this->db->where('co_data_compra >=', data_to_usa('01' . date('/m/Y')) . " 00:00:00");
            }
        }

        //Data de final. 
        if (get_parameter('ate')) {
            $this->db->where('co_data_compra <', data_to_usa(get_parameter('ate')) . " 59:59:59");
        } else {
            if (!get_parameter('co_id')) {
                $this->db->where('co_data_compra <', data_to_usa(date('t/m/Y')) . " 00:00:00");
            }
        }

        $page = '';
        $page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $total_pagina = isset($_REQUEST['totalpagina']) ? $_REQUEST['totalpagina'] : 25;

        if (isset($_REQUEST['totalpagina']) && $_REQUEST['totalpagina'] == 'todos') {
            $total_pagina = null;
        }

        $this->db->stop_cache();
        $responta_relatorio = $this->db->where('co_pago', 1)
                        ->where('co_ativacao_mensal ', 0)
                        ->join('produtos_comprados', 'pm_id_produto=pr_id')
                        ->join('compras', 'pm_id_compra=co_id')
                        ->join('distribuidores', 'co_id_distribuidor= di_id')
                        ->join('planos', 'pa_id=co_id_plano', 'left')
                        ->join('distribuidor_pessoa_juridica', 'dpj_id_distribuidor=co_id_distribuidor', 'left')
                        ->group_by('co_id')
                        ->order_by('co_id', 'desc')
                        ->get('produtos', $total_pagina, $page)->result();

        $quantidade = $this->db->where('co_pago', 1)
                        ->where('co_ativacao_mensal ', 0)
                        ->join('produtos_comprados', 'pm_id_produto=pr_id')
                        ->join('compras', 'pm_id_compra=co_id')
                        ->join('distribuidores', 'co_id_distribuidor= di_id')
                        ->join('planos', 'pa_id=co_id_plano', 'left')
                        ->join('distribuidor_pessoa_juridica', 'dpj_id_distribuidor=co_id_distribuidor', 'left')
                        ->group_by('co_id')
                        ->get('produtos', $total_pagina, $page)->result();

        
        $this->db->flush_cache();
        $this->load->library('paginacao');
        $this->paginacao->por_pagina($total_pagina);

        $this->load->library('pagination');
        $this->pagination->initialize(array(
            'base_url' => base_url('index.php/relatorios/relatorio_despachante'),
            'total_rows' => $quantidade->total,
            'per_page' => $total_pagina,
            'full_tag_open' => '<div  class="pagination pagination-centered" ><ul>',
            'full_tag_close' => '</ul></div>',
            'prev_link' => '&laquo;',
            'prev_tag_open' => '<li>',
            'prev_tag_close' => '</li>',
            'last_tag_open' => '<li>',
            'last_tag_close' => '</li>',
            'first_tag_open' => '<li>',
            'first_tag_close' => '</li>',
            'next_link' => '&raquo;',
            'last_link' => 'Ultima',
            'first_link' => 'Primeira',
            'next_tag_open' => '<li>',
            'next_tag_close' => '</li>',
            'cur_tag_open' => '<li class="active"><a href="#">',
            'cur_tag_close' => '</a></li>',
            'num_tag_open' => '<li>',
            'num_tag_close' => '</li>',
            'suffix' => '?' . urldecode(http_build_query(array_filter($_REQUEST)))
        ));

        $data['total_registro'] = $quantidade->total;
        $data['paginacao'] = $this->pagination->create_links();
        $data['responta_relatorio'] = $responta_relatorio;
        $data['de'] = isset($_REQUEST['de']) && !empty($_REQUEST['de']) ? $_REQUEST['de'] : '01' . date('/m/Y');
        $data['ate'] = isset($_REQUEST['ate']) && !empty($_REQUEST['ate']) ? $_REQUEST['ate'] : date('t/m/Y');
        $this->load->view('home/index_view', $data);
    }

    public function xlsRelatorioDespacho($responta_relatorio = '') {

        $this->db->start_cache();

        //Situação do pedido
        if (isset($_REQUEST['co_situacao']) && !empty($_REQUEST['co_situacao'])) {
            $this->db->where('co_situacao', get_parameter('co_situacao'));
        }

        //Usuário
        if (get_parameter('di_usuario')) {
            $this->db->where('di_usuario', get_parameter('di_usuario'));
        }
        
        if ($this->input->get('empresa')) {
            $this->db->where('di_empresa', $this->input->get('empresa'));
        }

        //Produto 
        if (get_parameter('pr_id')) {
            $this->db->where('pr_id', get_parameter('pr_id'));
        }

        //Agências.
        if (get_parameter('pa_id')) {
            $this->db->where('pa_id', get_parameter('pa_id'));
        }

        //Data de inicial.
        if (get_parameter('de')) {
            $this->db->where('co_data_compra >=', data_to_usa(get_parameter('de')) . " 00:00:00");
        } else {
            $this->db->where('co_data_compra >=', data_to_usa('01' . date('/m/Y')) . " 00:00:00");
        }

        //Data de final. 
        if (get_parameter('ate')) {
            $this->db->where('co_data_compra <', data_to_usa(get_parameter('ate')) . " 59:59:59");
        } else {
            $this->db->where('co_data_compra <', data_to_usa(date('t/m/Y')) . " 00:00:00");
        }

        $page = '';
        $page = $this->uri->segment(3) ? $this->uri->segment(3) - 1 : 0;
        $total_pagina = isset($_REQUEST['totalpagina']) ? $_REQUEST['totalpagina'] : 25;

        if (isset($_REQUEST['totalpagina']) && $_REQUEST['totalpagina'] == 'todos') {
            $total_pagina = null;
        }

        $this->db->stop_cache();

        $responta_relatorio = $this->db->where('co_pago', 1)
                        ->join('produtos_comprados', 'pm_id_produto=pr_id')
                        ->join('compras', 'pm_id_compra=co_id')
                        ->join('distribuidores', 'co_id_distribuidor= di_id')
                        ->join('planos', 'pa_id=co_id_plano', 'left')
                        ->group_by('co_id')
                        ->get('produtos', $total_pagina, $page)->result();

        $this->db->flush_cache();


        ob_start();
        echo <<<EOD
        <table class="table table-bordered table-hover" width="100%" style="resize:both; overflow:auto;">
            <thead>
                <tr>
                    <th width='3%'>Nº Pedido</th>
                    <th width='5%'>Situação</th>
                    <th width='5%'>Data Venda</th>
                    <th width='5%'>Produto</th>
                    <th width='5%'>Produto Escolhido</th>
                    <th width='5%'>Agência</th>
                    <th width='5%'>Niv</th>
                    <th width='5%'>Email</th>
                    <th width='5%'>Forma de Pagamento</th>
                    <th width='5%'>Status Login</th>
                    <th width='5%'>Motivo</th>
                    <th width='5%'>Usuário</th>
                    <th width='8%'>Nome</th>
                    <th width='8%'>Sobre nome</th>
                    <th width='8%'>Telefone</th>
                    <th width='8%'>Telefone Celular</th>
                    <th width='3%'>Tipo de Documento</th>
                    <th width='3%'>Número do Documento</th>
                    <th width='3%'>País</th>
                    <th width='3%'>Estado</th>
                    <th width='4%'>Cidade</th>
                    <th width='6%'>Rua</th>
                    <th width='8%'>Número</th>
                    <th width='10%'>Complemento</th>
                    <th width='5%'>Código Postal</th>
                </tr>
            </thead>
EOD;

        foreach ($responta_relatorio as $key => $relatorio) {
            $plano = DistribuidorDAO::getPlano($relatorio->di_id);
            if (count($plano) > 0) {

                $situacao = '';
                switch ($relatorio->co_situacao) {
                    case 7:
                        $situacao = 'label-important';
                        break;
                    case 6:
                        $situacao = 'label-important';
                        break;
                    case 3:
                        $situacao = 'label-important';
                        break;
                    case 3:
                        $situacao = 'label-important';
                        break;
                    case 5:
                        $situacao = 'label-important';
                        break;
                    case 1:
                        $situacao = 'label-important';
                        break;
                    case 8:
                        $situacao = 'label-success';
                        break;
                    case 2:
                        $situacao = 'label-success';
                        break;

                    default:
                        break;
                }
                echo <<<EOD
                    <tr>
                        <td><strong class="label {$situacao}">{$relatorio->co_id} </strong></td>
                        <td> 
EOD;
                if ($relatorio->co_situacao == 7 or $relatorio->co_situacao == 6 or $relatorio->co_situacao == 0 or $relatorio->co_situacao == 1) {
                    echo ' pendente';
                } else {
                    echo $this->db->where('st_id', $relatorio->co_situacao)->get('compra_situacao')->row()->st_descricao;
                }
                echo '
                        </td>
                        <td>' . date('d/m/y', strtotime($relatorio->co_data_compra)) . ' </td>
                        <td>' . $relatorio->pr_nome . '</td>
                        <td>' . DistribuidorDAO::getProdutoEscolhido($relatorio->co_id_produto_escolha_entrega) . '</td>
                        <td>' . $plano->pa_descricao . '</td>
                        <td>' . $relatorio->di_niv . '</td>
                        <td>' . $relatorio->di_email . '</td>
                        <td>' . $relatorio->co_forma_pgt_txt . '</td>
                        <td>' . ($relatorio->di_login_status == 1 ? 'Ativo' : 'Bloqueado') . '</td>
                        <td>';
                $motivo = $this->db->where('rdb_id_distribuidor', $relatorio->di_id)
                        ->order_by('rdb_id', 'desc')
                        ->get('registro_distribuidor_bloqueio', 1)
                        ->row();

                if (count($motivo) > 0 && $relatorio->di_login_status == 0) {
                    echo $motivo->rdb_mensagem;
                }

                echo '</td>
                        <td>' . $relatorio->di_usuario . '</td>
                        <td>' . $relatorio->di_nome . '</td>
                        <td>' . $relatorio->di_ultimo_nome . '</td>
                        <td>' . $relatorio->di_fone1 . '</td>
                        <td>' . $relatorio->di_fone2 . '</td>
                        <td>' . (empty($relatorio->di_tipo_documento) ? 'CPF' : $relatorio->di_tipo_documento) . '</td>
                        <td>' . (empty($relatorio->di_rg) ? $relatorio->di_cpf : $relatorio->di_rg) . '</td>
                        <td>' . DistribuidorDAO::getPais($relatorio->di_cidade)->ps_nome . '</td>
                        <td>' . DistribuidorDAO::getEstado($relatorio->di_uf)->es_nome . '</td>
                        <td>' . DistribuidorDAO::getCidade($relatorio->di_cidade)->ci_nome . '</td>
                        <td>' . $relatorio->di_endereco . '</td>
                        <td>' . $relatorio->di_numero . '</td>
                        <td>' . $relatorio->di_complemento . '</td>
                        <td>' . $relatorio->di_cep . '</td>
                        </tr>';
            }
        }

        echo ' </table>';

        $relatorio = ob_get_contents();
        ob_end_clean();
        //Greando xls   

        createXls::create($relatorio);
    }

    /**
     * Relatorio de despacho
     */
    public function alterar_situacao_relatorio_despacho() {

        $co_id = $this->uri->segment(3);

        if ($this->input->post('situacao') == 8 && empty($_POST['nome_responsavel'])) {
            set_notificacao(array(0 =>
                array(
                    'tipo' => 2,
                    'mensagem' => "Erro informe o nome do responsável.")));
            redirect(base_url('index.php/relatorios/relatorio_despachante'));
            return false;
        }


        //Relatório.
        if (!empty($co_id)) {

            $this->db->where('co_id', $co_id)->update('compras', array('co_situacao' => $this->input->post('situacao')));
        }
        //Se a situação foi da como enviada então.
        if (isset($_POST['nome_responsavel']) && !empty($_POST['nome_responsavel'])) {
            $this->db->insert('compras_situacao_reponsavel', array(
                'csr_id_compra' => $co_id,
                'csr_id_situacao' => $_POST['situacao'],
                'csr_nome_responsavel' => $_POST['nome_responsavel']
            ));
        }

        set_notificacao(array(0 =>
            array(
                'tipo' => 1,
                'mensagem' => "Sucesso.")));
        redirect(base_url('index.php/relatorios/relatorio_despachante'));
    }

    public function recebimento_produto() {
        $relatorios = $this->db->where('co_confirmou_recebimento = 1 or co_nao_recebeu_produto=1')
                        ->join('compras', 'co_id_distribuidor= di_id')
                        ->get('distribuidores')->result();
        ob_start();
        echo "<table>";
        echo "<tr>";
        echo "<th>Usuário</th>";
        echo "<th>Email</th>";
        echo "<th>Agência</th>";
        echo "<th>Status</th>";
        echo "</tr>";

        foreach ($relatorios as $key => $relatorio_value) {
            echo "<tr>";
            echo "  <td>{$relatorio_value->di_usuario}</td>";
            echo "  <td>{$relatorio_value->di_email}</td>";
            echo "  <td>" . ($relatorio_value->co_confirmou_recebimento == 1 ? "Produto foi Recebindo" : ($relatorio_value->co_nao_recebeu_produto == 1 ? "Não recebeu o produto" : "")) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        $relatorio = ob_get_contents();
        ob_end_clean();
        //Greando xls   
        createXls::create($relatorio, 'relatorio_de_recebimento_');
    }

}

?>
