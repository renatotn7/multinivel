<?php

class atualizar_cadastro extends CI_Controller {

    private $modificados_novo;
    private $modificados_antigo;

    public function run() {
        set_time_limit(0);
        CHtml::berginTime();
        $conteudo_log = '';
        $total_atualizados = 0;
        $total_inexistente = 0;

        echo '<script type="text/javascript" src="' . base_url('public/script/validar/js/jquery-1.7.2.min.js') . '"></script>';

        unset($_SESSION['modificados_novo']);
        unset($_SESSION['modificados_antigo']);

        $count = 0;
        $array_bd = array();
        $atualizados = array();
        $distribuidores = $this->db->where('di_niv IS NOT NULL')
                        ->where('di_email IS NOT NULL')
                        ->where('di_excluido', 0)
                        ->get('distribuidores')->result();

        $total_existente = count($distribuidores);
        $total_processado = 1;
        $porcentual_concluido = 0;

        $consulta = new atm();
        foreach ($distribuidores as $key => $distribuidor) {

            $obj = $consulta->consulta_cadastro_ewallet($distribuidor->di_email);
            $porcentual_concluido = round(($total_processado / $total_existente) * 100);

//             ob_start();
            if ($obj['status'] != 1) {
                $di_uf = self::getIdEstado($this->validainfo($distribuidor->di_uf, $obj, 'state'));
                $di_cidade = self::getIdCidade($this->validainfo($distribuidor->di_cidade, $obj, 'city'), $obj);


                 if($di_cidade==false){
                     continue;
                 }
                 
                 if($di_uf==false){
                     continue;
                 }

                $data = array(
                    'di_data_nascimento' => substr($this->validainfo($distribuidor->di_data_nascimento, $obj, 'birthday'), 0, -11),
                    'di_fone2' => $this->validainfo($distribuidor->di_fone2, $obj, 'cellPhone'),
                    'di_cidade' => $di_cidade,
                    'di_cidade_nascimento' => $this->validainfo($distribuidor->di_cidade_nascimento, $obj, 'cityOfBirth'),
                    'di_complemento' => $this->validainfo($distribuidor->di_complemento, $obj, 'completion'),
                    'di_bairro' => $this->validainfo($distribuidor->di_bairro, $obj, 'district'),
                    'di_email' => $this->validainfo($distribuidor->di_email, $obj, 'email'),
                    'di_nome' => $this->validainfo($distribuidor->di_nome, $obj, 'name'),
                    'di_niv' => $this->validainfo($distribuidor->di_niv, $obj, 'niv'),
                    'di_fone1' => $this->validainfo($distribuidor->di_fone1, $obj, 'phone'),
                    'di_uf' => $di_uf,
                    'di_endereco' => $this->validainfo($distribuidor->di_endereco, $obj, 'street'),
                    'di_ultimo_nome' => $this->validainfo($distribuidor->di_ultimo_nome, $obj, 'surname'),
                    'di_rg' => $this->validainfo($distribuidor->di_rg, $obj, 'taxIdNumber'),
                    'di_cep' => $this->validainfo($distribuidor->di_cep, $obj, 'zipCode')
                );

                if ($this->modificados($data, $distribuidor)) {

                    echo "<h4 style='color:#090'>Usuário: {$distribuidor->di_usuario}, Niv: {$distribuidor->di_niv} Foi atualizado</h4>";
                    echo "-----------------------Dados Atualizados------------------------";
                    echo "<ol>";

                    foreach ($this->modificados_novo[$distribuidor->di_id] as $key => $modificado) {

                        if ($key == 'di_cidade') {
                            echo "<li>{$this->dadosempresa($key)}: {$this->getNomeCidade($modificado)}</li>";
                        }
                        if ($key == 'di_uf') {
                            echo "<li>{$this->dadosempresa($key)}: {$this->getNomeEstado($modificado)}</li>";
                        }

                        if ($key != 'di_uf' && $key != 'di_cidade') {
                            echo "<li>{$this->dadosempresa($key)}: {$modificado}</li>";
                        }
                    }
                    echo "</ol>";
                    echo "-----------------------------fim---------------------------------";

                    //Atualizando os dados
                    $this->db->where('di_id', $distribuidor->di_id)
                            ->update('distribuidores', $data);
                    $total_atualizados++;
                } else {
                    echo "<h4 style='color:#006dcc'>Usuário: {$distribuidor->di_usuario}, Niv: {$distribuidor->di_niv} Não tem novas informações</h4>";
                }
            } else {
                echo "<h4 style='color:#f00'>Usuário: {$distribuidor->di_usuario}, Niv: {$distribuidor->di_niv} Não existe na Plataforma de Pagamento</h4>";
                $total_inexistente++;
            }
//             $conteudo_log.= ob_get_contents();
//            ob_flush();
//            ob_end_clean();
            //Salvando os dados da empresa
            $array_bd[] = array("gemstone" => $distribuidor,
                'wallet' => $this->arrayToObject($this->dadosempresa($obj)));
            $total_processado++;
            echo "<script>$('.bar',window.parent.document).css('width','{$porcentual_concluido}%').html('{$porcentual_concluido}%');</script>";
//            sleep(1);
        }


        $_SESSION['modificados_novo'] = $this->modificados_novo;
        $_SESSION['modificados_antigo'] = $this->modificados_antigo;
        set_notificacao(1, 'Processo de Atualização finalizado com sucesso.');
        CHtml::endTime();
        CHtml::logexec('atualizao_cadastro_' . date('d_m_Y_h_i_s'), 'Administrador' . get_user()->rf_id . 'ind, Execultou manuamente a atualizaçcao de cadastro<br/>' . $conteudo_log, 'atualizacao_cadastro');
        echo "<script>"
        . "$('.bar',window.parent.document).css('width','{$porcentual_concluido}%').html('Processo de Atualização Concluído');"
        . "$('.total_atualizado',window.parent.document).html('{$total_atualizados}');"
        . "$('.total_inexistente',window.parent.document).html('{$porcentual_concluido}');"
        . "</script>";
    }

