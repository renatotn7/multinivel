<?php
class configuracao_pl extends CI_Controller{
   
    public function conf_pl(){
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }
    
    public function salvar()
    {
           foreach ($_POST['bpl_id'] as $key => $value) {
            $this->db->where('bpl_id',$value)
                     ->update('bonus_pl_por_pais',array(
                         'bpl_id_pais'=>$_POST['pais_'.$key],
                         'bpl_valor'=>$_POST['valor_pl_'.$key]
                     )); 
             }
        set_notificacao(1, 'Atualizado com sucesso.');
        redirect(base_url('index.php/configuracao_pl/conf_pl'));
    }
    
    public function remover(){
        if(isset($_GET['cod']) ){
            
             $pais=$this->db->where('bpl_id',$_GET['cod'])
                            ->get('bonus_pl_por_pais')->row();
             
            if(count($pais)==0){
                set_notificacao(2, 'O País Não consta na lista');
                redirect(base_url('index.php/configuracao_pl/conf_pl'));
             }
             
             $this->db->where('bpl_id',$_GET['cod'])->delete('bonus_pl_por_pais');
              set_notificacao(1, 'Removido com Sucesso.');
             redirect(base_url('index.php/configuracao_pl/conf_pl'));
        }
    }
    
    public function adcionar()
    {
        $pais=$this->db->where('bpl_id_pais',$_POST['pais'])->get('bonus_pl_por_pais')->row();
        
        if(count($pais)>0){
            set_notificacao(2, 'O País escolhido já consta na lista.');
             redirect(base_url('index.php/configuracao_pl/conf_pl'));
        }
        
       if(isset($_POST['pais']) && empty($_POST['pais'])){
             set_notificacao(2, 'Selecione um País.');
             redirect(base_url('index.php/configuracao_pl/conf_pl'));
       }
       if(isset($_POST['valor_pl']) && empty($_POST['valor_pl'])){
             set_notificacao(2, 'Selecione um valor para País.');
              redirect(base_url('index.php/configuracao_pl/conf_pl'));
       }
       
        $this->db->insert('bonus_pl_por_pais',array(
                         'bpl_id_pais'=>$_POST['pais'],
                         'bpl_valor'=>$_POST['valor_pl']
                     ));
        
        set_notificacao(1, 'Criado com sucesso.');
        redirect(base_url('index.php/configuracao_pl/conf_pl'));
    }
}
