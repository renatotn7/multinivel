<?php

class funcoesdb {

    public static function valida_fields($table, $fields) {
        $ci = & get_instance();
        $fields_table = $ci->db->list_fields($table);
        $new_dados = array();
        foreach ($fields as $k => $f) {
            if (in_array($k, $fields_table)) {
                if ($f != "" && $f != NULL) {
                    $new_dados[$k] = $f;
                }
            }
        }

        return $new_dados;
    }

    public static function is_sha1($hash = '') {
        if (empty($hash)) {
            return false;
        }
        return (bool) preg_match('/^[a-f0-9]{40}$/i', $hash, $result);
    }

    public static function criar_hash_boleto() {
        return uniqid();
    }

    public static function arrayToObject($array) {
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

    public static function diffData($dataIni = '', $dataFinal = '', $type = 'd') {
        $data_stemp_cad = strtotime($dataIni);
        $data_stemp_atu = strtotime($dataFinal);
        $data_dif = $data_stemp_atu - $data_stemp_cad;

        //Retorna a diferença entre duas data em dias.
        if($type == 'd'){
            $dias_dif = (int) floor($data_dif / (60 * 60 * 24));
            return $dias_dif;
        }
    }

}
