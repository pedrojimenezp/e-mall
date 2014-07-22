<?php
require_once "engines/mysql.php";
class Connect{
    
    private $engine;
    private $user;
    private $password;
    private $host;
    private $db;
    private $connection;

    public function __construct(){
        require PROJECT_PATH . "settings.php";
        if($DATABASE){
            $this->engine = $DATABASE["engine"];
            $this->user = $DATABASE["user"];
            $this->password = $DATABASE["password"];
            $this->host = $DATABASE["host"];
            $this->db = $DATABASE["db"];
        }else{
            echo "Doesn't exist DATABASE within settings.php";
        }
    }

    public function connect(){
        if($this->is_a_valid_engine()){
            if ($this->engine == "mysql") {
                $this->connection = MySql::connect($this->host, $this->user, $this->password, $this->db);
                return $this->connection;
            }
        }else{
            echo "Invalid Engine";
            exit;
        }
    }

    public function disconnect(){
        if($this->is_a_valid_engine()){
            if ($this->engine == "mysql") {
                MySql::disconnect($this->connection);
            }
        }else{
            echo "Invalid Engine";
            exit;
        }
    }

    public function is_a_valid_engine(){
        if($this->engine == "mysql" or $this->engine == "postgres" or $this->engine == "sqlite"){
            return true;
        }else{
            return false;
        }
    }
}

?>