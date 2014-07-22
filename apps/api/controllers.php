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
    $productos_en_venta = new Productos_en_venta();
    $r = $productos_en_venta->buscar_por_id_tienda($req->query("id_tienda"));
    $res->json($r);
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
    $error = array("error"=>"faltan_parametros", "info"=>"Faltan parametros, debe enviar (id_producto, id_cliente, cantidad");
    $res->json($error);
  }
}

function eliminar_producto_del_carrito($req, $res){
  if ($req->query("id_carrito") && $req->query("id_cliente")) {
    $carro_de_compras = new Carro_de_compras();
    $r = $carro_de_compras->eliminar_por_id_carro_id_cliente($req->query("id_carrito"), $req->query("id_cliente"));
    $res->json($r);
  }else{
    $error = array("error"=>"faltan_parametros", "info"=>"Faltan parametros, debe enviar (id_carrito, id_cliente");
    $res->json($error);
  }
}

?>