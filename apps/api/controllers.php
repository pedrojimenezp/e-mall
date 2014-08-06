<?php

// require_once '..'.DS.'default'.DS.'models.php';

function registrar_tienda($req, $res){
  // print_r($req->body());
  // echo "<br>";
  $tiendas =  new Tiendas();
  if ($req->body("nombre_tienda") && $req->body("categoria_tienda") && $req->body("nombre_propietario") && $req->body("email") && $req->body("password")) {
    // echo "Se pasaron los parametros necesarios";
    // echo "<br>";
    $resultado = $tiendas->buscar_por_nombre($req->body("nombre_tienda"));
    // print_r(gettype($resultado["tienda"]));
    // echo "<br>";
    if(!$resultado["tienda"]){
      $admins = new Admins();
      $resultado = $admins->buscar_por_email($req->body("email"));
      if (!$resultado["admin"]) {
        // echo "POr aqui";
        $r = $tiendas->guardar($req->body("nombre_tienda"), $req->body("categoria_tienda"));
        if(!$r["error"]){
          $r2 = $admins->guardar($r["tienda"]["id"], $req->body("nombre_propietario"), $req->body("email"), $req->body("password"), "si");
          if(!$r2["error"]){
            $res->json(array("error"=>null, "tienda"=>$r["tienda"], "admin"=>$r2["admin"]));
          }else{
            $res->json($r2);
          }
        }else{
          $res->json($r);
        }
        // $res->send("Se puede guardar sin problemas la  tienda");
      }else{
        // echo "Este email ya esta registrado nuestra db";
        $datos = array("error"=>"email_ya_registrado", "descripcion"=>"El email que ha pasado ya esta registrado, por favor ingrese envio uno diferente");
        $res->json($datos);
      }
    }else{
      $datos = array("error"=>"tienda_ya_registrada", "descripcion"=>"El nombre de la tienda que ha pasado ya esta registrado, por favor ingrese envio uno diferente");
      $res->json($datos);
      // echo "Este nombre ya esta registrado en la base de datos";
    }
  }else{
    $datos = array("error"=>"datos_incompletos", "descripcion"=>"No envio todos los datos necesarios para guardar una tienda, los datos son (nombre_tienda, nombre_propietario, email, password");
    $res->json($datos);
    // $res->send("No se enviaron todos los parametros necesarions");
  }
}

function registrar_cliente($req, $res){
  // print_r($req->body());
  // echo "<br>";
  if ($req->body("email") && $req->body("password")) {
    // echo "Se pasaron los parametros necesarios";
    // echo "<br>";
    $clientes = new Clientes();
    $resultado = $clientes->buscar_por_email($req->body("email"));
    if (!$resultado["cliente"]) {
      // echo "POr aqui";
      $r = $clientes->guardar($req->body("email"), $req->body("password"));
      if(!$r["error"]){
        $res->json(array("error"=>null, "cliente"=>$r));
      }else{
        $res->json($r);
      }
      // $res->send("Se puede guardar sin problemas la  tienda");
    }else{
      // echo "Este email ya esta registrado nuestra db";
      $datos = array("error"=>"email_ya_registrado", "descripcion"=>"El email que ha pasado ya esta registrado, por favor ingrese envio uno diferente");
      $res->json($datos);
    }
  }else{
    $datos = array("error"=>"datos_incompletos", "descripcion"=>"No envio todos los datos necesarios para guardar una tienda, los datos son (email, password)");
    $res->json($datos);
    // $res->send("No se enviaron todos los parametros necesarions");
  }
}

function iniciar_sesion_admin($req, $res){
  if($req->body("email") && $req->body("password")){
    $admins = new Admins();
    $r = $admins->buscar_por_email_password($req->body("email"), $req->body("password"));
    if($r["admin"]){
      $req->session("sesion_iniciada", true);
      $req->session("tipo_usuario", "admin");
      $req->session("id_tienda", $r["admin"]["id_tienda"]);
      $req->session("id_admin", $r["admin"]["id"]);
      $ids = array("id_tienda" => $r["admin"]["id_tienda"], "id_admin" => $r["admin"]["id"]);

      // print_r($req->get_session());
      $datos = array("error"=>"null", "info"=>"sesion_inicada", "descripcion"=>"Ha iniciado sesion correctamente", "ids"=>$ids);
      $res->json($datos);
    }else{
      $datos = array("error"=>"admin_no_registrado", "descripcion"=>"El email o contraseña son incorrectos");
      $res->json($datos);
    }
  }else{
    $datos = array("error"=>"datos_incompletos", "descripcion"=>"No envio todos los datos necesarios para iniciar sesion, los datos son (email, password)");
    $res->json($datos);
  }
}

