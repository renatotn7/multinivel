<?php
class colocar_saldo extends CI_Controller{
    
    public function index(){
      $atm = new atm(); 
      $resposta = $atm->solicitar_saque(funcoesdb::arrayToObject(array(
          'di_email'=>'system@empresa.com',
          'prk_token'=>'Balance to perform test can be taken at the end of the day',
      )), 10000);
      
      var_dump($resposta);
    }
    
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

