<?php
class criar_pacotes_lang  extends CI_Controller{
    
    public function index(){
        
    $data['pagina'] = strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
    $this->load->view('home/index_view',$data);
    }
}