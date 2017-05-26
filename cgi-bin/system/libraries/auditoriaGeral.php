<?php

class auditoriaGeral {

    public static $instance = array();

    /**
     * Implementando a singleSton para evitar um possível e invitavel overflow 
     * na memoria ao ler os dados da auditoria.
     */

    function __destruct() {
        self::$instance=array();
    }

    public static function getInstance($sistema = '', $tabela = '', $usuario = 0) {

        if (count(self::$instance) == 0) {
            if (!empty($sistema)) {
                get_instance()->db->where('ag_sistema', $sistema);
            }

            if (!empty($tabela)) {
                get_instance()->db->where('ag_tabela', $tabela);
            }

            if (!empty($usuario)) {
                get_instance()->db->where('ag_id_responsavel', $usuario);
            }

            self::$instance = get_instance()->db->where('ag_data >="2014-09-11 11:09:10"')
                            ->get('auditoria_geral')->result();
        }
        return self::$instance;
    }

    public static function get_auditoria_geral($sistema = '', $tabela = '', $usuario = 0) {
        return self::getInstance($sistema = '', $tabela = '', $usuario = 0);
    }

    public static function ler_dados_josn($json = '', $type = '') {
        $array_json = array();
        if (empty($type)) {
            return false;
        }
        if (empty($json)) {
            return false;
        }

        if (!self::isJson($json)) {
            return false;
        }

        $array_json = json_decode($json);
        if ($type == 'Inclusao' || $type == 'Remocao') {
            echo "<strong>informações</strong>";
            var_export($array_json->dados);
//            echo "<ol>";
//            foreach (json_decode($array_json->dados) as $key => $dados_value) {
//                echo "<li>{$key}->{$dados_value}</li>";
//            }
//            echo "</ol>";
        } else {

            echo "<strong>Novas informações</strong>";
            var_export(($array_json->dados));
//            echo "<ol>";
//            foreach (json_decode($array_json->dados) as $key => $dados_value) {
//                echo "<li>{$key}->{$dados_value}</li>";
//            }
//
//            echo "</ol>";
            echo "<strong>Informações Antigas</strong>";
            var_export(($array_json->de));
//            echo "<ol>";
//            foreach (json_decode($array_json->de) as $key => $dados_value) {
//                echo "<li>{$key}->{$dados_value}</li>";
//            }
//            echo "</ol>";
        }
    }

    private function isJson($string) {
        return ((is_string($string) &&
                (is_object(json_decode($string)) ||
                is_array(json_decode($string))))) ? true : false;
    }

    public static function insert($data = array(), $table = '') {
        if (count($data) == 0) {
            echo 'Erro na auditoria: Não foi passando o objeto que vai ser inserido no banco o POST A variavel $_POST ou $_REQUERY ';
            return false;
        }
        if (empty($table)) {
            echo 'Erro na auditoria: Não foi passando A tabela.';
            return false;
        }
        //'Inclusao','Atualizacao','Remocao'
        $whois = self::whois();

        get_instance()->db->insert('auditoria_geral', array(
            'ag_id_responsavel' => get_user()->rf_id,
            'ag_tabela' => $table,
            'ag_acao_realizada' => 'Inclusao',
            'ag_descricao' => 'O usuário <b>' . $whois['id'] . '(' . $whois['nome'] . ')</b> incluíu um novo país, para o financiamento',
            'ag_dados' => json_encode(array('dados' => json_encode($data)))
        ));
    }

