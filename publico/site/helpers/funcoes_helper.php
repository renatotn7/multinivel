<?php

function valida_fields($table, $fields) {
    $ci = & get_instance();
    $fields_table = $ci->db->list_fields($table);
    $new_dados = array();
    foreach ($fields as $k => $f) {
        if (in_array($k, $fields_table)) {
            if ($f != "" && $f != NULL ) {
                $new_dados[$k] = $f;
            }
        }
    }
    return $new_dados;
}
function get_user(){
    
}
function manutencao() {
   @session_start();
   $id_administrador=0;
   if(isset($_SESSION['admin'])){
     $id_administrador = @$_SESSION['admin']->rf_id;
   }
   
    if (conf()->ativar_cadastro==0 && $id_administrador!=5000) {
        redirect('http://empresa.com/pagina-em-manutencao/');
        exit;
    }
    
}

function mask_name($user, $nome) {
    if (substr($user, 0, 16) == 'envix05059878643') {
        return "Envix Prime";
    } else {
        return $nome;
    }
}

function mask_user($user) {
    if (substr($user, 0, 16) == 'envix05059878643') {
        return "envix";
    } else {
        return $user;
    }
}

function reset_user_current_url() {
    $_SESSION['user_current_url'] = false;
}

function get_user_current_url() {
    if (!isset($_SESSION['user_current_url']) || $_SESSION['user_current_url'] == false) {
        return false;
    }

    $ci = & get_instance();
    $user = $ci->db->select(array('di_usuario', 'di_id'))
                    ->where('di_usuario', $_SESSION['user_current_url'])
                    ->get('distribuidores')->row();

    if (count($user) > 0) {
        return $user;
    } else {
        return false;
    }
}

function criar_hash_boleto() {
    return uniqid('bo-');
}

function criar_token_cadastro() {
    return uniqid() . "==";
}

function data_to_usa($data) {
    list($d, $m, $Y) = explode('/', $data);
    return "$Y-$m-$d";
}

function get_notificacao() {
    return $_SESSION['notificacao'];
}

function set_notificacao($notificacao) {
    $_SESSION['notificacao'] = $notificacao;
}

function set_user($user) {
    $_SESSION['admin'] = $user;
    redirect(base_url("index.php/home"));
    exit;
}

function sair_user() {
    $_SESSION['admin'] = FALSE;
    redirect(base_url("index.php/fabrica/entrar"));
    exit;
}

function dia_semana($day) {
    switch ($day) {
        case 1: return 'Segunda-Feira';
            break;
        case 2: return 'Terça-Feira';
            break;
        case 3: return 'Quarta-Feira';
            break;
        case 4: return 'Quinta-Feira';
            break;
        case 5: return 'Sexta-Feira';
            break;
        case 6: return 'Sábado';
            break;
        case 7: return 'Domingo';
            break;
    }
}

function set_loja() {
    $ci = & get_instance();
    $loja = $ci->db
                    ->join('cidades', 'ci_id=fa_cidade')
                    ->get('fabricas', 1)->result();
    $_SESSION['loja'] = $loja[0];
}

function get_loja() {
    if ($_SESSION['loja'] == false) {
        set_loja();
    }
    return $_SESSION['loja'];
}
function conf() {
    unset($_SESSION['config_sistema']);
    if (isset($_SESSION['config_sistema'])) {
        return $_SESSION['config_sistema'];
    } else {
        $ci = & get_instance();
        $dados = $ci->db->get('config')->result();
        $_SESSION['config_sistema'] = new stdClass;
        foreach ($dados as $key => $d) {
            $_SESSION['config_sistema']->{$d->field} = $d->valor;
        }
    }
    return $_SESSION['config_sistema'];
}

/**
 * Valor do plano em com as taxas do países
 * @param type $id_plano
 * @param type $objCambio
 * @return real
 */
