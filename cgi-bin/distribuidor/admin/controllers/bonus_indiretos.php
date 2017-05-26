<?php

/**
 * Description of bonus_indiretos
 *
 * @author Ronyldo12
 */
class bonus_indiretos extends CI_Controller {

    public function index() {
        $this->ganhos();
    }

    public function ganhos() {
        $redeLinear = new redeLinear();
        $data['redeLinear'] = $redeLinear->getRede(get_user()->di_id);

        $data['pagina'] = 'bonus_indiretos/ganhos';
        $this->load->view('home/index_view', $data);
    }

}
