<?php

/**
 *  inserir_distribuidor_rede
 *
 * @author Ronildo Souza <ronyldo12@hotmail.com>
 */
class inserir_distribuidor_rede extends CI_Controller {

    public function index() {

        $distribuidores = $this->db->query("
            SELECT co_id_distribuidor FROM compras WHERE co_pago = 1 AND co_eplano = 1 AND co_id_distribuidor IN(3578,3579,3580,3581,3582,3583,3584,3585,3586,3587,3588,3589,3590,3591,3592,3593,3594,3595,3596)
            GROUP BY co_id_distribuidor
            ")->result();

        foreach ($distribuidores as $distribuidor) {
            $this->load->library('rede');
            $this->rede->alocar($distribuidor->co_id_distribuidor);
        }
    }

}

?>
