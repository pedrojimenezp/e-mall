<?php
require_once CORE_PATH . 'session.php';

/*
Request is the class that manage the request of the navegator, this class provides methods to manage url, POST, GET, also this class extends from Session class wich allow to manage the sessions in the request of navegator.
*/
class Request extends Session 
{
	
	private $url;//url of navegator
	private $args_GET;//GET parameters if they were sent
	private $args_POST;//POST parameters if they were sent
	

	/*
	The __construct method check if the url was sent as a parameter and saves that url in $url, also check if were sent parameters GET or POST and saves the parameters in $args_GET or $args_POST respectively.
	*/
	public function __construct()
	{
		parent::__construct();
		$url = strtolower($_SERVER['REQUEST_URI']);	
		$pos = strpos($url, '?');
		if($pos){
			$url = substr($url, 0, $pos);
		}
		$this->url = filter_var($url, FILTER_SANITIZE_URL);
		$this->args_GET = $_GET;
		$this->args_POST = $_POST;
	}

	#Returns the url that was sent from the navegator
	public function get_url()
	{
		return $this->url;
	}

	#When is passed $value returns only a data from $args_POST which its has $value as its index, in other case returns all data in $args_POST
	public function body($value=null){
		if($value == null){
			return $this->args_POST;
		}else{
			if(isset($this->args_POST[$value])){
				return $this->args_POST[$value];
			}else{
				return null;
			}
		}
	}

	#When is passed $value returns only a data from $args_GET which its has $value as its index, in other case returns all data in $args_GET
	public function query($value=null){
		if($value == null){
			return $this->args_GET;
		}else{
			if(isset($this->args_GET[$value])){
				return $this->args_GET[$value];
			}else{
				return null;
			}
		}
	}
	
	#check if the method used for send the parameters was GET
	public function is_from_GET()
	{
		if($_SERVER["REQUEST_METHOD"]=="GET"){
			return true;
		}else{
			return false;
		}
	}

	#check if the method used for send the parameters was POST
	public function is_from_POST()
	{
		if(($_SERVER["REQUEST_METHOD"]=="POST")){
			return true;
		}else{
			return false;
		}
	}
}

?>