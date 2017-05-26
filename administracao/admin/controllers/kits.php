<?php

class kits extends CI_Controller {

    public function index() {
        $data['kits'] = kitModel::getKits();
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function addkits() {
        try {

            if (!$this->input->post('pr_nome')) {
                throw new Exception("Erro: informe um nome para o kit.");
            }

            $dados = array(
                'pr_nome' => $this->input->post('pr_nome'),
                'pr_valor' => $this->input->post('pr_valor')
            );

            if (!kitModel::criarKits($dados)) {
                ;
                throw new Exception("Erro: Criar um novo kit.");
            }

            set_notificacao(1, 'Cadastrado com Sucesso.');
            redirect(base_url('index.php/kits'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/kits'));
        }
    }

    public function removerProdutokits() {
        try {

            if (!$this->input->get('indeCombo')) {
                throw new Exception('Informe um código válido.');
            }

            kitModel::removerProdutoCombo($this->input->get('indeCombo'));

            //Calculando o valor do kit e atualiando o produto kit.
            $value_prod = 0.00;
            $produtos_kit = kitModel::getProdutoskits($this->input->get('indeKit'));
            
            if (count($produtos_kit) > 0) {
                foreach ($produtos_kit as $produtos_kit_value) {
                    $value_prod+= $produtos_kit_value->pr_valor * $produtos_kit_value->pc_quantidade;
                }
            }

            //Atualizando o produto.
            $produto_temkits = kitModel::getProdutoTemKit($this->input->get('indeKit'));

            if (count($produto_temkits) > 0) {
                produtoModel::atualizarProduto($produto_temkits->pr_id, array(
                    'pr_valor' => $value_prod
                ));
            }

            set_notificacao(1, 'Atualizado com sucesso.');
            redirect(base_url('/index.php/kits'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('/index.php/kits'));
        }
    }

    public function addProdutopKits() {
        try {

            if (!$this->input->post('pr_id_kit')) {
                throw new Exception("Erro: Kit não encontrado.");
            }

            if (!$this->input->post('pr_id')) {
                throw new Exception("Erro: Produto não encontrado.");
            }

            foreach ($this->input->post('pr_id') as $key => $produto) {
                //Não deixa incluir os produto já incluiodo.
                if (!kitModel::produtoexist($produto, $this->input->post('pr_id_kit'))) {
                    kitModel::addProdutoKits($produto, $this->input->post('pr_id_kit'), $this->input->post('pc_quantidade_' . $produto));
                } else {
                    throw new Exception('ATENÇÃO: Alguns produtos não foi Incluídos por que já existem.');
                }
            }

            //Calculando o valor do kit e atualiando o produto kit.
            $value_prod = 0.00;
            $produtos_kit = kitModel::getProdutoskits($this->input->post('pr_id_kit'));

            foreach ($produtos_kit as $produtos_kit_value) {
                $value_prod+= $produtos_kit_value->pr_valor * $produtos_kit_value->pc_quantidade;
            }

            //Atualizando o produto.
            $produto_temkits = kitModel::getProdutoTemKit($this->input->post('pr_id_kit'));

            if (count($produto_temkits) > 0) {
                produtoModel::atualizarProduto($produto_temkits->pr_id, array(
                    'pr_valor' => $value_prod
                ));
             
            }

            set_notificacao(1, 'Cadastrado com Sucesso.');
            redirect(base_url('index.php/kits'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/kits'));
        }
    }

    public function editarkits() {
        try {
            if (!$this->uri->segment(3)) {
                throw new Exception("Erro: Kit não encontrado.");
            }

            $data['kit'] = kitModel::getKits($this->uri->segment(3));
            $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
            $this->load->view('home/index_view', $data);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function atualizarKits() {
        try {
            if (!$this->uri->segment(3)) {
                throw new Exception("Erro: Kit não encontrado.");
            }

            if (!$this->input->post('pr_nome')) {
                throw new Exception("Erro: informe um nome para o kit.");
            }

            $dados = array(
                'pr_nome' => $this->input->post('pr_nome'),
                'pr_id' => $this->uri->segment(3)
            );

            if (!kitModel::AtualizarKits($dados)) {
                throw new Exception("Erro: Ao atualizar o kit.");
            }

            set_notificacao(1, 'Atualizado com Sucesso.');
            redirect(base_url('index.php/kits'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/kits'));
        }
    }

    public function removerkits() {
        try {
            if (!$this->uri->segment(3)) {
                throw new Exception("Erro: Kit não encontrado.");
            }

            kitModel::removeKits($this->uri->segment(3));
            set_notificacao(1, 'Removido com Sucesso.');
            redirect(base_url('index.php/kits'));
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/kits'));
        }
    }

    /**
     * Calcula op valor do kit no momento que escolhe o kit no produto
     * via ajax
     * @throws Exception
     */
    public function get_valor_kits_ajax() {
        try {

            if (!$this->input->post('id_kit')) {
                throw new Exception('Error não foi possível achar o valor do kit');
            }
            $value_prod = 0.00;
            $produtos_kit = kitModel::getProdutoskits($this->input->post('id_kit'));
            foreach ($produtos_kit as $produtos_kit_value) {
                $value_prod+= $produtos_kit_value->pr_valor * $produtos_kit_value->pc_quantidade;
            }
            echo json_encode(array('data' => number_format($value_prod, 2), 'error' => 0));
        } catch (Exception $exc) {
            echo json_encode(array('data' => $exc->getMessage(), 'error' => 1));
        }
    }

}
