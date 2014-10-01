<?php
// require_once 'models'.DS.'tiendas.php';
// require_once 'models.php';

function mostrar_plantilla_inicio($req, $res){
  if($req->session("sesion_iniciada")){
    if($req->session("tipo_usuario") == "admin"){
      $res->redirect_to("/dashboard/mis-productos/en-venta");
    }elseif ($req->session("tipo_usuario") == "cliente") {
      $res->redirect_to("/tiendas");
      // $res->send("Se inicio sesion como cliente");
    }elseif ($req->session("tipo_usuario") == "superadmin") {
      $res->send("Se inicio sesion como superadmin");
    }
  }else{
    // $res->send("Hola esta es la pagina de inicio");
    $categorias_tiendas = new Categorias_tiendas();
    $r2 = $categorias_tiendas->buscar_todas();
    $params = array("categorias_tiendas"=>$r2["categorias_tiendas"]);
    $res->render_template("index.html", $params);
  }
}

function mostrar_plantilla_iniciar_sesion($req, $res){
  if($req->session("sesion_iniciada")){
    $res->redirect_to("/");
  }else{
    $params = array();
    if ($req->query("redireccionar-a")) {
      $params["nombre_tienda"] = $req->query("redireccionar-a");
      if ($req->query("categoria-productos")) {
        $params["categoria_productos"] = $req->query("categoria-productos");
      }
    }
    if($req->query("email-cliente")!=null){
      $params["email-cliente"] = $req->query("email-cliente");
    }
    if($req->query("email-admin")!=null){
      $params["email-admin"] = $req->query("email-admin");
    }
    $res->render_template("iniciar_sesion.html", $params);
  }
}

function mostrar_plantilla_registrar_tienda($req, $res){
  if($req->session("sesion_iniciada")){
    $res->redirect_to("/");
  }else{
    $categorias_tiendas = new  Categorias_tiendas();
    $r = $categorias_tiendas->buscar_todas();
    $p = array('categorias_tiendas' => $r["categorias_tiendas"]);
    $res->render_template("registrar_tienda.html", $p);
  }
}

function mostrar_plantilla_registrar_cliente ($req, $res){
  if($req->session("sesion_iniciada")){
    $res->redirect_to("/");
  }else{
    $res->render_template("registrar_cliente.html");
  }
}

function notificaciones_tienda($id_receptor){
  $notificaciones = new Notificaciones();
  $n = array();
  $r = $notificaciones->buscar_por_id_receptor($id_receptor, "no leida");
  foreach ($r["notificaciones"] as $notificacion) {
    if(!isset($n[$notificacion["tipo"]])){
      $n[$notificacion["tipo"]] = array();
    }
    array_push($n[$notificacion["tipo"]], $notificacion);
  }

  return $n;
}

function notificaciones_cliente($id_receptor){
  $notificaciones = new Notificaciones();
  $n = array();
  $r = $notificaciones->buscar_por_id_receptor($id_receptor, "no leida");
  foreach ($r["notificaciones"] as $notificacion) {
    if(!isset($n[$notificacion["tipo"]])){
      $n[$notificacion["tipo"]] = array();
    }
    array_push($n[$notificacion["tipo"]], $notificacion);
  }

  return $n;
}