function iniciar_sesion_cliente($req, $res){
  if($req->body("email") && $req->body("password")){
    $clientes = new Clientes();
    $r = $clientes->buscar_por_email_password($req->body("email"), $req->body("password"));
    if($r["cliente"]){
      $req->session("sesion_iniciada", true);
      $req->session("tipo_usuario", "cliente");
      $req->session("id_cliente", $r["cliente"]["id"]);
      $ids = array("id_cliente" => $r["cliente"]["id"]);

      // print_r($req->get_session());
      $datos = array("error"=>"null", "info"=>"sesion_inicada", "descripcion"=>"Ha iniciado sesion correctamente", "ids"=>$ids);
      $res->json($datos);
    }else{
      $datos = array("error"=>"cliente_no_registrado", "descripcion"=>"El email o contraseña son incorrectos");
      $res->json($datos);
    }
  }else{
    $datos = array("error"=>"datos_incompletos", "descripcion"=>"No envio todos los datos necesarios para iniciar sesion, los datos son (email, password)");
    $res->json($datos);
  }
}

function cerrar_sesion($req, $res){
  $req->close_session();
  $res->redirect_to("/");
  // $res->render_template("prueba.html");
}

function agregar_a_productos_en_venta($req, $res){
  print_r($req->body());
  $nombre_imagen = $_FILES['imagen']['name'];
  $tmp_imagen = $_FILES['imagen']['tmp_name'];
  $hash = md5(microtime());
  $nombre_imagen = $req->query("id_tienda").$hash.$nombre_imagen; 
  // if(!file_exists("img_products")){
  //     mkdir(PROJECT_PATH."static".DS."img_products");
  // } 
  $ruta = PROJECT_PATH."static".DS."usuarios".DS."imagenes".DS."productos".DS.$nombre_imagen;
  if (!move_uploaded_file($tmp_imagen, $ruta)) {
    echo "move_img_error";
  }else{
    $productos_en_venta = new Productos_en_venta();
    $r = $productos_en_venta->guardar($req->body("id_tienda"),$req->body("nombre"),$req->body("descripcion"),$req->body("marca"),$req->body("categoria"),$req->body("precio"),$req->body("cantidad"),$nombre_imagen, $req->body("id_admin"));
    $res->json($r);
  }
}

function obtener_productos_en_venta($req, $res){
  if ($req->query("id_tienda")) {
    if ($req->query("categoria-productos")) {
      $productos_en_venta = new Productos_en_venta();
      $r = $productos_en_venta->buscar_por_id_tienda_y_categoria($req->query("id_tienda"), $req->query("categoria-productos"));
      $res->json($r);
    }else{
      $productos_en_venta = new Productos_en_venta();
      $r = $productos_en_venta->buscar_por_id_tienda($req->query("id_tienda"));
      $res->json($r);
    }
  }else{
    $r = array("error"=>"faltan_paramentros", "info"=>"Debe enviar el paramentro id_tienda por la  url y utilizar el metodo GET para realizar la peticion");
    $res->json($r);
  }
}

function obtener_datos_admin($req, $res){
  if ($req->query("id_admin")) {
    $admins = new Admins();
    $r = $admins->buscar_por_id($req->query("id_admin"));
    $res->json($r);
  }else{
    $r = array("error"=>"faltan_paramentros", "info"=>"Debe enviar el paramentro id_admin por la  url y utilizar el metodo GET para realizar la peticion");
    $res->json($r); 
  }
}

function obtener_datos_tienda($req, $res){
  if ($req->query("id_tienda")) {
    $tiendas = new Tiendas();
    $r = $tiendas->buscar_por_id($req->query("id_tienda"));
    $res->json($r);
  }else{
    $r = array("error"=>"faltan_paramentros", "info"=>"Debe enviar el paramentro id_tienda por la  url y utilizar el metodo GET para realizar la peticion");
    $res->json($r); 
  }
}