    /**
     * Verifica os que receberam alterações.
     * @param type $dadosNovos
     * @param type $id_distribuidor
     */
    private function modificados($dadosNovos, $distribuidor) {

        $dataAtual = array(
            'di_data_nascimento' => $distribuidor->di_data_nascimento,
            'di_fone2' => $distribuidor->di_fone2,
            'di_cidade' => $distribuidor->di_cidade,
            'di_cidade_nascimento' => $distribuidor->di_cidade_nascimento,
            'di_complemento' => $distribuidor->di_complemento,
            'di_bairro' => $distribuidor->di_bairro,
            'di_email' => $distribuidor->di_email,
            'di_nome' => $distribuidor->di_nome,
            'di_niv' => $distribuidor->di_niv,
            'di_fone1' => $distribuidor->di_fone1,
            'di_uf' => (int) $distribuidor->di_uf,
            'di_endereco' => $distribuidor->di_endereco,
            'di_ultimo_nome' => $distribuidor->di_ultimo_nome,
            'di_rg' => $distribuidor->di_rg,
            'di_cep' => $distribuidor->di_cep
        );



        $modificados_novo = array_diff($dadosNovos, $dataAtual);
        $modificados_antigo = array_diff($dataAtual, $dadosNovos);
        if (count($modificados_novo) > 0) {
            foreach ($modificados_novo as $key => $modificado) {
                @$this->modificados_novo[$distribuidor->di_id]->$key = new stdClass();
                $this->modificados_novo[$distribuidor->di_id]->$key = $modificado;
            }
            return true;
        }

        if (count($modificados_antigo) > 0) {
            foreach ($modificados_antigo as $key => $modificado) {
                @$this->modificados_antigo[$distribuidor->di_id]->$key = new stdClass();
                $this->modificados_antigo[$distribuidor->di_id]->$key = $modificado;
            }
        }
        return false;
    }