    /**
     * Passa o nonme do  campo da chave primaria do banco para fazer a busca dos dados antigos 
     *  apos isso passe o dados novos.
     *  
     * Ex.:  auditoriaGeral::update('nome campo',novos dados objeto,nome da tabela);
     * 
     * @param type $campo_primario é campo da chave primaria 
     * @param type $data_after dados novos 
     * @param type $table nome da tabela
     * @return boolean
     */
    public static function update($field_primary, $data_after, $table = '') {

        if (count($data_after) == 0) {
            echo 'Erro na auditoria: Não foi passando o objeto que vai ser inserido no banco o POST A variavel $_POST ou $_REQUERY ';
            return false;
        }

        if (empty($table)) {
            echo 'Erro na auditoria: Não foi passando A tabela.';
            return false;
        }

        $data_after = self::arrayToObject($data_after);

        $data_before = get_instance()->db
                        ->where($field_primary, $data_after->$field_primary)
                        ->get($table)->row();

        if (count($data_before) == 0) {
            echo 'Erro na auditoria: Com a chave primaria passada não foi possível encontrar o objeto antigo no banco.';
            return false;
        }

        //'Inclusao','Atualizacao','Remocao'
        $whois = self::whois();
        get_instance()->db->insert('auditoria_geral', array(
            'ag_id_responsavel' => get_user()->rf_id,
            'ag_tabela' => $table,
            'ag_relacionamento_id' => $data_after->$field_primary,
            'ag_acao_realizada' => 'Atualizacao',
            'ag_descricao' => 'O usuário <b>' . $whois['id'] . '(' . $whois['nome'] . ')</b> incluíu um novo país, para o financiamento',
            'ag_dados' => json_encode(array('de' => json_encode($data_before), 'para' => json_encode($data_after)))
        ));
    }

    public static function delete($field_primary, $key_primary = 0, $table = '') {

        if (empty($key_primary)) {
            echo 'Erro na auditoria: Não foi passando a chave primaria do objeto que vai ser excluido.';
            return false;
        }

        if (empty($table)) {
            echo 'Erro na auditoria: Não foi passando A tabela do local que vai excluir o objeto.';
            return false;
        }

        $data_before = get_instance()->db
                        ->where($field_primary, $key_primary)
                        ->get($table)->row();

        if (count($data_before) == 0) {
            return false;
        }

        //'Inclusao','Atualizacao','Remocao'
        $whois = self::whois();
        get_instance()->db->insert('auditoria_geral', array(
            'ag_id_responsavel' => get_user()->rf_id,
            'ag_tabela' => $table,
            'ag_relacionamento_id' => $key_primary,
            'ag_acao_realizada' => 'Remocao',
            'ag_descricao' => 'O usuário <b>' . $whois['id'] . '(' . $whois['nome'] . ')</b> incluíu um novo país, para o financiamento',
            'ag_dados' => json_encode(array('data' => json_encode($data_before)))
        ));
    }

    public static function whois() {

        if (isset(get_user()->rf_id)) {
            return array(
                'nome' => get_user()->rf_nome,
                'id' => get_user()->rf_id,
                'sistema' => 'Fabrica'
            );
        }

        if (isset(get_user()->di_id)) {
            return array(
                'nome' => get_user()->di_nome,
                'id' => get_user()->di_id,
                'sistema' => 'Backoffice'
            );
        }
    }

    protected function _reset_write() {
        $ar_reset_items = array(
            'ar_set' => array(),
            'ar_from' => array(),
            'ar_where' => array(),
            'ar_like' => array(),
            'ar_orderby' => array(),
            'ar_keys' => array(),
            'ar_limit' => FALSE,
            'ar_order' => FALSE
        );

        $this->_reset_run($ar_reset_items);
    }

    /**
     * Converte array para objeto usando a class padrão stdclass
     * @param ArrayObject $array
     * @return \stdClass|boolean
     */
    private function arrayToObject($array) {
        if (!is_array($array)) {
            return $array;
        }

        $object = new stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = self::arrayToObject($value);
                }
            }
            return $object;
        } else {
            return FALSE;
        }
    }

    private function objectToArray($object) {

        if (is_object($object) && is_array($object)) {
            return self::objectToArray($object);
        }
        if (is_object($object)) {
            $object = get_object_vars($object);
        }

        return $object;
    }

}