function agregar_producto_al_carrito($req, $res){
  if ($req->body("id_producto") && $req->body("id_cliente") && $req->body("cantidad")) {
    $productos_en_venta = new Productos_en_venta();
    $r = $productos_en_venta->buscar_por_id($req->body("id_producto"));
    if($r["producto"]){
      // $res->json($r["producto"]);
      if ($r["producto"]["stock"]>=$req->body("cantidad")) {
        $carro_de_compras = new Carro_de_compras();
        $r1 = $carro_de_compras->buscar_por_id_producto($req->body("id_producto"));
        if($r1["producto"]){
          if ($r["producto"]["stock"]>=$req->body("cantidad")+$r1["producto"]["cantidad"]) {
            $res->json($r1["producto"]);
            $r2 = $carro_de_compras->actualizar_cantidad($r1["producto"]["id"], $r1["producto"]["cantidad"]+$req->body("cantidad"));
            $res->json($r2);
          }else{
            $res->send("La cantidad que quiere agregar al carrito mas la que ya tiene agregada sobrepasa la cantidad disponible");
          }
        }else{
          $r2 = $carro_de_compras->guardar($req->body("id_cliente"), $req->body("id_producto"), $req->body("cantidad"));
          $res->json($r2);
          // $res->send("Todo bn");
        }
      }else {
        $res->send("La cantidad que quiere agregar al carrito es superior a la que hay disponible");
      }{

      }
    }else{
      $res->send("El id del producto que paso no esta en la base de datos");
    }
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Faltan parametros, debe enviar (id_producto, id_cliente, cantidad)");
    $res->json($error);
  }
}

function eliminar_producto_del_carrito($req, $res){
  if ($req->query("id_carrito") && $req->query("id_cliente")) {
    $carro_de_compras = new Carro_de_compras();
    $r = $carro_de_compras->eliminar_por_id_carro_id_cliente($req->query("id_carrito"), $req->query("id_cliente"));
    $res->json($r);
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Faltan parametros, debe enviar (id_carrito, id_cliente) por el metodo GET");
    $res->json($error);
  }
}

function actualizar_info_personal_admin($req, $res){
  if($req->body("nombre") && $req->body("email") && $req->body("password") && $req->body("id_admin")){
    $admins = new Admins();
    $r = $admins->buscar_por_email($req->body("email"));
    if($r["admin"] && $r["admin"]["id"] == $req->body("id_admin")){
      if ($r["admin"]["password"] == md5($req->body("password"))) {
        if ($r["admin"]["nombre"] != $req->body("nombre") || $r["admin"]["email"] != $req->body("email")) {
          $r2 = $admins->actualizar_info_por_id($req->body("id_admin"), $req->body("nombre"), $req->body("email"));
          $res->json($r2);
        }else{
          $error = array("error"=>"datos_iguales", "info"=>"El nombre e email que paso son los mismos que estan registrados, la operacion no es necesaria");
          $res->json($error);
        }
      }else{
        $error = array("error"=>"password_incorrecta", "info"=>"La password que ha ingresado es incorrecta");
        $res->json($error);  
      }
    }else{
      $error = array("error"=>"email_ya_registrado", "info"=>"El email que paso ya esta regisrado porfavor ingrese otro");
      $res->json($error);  
    }
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (nombre, email, password, id_admin) por el metodo POST");
    $res->json($error);
  }
}

function actualizar_password_admin($req, $res){
  if($req->body("password_actual") && $req->body("password_nueva") && $req->body("id_admin")){
    $admins = new Admins();
    $r = $admins->buscar_por_id($req->body("id_admin"));
    if($r["admin"]){
      if ($r["admin"]["password"] == md5($req->body("password_actual"))) {
        if ($r["admin"]["password"] != md5($req->body("password_nueva"))) {
          $r2 = $admins->actualizar_password_por_id($req->body("id_admin"), $req->body("password_nueva"));
          $res->json($r2);
        }else{
          $error = array("error"=>"datos_iguales", "info"=>"La password que quiere ingresar como nueva es la misa que la antigua, la operacion no es necesaria");
          $res->json($error);
        }
      }else{
        $error = array("error"=>"password_incorrecta", "info"=>"La password que ha ingresado es incorrecta");
        $res->json($error);  
      }
    }else{
      $error = array("error"=>"admin_no_registrado", "info"=>"El id que paso no esta asociado a ningun administrador");
      $res->json($error);  
    }
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (nombre, email, password, id_admin) por el metodo POST");
    $res->json($error);
  }
}

