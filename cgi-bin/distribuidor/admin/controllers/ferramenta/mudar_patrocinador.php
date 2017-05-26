<?php

class mudar_patrocinador extends CI_Controller {

    public function index() {
        $rede = new Rede();
        $rede->realocar(208,207);
    }

}
