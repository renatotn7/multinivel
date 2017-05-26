<?php

class Produtos extends CI_Controller {

    function index() {

        $this->load->library('paginacao');
        $this->paginacao->por_pagina(30);


        if (get_parameter('nome')) {
            $this->db->like('pr_nome', get_parameter('nome'));
        }

        if (get_parameter('cat')) {
            $this->db->where('ca_id', get_parameter('cat'));
        }

        $produtos = $this->db
                        ->join('categorias_produtos', 'categorias_produtos.ca_id = produtos.pr_categoria')
                        ->order_by('pr_estoque')
                        ->get('produtos')->result();

        $data['produtos'] = $this->paginacao->rows($produtos);
        $data['links'] = $this->paginacao->links();

        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function configurar_kit() {


        if ($this->input->post('pc_categoria')) {
            $this->db->insert('produtos_kit_categoria', array(
                'pc_categoria' => $this->input->post('pc_categoria'),
                'pc_quantidade' => $this->input->post('pc_quantidade'),
                'pc_id_kit' => $this->uri->segment(3)
            ));
        }


        if (isset($_GET['ex'])) {
            $this->db->where('pc_id', $_GET['ex'])->delete('produtos_kit_categoria');
            redirect(current_url());
            exit;
        }

        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function remover_imagem() {

        try {

            $path = realpath(dirname(dirname(dirname(__FILE__))));
            $conf_pasta = $path.'/public/imagem/uploads-produtos/';
            $conf_pasta_thumb = $path.'/public/imagem/uploads-produtos/thumbs/';

            if (!$this->input->post('img_id')) {
                throw new Exception('Erro: Não foi possível remover a imagem.');
            }

            $imagem = $this->db->where('img_id', $this->input->post('img_id'))
                            ->get('imagem_produto')->row();

            unlink($conf_pasta . $imagem->img_nome);
            unlink($conf_pasta_thumb . $imagem->img_nome);

            $this->db->where('img_id', $this->input->post('img_id'))->delete('imagem_produto');
            set_notificacao(1, 'Imagem removida com sucesso.');
            redirect(base_url('index.php/produtos'));
            
        } catch (Exception $exc) {
            set_notificacao(1, $exc->getMessage());
            redirect(base_url('index.php/produtos'));
        }
    }

    public function get_imagem() {
        $this->load->view('produtos/imagen_view');
    }

    public function get_imagens() {
        $this->load->view('produtos/imagens_view');
    }

    public function editar_produto() {

        /**
         *  Atualização do produto fluxo.
         *  
         * 1 - Checa a permissão 
         * 2 - Atualiza o produto ou as informações alterada.
         */
        permissao('produtos', 'editar', get_user(), true);

        if ($this->input->post('pr_nome')) {
            unset($_POST['x']);
            unset($_POST['y']);

            //Formatar

            $_POST['pr_valor'] = $_POST['pr_valor'] == '' ? '0.0' : $_POST['pr_valor'];
            $_POST['pr_desconto_distribuidor'] = $_POST['pr_desconto_distribuidor'] == '' ? '0.0' : $_POST['pr_desconto_distribuidor'];
            $_POST['pr_desconto_cd'] = $_POST['pr_desconto_cd'] == '' ? '0.0' : $_POST['pr_desconto_cd'];


            $_POST['pr_peso'] = number_format($_POST['pr_peso'], 2, '.', '');
            $_POST['pr_valor'] = number_format($_POST['pr_valor'], 2, '.', '');


            if ($this->db->where('pr_id', $this->uri->segment(3))->update('produtos', $_POST)) {
                set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => 'Produto atulizado com sucesso!')));
                redirect(base_url('index.php/produtos'));
                exit;
            } else {
                set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => 'Ocorreu um erro ao atualiza o produto.')));
                redirect(base_url('index.php/produtos'));
                exit;
            }
        }


        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    function removerCategoria() {
        try {
            if (!$this->input->get('ex')) {
                throw new Exception('Erro: Não foi possível excluir a categoria especificada.');
            }

            if (!permissao('produtos', 'excluir', get_user(), true)) {
                throw new Exception('Erro: Você não tem permissão para execultar essa ação.');
            }

            $this->db->where('ca_id', $this->input->get('ex'))
                    ->delete('categorias_produtos');

            set_notificacao(1, "Produto excluido com sucesso.");
            redirect(base_url('index.php/produtos/categorias'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/produtos/categorias'));
        }
    }

    function renderCategoria($data = array()) {
        $data['pagina'] = strtolower(__CLASS__) . "/categorias";
        $this->load->view('home/index_view', $data);
    }

    function categorias() {
        $data = array();
        try {
            if (!permissao('categoria_produtos', 'visualizar', get_user(), true)) {
                throw new Exception('Erro: Você não tem permissão para execultar essa ação.');
            }

            $data['categorias'] = categoriaModel::getCategorias();
            $this->renderCategoria($data);
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            $data['categorias'] = categoriaModel::getCategorias();
            $this->renderCategoria($data);
        }
    }

    function editar_categoria() {
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    function adicionar_categoria() {

        permissao('categoria_produtos', 'adicionar', get_user(), true);
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function salvarCategoria() {

        try {
            if (!permissao('categoria_produtos', 'editar', get_user(), true)) {
                throw new Exception('Erro: Você não tem permissão para executar essa ação.');
            }
            if (!$this->input->post('ca_descricao')) {
                throw new Exception('Erro: Informe um nome para categoria.');
            }

            $dados = array(
                'ca_id' => ($this->uri->segment(3) != "" ? $this->uri->segment(3) : ''),
                'ca_descricao' => $this->input->post('ca_descricao'),
                'ca_pai' => $this->input->post('ca_pai')
            );

            if ($this->uri->segment(3) != "") {
                categoriaModel::AtualizarCategoria($dados);
            } else {
                categoriaModel::criarCategoria($dados);
            }

            set_notificacao(1, "Atualizado com sucesso.");
            redirect(base_url("index.php/produtos/categorias"));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url("index.php/produtos/categorias"));
        }
    }

    function adicionar() {

        permissao('produtos', 'adicionar', get_user(), true);

        if ($this->input->post('pr_nome')) {
            unset($_POST['x']);
            unset($_POST['y']);

            //Formatar

            $_POST['pr_peso'] = $this->input->post('pr_peso') ? number_format($this->input->post('pr_peso'), 2, '.', '') : 0;
            $_POST['pr_valor'] = $this->input->post('pr_valor') ? number_format($this->input->post('pr_valor'), 2, '.', '') : 0.0
            ;
            $_POST['pr_desconto_distribuidor'] = $this->input->post('pr_desconto_distribuidor') == '' ? '0.0' : $this->input->post('pr_desconto_distribuidor');
            $_POST['pr_desconto_cd'] = $this->input->post('pr_desconto_cd') == '' ? '0.0' : $_POST['pr_desconto_cd'];
            
            if ($this->db->insert('produtos', funcoesdb::valida_fields('produtos', $_POST))) {
                set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => 'Produto cadastrado com sucesso!')));
                redirect(base_url('index.php/produtos'));
                exit;
            } else {
                set_notificacao(array(0 => array('tipo' => 1, 'mensagem' => 'Ocorreu um erro ao cadastrar o produto.')));
                redirect(base_url('index.php/produtos'));
                exit;
            }
        }

        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function combo_produtos_padrao() {

        //id do plano
        $id_plano = $data['id_plano'] = $this->uri->segment(3);

        //Produtos disponiveis para o combo
        $data['produtosCombo'] = $this->db
                        ->where('pr_kit', 1)
                        ->where('pr_estoque >', 1)
                        ->get('produtos')->result();

        //Produtos selecionados para o combo
        $data['produtosSelecionado'] = $this->db
                ->join('produtos', 'pr_id=pn_produto')
                ->where('pn_plano', $id_plano)
                ->get('produtos_padrao_plano')
                ->result();

        //Plano
        $data['plano'] = $this->db->where('pa_id', $id_plano)->get('planos')->row();


        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function salvar_combo_padrao() {

        $produto = $this->db->where('pr_id', $this->input->post('produto'))->get('produtos')->row();

        $id_plano = $this->input->post('idplano');

        if (count($produto) > 0 && $id_plano > 0) {
            $this->db->insert('produtos_padrao_plano', array(
                'pn_plano' => $id_plano,
                'pn_produto' => $produto->pr_id,
                'pn_data' => date('Y-m-d H:i:s')
            ));
        }

        redirect(base_url('index.php/produtos/combo_produtos_padrao/' . $id_plano));
    }

    public function remover_protudo() {

        $this->db
                ->where('pr_id', $this->uri->segment(3))
                ->delete('produtos');

        redirect(base_url('index.php/produtos/'));
    }

    public function remover_produto_padrao() {

        $this->db
                ->where('pn_id', $this->uri->segment(3))
                ->delete('produtos_padrao_plano');

        redirect(base_url('index.php/produtos/combo_produtos_padrao/' . $this->uri->segment(4)));
    }

    /*
      | -------------------------------------------------------------------------
      | FIM DO CONTROLLER
      | -------------------------------------------------------------------------
     */
}

?>