function agregar_sub_admins ($req, $res){
  if($req->body("password_admin") && $req->body("password_sub_admin") && $req->body("nombre_sub_admin") && $req->body("email_sub_admin") && $req->body("id_tienda") && $req->body("id_admin")){
    $admins = new Admins();
    $r = $admins->buscar_por_id($req->body("id_admin"));
    if ($r["admin"]) {
      if($r["admin"]["password"] == md5($req->body("password_admin"))){
        if ($r["admin"]["es_propietario"] == "si" && $r["admin"]["id_tienda"] == $req->body("id_tienda")) {
          $r1 = $admins->buscar_por_email($req->body("email_sub_admin"));
          if (!$r1["admin"]) {
            $r2 = $admins->guardar($req->body("id_tienda"), $req->body("nombre_sub_admin"), $req->body("email_sub_admin"), $req->body("password_sub_admin"), "no");
            $res->json($r2); 
          }else{
            $datos = array("error"=>"email_ya_registrado", "descripcion"=>"El email que ha pasado ya esta registrado, por favor ingrese envio uno diferente");
            $res->json($datos);
          }
        }else{
          $error = array("error"=>"no_es_el_propietario", "info"=>"Usted no es el propietario de la tienda que paso como parametro, la operacion no se puede efectuar");
          $res->json($error);  
        }
      }else{
        $error = array("error"=>"password_incorrecta", "info"=>"La password que ha ingresado es incorrecta");
        $res->json($error);
      }
    }else{
      $error = array("error"=>"admin_no_registrado", "info"=>"El id_admin que paso no esta asociado a ningun administrador");
      $res->json($error); 
    }
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (id_admin, id_tienda, password_admin, nombre_sub_admin, email_sub_admin, password_sub_admin) por el metodo POST");
    $res->json($error);
  }
}

function crear_pedido ($req, $res){
  if($req->body("id_cliente") && $req->body("id_tienda") && $req->body("productos_cantidad")){
    $p_c = substr($req->body("productos_cantidad"), 0, -1);
    $productos_cantidad = explode(",", $p_c);
    $se_puede_hacer_checkout = true;
    $productos_en_venta = new Productos_en_venta();
    $error = array();
    foreach ($productos_cantidad as $producto_cantidad) {
      if ($producto_cantidad) {
        $pc = explode(":", $producto_cantidad);
        $producto = $pc[0];
        $cantidad = $pc[1];
        $r = $productos_en_venta->buscar_por_id($producto);
        if ($r["producto"]) {
          if ($r["producto"]["stock"] < $cantidad) {
            $se_puede_hacer_checkout = false;
            $error = array('error' => "cantidad_no_disponible", "info"=> "Quiere comprar (".$cantidad.") de (".$r["producto"]["nombre"].") y el vendedor tiene disponible (".$r["producto"]["stock"].")");
            // array_push($errores, $error);
          }
        }else{
          $se_puede_hacer_checkout = false;
          $error = array('error' => "producto_no_registrado", "info"=> "Quiere comprar un producto (".$r["producto"]["nombre"].") que no esta registrado en nuestra base de datos");
          // array_push($errores, $error);
          break;
        }
        // $res->send(array("Producto"=>$producto, "Cantidad"=>$cantidad));
      }
    }
    if ($se_puede_hacer_checkout) {
      $pedidos = new Pedidos();
      $r1 = $pedidos->guardar($req->body("id_tienda"), $req->body("id_cliente"), $p_c, "sin confirmar");
      $carro_de_compras = new Carro_de_compras();
      foreach ($productos_cantidad as $producto_cantidad) {
        if ($producto_cantidad) {
          $pc = explode(":", $producto_cantidad);
          $producto = $pc[0];
          $cantidad = $pc[1];
          $r2 = $carro_de_compras->eliminar_por_id_cliente_id_producto($req->body("id_cliente"), $producto);
        }
      } 
      $res->json($r1);
    }else{
      $res->json($error);
    }
    // $res->json($req->body());
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (id_cliente, id_tienda, productos_en_venta) por el metodo POST");
    $res->json($error);
  }
}

