<?php

function show_array($array, $parar = false) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    $parar ? exit : "";
}

if (!function_exists('validaEmail')) {

// Define uma função que poderá ser usada para validar e-mails usando regexp
    function validaEmail($email) {
        $conta = "^[a-zA-Z0-9\._-]+@";
        $domino = "[a-zA-Z0-9\._-]+.";
        $extensao = "([a-zA-Z]{2,4})$";

        $pattern = $conta . $domino . $extensao;

        if (preg_match($pattern, $email))
            return true;
        else
            return false;
    }

}
if (!function_exists('cadastrado_quando')) {
/**
 * Verifica quando foi realizado o cadastro.
 * @param type $data
 * @return string hoje ou ontem ou data formatada 
 */
    function cadastrado_quando($data = '') {
        if (empty($data)) {
            echo "Informe a data";
        }

        if (!validaData($data, 'YYYY-MM-DD')) {
            echo "Formato inválido YYYY-mm-dd ";
        }

        $hoje = strtotime('now');
        $ontem = strtotime('now - 1 day');

        if (date('Y-m-d',$hoje) == date('Y-m-d', strtotime($data))) {
            return "Hoje";
        }

        if (date('Y-m-d', $ontem) == date('Y-m-d', strtotime($data))) {
            return "Ontem";
        }

        return date('d/m/Y', strtotime($data));
    }

}
if (!function_exists('validaData')) {

    function validaData($data, $formato = 'DD/MM/YYYY') {
        if (!is_string($formato)) {
            return false;
        }
        if (strlen($data) > 10) {
            $data = substr($data, 0, -9);
        }

        switch ($formato) {
            case 'DD-MM-YYYY':
            case 'DD/MM/YYYY':
                list($d, $m, $a) = preg_split('/[-.\/ ]/i', $data);
                break;

            case 'YYYY/MM/DD':
            case 'YYYY-MM-DD':
                list($a, $m, $d) = preg_split('/[-.\/ ]/i', $data);
                break;

            case 'YYYY/DD/MM':
            case 'YYYY-DD-MM':
                list($a, $d, $m) = preg_split('/[-.\/ ]/i', $data);
                break;

            case 'MM-DD-YYYY':
            case 'MM/DD/YYYY':
                list($m, $d, $a) = preg_split('/[-.\/ ]/i', $data);
                break;

            case 'YYYYMMDD':
                $a = substr($data, 0, 4);
                $m = substr($data, 4, 2);
                $d = substr($data, 6, 2);
                break;

            case 'YYYYDDMM':
                $a = substr($data, 0, 4);
                $d = substr($data, 4, 2);
                $m = substr($data, 6, 2);
                break;

            default:
                throw new Exception("Formato de data inválido");
                break;
        }

        return checkdate($m, $d, $a);
    }

}

if (!function_exists('data_to_usa')) {

    function data_to_usa($data) {
        list($d, $m, $Y) = explode('/', $data);
        return "$Y-$m-$d";
    }

}
if (!function_exists('get_usename')) {

    function get_usename($string) {
        if (!empty($string)) {
            if (preg_match('/<b+>([a-zA-Z_\.0-9\/\-\! :\@\$]*)\<\/b>/', $string, $result)) {
                return $result[1];
            } else {
                false;
            }
        }
    }

}
if (!function_exists('data_to_usa')) {

    function data_to_usa($data) {
        list($d, $m, $Y) = explode('/', $data);
        return "$Y-$m-$d";
    }

}
if (!function_exists('arrayToObject')) {

    function arrayToObject($array) {
        if (!is_array($array)) {
            return $array;
        }

        $object = new stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = arrayToObject($value);
                }
            }
            return $object;
        } else {
            return FALSE;
        }
    }

}


/**
 * Fun��o para colocar mascara em cnpj e cpf
 */
if (!function_exists('mascaracpfcnpj')) {

    function mascaracpfcnpj($campo, $formatado = true) {
        //retira formato
        $codigoLimpo = str_replace(".", '', str_replace('-', '', str_replace('/', '', $campo)));
        // pega o tamanho da string menos os digitos verificadores
        $tamanho = (strlen($codigoLimpo) - 2);
        //verifica se o tamanho do c�digo informado � v�lido
        if ($tamanho != 9 && $tamanho != 12) {
            return false;
        }

        if ($formatado) {
            // seleciona a m�scara para cpf ou cnpj
            $mascara = ($tamanho == 9) ? '###.###.###-##' : '##.###.###/####-##';

            $indice = -1;
            for ($i = 0; $i < strlen($mascara); $i++) {
                if ($mascara[$i] == '#')
                    $mascara[$i] = $codigoLimpo[++$indice];
            }
            //retorna o campo formatado
            $retorno = $mascara;
        }else {
            //se não quer formatado, retorna o campo limpo
            $retorno = $codigoLimpo;
        }

        return $retorno;
    }

}

/**
 * Verfifica se o usuario ta ou não bloqueado pelo o financeiro.
 */
