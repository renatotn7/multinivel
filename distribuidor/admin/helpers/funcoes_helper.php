<?php

define("STR_REDUCE_LEFT", 1);
define("STR_REDUCE_RIGHT", 2);
define("STR_REDUCE_CENTER", 4);

/**
 *  @autor: Carlos Reche
 *  @data:  Jan 21, 2005
 */
function str_reduce($str, $max_length, $append = NULL, $position = STR_REDUCE_RIGHT, $remove_extra_spaces = true)
{
    if (!is_string($str))
    {
        echo "<br /><strong>Warning</strong>: " . __FUNCTION__ . "() expects parameter 1 to be string.";
        return false;
    }
    else if (!is_int($max_length))
    {
        echo "<br /><strong>Warning</strong>: " . __FUNCTION__ . "() expects parameter 2 to be integer.";
        return false;
    }
    else if (!is_string($append)  &&  $append !== NULL)
    {
        echo "<br /><strong>Warning</strong>: " . __FUNCTION__ . "() expects optional parameter 3 to be string.";
        return false;
    }
    else if (!is_int($position))
    {
        echo "<br /><strong>Warning</strong>: " . __FUNCTION__ . "() expects optional parameter 4 to be integer.";
        return false;
    }
    else if (($position != STR_REDUCE_LEFT)  &&  ($position != STR_REDUCE_RIGHT)  &&
             ($position != STR_REDUCE_CENTER)  &&  ($position != (STR_REDUCE_LEFT | STR_REDUCE_RIGHT)))
    {
        echo "<br /><strong>Warning</strong>: " . __FUNCTION__ . "(): The specified parameter '" . $position . "' is invalid.";
        return false;
    }


    if ($append === NULL)
    {
        $append = "...";
    }


    $str = html_entity_decode($str);


    if ((bool)$remove_extra_spaces)
    {
        $str = preg_replace("/\s+/s", " ", trim($str));
    }


    if (strlen($str) <= $max_length)
    {
        return htmlentities($str);
    }


    if ($position == STR_REDUCE_LEFT)
    {
        $str_reduced = preg_replace("/^.*?(\s.{0," . $max_length . "})$/s", "\\1", $str);

        while ((strlen($str_reduced) + strlen($append)) > $max_length)
        {
            $str_reduced = preg_replace("/^\s?[^\s]+(\s.*)$/s", "\\1", $str_reduced);
        }

        $str_reduced = $append . $str_reduced;
    }


    else if ($position == STR_REDUCE_RIGHT)
    {
        $str_reduced = preg_replace("/^(.{0," . $max_length . "}\s).*?$/s", "\\1", $str);

        while ((strlen($str_reduced) + strlen($append)) > $max_length)
        {
            $str_reduced = preg_replace("/^(.*?\s)[^\s]+\s?$/s", "\\1", $str_reduced);
        }

        $str_reduced .= $append;
    }


    else if ($position == (STR_REDUCE_LEFT | STR_REDUCE_RIGHT))
    {
        $offset = ceil((strlen($str) - $max_length) / 2);

        $str_reduced = preg_replace("/^.{0," . $offset . "}|.{0," . $offset . "}$/s", "", $str);
        $str_reduced = preg_replace("/^[^\s]+|[^\s]+$/s", "", $str_reduced);

        while ((strlen($str_reduced) + (2 * strlen($append))) > $max_length)
        {
            $str_reduced = preg_replace("/^(.*?\s)[^\s]+\s?$/s", "\\1", $str_reduced);

            if ((strlen($str_reduced) + (2 * strlen($append))) > $max_length)
            {
                $str_reduced = preg_replace("/^\s?[^\s]+(\s.*)$/s", "\\1", $str_reduced);
            }
        }

        $str_reduced = $append . $str_reduced . $append;
    }


    else if ($position == STR_REDUCE_CENTER)
    {
        $pattern = "/^(.{0," . floor($max_length / 2) . "}\s)|(\s.{0," . floor($max_length / 2) . "})$/s";

        preg_match_all($pattern, $str, $matches);

        $begin_chunk = $matches[0][0];
        $end_chunk   = $matches[0][1];

        while ((strlen($begin_chunk) + strlen($append) + strlen($end_chunk)) > $max_length)
        {
            $end_chunk = preg_replace("/^\s?[^\s]+(\s.*)$/s", "\\1", $end_chunk);

            if ((strlen($begin_chunk) + strlen($append) + strlen($end_chunk)) > $max_length)
            {
                $begin_chunk = preg_replace("/^(.*?\s)[^\s]+\s?$/s", "\\1", $begin_chunk);
            }
        }

        $str_reduced = $begin_chunk . $append . $end_chunk;
    }

    return htmlentities($str_reduced);
}

