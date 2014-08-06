<?php 
require_once CORE_PATH . "models.php";

class Notificaciones extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function guardar ($id_receptor, $tipo, $texto, $estado) {
    $fecha = date("Y")."-".date("m")."-".date("d");
    $consulta = "INSERT INTO notificaciones (id_receptor, tipo, texto, estado, fecha) VALUES (".$id_receptor.", '".$tipo."', '".$texto."', '".$estado."', '".$fecha."');";
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM notificaciones WHERE id = ".$this->conexion->insert_id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"notificacion_registrada","notificacion"=>$fila);
    }
  }

  public function buscar_por_id ($id, $estado=null){
    if ($estado == null) {
      $consulta = "SELECT * FROM notificaciones WHERE id = ".$id." ORDER BY fecha DESC";
    }else{
      $consulta = "SELECT * FROM notificaciones WHERE id = ".$id." AND estado='".$estado."' ORDER BY fecha ASC";
    }
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "notificaciones"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_id_receptor ($id_receptor, $estado=null){
    if ($estado == null) {
      $consulta = "SELECT * FROM notificaciones WHERE id_receptor = ".$id_receptor." ORDER BY fecha ASC";
    }else{
      $consulta = "SELECT * FROM notificaciones WHERE id_receptor = ".$id_receptor." AND estado='".$estado."'";
    }
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "notificaciones"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function actualizar_estado ($id, $estado){
    $consulta = "UPDATE notificaciones SET estado='".$estado."' WHERE id = ".$id;
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM notificaciones WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"notificacion_actualizado", "notificaciones"=>$fila);
    } 
  }


  public function eliminar_por_id ($id){
    $consulta = "DELETE FROM notificaciones WHERE id = ".$id;
    if($resultado = $this->conexion->query($consulta)){
      return array("error"=>null, "info"=>"notificacion_eliminada");
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }
}
?>