function usuario_bloqueado($di_usuario = '') {
    if (empty($di_usuario)) {
        return false;
    }

    $ci = get_instance();
    $status = $ci->db->where('di_usuario', $di_usuario)
            ->select('di_login_status')
            ->get('distribuidores')
            ->row();

    if ($status->di_login_status == 0 || in_array($di_usuario, explode(',', conf()->grupo_usuarios))) {
        return true;
    } else {
        return false;
    }
}

function e_pessoa_juridica($di_id) {
    if (empty($di_id)) {
        return false;
    }

    $ci = & get_instance();
    $distribuidor = $ci->db->where('dpj_id_distribuidor', $di_id)
            ->get('distribuidor_pessoa_juridica')
            ->row();

    if (count($distribuidor) > 0) {
        return true;
    } else {
        return false;
    }
}

function permissao($modulo, $acao, $user, $redirectOnFalse = false) {
    if ($user->rf_tipo == 2) {
        $permissao = json_decode($user->rf_permissao);
        $re = isset($permissao->$modulo->$acao);

        if ($redirectOnFalse && $re == false) {
            redirect(base_url());
        }
        return $re;
    } else {
        return true;
    }
}

function get_modulos() {
    return array(
        'info_home' => array('name' => 'Informações Página Inicial', 'option' => array('visualizar')),
        'categoria_produtos' => array('name' => 'Categorias de Produto', 'option' => array('combo')),
        'produtos' => array('name' => 'Produtos', 'option' => array('combo')),
        'empreendedores' => array('name' => 'Menu Empreendedores', 'option' => array('visualizar')),
        'financeiro' => array('name' => 'Menu Financeiro', 'option' => array('visualizar')),
        'configuracao' => array('name' => 'Menu Financeiro', 'option' => array('visualizar')),
        'cadastro_pendente' => array('name' => 'Cadastro Pendentes', 'option' => array('visualizar', 'editar', 'excluir')),
        'arede' => array('name' => 'A Rede(Empreendedores)', 'option' => array('visualizar', 'editar', 'login')),
        'verificar_conta' => array('name' => 'Verificação de Conta', 'option' => array('visualizar', 'editar')),
        'solicitacao_deposito' => array('name' => 'Solicitação de depósito', 'option' => array('visualizar', 'editar')),
        'vendas' => array('name' => 'Vendas', 'option' => array('visualizar', 'editar', 'excluir')),
        'relacao_pedidos' => array('name' => 'Relação de Pedidos', 'option' => array('visualizar', 'editar', 'excluir')),
        'cds' => array('name' => 'CDs', 'option' => array('combo')),
        'fabrica' => array('name' => 'FABRICA', 'option' => array('visualizar', 'editar', 'excluir')),
        'boleto' => array('name' => 'Baixar Boleto', 'option' => array('visualizar', 'editar')),
        'relatorios' => array('name' => 'Relatórios', 'option' => array('visualizar')),
        'relatorio_produtos' => array('name' => 'Relatórios Produtos Vendidos', 'option' => array('visualizar')),
        'relatorio_celular' => array('name' => 'Relatórios Celular Distribuidores', 'option' => array('visualizar')),
        'relatorio_usuario_rede' => array('name' => 'Relatórios Usuário da Rede', 'option' => array('visualizar')),
        'relatorio_email' => array('name' => 'Relatórios E-mails Distribuidores', 'option' => array('visualizar')),
        'relatorio_vendas' => array('name' => 'Relatórios de Vendas', 'option' => array('visualizar')),
        'relatorio_financeiro' => array('name' => 'Relatório Financeiro', 'option' => array('visualizar')),
        'relatorio_bonus' => array('name' => 'Relatórios de Bônus', 'option' => array('visualizar')),
        'relatorio_deposito' => array('name' => 'Relatórios de Depósito', 'option' => array('visualizar')),
        'marketing' => array('name' => 'Marketing', 'option' => array('visualizar')),
        'notificacao' => array('name' => 'Notificação', 'option' => array('visualizar')),
        'email_marketing' => array('name' => 'E-mail Marketing', 'option' => array('visualizar')),
        'download' => array('name' => 'Download', 'option' => array('combo')),
        'configuracoes' => array('name' => 'Configurações', 'option' => array('visualizar'))
    );
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

function get_user() {
    return $_SESSION['admin'];
}

function get_notificacao() {
    return isset($_SESSION['notificacao'])?$_SESSION['notificacao']:array();
}

function set_notificacao($tipo, $mensagem = '') {
    if (is_array($tipo)) {
        $_SESSION['notificacao'] = $tipo;
    } else {
        $_SESSION['notificacao'][] = array('tipo' => $tipo, 'mensagem' => $mensagem);
    }
}

function autenticar() {
    if ($_SESSION['admin'] == FALSE) {
        redirect(URL_SITE . "index.php/entrar");
        exit;
    }
}

function set_user($user, $redirect = true) {
    $_SESSION['admin'] = $user;
    if ($redirect) {
        redirect(base_url("index.php/home"));
        exit;
    }
}

function sair_user() {
    $_SESSION['admin'] = FALSE;
    redirect(URL_SITE . "index.php/entrar");
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

function dia_semana_sigla($day) {
    switch ($day) {
        case 1: return 'Seg';
            break;
        case 2: return 'Ter';
            break;
        case 3: return 'Qua';
            break;
        case 4: return 'Qui';
            break;
        case 5: return 'Sex';
            break;
        case 6: return 'Sáb';
            break;
        case 7: return 'Dom';
            break;
    }
}

function data_brasil($data) {
    list($d, $m, $y) = explode('/', $data);
    return "$y-$m-$d";
}

function data_usa($data) {
    list($d, $m, $y) = explode('/', $data);
    return "$y-$m-$d";
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

function mailSend($destinatario, $mensagem, $assunto, $reply) {

    $header = "MIME-Version: 1.0\n";
    $header .= "Content-type: text/html; charset=utf-8\r\n";
    $header .= "From: $reply\n";
    $header .= "Reply-to: $reply\n";

    if (@mail($destinatario, $assunto, $mensagem, $header)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Valor do plano em com as taxas do países
 * @param type $id_plano
 * @param type $objCambio
 * @return real
 */
function valor_plano_percetual_tx($id_plano = 0, $objCambio = array()) {


    if ($id_plano == 0) {
        return 0.00;
    }

    if (count($objCambio) == 0) {
        return 0.00;
    }

    $total_percentual = 0.00;
    $ci = & get_instance();
    $planos = $ci->db->where('pa_id', $id_plano)
            ->get('planos')
            ->row();

    $member_ship_valor = $ci->db->where('pa_id', 99)
            ->get('planos')
            ->row();

    $total_percentual +=$objCambio->camb_taxas;
    $total_percentual +=$objCambio->camb_impostos;
    $total_percentual +=$objCambio->camb_frete;
    $total_percentual = (int) $total_percentual / 100;

    if ($id_plano != 99) {
        //$planos->pa_valor += $member_ship_valor->pa_valor;
        $valor = $planos->pa_valor + (int) ($total_percentual * (int) $planos->pa_valor);
        return 'US$' . number_format($valor, 2);
    } else {
        $valor = $planos->pa_valor + (int) ($total_percentual * (int) $planos->pa_valor);
        return 'US$' . number_format($valor, 2);
    }
}

/**
 * Valor da moeda em realção ao dorla 
 * @param type $id_plano
 * @param type $objCambio
 * @return real
 */
function valor_plano_relacao_dolar($id_plano = 0, $objCambio = array()) {


    if ($id_plano == 0) {
        return 0.00;
    }

    if (count($objCambio) == 0) {
        return 0.00;
    }

    $total_percentual = 0.00;
    $ci = & get_instance();
    $planos = $ci->db->where('pa_id', $id_plano)
            ->get('planos')
            ->row();

    $member_ship_valor = $ci->db->where('pa_id', 99)
            ->get('planos')
            ->row();

    $total_percentual +=$objCambio->camb_taxas;
    $total_percentual +=$objCambio->camb_impostos;
    $total_percentual +=$objCambio->camb_frete;
    $total_percentual = (int) $total_percentual / 100;

    if ($id_plano != 99) {
        //$planos->pa_valor += $member_ship_valor->pa_valor;
        $valor = $planos->pa_valor + (int) ($total_percentual * (int) $planos->pa_valor);
        $valor = $valor * $objCambio->camb_valor;
        return $objCambio->moe_sibolo . ':' . number_format($valor, 2);
    } else {
        $valor = $planos->pa_valor + (int) ($total_percentual * (int) $planos->pa_valor);
        $valor = $valor * $objCambio->camb_valor;
        return $objCambio->moe_sibolo . ':' . number_format($valor, 2);
    }
}

/**
 * Câmbio do euro em relação ao dóla.
 * @param type $id_plano
 * @param type $objCambio
 * @return real
 */
function valor_plano_euro_relacao_dolar($id_plano = 0, $objCambio = array()) {


    if ($id_plano == 0) {
        return 0.00;
    }

    if (count($objCambio) == 0) {
        return 0.00;
    }

    $total_percentual = 0.00;
    $ci = & get_instance();
    $planos = $ci->db->where('pa_id', $id_plano)
            ->get('planos')
            ->row();

    $member_ship_valor = $ci->db->where('pa_id', 99)
            ->get('planos')
            ->row();

    $total_percentual +=$objCambio->camb_taxas;
    $total_percentual +=$objCambio->camb_impostos;
    $total_percentual +=$objCambio->camb_frete;
    $total_percentual = (int) $total_percentual / 100;
    if ($objCambio->moe_sibolo != '€') {
        if ($id_plano != 99) {
            //$planos->pa_valor += $member_ship_valor->pa_valor;
            $valor = $planos->pa_valor + (int) ($total_percentual * (int) $planos->pa_valor);
            $valor = $valor * conf()->cambio_euro;
            return '€:' . number_format($valor, 2);
        } else {
            $valor = $planos->pa_valor + (int) ($total_percentual * (int) $planos->pa_valor);
            $valor = $valor * conf()->cambio_euro;
            return '€:' . number_format($valor, 2);
        }
    }
}
