<?php

class gestao_bonus extends CI_Controller {

    public function registro_dia() {
        $data['page'] = 1;
        $data['registro_direto'] = array();
        $data['registro_indireto'] = array();
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function pagar_pl() {
        try {
            if (!$this->input->get('data')) {
                throw new Exception('Erro: Informe uma data');
            }
            if (data_to_usa($this->input->get('data')) > date('Y-m-d')) {
                throw new Exception('Erro: A data não pode ser maior que a data atual.');
            }
        } catch (Exception $exc) {
            set_notificacao(2, $exc->getMessage());
            redirect(base_url('index.php/gestao_bonus/registro_dia'));
        }
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function liberar_pl() {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        CHtml::berginTime();
        new bonusPL(null, data_to_usa($this->input->get('data')));
        echo "<br/>Finalizou<br/>";
        CHtml::endTime();
    }

    public function liberar_bonus_indicacao_direta() {
        if (isset($_REQUEST['cb'])) {
            $sql = "INSERT INTO conta_bonus
            (
                        cb_distribuidor,
                        cb_compra      ,
                        cb_descricao   ,
                        cb_credito     ,
                        cb_debito      ,
                        cb_tipo        ,
                        cb_data_hora
            )
            (SELECT cb_distribuidor,
                    cb_compra      ,
                    cb_descricao   ,
                    cb_credito     ,
                    cb_debito      ,
                    cb_tipo        ,
                    cb_data_hora
            FROM    conta_bonus_perdido

            WHERE   cb_id   = '{$_REQUEST['cb']}'
                   and cb_devolvido = 0
            )";
            $this->db->query($sql);
//Salvando no registro distribuidor idicação
            $this->db->query("INSERT INTO registro_bonus_indicacao_pagos
                                (
                                            rb_indicador,
                                            rb_indicado ,
                                            rb_valor    ,
                                            rb_data
                                )
                                (SELECT cb_distribuidor,
                                        '{$_REQUEST['indicado']}',
                                        cb_credito     ,
                                        cb_data_hora
                                FROM    distribuidores
                                        JOIN conta_bonus_perdido
                                        ON      cb_distribuidor = di_id
                                WHERE   cb_id   = '{$_REQUEST['cb']}'
                                )");

//Atualizando todos os devolvidos.
            $this->db->where('cb_id', $_REQUEST['cb'])
                    ->update('conta_bonus_perdido', array('cb_devolvido' => 1));

            $resgitro_indireto = $this->db->query(
                            "select * from distribuidores
                  join conta_bonus_perdido on cb_distribuidor = di_id
                  where di_id = '{$_REQUEST['di_id']}'"
                            . "and cb_devolvido=0")->result();

            $data['registro_direto'] = $resgitro_indireto;
            $data['registro_direto'] = $resgitro_indireto;
        } else {
            $data['registro_direto'] = array();
            $data['registro_indireto'] = array();
        }

        $data['page'] = 3;
        set_notificacao(1, "Sucesso ");
        $data['pagina'] = strtolower(__CLASS__) . "/registro_dia";
        $this->load->view('home/index_view', $data);
    }

    public function verificar_bonus() {
        if (isset($_REQUEST['di_usuario'])) {
            $resgitro_indireto = $this->db->query(
                            "select * from distribuidores
                  join conta_bonus_perdido on cb_distribuidor = di_id
                  where di_usuario = '{$_REQUEST['di_usuario']}'"
                            . "and cb_devolvido=0"
                    )->result();

            $data['registro_direto'] = $resgitro_indireto;
        } else {
            $data['registro_direto'] = array();
            $data['registro_indireto'] = array();
        }
        $data['page'] = 3;
        $data['pagina'] = strtolower(__CLASS__) . "/registro_dia";
        $this->load->view('home/index_view', $data);
    }

    public function verificar_distribuidor() {

        if (isset($_REQUEST['di_usuario']) && !empty($_REQUEST['di_usuario'])) {

//Verifica se o distribuidor existe no sistema.
            $distribuidor = $this->db->where('di_usuario', $_REQUEST['di_usuario'])
                    ->get('distribuidores')
                    ->row();
        }

        if (isset($_REQUEST['di_indicado']) && !empty($_REQUEST['di_indicado'])) {

//Verifica se o distribuidor indicado existe no sistema.
            $distribuidor = $this->db->where('di_usuario', $_REQUEST['di_indicado'])
                    ->get('distribuidores')
                    ->row();
        }



        if (count($distribuidor) > 0) {
            echo json_encode(array('response' => 'ok'));
        } else {
            echo json_encode(array('response' => 'Distribuidor não encontrado.'));
        }
    }

    public function bonus_indireto() {
        $data['page'] = 4;
        $data['registro_direto'] = array();
        $data['registro_indireto'] = array();
        $data['pagina'] = strtolower(__CLASS__) . "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    public function verificar_indiretos() {

        $dados = array();
        $valor = 0;
        $di_id = 0;
        if (isset($_REQUEST['di_usuario_indireto'])) {
            $distribuidor = $this->db->where('di_usuario', $_REQUEST['di_usuario_indireto'])
                            ->get('distribuidores')->row();
            $patrocinador = $distribuidor->di_usuario;

            if (count($distribuidor) > 0) {
                $di_id = $distribuidor->di_id;
//Pegando o valor do plano
                $valor = DistribuidorDAO::getPlano($distribuidor->di_id)->pa_indicacao_indireta;

                $rede = redeLinear::getRedeLinear($distribuidor->di_id, 8);
                foreach ($rede as $posicao => $r) {
                    foreach ($r['distribuidores'] as $key => $distribuidor) {

//Para não pagar o bonus em relação a ele mesmo
                        if ($distribuidor->di_id == $di_id) {
                            continue;
                        }
                        if ($distribuidor->di_usuario_patrocinador == $patrocinador) {
                            continue;
                        }


                        if ($this->possoPagar($distribuidor->di_usuario, $di_id)) {
                            $dados[] = array('distribuidor' => $distribuidor, 'posicao' => $posicao);
                        }
                    }
                }
            }
        }

        $data['page'] = 4;
        $data['registro_direto'] = array();
        $data['valor'] = $valor;
        $data['id_distribuidor'] = $di_id;
        $data['registro_indireto'] = $dados;
        $data['pagina'] = strtolower(__CLASS__) . "/registro_dia";
        $this->load->view('home/index_view', $data);
    }

    public function pagar_indiretos() {

        if (isset($_REQUEST['di_id'])) {

            $id_distribuidor = $_REQUEST['di_id'];
            $distribuidor = $this->db->where('di_id', $_REQUEST['di_id'])
                            ->get('distribuidores')->row();
            $patrocinador = $distribuidor->di_usuario;

            $rede = redeLinear::getRedeLinear($id_distribuidor, 8);

            foreach ($rede as $posicao => $r) {
                foreach ($r['distribuidores'] as $distribuidor) {

//Para não pagar o bonus em relação a ele mesmo
                    if ($distribuidor->di_id == $id_distribuidor) {
                        continue;
                    }
                    if ($distribuidor->di_usuario_patrocinador == $patrocinador) {
                        continue;
                    }

                    if ($this->possoPagar($distribuidor->di_usuario, $id_distribuidor)) {
                        $descicaoBonus = 'Bônus Residual <b>' . $distribuidor->di_usuario . '</b>';
                        $this->db->insert('conta_bonus', array(
                            'cb_distribuidor' => $id_distribuidor,
                            'cb_descricao' => $descicaoBonus,
                            'cb_credito' => DistribuidorDAO::getPlano($id_distribuidor)->pa_indicacao_indireta,
                            'cb_debito' => 0,
                            'cb_data_hora' => $distribuidor->di_data_cad,
                            'cb_tipo' => 107
                        ));

##-- Inserir registro do pagamento de bonus
                        $this->db->insert("bonus_venda_volume_pagos", array(
                            'bp_distribuidor' => $distribuidor->di_id,
                            'bp_distribuidor_recebeu' => $id_distribuidor,
                            'bp_posicao' => $posicao,
                            'bp_data' => date('Y-m-d', strtotime($distribuidor->di_data_cad))
                        ));
                    }
                }
            }
        }
        set_notificacao(1, "Sucesso");
        $data['page'] = 4;
        $data['registro_direto'] = array();
        $data['valor'] = '';
        $data['id_distribuidor'] = array();
        $data['registro_indireto'] = array();
        $data['pagina'] = strtolower(__CLASS__) . "/registro_dia";
        $this->load->view('home/index_view', $data);
    }

    /**
     * @param type $distribuidor do sitribuidor da rede
     * @param type $id_distribuidor do distribuidor que vai receber o bonus.
     * @return boolean
     */
    public function possoPagar($distribuidor, $id_distribuidor) {
        $string = "<b>{$distribuidor}</b>";
        $conta_bonus = $this->db->like('cb_descricao', $string)
                        ->where('cb_distribuidor', $id_distribuidor)
                        ->where('cb_tipo', 107)
                        ->get('conta_bonus')->row();

        if (count($conta_bonus) > 0) {
            return false;
        } else {
            return true;
        }
    }

}
