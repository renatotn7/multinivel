<?php

class usuarioAPIModel extends CI_Controller {

    public function db() {
        return parent::get_instance();
    }

    public static function getUsuario($idUsuario = 0) {

        if (!empty($idUsuario)) {
            self::db()->db->where('api_id', $idUsuario);
        }

        $usuario = self::db()->db->get('usuario_api');

        if (!empty($idUsuario)) {
            return $usuario->row();
        }

        return $usuario->result();
    }

    public static function addUsuario($usuario = array()) {
        if (count($usuario) == 0) {
            return false;
        }
        self::db()->db->insert('usuario_api', funcoesdb::valida_fields('usuario_api', $usuario));
        return self::getUsuario(self::db()->db->insert_id());
    }

    public static function atualiarUsuario($idUsuario = 0, $usuario = array()) {
        if (empty($idUsuario)) {
            return false;
        }

        if (count($usuario) == 0) {
            return false;
        }

        return self::db()->db->where('api_id', $idUsuario)->update('usuario_api', funcoesdb::valida_fields('usuario_api', $usuario));
    }

    public static function removerUsuario($idUsuario = 0) {
        if (empty($idUsuario)) {
            return false;
        }

        return self::db()->db->where('api_id', $idUsuario)->delete('usuario_api');
    }

    public static function geraAppID($idUsuario = 0) {
        $appID = substr(rand(10000000, 999999999), 0, 15);

        $App = self::db()->db->where('api_app_id', $appID)
                        ->get('usuario_api')->row();

        if (count($App) > 0) {
            self::geraAppID($idUsuario);
        }

        self::db()->db->where('api_id', $idUsuario)
                ->update('usuario_api', array('api_app_id' => $appID));

        return $appID;
    }

    public static function geraSecretKey($idUsuario = 0) {
        $secretkey = substr(md5(time() + 1), 0, 20);

        $secre = self::db()->db->where('api_secret_key', $secretkey)
                        ->get('usuario_api')->row();

        if (count($secre) > 0) {
            self::geraSecretKey($idUsuario);
        }

        self::db()->db->where('api_id', $idUsuario)
                ->update('usuario_api', array('api_secret_key' => $secretkey));

        return $secretkey;
    }

    public static function gerarToken($idUsuario = 0) {
        $token = strtoupper(md5(time() + 1));

        $tok = self::db()->db->where('api_token', $token)
                        ->get('usuario_api')->row();

        if (count($tok) > 0) {
            self::gerarToken($idUsuario);
        }
        //Falta colocar o usuari
        self::db()->db->where('api_id', $idUsuario)
                ->update('usuario_api', array('api_token' => $token)
        );

        return $token;
    }

    public static function getHistorioAPI($appID = 0) {
        if (empty($appID)) {
            return array();
        }
        //Falta colocar o usuari
        $hist= self::db()->db->query("select 
                                    ac_data,
                                    count(ac_id) as total_acesso ,
                                    (select count(ac_id) from acesso_api where ac_error !=0 and ac_app_id={$appID}) as  total_erros 
                                    from acesso_api 
                                    where ac_app_id = {$appID}"
                                   ." group by DATE_FORMAT(ac_data,'%Y-%m-%d %H') order by ac_id asc limit 10 ")->result();
      return $hist;
    }

}