function valor_plano_percetual_tx($id_plano=0,$objCambio=array()){
    
   
    if($id_plano==0)
    {
        return 0.00;
    }
    
    if(count($objCambio)==0)
    {
        return 0.00;
    }
    
    $total_percentual=0.00;
    $ci = & get_instance();
    $planos = $ci->db->where('pa_id',$id_plano)
                       ->get('planos')
                       ->row();
    
    $member_ship_valor = $ci->db->where('pa_id',99)
                       ->get('planos')
                       ->row();
    
    $total_percentual +=$objCambio->camb_taxas;
    $total_percentual +=$objCambio->camb_impostos;
    $total_percentual +=$objCambio->camb_frete;
    $total_percentual  = (int)$total_percentual/100;
    
    if($id_plano !=99){
        //$planos->pa_valor += $member_ship_valor->pa_valor;
        $valor = $planos->pa_valor+(int)($total_percentual*(int)$planos->pa_valor);
        return 'US$'. $valor;
    }else{
        $valor = $planos->pa_valor+(int)($total_percentual*(int)$planos->pa_valor);
        return 'US$'. $valor;
    }
    
}
/**
 * Valor da moeda em realção ao dorla 
 * @param type $id_plano
 * @param type $objCambio
 * @return real
 */
function valor_plano_relacao_dolar($id_plano=0,$objCambio=array()){
    
     
    if($id_plano==0)
    {
        return 0.00;
    }
    
    if(count($objCambio)==0)
    {
        return 0.00;
    }
    
    $total_percentual=0.00;
    $ci = & get_instance();
    $planos = $ci->db->where('pa_id',$id_plano)
                       ->get('planos')
                       ->row();
    
    $member_ship_valor = $ci->db->where('pa_id',99)
                       ->get('planos')
                       ->row();
    
    $total_percentual +=$objCambio->camb_taxas;
    $total_percentual +=$objCambio->camb_impostos;
    $total_percentual +=$objCambio->camb_frete;
    $total_percentual  = (int)$total_percentual/100;
   
    
    if($id_plano !=99){
        //$planos->pa_valor += $member_ship_valor->pa_valor;
        $valor = $planos->pa_valor+(int)($total_percentual*(int)$planos->pa_valor);
        $valor = $valor * $objCambio->camb_valor;        
        return $objCambio->moe_sibolo.':'. number_format($valor,2);
    }else{
        $valor = $planos->pa_valor+(int)($total_percentual*(int)$planos->pa_valor);
        $valor = $valor * $objCambio->camb_valor;
        return $objCambio->moe_sibolo.':'. number_format($valor,2);
    }
    
}

/**
 * Câmbio do euro em relação ao dóla.
 * @param type $id_plano
 * @param type $objCambio
 * @return real
 */
function valor_plano_euro_relacao_dolar($id_plano=0,$objCambio=array()){
    
     
    if($id_plano==0)
    {
        return 0.00;
    }
    
    if(count($objCambio)==0)
    {
        return 0.00;
    }
    
    $total_percentual=0.00;
    $ci = & get_instance();
    $planos = $ci->db->where('pa_id',$id_plano)
                       ->get('planos')
                       ->row();
    
    $member_ship_valor = $ci->db->where('pa_id',99)
                       ->get('planos')
                       ->row();
    
    $total_percentual +=$objCambio->camb_taxas;
    $total_percentual +=$objCambio->camb_impostos;
    $total_percentual +=$objCambio->camb_frete;
    $total_percentual  = (int)$total_percentual/100;
    
    if($id_plano !=99){
        //$planos->pa_valor += $member_ship_valor->pa_valor;
        $valor = $planos->pa_valor+(int)($total_percentual*(int)$planos->pa_valor);
        $valor = $valor * conf()->cambio_euro;        
        return '€:'. number_format($valor,2);
    }else{
        $valor = $planos->pa_valor+(int)($total_percentual*(int)$planos->pa_valor);
        $valor = $valor * conf()->cambio_euro;
        return  '€:'. number_format($valor,2);
    }
    
}

function uniqueAlfa($length=16)
{
  $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
  $len = strlen($salt);
  $pass = '';
  mt_srand(10000000*(double)microtime());
  
 for ($i = 0; $i < $length; $i++)
  {
  $pass .= $salt[mt_rand(0,$len - 1)];
  }
 return strtoupper($pass);
}
