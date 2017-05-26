<?php
class bonus_perdido extends CI_Controller{
    
    public function bonus()
    {
       $bonus_perdido = $this->db->query("select * from conta_bonus_perdido "
               . "join distribuidores on di_id = cb_distribuidor "
               . "join conta_bonus_tipo on cbt_id= cb_tipo"
               . " where "
               . "cb_data_hora >='2014-03-29 00:00:00' "
               . "and cb_devolvido !=1"
               . " and cb_data_hora <='2014-03-29 23:59:59'"
               . "group by cb_distribuidor")->result();

       $data['bonus_perdidos'] = $bonus_perdido;
       $data['pagina'] = strtolower(__CLASS__).
		 "/bonus";
		 $this->load->view('home/index_view',$data);
    }
    
    public function liberar_bonus(){
        
        if(!isset($_GET['cb_id']) && empty($_GET['cb_id'])){
            $this->bonus();
            return false;
        }

        
          $bonus_perdido = $this->db->query("select * from conta_bonus_perdido "
               . "join distribuidores on di_id = cb_distribuidor "
               . "join conta_bonus_tipo on cbt_id= cb_tipo"
               . " where "
               . "cb_data_hora >='2014-03-29 00:00:00' "
               . " and cb_data_hora <='2014-03-29 23:59:59'"
               . "and cb_id= {$_GET['cb_id']}")->row();

      //Inserido os bonus na conta bonus
       $dadosContaBonus = array(
                'cb_id' => NULL,
                'cb_distribuidor' =>$bonus_perdido->cb_distribuidor,
                'cb_compra' => $bonus_perdido->cb_compra,
                'cb_descricao' =>$bonus_perdido->cb_descricao ,
                'cb_credito' =>$bonus_perdido->cb_credito ,
                'cb_debito' => $bonus_perdido->cb_debito ,
                'cb_tipo' => $bonus_perdido->cb_tipo ,
                'cb_data_hora' => $bonus_perdido->cb_data_hora
            );

            $this->db->insert('conta_bonus', $dadosContaBonus);
            $idBonus = $this->db->insert_id();
            
            //Se for pl então vai salber no regitro bonus pl
          if($bonus_perdido->cb_tipo ==106) {
             
              $dadosBonusPl = array(
                'rbpl_id' => NULL,
                'rbpl_valor' =>$bonus_perdido->cb_credito,
                'rbpl_distribuidor' => $bonus_perdido->cb_distribuidor,
                'rbpl_percentual_pl' => ($bonus_perdido->cb_credito / 100),
                'rbpl_id_conta_bonus' =>$idBonus,
                'rbpl_data' => date('y-m-d',  strtotime($bonus_perdido->cb_data_hora)) ,
                'rbpl_data_fatura' =>  $bonus_perdido->cb_data_hora,
                'rbpl_tipo' => 1
            );
            $this->db->insert('registro_bonus_pl', $dadosBonusPl);    
          } 
          //Se for bonus bonário
          if($bonus_perdido->cb_tipo ==2){
               $pontos = ($bonus_perdido->cb_credito/DistribuidorDAO::getPlano($bonus_perdido->cb_distribuidor)->pa_binario)*100;
              $this->db->insert('registro_bonus_indireto_pagos', array(
                    'pg_distribuidor' => $bonus_perdido->cb_distribuidor,
                    'pg_pontos' => $pontos,
                    'pg_data' => $bonus_perdido->cb_data_hora,
                    'pg_atualizacao' => $bonus_perdido->cb_data_hora
                ));

          }
           $this->db->where('cb_id',$_GET['cb_id'])->update('conta_bonus_perdido',array('cb_devolvido'=>1));
          redirect(base_url('index.php/bonus_perdido/bonus/'));
    }
}
