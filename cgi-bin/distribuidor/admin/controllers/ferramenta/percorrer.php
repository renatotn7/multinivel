<?php

class percorrer extends CI_Controller {

    private $nos_ids;

    public function index() {
        $this->caminho_distribuidor(6737);
        var_dump(count($this->nos_ids));
    }

    private function caminho_distribuidor($patrocinador) {
        
        $dis = $this->db
                        ->select(array('di_id'))
                        ->where('di_direita', $patrocinador)
                        ->or_where('di_esquerda', $patrocinador)
                        ->get('distribuidores')->row();
        var_dump($dis->di_id);

        if (count($dis) > 0) {
            $this->nos_ids[] = $dis->di_id;
            $this->caminho_distribuidor($dis->di_id);
        }
    }

}
