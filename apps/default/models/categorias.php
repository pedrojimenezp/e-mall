<?php 
require_once CORE_PATH . "models.php";

/* Clase que abstrae los metodos para manejar lo relacionado con las tiendas*/
class Categorias_tiendas extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function buscar_todas () {
    $consulta = "SELECT * FROM categorias_tiendas";   
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "categorias_tiendas"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
    
  }
}

class Categorias_productos extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function buscar_todas () {
    $consulta = "SELECT * FROM categorias_productos";   
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "categorias_productos"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
    
  }

  public function buscar_por_categoria_tienda ($categoria_tienda) {
    $consulta = "SELECT * FROM categorias_productos WHERE categoria_tienda = '".$categoria_tienda."'";
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "categorias_productos"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
    
  }
}
?>