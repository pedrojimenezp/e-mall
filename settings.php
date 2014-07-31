<?php

// This constant is converted in '/' when is working in linux|mac or '\' when is working in windows 
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('PROJECT_PATH')) {
    define('PROJECT_PATH', realpath(dirname(__file__)) . DS);
}

if (!defined('easyPHP_PATH')) {
    define('easyPHP_PATH', PROJECT_PATH . 'easyPHP'. DS);
}
if (!defined('libs_PATH')) {
    define('libs_PATH', PROJECT_PATH . 'libs'. DS);
}
if (!defined('CORE_PATH')) {
    define('CORE_PATH', easyPHP_PATH . 'core' . DS);
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}
if (!defined('APPS_PATH')) {
    define('APPS_PATH', PROJECT_PATH . 'apps' . DS);
}
if (!defined('STATIC_DIR')) {
    define('STATIC_DIR', BASE_URL . 'static' . DS);
}
if (!defined('easyPHP_STATIC_DIR')) {
    define('easyPHP_STATIC_DIR', BASE_URL . 'easyPHP' . DS . 'default_static' . DS);
}
if (!defined('easyPHP_TEMPLATES_DIR')) {
    define('easyPHP_TEMPLATES_DIR', easyPHP_PATH . 'default_templates' . DS);
}
if (!defined('TEMPLATES_DIR')) {
    define('TEMPLATES_DIR', PROJECT_PATH . 'templates' . DS);
}
if (!defined('PROJECT_NAME')) {
    define('PROJECT_NAME', "E-Mall");
}

$DATABASE = array(
	"engine" => 'mysql', //mysql, postgres, sqlite
	'user' => 'eshos_15158121',
	'password' => '1q2w3e4r',
    'host' => 'sql109.eshost.es',
    'db' => 'eshos_15158121_emall'
	);
?>