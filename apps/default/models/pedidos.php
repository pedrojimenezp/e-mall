<?php 
require_once CORE_PATH . "models.php";

class Pedidos extends Models{
  private  $conexion;
  function __construct(){
    parent::__construct();
    $this->conexion = $this->connect();
  }

  function __destruct() {
    $this->disconnect();
  }

  public function guardar ($id_tienda, $id_cliente, $ids_productos_cantidad, $estado) {
    $fecha = date("Y")."-".date("m")."-".date("d");
    $consulta = "INSERT INTO pedidos (id_tienda, id_cliente, ids_productos_cantidad, estado, fecha_registro) VALUES (".$id_tienda.", ".$id_cliente.", '".$ids_productos_cantidad."', '".$estado."', '".$fecha."');";
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM pedidos WHERE id = ".$this->conexion->insert_id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"pedido_registrado","pedido"=>$fila);
    }
  }

  public function buscar_por_id ($id){
    $consulta = "SELECT * FROM pedidos WHERE id = ".$id." ORDER BY 'fecha_registro' DESC";
    if($resultado = $this->conexion->query($consulta)){
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "pedido"=>$fila);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_id_cliente ($id_cliente, $estado=null){
    if ($estado == null) {
      $consulta = "SELECT * FROM pedidos WHERE id_cliente = ".$id_cliente." ORDER BY 'fecha_registro' ASC";
    }else{
      $consulta = "SELECT * FROM pedidos WHERE id_cliente = ".$id_cliente." AND estado='".$estado."' ORDER BY 'fecha_registro' ASC";
    }
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "pedidos"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function buscar_por_id_tienda ($id_tienda, $estado=null){
    if ($estado == null) {
      $consulta = "SELECT * FROM pedidos WHERE id_tienda = ".$id_tienda." ORDER BY 'fecha_registro' ASC";
    }else{
      $consulta = "SELECT * FROM pedidos WHERE id_tienda = ".$id_tienda." AND estado='".$estado."' ORDER BY 'fecha_registro' ASC";
    }
    if($resultado = $this->conexion->query($consulta)){
      $filas = array();
      while ($fila = $resultado->fetch_assoc()) {
        array_push($filas, $fila);
      }
      return array("error"=>null, "pedidos"=>$filas);
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }



  public function eliminar_por_id ($id){
    $consulta = "DELETE FROM pedidos WHERE id = ".$id;
    if($resultado = $this->conexion->query($consulta)){
      return array("error"=>null, "info"=>"pedido eliminado");
    }else{
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;
    }
  }

  public function actualizar_estado ($id, $estado){
    $consulta = "UPDATE pedidos SET estado='".$estado."' WHERE id = ".$id;
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM pedidos WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"pedido_actualizado", "pedido"=>$fila);
    } 
  }

  public function actualizar_ids_pc ($id, $ids_pc){
    $consulta = "UPDATE pedidos SET ids_productos_cantidad='".$ids_pc."' WHERE id = ".$id;
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM pedidos WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"pedido_actualizado", "pedido"=>$fila);
    } 
  }

  public function cambiar_estado_a_enviado ($id){
    $fecha = date("Y")."-".date("m")."-".date("d");
    
    $consulta = "UPDATE pedidos SET estado='enviado', fecha_envio='".$fecha."' WHERE id = ".$id;
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM pedidos WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"pedido_actualizado", "pedido"=>$fila);
    }
  }

  public function cambiar_estado_a_entregado ($id){
    $fecha = date("Y")."-".date("m")."-".date("d");
    
    $consulta = "UPDATE pedidos SET estado='entregado', fecha_entrega='".$fecha."' WHERE id = ".$id;
    // echo $consulta;
    if(!$this->conexion->query($consulta)){
      echo "Error: (" . $this->conexion->errno . ") " . $this->conexion->error;  
    }else{
      $consulta = "SELECT * FROM pedidos WHERE id = ".$id;
      $resultado = $this->conexion->query($consulta);
      $fila = $resultado->fetch_assoc();
      return array("error"=>null, "info"=>"pedido_actualizado", "pedido"=>$fila);
    }
  }
}
?>