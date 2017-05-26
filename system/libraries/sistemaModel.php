<?php

class sistemaModel extends CI_Controller {

    public function db() {
        return parent::get_instance();
    }

    public static function sistemas($idSisttema = 0) {

        if (!empty($idSisttema)) {
            self::db()->db->where('sys_id', $idSisttema);
        }

        $sitemas = self::db()->db->get('sistemas');

        if (!empty($idSisttema)) {
            return $sitemas->row();
        }

        return $sitemas->result();
    }

}
