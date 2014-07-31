<?php
/**/
class Views
{	
	function __construct()
	{

	}
	
	public static function render_template($template, $parameters = null){
		$template_path = TEMPLATES_DIR . $template;
		if($parameters){
			$params = $parameters;
		}else{
			$params = array();
		}
		if(is_readable($template_path)){
			require $template_path;
		}else{
			echo "Doesn't can to render the template.";exit;
		}
	}

	public static function get_template($template, $parameters = null){
		$template_path = TEMPLATES_DIR . $template;
		if($parameters){
			$params = $parameters;
		}else{
			$params = array();
		}
		if(is_readable($template_path)){
			return $template_path;
		}else{
			echo "Doesn't can to render the template.";exit;
		}
	}

	public static function render_easyPHP_template($template, $e){
		$template_path = easyPHP_TEMPLATES_DIR . $template;
		$error = $e;
		if(is_readable($template_path)){
			require $template_path;
		}else{
			echo "Doesn't can to render the template because the render doesn't exist.";exit;
		}
	}

	public static function redirect_to($url){
		header('Location: '.$url);
	}

	public static function send($str){
		print_r($str);
	}

	public static function json($obj){
		echo json_encode($obj);
	} 


}
?>