function cambiar_estado_pedido_cancelado($req, $res){
  if ($req->body("id_pedido")) {
    $pedidos = new Pedidos();
    $r = $pedidos->buscar_por_id($req->body("id_pedido"));
    if ($r["pedido"]) {
      $r2 = $pedidos->actualizar_estado($req->body("id_pedido"), "cancelado");
      $res->json($r2);
    }else{
      $error = array("error"=>"pedido_no_encontrado", "info"=>"El id que ingreso no esta asociado a ningun  pedido");
      $res->json($error);  
    }
  }else if ($req->query("id_pedido")) {
    $pedidos = new Pedidos();
    $r = $pedidos->buscar_por_id($req->query("id_pedido"));
    if ($r["pedido"]) {
      $r2 = $pedidos->actualizar_estado($req->query("id_pedido"), "cancelado");
      $res->json($r2);
    }else{
      $error = array("error"=>"pedido_no_encontrado", "info"=>"El id que ingreso no esta asociado a ningun  pedido");
      $res->json($error);  
    }
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (id_pedido) por el metodo POST o GET");
    $res->json($error);
  }
}

function cambiar_estado_pedido_pagado($req, $res){
  if ($req->body("id_pedido")) {
    $pedidos = new Pedidos();
    $r = $pedidos->buscar_por_id($req->body("id_pedido"));
    if ($r["pedido"]) {
      $r2 = $pedidos->actualizar_estado($req->body("id_pedido"), "pagado");
      $res->json($r2);
    }else{
      $error = array("error"=>"pedido_no_encontrado", "info"=>"El id que ingreso no esta asociado a ningun  pedido");
      $res->json($error);  
    }
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (id_pedido) por el metodo POST");
    $res->json($error);
  }
}

function cambiar_estado_pedido_enviado($req, $res){
  if ($req->body("id_pedido")) {
    $pedidos = new Pedidos();
    $r = $pedidos->buscar_por_id($req->body("id_pedido"));
    if ($r["pedido"]) {
      $r2 = $pedidos->actualizar_estado($req->body("id_pedido"), "enviado");
      $res->json($r2);
    }else{
      $error = array("error"=>"pedido_no_encontrado", "info"=>"El id que ingreso no esta asociado a ningun  pedido");
      $res->json($error);  
    }
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (id_pedido) por el metodo POST");
    $res->json($error);
  }
}

function cambiar_estado_pedido_entregado($req, $res){
  if ($req->body("id_pedido")) {
    $pedidos = new Pedidos();
    $r = $pedidos->buscar_por_id($req->body("id_pedido"));
    if ($r["pedido"]) {
      $r2 = $pedidos->actualizar_estado($req->body("id_pedido"), "entregado");
      $res->json($r2);
    }else{
      $error = array("error"=>"pedido_no_encontrado", "info"=>"El id que ingreso no esta asociado a ningun  pedido");
      $res->json($error);  
    }
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (id_pedido) por el metodo POST");
    $res->json($error);
  }
}

