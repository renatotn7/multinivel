<?php

class Verificacao extends CI_Controller {

    public function links() {

        $this->load->library('pontos');
        $p = new Pontos();

        $dis = $this->db->where('di_id', 407)->get('distribuidores')->row();
        $p->carregar_distribuidor($dis);

        echo 'E: ' . $p->get_pontos_esquerda_formatado() . '<br>';
        echo 'D: ' . $p->get_pontos_direita_formatado() . '<br>';
        echo 'Menor: ' . $p->get_pontos_perna_menor() . '<br>';
        echo 'Pagos: ' . $p->get_pontos_pagos() . '<br>';
        exit;
        $data['pagina'] = 'verificacao/links';
        $this->load->view('home/index_view', $data);
    }

    public function pontos_pagos() {
        $data['pagina'] = 'verificacao/pontos_pagos';
        $this->load->view('home/index_view', $data);
    }

    public function relatorio_dia() {
        $data['pagina'] = 'verificacao/relatorio_dia';
        $this->load->view('home/index_view', $data);
    }

}
