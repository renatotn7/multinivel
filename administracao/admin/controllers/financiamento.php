<?php

class financiamento extends CI_Controller{
 
    public function financiamentos(){
          autenticar();
        $data ['pagina'] = 'financiamento/financiamento';
        $this->load->view ( 'home/index_view', $data );
    }
    
    public function salvar(){
          autenticar();
       if($this->input->post('cp_id_pais')){
           
           //Auditoria geral.
//           auditoriaGeral::insert($_POST,'config_pais_parcelamento');
           
           $this->db->insert('config_pais_parcelamento', 
                   valida_fields('config_pais_parcelamento', $_POST));
           set_notificacao(1,"Salvo com sucesso!");
       }else{
           set_notificacao(2,"Erro ao salvar!");
       }
       redirect(base_url('index.php/financiamento/financiamentos'));
    }
    
    public function editar(){
          autenticar();
          
       if($this->input->post('cp_id')){
          
           
           //Auditoria geral.
//           auditoriaGeral::update('cp_id',$_POST,'config_pais_parcelamento');
           
           $this->db->where('cp_id',$this->input->post('cp_id'))->update('config_pais_parcelamento', 
                   valida_fields('config_pais_parcelamento', $_POST));
           set_notificacao(1,"Alterado com sucesso!");
       }else{
           set_notificacao(2,"erro oa alterar!");
       } 
       redirect(base_url('index.php/financiamento/financiamentos'));
    }
    
    public function remover(){
          autenticar();
      if(get_parameter('id')){
          
           //Auditoria geral.
//           auditoriaGeral::delete('cp_id',get_parameter('id'),'config_pais_parcelamento');
           
           $this->db->where('cp_id',  get_parameter('id'))->delete('config_pais_parcelamento');
           set_notificacao(1,"Removido com sucesso!");
       }else{
           set_notificacao(2,"erro ao Remover!");
       } 
       redirect(base_url('index.php/financiamento/financiamentos'));
    }
}
