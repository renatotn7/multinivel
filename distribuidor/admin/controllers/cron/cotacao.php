<?php

class cotacao extends CI_Controller {

    public function index() {

        set_time_limit(0);
        $cambio = array();
        $moedas = array();

        //Atualiza somente os paizes que estão cadastrados nas cotações de cambio e país.s
        $moedas = $this->db->select('ps_id,ps_codigo_moeda')
                        ->join('moeda_cambio', 'camb_id_pais=ps_id')
                        ->get('pais')->result();


        if (count($moedas) == 0) {
            return false;
        }

        foreach ($moedas as $key => $moeda) {
            $cambio = funcoesdb::arrayToObject(atm::contacao_cambio($moeda->ps_codigo_moeda));

            if ($cambio->status != 0) {
                $this->db->where('camb_id_pais', $moeda->ps_id)->update('moeda_cambio', array(
                    'status' => $cambio->status,
                    'description' => $cambio->description
                ));
                
                continue;
            }

            $this->db->where('camb_id_pais', $moeda->ps_id)->update('moeda_cambio', array(
                'camb_valor' => $cambio->bid,
                'camb_experculacao' => $cambio->ask,
                'camp_data_ultima_atualizacao' => date('Y-m-d H:i:s'),
                'status' => $cambio->status,
                'description' => $cambio->description
            ));
        }
    }

}