function mostrar_plantilla_productos_en_venta($req, $res){
  if($req->session("sesion_iniciada")){
    if ($req->session("tipo_usuario") == "admin") {
      $admins = new Admins();
      $r = $admins->buscar_por_id($req->session("id_admin"));
      
      $tiendas = new Tiendas();
      $r2 = $tiendas->buscar_por_id($req->session("id_tienda"));
      
      $categorias_productos = new Categorias_productos();
      $r3 = $categorias_productos->buscar_por_categoria_tienda($r2["tienda"]["categoria"]);
      

      $nt = notificaciones_tienda($req->session("id_tienda"));
      if ($r["admin"]) {
        $params = array(
          "admin"=>$r["admin"], 
          "tienda"=>$r2["tienda"], 
          "categorias_productos"=>$r3["categorias_productos"],
          "notificaciones"=> $nt
        );
        $res->render_template("dashboard/productos_en_venta.html", $params);
      }else{
        print_r($r);
      }
    }elseif ($req->session("tipo_usuario") == "cliente") {
      $res->redirect_to("/tiendas");
      // $res->send("Se inicio sesion como cliente");
    }
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_productos_vendidos($req, $res){
  if($req->session("sesion_iniciada")){
    if ($req->session("tipo_usuario") == "admin") {
      $admins = new Admins();
      $r = $admins->buscar_por_id($req->session("id_admin"));
      
      $tiendas = new Tiendas();
      $r2 = $tiendas->buscar_por_id($req->session("id_tienda"));

      $categorias_productos = new Categorias_productos();
      $r3 = $categorias_productos->buscar_por_categoria_tienda($r2["tienda"]["categoria"]);
      

      $nt = notificaciones_tienda($req->session("id_tienda"));
      if ($r["admin"]) {
        $params = array(
          "admin"=>$r["admin"], 
          "tienda"=>$r2["tienda"], 
          "categorias_productos"=>$r3["categorias_productos"],
          "notificaciones"=> $nt
        );
        $res->render_template("dashboard/productos_vendidos.html", $params);
      }else{
        print_r($r);
      }
    }elseif ($req->session("tipo_usuario") == "cliente") {
      $res->redirect_to("/tiendas");
      // $res->send("Se inicio sesion como cliente");
    }
  }else{
    $res->redirect_to("/");
  }
}


function mostrar_plantilla_pedidos_nuevos($req, $res){
  if($req->session("sesion_iniciada")){
    if ($req->session("tipo_usuario") == "admin") {
      $admins = new Admins();
      $r = $admins->buscar_por_id($req->session("id_admin"));
      
      $tiendas = new Tiendas();
      $r2 = $tiendas->buscar_por_id($req->session("id_tienda"));
      
      $pedidos = new Pedidos();
      $r3 = $pedidos->buscar_por_id_tienda($req->session("id_tienda"), "pagado");
      
      $lista_pedidos = array();
      foreach ($r3["pedidos"] as $p) {
        $pedido = array();
        $productos_cantidad = explode(",", $p["ids_productos_cantidad"]);
        $productos = array();
        foreach ($productos_cantidad as $producto_cantidad) {
          if ($producto_cantidad) {
            $pc = explode(":", $producto_cantidad);
            $producto = $pc[0];
            $cantidad = $pc[1];
            if ($p["estado"] == "pagado" || $p["estado"] == "enviado" || $p["estado"] == "entregado") {
              $productos_vendidos = new Productos_vendidos();
              $r4 = $productos_vendidos->buscar_por_id($producto);
            }else{
              $productos_en_venta = new Productos_en_venta();
              $r4 = $productos_en_venta->buscar_por_id($producto);
            }
            if ($r4["producto"]) {
              $p2 = array("producto"=>$r4["producto"], "cantidad_comprada"=>$cantidad);
              array_push($productos, $p2);
            }
          }
        }
        $pedido["productos"] = $productos;
        $pedido["estado"] = $p["estado"];
        $pedido["fecha_registro"] = $p["fecha_registro"];
        $pedido["id_pedido"] = $p["id"];
        $pedido["id_cliente"] = $p["id_cliente"];
        array_push($lista_pedidos, $pedido);
      }

      $notificaciones = new Notificaciones();
      $notificaciones->actualizar_estado_por_id_receptor($req->session("id_tienda"), "leida", "nuevo_pedido");
      $nt = notificaciones_tienda($req->session("id_tienda"));
      if ($r["admin"]) {
        $params = array(
          "admin"=>$r["admin"], 
          "tienda"=>$r2["tienda"], 
          "pedidos_nuevos"=>$lista_pedidos,
          "notificaciones"=>$nt
        );
        $res->render_template("dashboard/pedidos_nuevos.html", $params);
      }else{
        print_r($r);
      }
    }elseif ($req->session("tipo_usuario") == "cliente") {
      $res->redirect_to("/tiendas");
      // $res->send("Se inicio sesion como cliente");
    }
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_pedidos_enviados($req, $res){
  if($req->session("sesion_iniciada")){
    if ($req->session("tipo_usuario") == "admin") {
      $admins = new Admins();
      $r = $admins->buscar_por_id($req->session("id_admin"));
      
      $tiendas = new Tiendas();
      $r2 = $tiendas->buscar_por_id($req->session("id_tienda"));
      
      $pedidos = new Pedidos();
      $r3 = $pedidos->buscar_por_id_tienda($req->session("id_tienda"), "enviado");
      
      $lista_pedidos = array();
      foreach ($r3["pedidos"] as $p) {
        $pedido = array();
        $productos_cantidad = explode(",", $p["ids_productos_cantidad"]);
        $productos = array();
        foreach ($productos_cantidad as $producto_cantidad) {
          if ($producto_cantidad) {
            $pc = explode(":", $producto_cantidad);
            $producto = $pc[0];
            $cantidad = $pc[1];
            if ($p["estado"] == "pagado" || $p["estado"] == "enviado" || $p["estado"] == "entregado") {
              $productos_vendidos = new Productos_vendidos();
              $r4 = $productos_vendidos->buscar_por_id($producto);
            }else{
              $productos_en_venta = new Productos_en_venta();
              $r4 = $productos_en_venta->buscar_por_id($producto);
            }
            if ($r4["producto"]) {
              $p2 = array("producto"=>$r4["producto"], "cantidad_comprada"=>$cantidad);
              array_push($productos, $p2);
            }
          }
        }
        $pedido["productos"] = $productos;
        $pedido["estado"] = $p["estado"];
        $pedido["fecha_registro"] = $p["fecha_registro"];
        $pedido["fecha_envio"] = $p["fecha_envio"];
        $pedido["id_pedido"] = $p["id"];
        $pedido["id_cliente"] = $p["id_cliente"];
        array_push($lista_pedidos, $pedido);
      }
      $nt = notificaciones_tienda($req->session("id_tienda"));
      if ($r["admin"]) {
        $params = array(
          "admin"=>$r["admin"], 
          "tienda"=>$r2["tienda"], 
          "pedidos_enviados"=>$lista_pedidos,
          "notificaciones"=>$nt
        );
        $res->render_template("dashboard/pedidos_enviados.html", $params);
      }else{
        print_r($r);
      }
    }elseif ($req->session("tipo_usuario") == "cliente") {
      $res->redirect_to("/tiendas");
      // $res->send("Se inicio sesion como cliente");
    }
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_pedidos_entregados($req, $res){
  if($req->session("sesion_iniciada")){
    if ($req->session("tipo_usuario") == "admin") {
      $admins = new Admins();
      $r = $admins->buscar_por_id($req->session("id_admin"));
      
      $tiendas = new Tiendas();
      $r2 = $tiendas->buscar_por_id($req->session("id_tienda"));
      
      $pedidos = new Pedidos();
      $r3 = $pedidos->buscar_por_id_tienda($req->session("id_tienda"), "entregado");

      $lista_pedidos = array();
      foreach ($r3["pedidos"] as $p) {
        $pedido = array();
        $productos_cantidad = explode(",", $p["ids_productos_cantidad"]);
        $productos = array();
        foreach ($productos_cantidad as $producto_cantidad) {
          if ($producto_cantidad) {
            $pc = explode(":", $producto_cantidad);
            $producto = $pc[0];
            $cantidad = $pc[1];
            if ($p["estado"] == "pagado" || $p["estado"] == "enviado" || $p["estado"] == "entregado") {
              $productos_vendidos = new Productos_vendidos();
              $r4 = $productos_vendidos->buscar_por_id($producto);
            }else{
              $productos_en_venta = new Productos_en_venta();
              $r4 = $productos_en_venta->buscar_por_id($producto);
            }
            if ($r4["producto"]) {
              $p2 = array("producto"=>$r4["producto"], "cantidad_comprada"=>$cantidad);
              array_push($productos, $p2);
            }
          }
        }
        $pedido["productos"] = $productos;
        $pedido["estado"] = $p["estado"];
        $pedido["fecha_registro"] = $p["fecha_registro"];
        $pedido["fecha_envio"] = $p["fecha_envio"];
        $pedido["fecha_entrega"] = $p["fecha_entrega"];
        $pedido["id_pedido"] = $p["id"];
        $pedido["id_cliente"] = $p["id_cliente"];
        $pedido["confirmado_por_cliente"] = $p["confirmado_por_cliente"];
        array_push($lista_pedidos, $pedido);
      }
      $notificaciones = new Notificaciones();
      $notificaciones->actualizar_estado_por_id_receptor($req->session("id_tienda"), "leida", "pedido_confirmado");
      $nt = notificaciones_tienda($req->session("id_tienda"));
      if ($r["admin"]) {
        $params = array(
          "admin"=>$r["admin"], 
          "tienda"=>$r2["tienda"], 
          "pedidos_entregados"=>$lista_pedidos,
          "notificaciones"=>$nt
        );
        // $res->json($params);
        $res->render_template("dashboard/pedidos_entregados.html", $params);
      }else{
        print_r($r);
      }
    }elseif ($req->session("tipo_usuario") == "cliente") {
      $res->redirect_to("/tiendas");
      // $res->send("Se inicio sesion como cliente");
    }
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_configuraciones($req, $res){
  if($req->session("sesion_iniciada")){
    if ($req->session("tipo_usuario") == "admin") {
      $admins = new Admins();
      $r = $admins->buscar_por_id($req->session("id_admin"));
      $tiendas = new Tiendas();
      $r2 = $tiendas->buscar_por_id($req->session("id_tienda"));
      $nt = notificaciones_tienda($req->session("id_tienda"));
      $r3 = $admins->buscar_por_id_tienda($req->session("id_tienda"), "no");
      if ($r["admin"]) {
        $params = array(
          "admin"=>$r["admin"], 
          "tienda"=>$r2["tienda"],
          "admins"=>$r3["admins"],
          "notificaciones"=>$nt
        );
        $res->render_template("dashboard/configuraciones.html", $params);
      }else{
        print_r($r);
      }
    }elseif ($req->session("tipo_usuario") == "cliente") {
      $res->redirect_to("/tiendas");
      // $res->send("Se inicio sesion como cliente");
    }
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_resultado_productos($req, $res){
  if($req->query("keyword")){
    $productos_en_venta = new Productos_en_venta();
    $r = $productos_en_venta->buscar_por_palabra_clave($req->query("keyword"));
    $res->json($r);
  }else{
    $res->send("No ingreso algo en el campo de busqueda");
  }
}





function mostrar_plantilla_pedidos($req, $res){
  if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
    $datos_clientes = new Datos_clientes();
    $r = $datos_clientes->buscar_por_id_cliente($req->session("id_cliente"));
    if ($r["datos_cliente"]["nombre"] && $r["datos_cliente"]["edad"] && $r["datos_cliente"]["direccion"] && $r["datos_cliente"]["barrio"] && $r["datos_cliente"]["telefonos"]) {

      $pedidos = new Pedidos();
      $r = $pedidos->buscar_por_id_cliente($req->session("id_cliente"));
      $lista_pedidos = array();
      foreach ($r["pedidos"] as $p) {
        $pedido = array();
        $productos_cantidad = explode(",", $p["ids_productos_cantidad"]);
        $productos = array();
        foreach ($productos_cantidad as $producto_cantidad) {
          if ($producto_cantidad) {
            $pc = explode(":", $producto_cantidad);
            $producto = $pc[0];
            $cantidad = $pc[1];
            if ($p["estado"] == "pagado" || $p["estado"] == "enviado" || $p["estado"] == "entregado") {
              $productos_vendidos = new Productos_vendidos();
              $r1 = $productos_vendidos->buscar_por_id($producto);
            }else{
              $productos_en_venta = new Productos_en_venta();
              $r1 = $productos_en_venta->buscar_por_id($producto);
            }
            if ($r1["producto"]) {
              $p2 = array("producto"=>$r1["producto"], "cantidad_comprada"=>$cantidad);
              array_push($productos, $p2);
            }
          }
        }
        $pedido["productos"] = $productos;
        $pedido["estado"] = $p["estado"];
        $pedido["fecha_registro"] = $p["fecha_registro"];
        $pedido["id_pedido"] = $p["id"];
        array_push($lista_pedidos, $pedido);
      }
      $p = array('pedidos' => $lista_pedidos, "sesion_comprador_iniciada"=>true);
      $nt = notificaciones_cliente($req->session("id_cliente"));
      $p["notificaciones"] = $nt;
      $res->render_template("pedidos.html", $p);
    }else{
      $res->redirect_to("/cliente/mis-configuraciones");
    }
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_configuraciones_cliente($req, $res){
  if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
    $p = array("sesion_comprador_iniciada"=>true);
    $res->render_template("mis_configuraciones.html", $p);
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_pedido_cancelado($req, $res){
  if ($req->query("id")) {
    $pedidos = new Pedidos();
    $r = $pedidos->eliminar_por_id($req->query("id"));
    $res->render_template("pedido_cancelado.html");
  }else{
    $res->send("Debe pasar un id por la url para cancelar el pedido");
  }
}

function mostrar_plantilla_pedido_pagado($req, $res){
  $res->render_template("pedido_pagado.html");
}


?>