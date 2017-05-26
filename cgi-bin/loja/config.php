<?php
require '../config.php';
// HTTP
define('HTTP_SERVER', APP_BASE_URL.APP_LOJA.'/');
define('HTTP_IMAGE', APP_BASE_URL.APP_LOJA.'/image/');
define('HTTP_IMAGE_TEMP', APP_BASE_URL.APP_LOJA.'/catalog/view/theme/default/image/');
define('HTTP_ADMIN', APP_BASE_URL.APP_LOJA.'/admin/');

// HTTPS
define('HTTPS_SERVER', APP_BASE_URL.APP_LOJA);
define('HTTPS_IMAGE', APP_BASE_URL.APP_LOJA.'/image/');

//CATALOG DESKTOP
define('DIR_APPLICATION', __DIR__.'/catalog/');
define('DIR_LANGUAGE', __DIR__.'/catalog/language/');
define('DIR_TEMPLATE', __DIR__.'/catalog/view/theme/');
define('DIR_SYSTEM', __DIR__.'/system/');
define('DIR_DATABASE', __DIR__.'/system/database/');
define('DIR_CONFIG', __DIR__.'/system/config/');
define('DIR_IMAGE', __DIR__.'/image/');
define('DIR_CACHE', __DIR__.'/system/cache/');
define('DIR_DOWNLOAD', __DIR__.'/download/');
define('DIR_LOGS', __DIR__.'/system/logs/'); 

// DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', APP_HOST);
define('DB_USERNAME', APP_USER);
define('DB_PASSWORD', APP_SENHA);
define('DB_DATABASE', APP_DATABASE);
define('DB_PREFIX', 'loja_');

?>