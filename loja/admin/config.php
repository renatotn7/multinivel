<?php

require '../../config.php';
// HTTP
define('HTTP_SERVER', APP_BASE_URL . APP_LOJA . '/admin/');
define('HTTP_CATALOG', APP_BASE_URL . APP_LOJA . '/');
define('HTTP_IMAGE', APP_BASE_URL . APP_LOJA . '/image/');

// HTTPS
define('HTTPS_SERVER', APP_BASE_URL . APP_LOJA . '/admin/');
define('HTTPS_CATALOG', APP_BASE_URL . APP_LOJA);
define('HTTPS_IMAGE', APP_BASE_URL . APP_LOJA . '/image/');
define('HTTP_IMAGE_TEMP', APP_BASE_URL . APP_LOJA . '/catalog/view/theme/default/image/');

// DIR
define('DIR_APPLICATION', __DIR__ . '/../admin/');
define('DIR_SYSTEM', __DIR__ . '/../system/');
define('DIR_DATABASE', __DIR__ . '/../system/database/');
define('DIR_LANGUAGE', __DIR__ . '/../admin/language/');
define('DIR_TEMPLATE', __DIR__ . '/../admin/view/template/');
define('DIR_CONFIG', __DIR__ . '/../system/config/');
define('DIR_IMAGE', __DIR__ . '/../image/');
define('DIR_CACHE', __DIR__ . '/../system/cache/');
define('DIR_DOWNLOAD', __DIR__ . '/../download/');
define('DIR_LOGS', __DIR__ . '/../system/logs/');
define('DIR_CATALOG', __DIR__ . '/../catalog/');

// DB
define('DB_DRIVER', 'mysql');
define('DB_HOSTNAME', APP_HOST);
define('DB_USERNAME', APP_USER);
define('DB_PASSWORD', APP_SENHA);
define('DB_DATABASE', APP_DATABASE);
define('DB_PREFIX', 'loja_');
?>