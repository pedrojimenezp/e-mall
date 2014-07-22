<?php 
require_once CORE_PATH . "models.php";

/* Clase que abstrae los metodos para manejar lo relacionado con las tiendas*/
class Tiendas extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function guardar ($nombre, $categoria) {
    $fecha = date("Y")."-".date("m")."-".date("d");
    $consulta = "INSERT INTO tiendas (nombre, categoria, estado, fecha_registro) VALUES ('".$nombre."', '".$categoria."','inhabilitada','".$fecha."')";
    echo $consulta;
    if(!$this->conexion->query($consulta)){
      if($this->conexion->errno == 1062){
        $error = array("numero"=>1062, "descripcion"=>"Ya existe una tienda con ese nombre por lo anto no se puede guardar"); 
        return array("error" => $error);
      }else{
        echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
      }
    }else{
      $consulta = "SELECT * FROM tiendas WHERE id = ".$this->conexion->insert_id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"tienda_registrada", "tienda"=>$fila);
    }
  }

  public function actualizar_nombre ($id, $nombre) {
    $consulta = "UPDATE tiendas SET nombre='".$nombre."' WHERE id = ".$id.")";
    if(!$this->conexion->query($consulta)){
      if($this->conexion->errno == 1062){
        $error = array("numero"=>1062, "descripcion"=>"Ya existe una tienda con ese nombre por lo anto no se puede guardar"); 
        return array("error" => $error);
      }else{
        echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
      }
    }else{
      $consulta = "SELECT * FROM tiendas WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "tienda"=>$fila);
    }
  }

  public function actualizar_estado ($id, $estado) {
    $consulta = "UPDATE tiendas SET estado='".$estado."' WHERE id = ".$id.")";
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM tiendas WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "tienda"=>$fila);
    }
  }

  public function buscar_todas ($categoria = null, $estado = null) {
    if($estado != null and $categoria != null) {
      $consulta = "SELECT * FROM tiendas WHERE categoria = '".$categoria."' AND estado = '".$estado."'";
    }elseif($estado != null and $categoria == null){
      $consulta = "SELECT * FROM tiendas WHERE estado = '".$estado."'";
    }elseif($estado == null and $categoria != null){
      $consulta = "SELECT * FROM tiendas WHERE categoria = '".$categoria."'";      
    }else{
      $consulta = "SELECT * FROM tiendas"; 
    }

    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "tiendas"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
    
  }

  public function buscar_por_id ($id) {
    $consulta = "SELECT * FROM tiendas WHERE id = ".$id;
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "tienda"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_nombre ($nombre, $estado) {
    $consulta = "SELECT * FROM tiendas WHERE nombre = '".$nombre."'";
    if($estado){
      $consulta = $consulta."AND estado='".$estado."'";
    }
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "tienda"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_nombre_similar ($nombre, $estado = null) {
    if($estado != null) {
      $consulta = "SELECT * FROM tiendas WHERE nombre like '".$nombre."' AND estado = '".$estado."'";
    }else{
      $consulta = "SELECT * FROM tiendas WHERE nombre like '".$nombre."'";
    }
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "tiendas"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_registradas_desde ($date, $estado = null) {
    if($estado != null) {
      $consulta = "SELECT * FROM tiendas WHERE fecha_registro >= '".$date."' AND estado = '".$estado."'";
    }else{
      $consulta = "SELECT * FROM tiendas WHERE fecha_registro >= '".$date."'";
    }
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "tiendas"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    } 
  }
}
?>