<?php
/*
Session is the class that provides methods to manage all sessions.
This class has methods to inspect all sessions, add data to the session, to remove session data, to update session data, etc. 
*/
class Session
{

	#Start the sesion
	public function __construct(){
		session_cache_expire(30);
		$cache_expire = session_cache_expire();
		session_start();
	}

	#Add a $var to the session with $name as its index
	public function session($name = null, $var = null){
		if ($name != null && $var != null) {
			$_SESSION[$name]=$var;
		}elseif($name != null && $var == null){
			if(isset($_SESSION[$name])){
				return $_SESSION[$name];
			}else{
				return null;
			}	
		}elseif ($name == null && $var == null) {
			return $_SESSION;
		}
	}

	#Delete a data from the session whit $name as its index 
	public function del_from_session($name){
		if(isset($_SESSION[$name])){
			unset($_SESSION[$name]);
		}
	}

	public function close_session(){
		session_unset();
		session_destroy();
	} 
}
?>