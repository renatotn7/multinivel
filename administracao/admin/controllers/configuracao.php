<?php

class Configuracao extends CI_Controller {

    public function index() {

        $configAux = $this->db->select(array('SQL_CACHE *'), false)->order_by('ct_ordem', 'asc')->get('config_tabs')->result();

        //Pegando as configurações das tabs
        $tabs = '  <ul id="myTab" class="nav nav-tabs">';
        foreach ($configAux as $key => $c) {
            $tabs.=' <li ' . ($key == 0 ? ' class="active"' : "") . '><a href="#' . $c->ct_id . '" data-toggle="tab">' . $c->ct_descricao . '</a></li>';
        }

        $tabs.='</ul>';
        $tabs.='<div class="tab-content">';

        //Colocando o conteúdo da tab relacionado.
        foreach ($configAux as $key => $c) {
            $tabs.='<div class="tab-pane ' . ($key == 0 ? ' active' : "") . '" id="' . $c->ct_id . '">';

            $configs = $this->db
                            ->where('id_tab', $c->ct_id)
                            ->order_by('ordem', 'desc')
                            ->order_by('descricao', 'ASC')
                            ->get('config')->result();
            $html = " ";

            foreach ($configs as $key => $config) {

                if ($config->field == "cambio_euro") {
                    continue;
                }

                if ($config->field == 'loja_manutencao') {

                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('0' => 'Ativar', '1' => 'Desativar'), array('selected' => $config->valor));
                    continue;
                }

                if ($config->field == "ativar_desativar_verificacao_conta") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('1' => 'Ativar', '0' => 'Desativar'), array('selected' => $config->valor));
                    continue;
                }

