<?php 
require_once CORE_PATH . "models.php";

class Clientes extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function guardar ($email, $password) {
    $fecha = date("Y")."-".date("m")."-".date("d");
    $password = md5($password);
    $consulta = "INSERT INTO clientes (email, password, fecha_registro) VALUES ('".$email."', '".$password."', '".$fecha."')";
    if(!$this->conexion->query($consulta)){
      if($this->conexion->errno == 1062){
        $error = array("numero"=>1062, "descripcion"=>"Este email ya esta registrado"); 
        return array("error" => $error);
      }else{
        echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
      }
    }else{
      $consulta = "SELECT * FROM clientes WHERE id = ".$this->conexion->insert_id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"cliente_registrado","clinte"=>$fila);
    }
  }

  public function buscar_por_id ($id){
    $consulta = "SELECT * FROM clientes WHERE id = ".$id;
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "cliente" =>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_email ($email){
    $consulta = "SELECT * FROM clientes WHERE email = '".$email."'";
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "cliente"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_email_password ($email, $password){
    $password = md5($password);
    $consulta = "SELECT * FROM clientes WHERE email = '".$email."' AND password = '".$password."'";
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "cliente"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }
}
?>