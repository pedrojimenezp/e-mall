<?php 
require_once CORE_PATH . "models.php";

class Carro_de_compras extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function guardar ($id_cliente, $id_producto, $cantidad) {
    // $fecha = date("Y")."-".date("m")."-".date("d");
    // $password = md5($password);
    $consulta = "INSERT INTO carro_de_compras (id_cliente, id_producto, cantidad) VALUES (".$id_cliente.",".$id_producto.",".$cantidad.");";
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM carro_de_compras WHERE id = ".$this->conexion->insert_id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"producto_agregado","producto"=>$fila);
    }
  }

  public function buscar_todos (){
    $consulta = "SELECT t1.id AS id_producto, t2.id AS id_carro_compra, t1.nombre AS nombre_producto, t1.imagen, t1.marca, t1.descripcion, t1.precio, t2.cantidad, t3.nombre AS nombre_tienda FROM productos_en_venta AS t1 JOIN carro_de_compras AS t2 JOIN tiendas AS t3";
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "productos"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_id ($id){
    $consulta = "SELECT t1.id AS id_producto, t2.id AS id_carro_compra, t1.nombre AS nombre_producto, t1.imagen, t1.marca, t1.descripcion, t1.precio, t2.cantidad, t2.id_cliente, t3.nombre AS nombre_tienda FROM productos_en_venta AS t1 JOIN carro_de_compras AS t2 JOIN tiendas AS t3 WHERE t2.id = ".$id." AND t2.id_producto=t1.id AND t1.id_tienda=t3.id";
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "producto"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function eliminar_por_id ($id){
    $consulta = "DELETE FROM carro_de_compras as WHERE id = ".$id;
    if($resultado = $this->conexion->query($consulta)){
      return array("error"=>null, "info"=>"producto eliminado");
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }
  public function eliminar_por_id_carro_id_cliente ($id_carro, $id_cliente){
    $consulta = "DELETE FROM carro_de_compras WHERE id = ".$id_carro." AND id_cliente=".$id_cliente;
    if($resultado = $this->conexion->query($consulta)){
      return array("error"=>null, "info"=>"producto eliminado");
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function actualizar_cantidad ($id, $cantidad){
    $consulta = "UPDATE carro_de_compras SET cantidad=".$cantidad." WHERE id = ".$id;
    echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM carro_de_compras WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"producto_actualizado", "producto"=>$fila);
    } 
  }

  public function buscar_por_id_producto ($id_producto){
    $consulta = "SELECT * FROM carro_de_compras WHERE id_producto = ".$id_producto;
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "producto"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_id_cliente ($id_cliente){
    $consulta = "SELECT t1.id AS id_producto, t2.id AS id_carro_compra, t1.nombre AS nombre_producto, t1.imagen, t1.marca, t1.descripcion, t1.precio, t2.cantidad, t2.id_cliente, t3.nombre AS nombre_tienda FROM productos_en_venta AS t1 JOIN carro_de_compras AS t2 JOIN tiendas AS t3 WHERE t2.id_cliente = ".$id_cliente." AND t2.id_producto=t1.id AND t1.id_tienda=t3.id ORDER BY  t3.nombre ";
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "productos"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

}
?>