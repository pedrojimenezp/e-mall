<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
    
	require_once 'settings.php';

    require_once CORE_PATH . 'error.php';
    require_once CORE_PATH . 'urls.php';
    require_once CORE_PATH . 'request.php';

    require_once APPS_PATH . 'default'.DS.'models.php';
    // echo "Se metio bn";
    // print_r($GLOBALS);
    $urls = new Urls();
	$r = new Request();
    // echo $r->get_url();
    // echo "<br>";
    // print_r($r->get_args_GET());  
    // echo "<br>";
    // print_r($r->get_args_POST());  
    // echo "<br>";
    // exit;
	$urls->url_parsing($r);
?>
