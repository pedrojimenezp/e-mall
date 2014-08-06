<?php 
require_once CORE_PATH . "models.php";

class Datos_clientes extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function actualizar ($id_cliente, $nombre, $edad, $direccion, $barrio, $telefonos) {
    $consulta = "UPDATE datos_clientes SET nombre='".$nombre."', edad=".$edad.", direccion='".$direccion."', barrio='".$barrio."', telefonos='".$telefonos."' WHERE id_cliente=".$id_cliente;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM datos_clientes WHERE id_cliente = ".$id_cliente;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"datos_actualizados", "datos_personales"=>$fila);
    }
  }

  public function buscar_por_id ($id){
    $consulta = "SELECT * FROM datos_clientes WHERE id = ".$id;
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "datos_cliente" =>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_id_cliente ($id_cliente){
    $consulta = "SELECT * FROM datos_clientes WHERE id_cliente = ".$id_cliente;
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "datos_cliente" =>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }
}

?>