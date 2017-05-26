<?php

class bonus_residual extends CI_Controller{
    
    public function index(){
    $bonus_residual = new BonusResidual();    
    $bonus_residual->pagar_bonus($compra, $plano, $id_ativacao);
    }
    
}