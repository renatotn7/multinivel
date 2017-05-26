<?php
class combopacotes extends CI_Controller{
    public function index(){
        $combo='';
        if($this->input->get('ident')){
          $combo = combopacoteModel::getComboPacote($this->input->get('ident'));
        }
        $data['comboPacotes'] = combopacoteModel::getComboPacotes();
        $data['planos'] = combopacoteModel::getPlanos();
        $data['combo']  = $combo;
        $data['pagina'] = 'combopacotes/combopacotes';
        $this->load->view('home/index_view',$data);	 
    }
    
    public function addCombo(){
        try {
            
            if(!$this->input->post('pn_codigo')){
                throw new Exception('Informe um código Para o Combo/produto'); 
             }
             
            if(!$this->input->post('pn_descricao')){
                throw new Exception('Informe um nome para o Combo/produto'); 
             }
             
            if(!$this->input->post('pn_plano')){
                throw new Exception('Informe o plano relacionado com o Combo/produto'); 
             }
             
            //Inserido no banco o novo combo.
             combopacoteModel::criarCombo($this->input->post());
             
            set_notificacao(1,'Criado com sucesso.');
            redirect(base_url('/index.php/combopacotes'));
            
        } catch (Exception $exc) {
            set_notificacao(2,$exc->getMessage());
            redirect(base_url('/index.php/combopacotes'));
        }
            
    }
    
    public function removerCombo(){
       try {
            
            if(!$this->input->get('ident')){
                throw new Exception('Informe um código valido'); 
             }
         
            //Inserido no banco o novo combo.
             combopacoteModel::removerCombo($this->input->get('ident'));
             
            set_notificacao(1,'Excluido com sucesso.');
            redirect(base_url('/index.php/combopacotes'));
            
        } catch (Exception $exc) {
            set_notificacao(2,$exc->getMessage());
            redirect(base_url('/index.php/combopacotes'));
        }   
    }
    
    public function editarCombo(){
         try {
            
            if(!$this->input->get('ident')){
                throw new Exception('Informe um código válido.'); 
             }
         
             combopacoteModel::atualizarCombo($this->input->get('ident'),
             $this->input->post());
            set_notificacao(1,'Atualizado com sucesso.');
            redirect(base_url('/index.php/combopacotes'));
            
        } catch (Exception $exc) {
            set_notificacao(2,$exc->getMessage());
            redirect(base_url('/index.php/combopacotes'));
        }   
    }
    
    public function removerProdutoCombo(){
          try {
         
            if(!$this->input->get('indeCombo')){
                throw new Exception('Informe um código válido.'); 
             }
       
           combopacoteModel::removerProdutoCombo($this->input->get('indeCombo'));
             
            set_notificacao(1,'Atualizado com sucesso.');
            redirect(base_url('/index.php/combopacotes'));
            
        } catch (Exception $exc) {
            set_notificacao(2,$exc->getMessage());
            redirect(base_url('/index.php/combopacotes'));
        }   
    }
    
    public function addprodutocombo(){
         try {
         
            if(!$this->input->post('pn_id')){
                throw new Exception('Informe um código válido.'); 
             }
             
            if($this->input->post('pr_id')==""){
                throw new Exception('Selecione o(s) produto(s).'); 
             }
           
             
             foreach ($this->input->post('pr_id') as $produto) {
                 //Não deixa incluir os produto já incluiodo.
                if(!combopacoteModel::produtoexist($produto,$this->input->post('pn_id'))){ 
                 combopacoteModel::addProdutoCombo($produto,$this->input->post('pn_id')); 
                }else{
                      throw new Exception('ATENÇÃO: Alguns produtos não foi Incluídos por que já existem.'); 
                }
             }
             
            set_notificacao(1,'Atualizado com sucesso.');
            redirect(base_url('/index.php/combopacotes'));
            
        } catch (Exception $exc) {
            set_notificacao(2,$exc->getMessage());
            redirect(base_url('/index.php/combopacotes'));
        }   
    }
}

