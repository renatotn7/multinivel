<?php
require_once 'config.php';

$usuario = trim($_SERVER['REQUEST_URI']);
$usuario = str_ireplace('/','',$usuario);
session_start();
//$_SESSION['user_current_url'] = $usuario;


header("Location:".URL_SITE);