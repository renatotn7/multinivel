<?php

/**
 * Description of SenhaSeguranca
 *
 * @author Claudivan
 */
class SenhaSeguranca {

    private $db;

    public function __construct() {
        $this->db = get_instance()->db;
    }

    public function eIgualSenhaDeLogin($senhaSeguranca) {
        $senha_login = $this->db
                        ->Select('di_senha')
                        ->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->row()->di_senha;
        
        return $senha_login == sha1($senhaSeguranca)? true : false;
    }

    public function jaDefiniuSenha() {
        return $this->db
                        ->Select('di_pw')
                        ->where('di_id', get_user()->di_id)
                        ->get('distribuidores')->row();
    }

}
