<?php
error_reporting(E_ALL);
set_time_limit(0);

class verificar_apotos_receber_pl extends CI_Controller {

    public function index() {
        CHtml::berginTime();
         ob_start();
         
        $distribuidores = $this->db
                ->select('di_id,di_email,di_usuario')
                ->where('di_excluido', 0)
                ->where('co_pago', 1)
                ->join('compras', 'di_id=co_id_distribuidor')
                ->group_by('di_id ')
                ->get('distribuidores')
                ->result();

        //Percorrendo todos os distribuidores.
        foreach ($distribuidores as $key => $distribuidor) {
            
             if(!$this->deve_registrar_pagar($distribuidor->di_id)){
                 continue;
             }
             
            $status = funcoesdb::arrayToObject(atm::status_universidade($distribuidor));
            
            $this->db->insert('aptos_receber_pl', array(
                'apt_semana' => date('W'),
                'apt_ano' => date('Y'),
                'apt_intervalor_semana' => date('Y-m-d', strtotime('next Monday')) . '&' . date('Y-m-d', strtotime('last Monday')),
                'apt_id_distribuidor' => $distribuidor->di_id,
                'apt_status' => $status->status,
                'apt_descricao' => $status->description,
                'apt_apto_receber_pl' => $status->status == 0 ? $status->statuschallenge : false,
            ));
        }
        
        echo CHtml::endTime();
        $registro = ob_get_contents();
            ob_end_clean();   
            echo $registro;
       CHtml::logexec('verificar_apotos_receber_pl_rodou_em_data_'.date('d_m_Y'),$registro.' em '.date('d_m_Y_H_s_i'),'verificar_apotos_receber_pl');  
    }

    public function deve_registrar_pagar($idDistribuidor = 0) {

        $registro = $this->db
                        ->select('apt_id_distribuidor')
                        ->where('apt_id_distribuidor', $idDistribuidor)
                        ->where('apt_semana', date('W'))
                        ->where('apt_ano', date('Y'))
                        ->get('aptos_receber_pl')->row();

        if (count($registro) > 0) {
            return false;
        }
        
        return true;
    }

}
