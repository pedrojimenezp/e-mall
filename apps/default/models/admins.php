<?php 
require_once CORE_PATH . "models.php";

class Admins extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function guardar ($id_tienda, $nombre, $email, $password, $es_propietario) {
    $fecha = date("Y")."-".date("m")."-".date("d");
    $password = md5($password);
    $consulta = "INSERT INTO admins (id_tienda, nombre, email, password, es_propietario, fecha_registro) VALUES (".$id_tienda.",'".$nombre."','".$email."', '".$password."', '".$es_propietario."', '".$fecha."')";
    if(!$this->conexion->query($consulta)){
      if($this->conexion->errno == 1062){
        $error = array("numero"=>1062, "descripcion"=>"Este email ya esta registrado"); 
        return array("error" => $error);
      }else{
        echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
      }
    }else{
      $consulta = "SELECT * FROM admins WHERE id = ".$this->conexion->insert_id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"admin_registrado","admin"=>$fila);
    }
  }

  public function buscar_por_id ($id){
    $consulta = "SELECT * FROM admins WHERE id = ".$id;
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "admin"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function eliminar_por_id ($id){
    $consulta = "DELETE FROM admins WHERE id = ".$id;
    echo $consulta;
    if($resultado = $this->conexion->query($consulta)){
      return array("error"=>null, "info"=>"admin_eliminado");
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_id_tienda ($id_tienda, $es_propietario=null){
    if ($es_propietario) {
      $consulta = "SELECT * FROM admins WHERE id_tienda = ".$id_tienda." AND es_propietario='".$es_propietario."'";
    }else{
      $consulta = "SELECT * FROM admins WHERE id_tienda = ".$id_tienda;
    }
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "admins"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }


  public function buscar_por_email ($email){
    $consulta = "SELECT * FROM admins WHERE email = '".$email."'";
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "admin"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_email_password ($email, $password){
    $password = md5($password);
    $consulta = "SELECT * FROM admins WHERE email = '".$email."' AND password = '".$password."'";
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "admin"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function esta_registrado ($email, $password) {
    $password = md5($password);
    $consulta = "SELECT * FROM admins WHERE email = '".$email."' AND password = '".$password."'";
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      if(count($fila) > 0){
        return true;
      }else{
        return false;
      }
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function actualizar_info_por_id($id, $nombre, $email){
    $consulta = "UPDATE admins SET nombre='".$nombre."', email='".$email."' WHERE id = ".$id;
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM admins WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"info_actualizada", "admin"=>$fila);
    } 
  }

  public function actualizar_password_por_id($id, $password){
    $password = md5($password);
    $consulta = "UPDATE admins SET password='".$password."' WHERE id = ".$id;
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM admins WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"info_actualizada", "admin"=>$fila);
    } 
  }
}
?>