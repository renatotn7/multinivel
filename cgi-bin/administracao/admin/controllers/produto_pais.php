<?php

class produto_pais extends CI_Controller {

    public function index() {
        
        $id_pais = 0;
        
        if($this->input->get('ident')){
          $id_pais =  $this->input->get('ident');   
        }
        
        $data['produtos'] = produtoPaisModel::getProdutoPais($id_pais);
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data); 
    }

    public function add_combo_pais() {
        
        try {
            if(!$this->input->post('prv_id_produto')){
                throw new Exception('Erro: Selecione um produto.'); 
            }
            
            if(!$this->input->post('prv_id_pais')){
                throw new Exception('Erro: Selecione um pais.'); 
            }
            
            if(!$this->input->post('prv_valor')){
                throw new Exception('Erro: informe um valor.'); 
            }
            
            $dados = $this->input->post();
            if(!produtoPaisModel::addProduto($dados)){
                throw new Exception('Erro: Não foi possível incluir o produto.');
            }
            
            set_notificacao(1,'Produto Incluído com sucesso.');
            redirect(base_url('index.php/produto_pais'));
            
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/produto_pais'));
        }
    }

    public function editar_combo_pais() {
        try {
            
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/produto_pais'));
        }
    }

    public function atualizar_combo_pais() {
        try {
            
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/produto_pais'));
        }
    }

    public function remover_combo_pais() {
        try {
            
            if(!$this->input->get('ident')){
                throw new Exception('error: Não foi possível excluir');   
             }
            
          if(!produtoPaisModel::removerProduto($this->input->get('ident'))){
              throw new Exception('error Não foi possível excluir');  
          }
          
            set_notificacao(1, 'Excluido com sucesso.');
            redirect(base_url('index.php/produto_pais'));
             
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/produto_pais'));
        }
    }

}
