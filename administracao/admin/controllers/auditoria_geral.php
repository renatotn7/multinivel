<?php
error_reporting(E_ALL);
set_time_limit(0);
ini_set('memory_limit', '1024M');
class auditoria_geral extends CI_Controller{

    public function index(){
        
        $data['auditorias']= auditoriaGeral::get_auditoria_geral();
        $data['pagina'] = strtolower(__CLASS__)."/".strtolower(str_ireplace(__CLASS__.'::','',__METHOD__));
        $this->load->view('home/index_view',$data);
    }
}