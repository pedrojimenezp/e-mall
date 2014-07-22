<?php 
class MySql{
    public function __construct(){

    }

    public static function connect($host, $user, $password, $db){
        $connection = new mysqli($host, $user, $password, $db);
        if ($connection->connect_errno) {
            echo "Error to connect to MySql: " . $connection->connect_error;
        }else{
            return $connection;
        }
    }

    public static function disconnect($connection){
        mysqli_close($connection);
    }    
}


?>