function mask_name($user, $nome) {
    if (substr($user, 0, 16) == 'envix05059878643') {
        return "Envix Prime";
    } else {
        return $nome;
    }
}

function converte_moeda($valor)
{
    return substr($valor,0,strlen($valor)-2).'.'.substr($valor,-2,strlen($valor));
}

function mask_user($user) {
    if (substr($user, 0, 16) == 'envix05059878643') {
        return "envix";
    } else {
        return $user;
    }
}
function verificar_permissao_acesso($redirect = true) {
    $ci = & get_instance();
    $ci->load->library('bloqueio');
    $naoTemPermissao = $ci->bloqueio->get_permissao(get_user()->di_usuario);


    if ($redirect) {
        if ($naoTemPermissao) {
            set_notificacao(array(array('tipo' => 2, 'mensagem' => '<h4>Permissão Negada</h4>Você não tem permissão para acessar essa funcionalidade')));
            redirect(base_url());
        }
    } else {
        return $naoTemPermissao;
    }
}

function conf() {


    $ci = & get_instance();
    $dados = $ci->db->get('config')->result();
    $_SESSION['config_sistema'] = new stdClass;
    foreach ($dados as $key => $d) {
        $_SESSION['config_sistema']->{$d->field} = $d->valor;
    }

    return $_SESSION['config_sistema'];
}

function criar_hash_boleto() {
    return uniqid('bo-');
}
function criar_token_cadastro() {
    return uniqid()."==";
}

function valida_fields($table, $fields) {
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
    echo('<pre>');
    print_r($new_dados);
    echo('</pre>');
    exit;
}

function valida_token() {
    $token = base64_encode('8bG5$6&¨%$');
    if ($token != get_parameter('TOKEN')) {
        redirect(URL_SITE);
        exit;
    }
}

function converte_data_banco($data_banco) {
    //quebra os espaços para pegar apenas a parte data e nao o tempo
    $onlyData = explode(' ', $data_banco);
    $D_M_Y = explode('-', $onlyData[0]);
    $data_convertida = $D_M_Y[2] . '/' . $D_M_Y[1] . '/' . $D_M_Y[0] . ' ' . $onlyData[1];
    return $data_convertida;
}

function data_to_usa($data) {
    list($d, $m, $Y) = explode('/', $data);
    return "$Y-$m-$d";
}

function set_compra($compra) {
    $_SESSION['compra'] = $compra;
}

function get_compra() {
    return $_SESSION['compra'];
}

function get_user() {
    return $_SESSION['distribuidor_log'];
}

function get_notificacao() {
    return $_SESSION['notificacao'];
}

function set_notificacao($tipo, $mensagem = '') {
    if (is_array($tipo)) {
        $_SESSION['notificacao'] = $tipo;
    } else {
        $_SESSION['notificacao'][] = array('tipo' => $tipo, 'mensagem' => $mensagem);
    }
}

function autenticar() {
    if ($_SESSION['distribuidor_log'] == FALSE) {
        redirect(SITE . "index.php/entrar");
        exit;
    }
}

function set_user($user, $redirect = true) {
    if ($user != false) {
        $ci = & get_instance();

        //Verificar Ativação
        $esta_ativo = $ci->db
                        ->where('at_data >', date('Y-m-01'))
                        ->where('at_distribuidor', get_user()->di_id)
                        ->get('registro_ativacao')->result();

        if (count($esta_ativo) == 0) {
            $ativo_agora = 0;
        } else {
            $ativo_agora = 1;
        }

        $ci->db->where('di_id', $user->di_id)->update('distribuidores', array(
            'di_ativo' => $ativo_agora
        ));
        $user->di_ativo = $ativo_agora;

        $distribuidorDAO = new DistribuidorDAO();
        $usuario = $distribuidorDAO->getById(get_user()->di_id);
        $user->distribuidor = $usuario;
    }
    $_SESSION['distribuidor_log'] = $user;
}