                if ($config->field == "ativar_ou_destivar_codigo_promocional") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('1' => 'Desativar', '0' => 'Ativar'), array('selected' => $config->valor));
                    continue;
                }

                if ($config->field == "ativar_regra_universidade") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('1' => 'Ativar', '0' => 'Desativar'), array('selected' => $config->valor));
                    continue;
                }

                if($config->field == "kitOwnership") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('1' => 'Não', '0' => 'Sim'), array('selected' => $config->valor));
                    continue;
                }

                if($config->field == "cadastro_por_usuario") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('sim' => 'Sim', 'nao' => 'Não'), array('selected' => $config->valor));
                    continue;
                }

                if($config->field == "currency_form_pagament") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('USD' => 'USD - Dólar Americano', 'EUR' => 'EUR - Euro', 'DOP' => 'DOP - Peso (Republica Dominicana)', 'COP' => 'COP - Peso Colombiano', 'BRL' => 'BRL - Real'), array('selected' => $config->valor));
                    continue;
                }

                $html .= $this->rende($config->field, $config->descricao, $config->valor);
            }
            $tabs.=$html;
            $tabs.='</div>';
        }

        $tabs.='</div>';

        $data['html'] = $tabs;
        $data['pagina'] = 'configuracao/configuracao';
        $this->load->view('home/index_view', $data);
    }

    public function bloqueio_upgrade_trava_sessenta() {
        if (conf()->data_max_sessenta == 1) {

            $data = array('valor' => 0, 'field' => 'data_max_sessenta');
            //Salvando auditoria
//             auditoriaGeral::update('field', $data,'config');
            $this->db->where('field', 'data_max_sessenta')->update('config', $data);
            set_notificacao(1, 'Bloqueio upgrade maior que 60 dias liberado.');
        } else {

            $data = array('valor' => 1, 'field' => 'data_max_sessenta');
            //Salvando auditoria
//             auditoriaGeral::update('field', $data,'config');
            $this->db->where('field', 'data_max_sessenta')->update('config', $data);
            set_notificacao(1, 'Bloqueio upgrade maior que 60 dias travado.');
        }


        redirect(base_url());
    }

    public function salvar_config() {

        foreach ($_POST as $key => $input) {
            $this->custo_base_registro($key, $input);
            $this->db->where('field', $key);
            $this->db->update('config', array('valor' => $input));
        }

        $configAux = $this->db->select(array('SQL_CACHE *'), false)->order_by('ct_ordem', 'asc')->get('config_tabs')->result();

        //Pegando as configurações das tabs
        $tabs = '  <ul id="myTab" class="nav nav-tabs">';
        foreach ($configAux as $key => $c) {
            $tabs.=' <li ' . ($key == 0 ? ' class="active"' : "") . '><a href="#' . $c->ct_id . '" data-toggle="tab">' . $c->ct_descricao . '</a></li>';
        }

        $tabs.='</ul>';
        $tabs.='<div class="tab-content">';
        //Colocando o conteúdo da tab relacionado.
        foreach ($configAux as $key => $c) {
            $tabs.='<div class="tab-pane ' . ($key == 0 ? ' active' : "") . '" id="' . $c->ct_id . '">';

            $config = $this->db->select(array("SQL_CACHE *"), false)
                            ->where('id_tab', $c->ct_id)
                            ->order_by('ordem', 'desc')->get('config')->result();
            $html = "";

            foreach ($config as $key => $config) {
                if ($config->field == "cambio_euro") {
                    continue;
                }

                if ($config->field == 'loja_manutencao') {

                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('0' => 'Ativar', '1' => 'Desativar'), array('selected' => $config->valor));
                    continue;
                }

                if ($config->field == "ativar_desativar_verificacao_conta") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('1' => 'Ativar', '0' => 'Desativar'), array('selected' => $config->valor));
                    continue;
                }

                if ($config->field == "ativar_ou_destivar_codigo_promocional") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('1' => 'Desativar', '0' => 'Ativar'), array('selected' => $config->valor));
                    continue;
                }

                if ($config->field == "ativar_regra_universidade") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('1' => 'Ativar', '0' => 'Desativar'), array('selected' => $config->valor));
                    continue;
                }

                if($config->field == "kitOwnership") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('1' => 'Não', '0' => 'Sim'), array('selected' => $config->valor));
                    continue;
                }

                if($config->field == "cadastro_por_usuario") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('sim' => 'Sim', 'nao' => 'Não'), array('selected' => $config->valor));
                    continue;
                }

                if($config->field == "currency_form_pagament") {
                    $html.="<label>" . $config->descricao . "</label>";
                    $html.=CHtml::dropdow($config->field, array('USD' => 'USD - Dólar Americano', 'EUR' => 'EUR - Euro', 'DOP' => 'DOP - Peso (Republica Dominicana)', 'COP' => 'COP - Peso Colombiano', 'BRL' => 'BRL - Real'), array('selected' => $config->valor));
                    continue;
                }

                $html .= $this->rende($config->field, $config->descricao, $config->valor);
            }
            $tabs.=$html;
            $tabs.='</div>';
        }

        $tabs.='</div>';

        $data['html'] = $tabs;
        $data['pagina'] = 'configuracao/configuracao';
        $this->load->view('home/index_view', $data);
    }

    private function custo_base_registro($key, $valor) {

        /**
         * Guardando registro do custo base do produto.
         * 1 - Verifica se tem valor anterio para gerá o intervalo de tempo
         * 2 - verifica se o valor passado não é o mesmo
         * 3 - Salva o resgistro do valor custo do produto.
         */
        if ($key == "custo_base_produto") {

            //Pega o valor do custo base do produto anterio.
            $custo_base = $this->db->select(array('SQL_CACHE *'), false)->order_by('rvp_id', 'desc')->get('registro_valor_produto', 1)->row();

            $rvp_data_ini = '';
            if (count($custo_base) > 0) {
                $rvp_data_fin = $custo_base->rvp_data_ini;
            }

            //Verificando se o valor não é o mesmo
            $custo_base_valor = $this->db->where('rvp_valor', str_replace(',', '.', $valor))->get('registro_valor_produto', 1)->row();

            if (count($custo_base_valor) == 0) {
                //Guardando o registro do valor do produto.
                $this->db->insert('registro_valor_produto', array(
                    'rvp_valor' => str_replace(',', '.', $valor),
                    'rvp_data_fin' => $rvp_data_fin
                ));
            }
        }
    }

    private function rende($nome = '', $descricao = '', $value) {

        if ($nome == "grupo_usuarios") {
            $this->db->query('SET group_concat_max_len=100000000; ');
            $distribuidores = $this->db->query('
     		    		select GROUP_CONCAT(di_usuario SEPARATOR "\',\'") as di_usuario
     		    		 from distribuidores;
     		    		')->row();

            $html = '';
            $html.="<label>" . $descricao . "</label>
	     			<input id='myTags_" . $nome . "' autocomplete='off' class='input-xlarge' type='text' name='" . $nome . "' value='" . $value . "'>";
            $html.="<script>$('#myTags_" . $nome . "').tagit({
	                availableTags: ['" . $distribuidores->di_usuario . "'],
	                allowSpaces: true
	            });</script>";
            return $html;
        } else if ($nome == "forma_pagamentos") {

            $html = '';
            $html.="<label>" . $descricao . "</label>
	     			<input id='myTags_" . $nome . "' autocomplete='off' class='input-xlarge' type='text' name='" . $nome . "' value='" . $value . "'>";
            $html.="<script>$('#myTags_" . $nome . "').tagit({
	                availableTags: ['upline,bonus'],
	                allowSpaces: true
	            });</script>";
            return $html;
        } else {

            $html = '';
            $html.="<label>" . $descricao . "</label><input class='input-xlarge' type='text' name='" . $nome . "' value='" . $value . "'>";
            return $html;
        }
    }

    /**
     * Ativação e bloqueio do login
     */
    public function alterar_status_login() {
        autenticar();
        //Veriifca qual será a função a ser execultada.
        $ativacao = (int) conf()->ativar_login == 0 ? 1 : 0;

        $this->db->where('field', 'ativar_login')
                ->update('config', array('valor' => $ativacao));

        if ($ativacao == 1) {
            set_notificacao(array(0 =>
                array(
                    'tipo' => 1,
                    'mensagem' => "Ação execultada com sucesso, "
                    . "<strong>O login está aberto.</strong>")));
        } else {
            set_notificacao(array(0 =>
                array(
                    'tipo' => 1,
                    'mensagem' => "Ação execultada com sucesso, "
                    . "<strong>O login está Fechado.</strong>")));
        }
        redirect(base_url());
    }

    /**
     * Ativação e bloqueio do login
     */
    public function alterar_status_cadastro() {
        autenticar();
        //Veriifca qual será a função a ser execultada.
        $ativacao = (int) conf()->ativar_cadastro == 0 ? 1 : 0;

        $this->db->where('field', 'ativar_cadastro')
                ->update('config', array('valor' => $ativacao));

        if ($ativacao == 1) {
            set_notificacao(array(0 =>
                array(
                    'tipo' => 1,
                    'mensagem' => "Ação execultada com sucesso, "
                    . "<strong>O Cadastro está aberto.</strong>")));
        } else {
            set_notificacao(array(0 =>
                array(
                    'tipo' => 1,
                    'mensagem' => "Ação execultada com sucesso, "
                    . "<strong>O Cadastro está Fechado.</strong>")));
        }
        redirect(base_url());
    }

}
