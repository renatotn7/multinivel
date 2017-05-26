<?php

session_start();
// Version
define('VERSION', '1.5.4');

// Configuration
require_once('config.php');

// Install 

if (!defined('DIR_APPLICATION')) {

    header('Location: install/index.php');

    exit;
}


// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/affiliate.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');
require_once(DIR_SYSTEM . 'library/bonusVendaLojaModel.php');
// Registry

$registry = new Registry();



// Loader

$loader = new Loader($registry);

$registry->set('load', $loader);



// Config

$config = new Config();

$registry->set('config', $config);



// Database 

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

$registry->set('db', $db);
$base_uri = '/empresa/loja/';
$uri_atual = $_SERVER['REQUEST_URI'];
$user_url = str_ireplace($base_uri, '', $uri_atual);

if (isset($_SESSION['distribuidor_log'])) {

    if ($_SESSION['distribuidor_log'] != false) {
        if ($_SESSION['distribuidor_log']->di_usuario != $user_url) {
            $distribuidor = $db->query("SELECT * FROM distribuidores WHERE di_usuario ='" . $user_url . "'")->row;

            if (count($distribuidor) > 0) {
                die(header('Location: ' . APP_BASE_URL . APP_LOJA . 'index.php?route=common/home&user_afiliate=' . $user_url));
            }
        } else {
            die(header('Location: ' . APP_BASE_URL . APP_LOJA . 'index.php?route=common/home'));
        }
    }
}

//Confirmar o cadastro do distribuidor.
if (isset($_REQUEST['codigo_ativacao']) && !empty($_REQUEST['codigo_ativacao'])) {
    $codigo_ativaocao = $db->query("SELECT * FROM token_confirmacao_cadastro WHERE tk_token ='{$_REQUEST['codigo_ativacao']}'");
    
    //Verificando se o novo cadastrado já foi confirmado no sistema pela url.
    if($codigo_ativaocao->row['tk_confirmado']==1){
     die(header('Location:' . APP_BASE_URL . APP_LOJA.'/index.php?route=account/jaconfirmado'));   
    }

    if ($codigo_ativaocao->num_rows) {
        //Ativa o distribuidor.
        $sql = "update loja_customer set approved=1 where customer_id={$codigo_ativaocao->row['tk_distribuidor']}";
        $db->query($sql);
        //inativando a a url.
        
        $sql = "update token_confirmacao_cadastro set tk_confirmado=1 where tk_token='{$_REQUEST['codigo_ativacao']}'";
        $db->query($sql);
        
        die(header('Location:' . APP_BASE_URL . APP_LOJA.'/index.php?route=account/confirmar'));
    }
}



if (!isset($_SESSION['distribuidor_recebera_bonus'])) {
    if (!isset($_REQUEST['user_afiliate']) && empty($_REQUEST['user_afiliate'])) {
        die(header('Location: ' . APP_BASE_URL . APP_LOJA . '?user_afiliate=ewalletclub'));
    }
}

// Store

if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {

    $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
} else {

    $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
}



if ($store_query->num_rows) {

    $config->set('config_store_id', $store_query->row['store_id']);
} else {

    $config->set('config_store_id', 0);
}



// Settings

$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int) $config->get('config_store_id') . "' ORDER BY store_id ASC");



foreach ($query->rows as $setting) {

    if (!$setting['serialized']) {

        $config->set($setting['key'], $setting['value']);
    } else {

        $config->set($setting['key'], unserialize($setting['value']));
    }
}



if (!$store_query->num_rows) {

    $config->set('config_url', HTTP_SERVER);

    $config->set('config_ssl', HTTPS_SERVER);
}



// Url

$url = new Url($config->get('config_url'), $config->get('config_use_ssl') ? $config->get('config_ssl') : $config->get('config_url'));

$registry->set('url', $url);



// Log 

$log = new Log($config->get('config_error_filename'));

$registry->set('log', $log);

function error_handler($errno, $errstr, $errfile, $errline) {

    global $log, $config;



    switch ($errno) {

        case E_NOTICE:

        case E_USER_NOTICE:

            $error = 'Notice';

            break;

        case E_WARNING:

        case E_USER_WARNING:

            $error = 'Warning';

            break;

        case E_ERROR:

        case E_USER_ERROR:

            $error = 'Fatal Error';

            break;

        default:

            $error = 'Unknown';

            break;
    }



    if ($config->get('config_error_display')) {

        echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
    }



    if ($config->get('config_error_log')) {

        $log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
    }



    return true;
}

// Error Handler

set_error_handler('error_handler');



// Request

$request = new Request();

$registry->set('request', $request);



// Response

$response = new Response();

$response->addHeader('Content-Type: text/html; charset=utf-8');

$response->setCompression($config->get('config_compression'));

$registry->set('response', $response);



// Cache

$cache = new Cache();

$registry->set('cache', $cache);



// Session

$session = new Session();

$registry->set('session', $session);



// Language Detection

$languages = array();



$query = $db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1'");



foreach ($query->rows as $result) {

    $languages[$result['code']] = $result;
}



$detect = '';



if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && ($request->server['HTTP_ACCEPT_LANGUAGE'])) {

    $browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);


    foreach ($browser_languages as $browser_language) {

        foreach ($languages as $key => $value) {

            if ($value['status']) {

                $locale = explode(',', $value['locale']);



                if (in_array($browser_language, $locale)) {

                    $detect = $key;
                }
            }
        }
    }
}



if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages) && $languages[$session->data['language']]['status']) {

    $code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages) && $languages[$request->cookie['language']]['status']) {

    $code = $request->cookie['language'];
} elseif ($detect) {

    $code = $detect;
} else {

    $code = $config->get('config_language');
}



if (!isset($session->data['language']) || $session->data['language'] != $code) {

    $session->data['language'] = $code;
}



if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {

    setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}



$config->set('config_language_id', $languages[$code]['language_id']);

$config->set('config_language', $languages[$code]['code']);



// Language	

$language = new Language($languages[$code]['directory']);
$language->load($languages[$code]['filename']);
$registry->set('language', $language);



// Document

$registry->set('document', new Document());



// Customer

$registry->set('customer', new Customer($registry));

// Affiliate

$registry->set('affiliate', new Affiliate($registry));

$registry->set("bonusVendaLojaModel", new bonusVendaLojaModel($registry));

if (isset($request->get['tracking']) && !isset($request->cookie['tracking'])) {

    setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/');
}



// Currency

$registry->set('currency', new Currency($registry));



// Tax

$registry->set('tax', new Tax($registry));



// Weight

$registry->set('weight', new Weight($registry));



// Length

$registry->set('length', new Length($registry));



// Cart

$registry->set('cart', new Cart($registry));



//  Encryption

$registry->set('encryption', new Encryption($config->get('config_encryption')));



// Front Controller 

$controller = new Front($registry);



// Maintenance Mode

$controller->addPreAction(new Action('common/maintenance'));



// SEO URL's

$controller->addPreAction(new Action('common/seo_url'));



// Router

if (isset($request->get['route'])) {

    $action = new Action($request->get['route']);
} else {

    $action = new Action('common/home');
    //$action = new Action('pavblog/blogs');
    //$action = new Action('pavblog/category');
    //header('index.php?route=pavblog/category&id=25');
}



// Dispatch

$controller->dispatch($action, new Action('error/not_found'));


// Output
$response->output();
?>