    /**
     * Faz a validação dos campos.
     * @param type $info1
     * @param type $info2
     * @param type $key_elemento
     * @return type
     */
    private function validainfo($info1, $info2, $key_elemento = '') {

        if (!array_key_exists($key_elemento, $info2) && empty($info2[$key_elemento])) {
            return $info1;
        }
        if ($info1 != $info2) {
            return $info2[$key_elemento];
        } else {
            return $info1;
        }
    }

    /**
     * Trata as informações vinda da empresa
     * @param type $objeto
     */
    private function dadosempresa($key) {

        switch ($key) {
            case 'di_data_nascimento':
                return 'Data de Nascimento';
                break;
            case 'di_fone2':
                return 'Telefone celular';
                break;
            case 'di_cidade':
                return 'Cidade';
                break;
            case 'di_cidade_nascimento':
                return 'Cidade de Nascimento';
                break;
            case 'di_complemento':
                return 'Complemento';
                break;
            case 'di_bairro':
                return 'Bairro';
                break;
            case 'di_email':
                return 'Email';
                break;
            case 'di_nome':
                return 'Nome';
                break;
            case 'di_niv':
                return 'Niv';
                break;
            case 'di_fone1':
                return 'Telefone';
                break;
            case 'di_uf':
                return 'Estado';
                break;
                break;
            case 'di_endereco':
                return 'Rua';
                break;
                break;
            case 'di_ultimo_nome':
                return 'Sobre nome';
                break;
            case 'di_rg':
                return 'Número do documento';
                break;
            case 'di_cep':
                return 'Código postal';
                break;
        }
    }

    public static function getIdEstado($estado) {

        if (is_numeric($estado)) {
            return $estado;
        }

        $id_estado = get_instance()->db
                        ->where('es_nome  like "%' . str_replace("S?o", 'São', utf8_encode(utf8_decode(($estado)))) . '%"')
                        ->get('estados')->row();
        if (count($id_estado) == 0) {
            return false;
        }

        return (int) $id_estado->es_id;
    }

    public function getNomeEstado($id_estado) {



        $estado = get_instance()->db
                        ->where('es_id', $id_estado)
                        ->get('estados')->row();

        return $estado->es_nome;
    }

    public static function getIdCidade($cidade_string, $objeto_atm = array()) {

        if (is_numeric($cidade_string)) {
            return $cidade_string;
        }


        $sql_string = utf8_encode("upper(ci_nome) = upper(\"{$cidade_string}\")");

        $id_cidade = get_instance()->db
                        ->where($sql_string)
                        ->get('cidades')->row();

        if (count($id_cidade) == 0) {

            if ($objeto_atm['country']) {
                return false;
            }

            $estado = self::getIdEstado($objeto_atm['state']);
            $pais = get_instance()->db->where('ps_iso3', $objeto_atm['country'])
                            ->get('pais')->row();

            get_instance()->db->insert('cidades', array(
                'ci_uf' => $estado,
                'ci_nome' => $cidade_string,
                'ci_pais' => $pais->ps_id,
            ));
            return (int) get_instance()->db->insert_id();
        }

        return (int) $id_cidade->ci_id;
    }

    public function getNomeCidade($id_cidade) {

        if (!is_numeric($id_cidade)) {
            return $id_cidade;
        }

        $cidade = get_instance()->db
                        ->where('ci_id', $id_cidade)
                        ->get('cidades')->row();

        return $cidade->ci_nome;
    }

    public function index() {
        $data['pagina'] = strtolower(__CLASS__) .
                "/" . strtolower(str_ireplace(__CLASS__ . '::', '', __METHOD__));
        $this->load->view('home/index_view', $data);
    }

    private function arrayToObject($array) {
        if (!is_array($array)) {
            return $array;
        }

        $object = new stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = $this->arrayToObject($value);
                }
            }
            return $object;
        } else {
            return FALSE;
        }
    }

}

?>
