<?php

class acessar_loja extends CI_Controller {
    
    public function index(){
        $this->administracao_loja();
    }

    public function administracao_loja() {
        autenticar();
        $user = $this->db->where('store_id', 0)->get('loja_user')->row();
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['token'] = md5(mt_rand() . uniqid());
        redirect(APP_BASE_URL . APP_LOJA . "/admin/index.php?route=common/home&token=" . $_SESSION['token']);
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