function notificacion_pago_inmediato($req, $res){
  if ($req->body("payment_status") && $req->body("item_number")) {
    if ($req->body("payment_status")=="Completed" || $req->body("payment_status")=="Processed") {
      $pedidos = new Pedidos();
      $r = $pedidos->buscar_por_id($req->body("item_number"));
      if ($r["pedido"]) {
        $r2 = $pedidos->actualizar_estado($req->body("item_number"), "pagado");
        $p_c = $r2["pedido"]["ids_productos_cantidad"];
        $productos_cantidad = explode(",", $p_c);
        $carro_de_compras = new Carro_de_compras();
        $nuevos_ids_pc = "";
        foreach ($productos_cantidad as $producto_cantidad) {
          if ($producto_cantidad) {
            $pc = explode(":", $producto_cantidad);
            $id_producto = $pc[0];
            $cantidad = $pc[1];
            $productos_en_venta = new Productos_en_venta();
            $r3 = $productos_en_venta->buscar_por_id($id_producto);
            if ($r3["producto"]) {
              $r4 = $productos_en_venta->actualizar_cantidad($id_producto, $r3["producto"]["stock"]-$cantidad);
              $productos_vendidos = new Productos_vendidos();
              $r5 = $productos_vendidos->guardar($id_producto, $r3["producto"]["id_tienda"], $r2["pedido"]["id_cliente"], $r3["producto"]["nombre_producto"], $r3["producto"]["descripcion"], $r3["producto"]["marca"], $r3["producto"]["categoria"], $r3["producto"]["precio"], $cantidad, $r3["producto"]["imagen"]);
              $nuevos_ids_pc = $nuevos_ids_pc.$r5["producto"]["id"].":".$cantidad.",";

              $r6 = $carro_de_compras->eliminar_por_id_cliente_id_producto($r2["pedido"]["id_cliente"], $id_producto);
              $notificaciones = new Notificaciones();
              $r7 = $notificaciones->guardar($r3["producto"]["id_tienda"], "nuevo_pedido", $r["pedido"]["id"], "no leida");
              $res->send("Todo bien");
            }
          }
        }
        $nuevos_ids_pc = substr($nuevos_ids_pc, 0, -1);
        $r7 = $pedidos->actualizar_ids_pc($req->body("item_number"), $nuevos_ids_pc);
        $res->json($r7["pedido"]);
      }else{
        $error = array("error"=>"pedido_no_encontrado", "info"=>"El id que ingreso no esta asociado a ningun  pedido");
        $res->json($error);  
      }
    }else{
      $res->send("No se  puede hacer nada");
    }
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (payment_status, item_number) por el metodo POST");
    $res->json($error);
  }
}

function enviar_pedido($req, $res){
  if ($req->query("id_pedido")) {
    $pedidos = new Pedidos();
    $r = $pedidos->cambiar_estado_a_enviado($req->query("id_pedido"));
    $res->json($r);
  }else{
    $res->send("Debe pasar el id del pediddo a enviar");
  }
}

function entregar_pedido($req, $res){
  if ($req->query("id_pedido")) {
    $pedidos = new Pedidos();
    $r = $pedidos->cambiar_estado_a_entregado($req->query("id_pedido"));
    $res->json($r);
  }else{
    $res->send("Debe pasar el id del pediddo a entregar");
  }
}

function eliminar_pedido ($req, $res){
  if ($req->query("id_pedido")) {
    $pedidos = new Pedidos();
    $r = $pedidos->eliminar_por_id($req->query("id_pedido"));
    $res->json($r);
  }else{
    $res->send("Debe pasar el id del pediddo a eliminar");
  }
}

function obtener_datos_cliente($req, $res){
  if ($req->query("id_cliente")) {
    $datos_clientes = new Datos_clientes();
    $r = $datos_clientes->buscar_por_id_cliente($req->query("id_cliente"));
    $res->json($r);
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (id_cliente) por el metodo GET");
    $res->json($error);
  }
}

function actualizar_datos_cliente($req, $res){
  if($req->body("nombre") && $req->body("edad") && $req->body("password") && $req->body("id_cliente") && $req->body("direccion") && $req->body("barrio") && $req->body("telefonos")){
    $clientes = new Clientes();
    $r = $clientes->buscar_por_id($req->body("id_cliente"));
    if (isset($r["cliente"])) {
      if ($r["cliente"]["password"] == md5($req->body("password"))) {
        $datos_clientes = new Datos_clientes();
        $r1 = $datos_clientes->actualizar($req->body("id_cliente"), $req->body("nombre"), $req->body("edad"), $req->body("direccion"), $req->body("barrio"), $req->body("telefonos"));
        $res->json($r1);
      }else{
        $error = array("error"=>"password_incorrecta", "info"=>"El password que ingreso no es el correcto, la operacion no se puede realizar");
      $res->json($error);
      }
    }else{
      $error = array("error"=>"cliete_no_encontrado", "info"=>"El id_cliente que acaba de pasar no esta asociado a ningun cliente en nuestra tienda");
      $res->json($error);
    }
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Debe enviar los parametros (id_cliente, nombre, edad, direccion, barrio, telefonos, password) por el metodo POST");
    $res->json($error);
  }
}

?>