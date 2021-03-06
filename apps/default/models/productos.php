<?php 
require_once CORE_PATH . "models.php";

class Productos_en_venta extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function guardar ($id_tienda, $nombre, $descripcion, $marca, $categoria, $precio, $stock, $imagen, $guardado_por) {
    // $fecha = date("Y")."-".date("m")."-".date("d");
    // $password = md5($password);
    $consulta = "INSERT INTO productos_en_venta (id_tienda, nombre, descripcion, marca, categoria, precio, stock, imagen, guardado_por) VALUES (".$id_tienda.",'".$nombre."','".$descripcion."', '".$marca."', '".$categoria."', ".$precio.", ".$stock.", '".$imagen."', ".$guardado_por.");";
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM productos_en_venta WHERE id = ".$this->conexion->insert_id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"producto_registrado","producto"=>$fila);
    }
  }

  public function actualizar ($id, $nombre, $descripcion, $marca, $categoria, $precio, $stock, $guardado_por) {
    // $fecha = date("Y")."-".date("m")."-".date("d");
    // $password = md5($password);
    $consulta = "UPDATE productos_en_venta SET nombre='".$nombre."', descripcion='".$descripcion."', marca='".$marca."', categoria='".$categoria."', precio=".$precio.", stock=".$stock.", guardado_por=".$guardado_por." WHERE id=".$id;
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM productos_en_venta WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"producto_actualizado", "producto"=>$fila);
    }
  }

  public function eliminar ($id) {
    // $fecha = date("Y")."-".date("m")."-".date("d");
    // $password = md5($password);
    $consulta = "DELETE FROM productos_en_venta WHERE id=".$id;
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      return array("error"=>null, "info"=>"producto_eliminado");
    }
  }

  public function buscar_por_id ($id){
    // $consulta = "SELECT * FROM productos_en_venta WHERE id = ".$id;
    $consulta = "SELECT t1.id AS id_producto, t2.id AS id_tienda, t1.nombre AS nombre_producto, t2.nombre AS nombre_tienda, t1.imagen, t1.marca, t1.descripcion, t1.precio, t1.stock, t1.categoria FROM productos_en_venta AS t1 JOIN tiendas AS t2 WHERE t1.id = ".$id." AND t1.id_tienda=t2.id";
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "producto"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function actualizar_cantidad ($id, $cantidad){
    $consulta = "UPDATE productos_en_venta SET stock=".$cantidad." WHERE id = ".$id;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM admins WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"cantidad_actualizada", "producto"=>$fila);
    }
  }

  public function buscar_por_id_tienda ($id_tienda){
    $consulta = "SELECT t1.id, t1.nombre AS nombre_producto, t1.descripcion, t1.marca, t1.precio, t1.stock, t1.imagen, t1.categoria, t2.nombre AS guardado_por  FROM productos_en_venta as t1 INNER JOIN admins as t2 ON t1.id_tienda = ".$id_tienda." AND t1.guardado_por=t2.id";
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

  public function buscar_por_id_tienda_y_categoria ($id_tienda, $categoria){
    $consulta = "SELECT t1.id, t1.nombre AS nombre_producto, t1.descripcion, t1.marca, t1.precio, t1.stock, t1.imagen, t1.categoria, t2.nombre AS guardado_por  FROM productos_en_venta as t1 INNER JOIN admins as t2 ON t1.id_tienda = ".$id_tienda." AND t1.categoria = '".$categoria."' AND t1.guardado_por=t2.id";
    if($resultado = $this->conexion->query($consulta)){
      // print_r($resultado);
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "productos"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_palabra_clave ($keyword){
    $consulta = "SELECT * FROM productos_en_venta WHERE nombre LIKE '%".$keyword."%' OR descripcion LIKE '%".$keyword."%'";
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

class Productos_vendidos extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function guardar ($id_producto_ev, $id_tienda, $id_cliente, $nombre, $descripcion, $marca, $categoria, $precio, $cantidad, $imagen) {
    $fecha = date("Y")."-".date("m")."-".date("d");
    // $password = md5($password);
    $consulta = "INSERT INTO productos_vendidos (id_producto_ev, id_tienda, id_cliente, nombre, descripcion, marca, categoria, precio, cantidad, imagen, fecha_registro) VALUES (".$id_producto_ev.", ".$id_tienda.", ".$id_cliente.", '".$nombre."','".$descripcion."', '".$marca."', '".$categoria."', ".$precio.", ".$cantidad.", '".$imagen."', '".$fecha."');";
    echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM productos_vendidos WHERE id = ".$this->conexion->insert_id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"producto_registrado","producto"=>$fila);
    }
  }

  public function buscar_por_id ($id){
    $consulta = "SELECT t1.id AS id_producto, t2.id AS id_tienda, t1.id_cliente, t1.nombre AS nombre_producto, t2.nombre AS nombre_tienda, t1.imagen, t1.marca, t1.descripcion, t1.precio, t1.cantidad, t1.categoria FROM productos_vendidos AS t1 JOIN tiendas AS t2 WHERE t1.id = ".$id." AND t1.id_tienda=t2.id";
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "producto"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_id_producto_ev ($id_producto_ev){
    $consulta = "SELECT t1.id AS id_producto, t2.id AS id_tienda, t1.id_cliente, t1.nombre AS nombre_producto, t2.nombre AS nombre_tienda, t1.imagen, t1.marca, t1.descripcion, t1.precio, t1.cantidad, t1.categoria FROM productos_vendidos AS t1 JOIN tiendas AS t2 WHERE t1.id_producto_ev = ".$id_producto_ev." AND t1.id_tienda=t2.id";
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "producto"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function actualizar_cantidad ($id, $cantidad){
    $consulta = "UPDATE productos_en_venta SET stock=".$cantidad." WHERE id = ".$id;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM admins WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"cantidad_actualizada", "producto"=>$fila);
    }
  }

  public function buscar_por_id_tienda ($id_tienda){
    $consulta = "SELECT *, COUNT(*) FROM productos_vendidos WHERE id_tienda = ".$id_tienda." GROUP BY id_producto_ev";
    // echo $consulta;
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

  public function buscar_por_id_tienda_y_categoria ($id_tienda, $categoria){
    $consulta = "SELECT *, COUNT(*) FROM productos_vendidos WHERE id_tienda = ".$id_tienda." AND categoria = '".$categoria."' GROUP BY id_producto_ev";
    if($resultado = $this->conexion->query($consulta)){
      // print_r($resultado);
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "productos"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_palabra_clave ($keyword){
    $consulta = "SELECT * FROM productos_en_venta WHERE nombre LIKE '%".$keyword."%' OR descripcion LIKE '%".$keyword."%'";
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