function sair_user() {
    $_SESSION['distribuidor_log'] = FALSE;
    $_SESSION['compra'] = 0;

    redirect(APP_BASE_URL);
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

function show_array($array, $parar = false) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    $parar ? exit : "";
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

function compra_status($st) {
    switch ($st) {
        case 0:return "Pendente";
            break;
        case 1:return "Concluida";
            break;
        case 2:return "Encaminhado para cliente";
            break;
    }
}

function get_parameter($parameter) {
    return isset($_GET[$parameter]) && $_GET[$parameter] != '' ? $_GET[$parameter] : FALSE;
}

function get_contaverificada() {

    $ci = & get_instance();
    $contaVerificada = $ci->db->query("
            select * from distribuidores 
					  join cidades on ci_id=di_cidade 
					  where 
                                          (di_conta_verificada =0 OR di_contrato = 0)
                                          and 
					  di_id=" . get_user()->di_id . "
                                          AND ci_pais = 1 
					  ")->row();

    return count($contaVerificada) > 0 ? true : false;
}

function get_produtosentregues() {
    $ce = & get_instance();
    $produtos_entregues = $ce->db
                    ->where('co_situacao <>', -1)
                    ->where('co_eplano', 0)
                    ->where('co_id_distribuidor', get_user()->di_id)
                    ->where("co_pago", "1")
                    ->where('ce_id', null)
                    ->join('compras_entregues', 'ce_compra=co_id', 'left')
                    ->order_by('co_id', 'DESC')
                    ->get('compras')->result();
    return $produtos_entregues;
}

function mascaraRG($str = "") {
    $Inicio = substr($str, -3);
    return str_repeat('*', strlen($str)) . $Inicio;
}

function mascaraEndereco($str = "") {
    $Inicio = substr($str, -10);
    return str_repeat('*', strlen($str)) . $Inicio;
}

function mascaraFone($str = "") {
    $Inicio = substr($str, -3);
    return '(**) ****-*' . $Inicio;
}

function mascararCPF($str = "") {

    $cpfInicio = substr($str, -3, 1) . "-" . substr($str, -2);
    $resto_nome = substr($str, 3, strlen($str));
    $ast = '';
    $total = 0;
    for ($i = 0; $total < strlen($resto_nome); $i++) {
        $ast.="*";
        $total++;
    }

    return '***.***.**' . $cpfInicio;
}

function mascararNome($str = "") {
    $ultimo = @end(@explode(" ", $str));
    return str_repeat('*', strlen($str)) . ' ' . $ultimo;
}

function verificar_compra_valor_acima_permitido(){
	
	$ci =& get_instance();
	$contaVerificada = $ci->db->query("select * from registro_planos_distribuidor where registro_planos_distribuidor.ps_distribuidor=".get_user()->di_id."")->row();
	if(count($contaVerificada)>0){
		return true;
	}else{
		return false;
	}
}

function mask($val, $mask)
{
	$maskared = '';
	$k = 0;
	for($i = 0; $i<=strlen($mask)-1; $i++)
	{
	if($mask[$i] == '#')
	{
	if(isset($val[$k]))
		$maskared .= $val[$k++];
	}
	else
	{
	if(isset($mask[$i]))
		$maskared .= $mask[$i];
	}
	}
	return $maskared;
}
/**
 * Retorna a diferênça entre duas datas.
 * @param type $dataIni Obrigatório
 * @param type $dataFinal Não obrigatório
 * @param type $type string tipo de retorno {d=dia,m=mes,a=ano}
 * @return int
 */
function diffData($dataIni='',$dataFinal='',$type='d'){
    
    $dataFinal = empty($dataFinal)?date('Y-m-d'):date('Y-m-d',  strtotime($dataFinal));
    
        $data_stemp_cad = strtotime(date('Y-m-d',strtotime($dataIni)));
        $data_stemp_atu =  strtotime($dataFinal);
        $data_dif = $data_stemp_atu-$data_stemp_cad;
              
    //Retorna a diferença entre duas data em dias.
    if($type=='d'){
        $dias_dif = (int)floor( $data_dif / (60 * 60 * 24));
        return $dias_dif;
    }
}

function e_pessoa_juridica($di_id)
{
   if(empty($di_id)){
       return false;
   }
    
    $ci = & get_instance();
    $distribuidor = $ci->db->where('dpj_id_distribuidor',$di_id)
                           ->get('distribuidor_pessoa_juridica')
                           ->row();
    
    if(count($distribuidor)>0){
        return true;
    }else{
        return false;
    }
        
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
        return 'US$: '. number_format($valor,2);
    }else{
        $valor = $planos->pa_valor+(int)($total_percentual*(int)$planos->pa_valor);
        return 'US$: '. number_format($valor,2);
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
    
    
    if($objCambio->moe_sibolo == '€'){
      return '';
    }
    
    if($id_plano !=99){
        //$planos->pa_valor += $member_ship_valor->pa_valor;
        $valor = $planos->pa_valor+(int)($total_percentual*(int)$planos->pa_valor);
        $valor = $valor * $objCambio->camb_valor;        
        return $objCambio->moe_sibolo.': '. number_format($valor,2);
    }else{
        $valor = $planos->pa_valor+(int)($total_percentual*(int)$planos->pa_valor);
        $valor = $valor * $objCambio->camb_valor;
        return $objCambio->moe_sibolo.': '. number_format($valor,2);
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
        return '€: '. number_format($valor,2);
    }else{
        $valor = $planos->pa_valor+(int)($total_percentual*(int)$planos->pa_valor);
        $valor = $valor * conf()->cambio_euro;
        return  '€: '. number_format($valor,2);
    }
    
}
