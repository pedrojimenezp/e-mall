<?php
class Error
{
	private $url_error;
	private $error_message;
	private $file_error_path;
	private $help;

	public function __construct(){
		$this->url_error = '';
		$this->error_message = '';
		$this->file_error_path = '';
	}

	public function set_url_error($url){
		$this->url_error = $url;
	}

	public function set_error_message($msg){
		$this->error_message = $msg;
	}

	public function set_file_error_path($path){
		$this->file_error_path = $path;
	}

	public function set_help($h){
		$this->help = $h;
	}

	public function get_url_error(){
		return $this->url_error;
	}

	public function get_error_message(){
		return $this->error_message;
	}

	public function get_file_error_path(){
		return $this->file_error_path;
	}

	public function get_help(){
		return $this->help;
	}
}
?>