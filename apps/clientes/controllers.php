<?php

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

function mostrar_plantilla_carrito_de_compras($req, $res) {
  if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
    $datos_clientes = new Datos_clientes();
    $r = $datos_clientes->buscar_por_id_cliente($req->session("id_cliente"));
    if ($r["datos_cliente"]["nombre"] && $r["datos_cliente"]["edad"] && $r["datos_cliente"]["direccion"] && $r["datos_cliente"]["barrio"] && $r["datos_cliente"]["telefonos"]) {
      $carro_de_compras = new Carro_de_compras();
      $r1 = $carro_de_compras->buscar_por_id_cliente($req->session("id_cliente"));
      $p = array("productos"=>$r1["productos"]);
      if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
        $p["sesion_comprador_iniciada"] = true;
      }
      $nt = notificaciones_cliente($req->session("id_cliente"));
      $p["notificaciones"] = $nt;
      $res->render_template("/cliente/carro_de_compras.html", $p);
      // $res->send("si tiene");
    }else{
      $res->redirect_to("/cliente/mis-configuraciones");
    }
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_pedidos_sin_confirmar($req, $res){
  if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
    $datos_clientes = new Datos_clientes();
    $r = $datos_clientes->buscar_por_id_cliente($req->session("id_cliente"));
    if ($r["datos_cliente"]["nombre"] && $r["datos_cliente"]["edad"] && $r["datos_cliente"]["direccion"] && $r["datos_cliente"]["barrio"] && $r["datos_cliente"]["telefonos"]) {

      $pedidos = new Pedidos();
      $r = $pedidos->buscar_por_id_cliente($req->session("id_cliente"), "sin confirmar");
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
            
            $productos_en_venta = new Productos_en_venta();
            $r1 = $productos_en_venta->buscar_por_id($producto);
            
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
      $notificaciones = new Notificaciones();
      $notificaciones->actualizar_estado_por_id_receptor($req->session("id_cliente"), "leida", "pedido_sin_confirmar");
      $p = array('pedidos' => $lista_pedidos, "sesion_comprador_iniciada"=>true);
      $nt = notificaciones_cliente($req->session("id_cliente"));
      $p["notificaciones"] = $nt;
      $p["tipo_pedido"] = "sin confirmar";
      $res->render_template("/cliente/pedidos.html", $p);
    }else{
      $res->redirect_to("/cliente/mis-configuraciones");
    }
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_pedidos_pagados($req, $res){
  if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
    $datos_clientes = new Datos_clientes();
    $r = $datos_clientes->buscar_por_id_cliente($req->session("id_cliente"));
    if ($r["datos_cliente"]["nombre"] && $r["datos_cliente"]["edad"] && $r["datos_cliente"]["direccion"] && $r["datos_cliente"]["barrio"] && $r["datos_cliente"]["telefonos"]) {

      $pedidos = new Pedidos();
      $r = $pedidos->buscar_por_id_cliente($req->session("id_cliente"), "pagado");
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
            
            $productos_vendidos = new Productos_vendidos();
            $r1 = $productos_vendidos->buscar_por_id($producto);
            
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
      $notificaciones = new Notificaciones();
      $notificaciones->actualizar_estado_por_id_receptor($req->session("id_cliente"), "leida", "pedido_pagado");
      $p = array('pedidos' => $lista_pedidos, "sesion_comprador_iniciada"=>true);
      $nt = notificaciones_cliente($req->session("id_cliente"));
      $p["notificaciones"] = $nt;
      $p["tipo_pedido"] = "pagados";
      $res->render_template("/cliente/pedidos.html", $p);
    }else{
      $res->redirect_to("/cliente/mis-configuraciones");
    }
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_pedidos_enviados($req, $res){
  if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
    $datos_clientes = new Datos_clientes();
    $r = $datos_clientes->buscar_por_id_cliente($req->session("id_cliente"));
    if ($r["datos_cliente"]["nombre"] && $r["datos_cliente"]["edad"] && $r["datos_cliente"]["direccion"] && $r["datos_cliente"]["barrio"] && $r["datos_cliente"]["telefonos"]) {

      $pedidos = new Pedidos();
      $r = $pedidos->buscar_por_id_cliente($req->session("id_cliente"), "enviado");
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
            
            $productos_vendidos = new Productos_vendidos();
            $r1 = $productos_vendidos->buscar_por_id($producto);
            
            if ($r1["producto"]) {
              $p2 = array("producto"=>$r1["producto"], "cantidad_comprada"=>$cantidad);
              array_push($productos, $p2);
            }
          }
        }
        $pedido["productos"] = $productos;
        $pedido["estado"] = $p["estado"];
        $pedido["fecha_registro"] = $p["fecha_registro"];
        $pedido["fecha_envio"] = $p["fecha_envio"];
        $pedido["id_pedido"] = $p["id"];
        array_push($lista_pedidos, $pedido);
      }
      $notificaciones = new Notificaciones();
      $notificaciones->actualizar_estado_por_id_receptor($req->session("id_cliente"), "leida", "pedido_enviado");
      $p = array('pedidos' => $lista_pedidos, "sesion_comprador_iniciada"=>true);
      $nt = notificaciones_cliente($req->session("id_cliente"));
      $p["notificaciones"] = $nt;
      $p["tipo_pedido"] = "enviados";
      $res->render_template("/cliente/pedidos.html", $p);
    }else{
      $res->redirect_to("/cliente/mis-configuraciones");
    }
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_pedidos_entregados($req, $res){
  if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
    $datos_clientes = new Datos_clientes();
    $r = $datos_clientes->buscar_por_id_cliente($req->session("id_cliente"));
    if ($r["datos_cliente"]["nombre"] && $r["datos_cliente"]["edad"] && $r["datos_cliente"]["direccion"] && $r["datos_cliente"]["barrio"] && $r["datos_cliente"]["telefonos"]) {

      $pedidos = new Pedidos();
      $r = $pedidos->buscar_por_id_cliente($req->session("id_cliente"), "entregado");
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
            
            $productos_vendidos = new Productos_vendidos();
            $r1 = $productos_vendidos->buscar_por_id($producto);
            
            if ($r1["producto"]) {
              $p2 = array("producto"=>$r1["producto"], "cantidad_comprada"=>$cantidad);
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
        $pedido["confirmado_por_cliente"] = $p["confirmado_por_cliente"];
        array_push($lista_pedidos, $pedido);
      }
      $notificaciones = new Notificaciones();
      $notificaciones->actualizar_estado_por_id_receptor($req->session("id_cliente"), "leida", "pedido_entregado");
      $p = array('pedidos' => $lista_pedidos, "sesion_comprador_iniciada"=>true);
      $nt = notificaciones_cliente($req->session("id_cliente"));
      $p["notificaciones"] = $nt;
      $p["tipo_pedido"] = "entregados";
      $res->render_template("/cliente/pedidos.html", $p);
    }else{
      $res->redirect_to("/cliente/mis-configuraciones");
    }
  }else{
    $res->redirect_to("/");
  }
}


function mostrar_plantilla_configuraciones($req, $res){
  if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
    $p = array("sesion_comprador_iniciada"=>true);
    $nt = notificaciones_cliente($req->session("id_cliente"));
    $p["notificaciones"] = $nt;
    $res->render_template("/cliente/mis_configuraciones.html", $p);
  }else{
    $res->redirect_to("/");
  }
}

function mostrar_plantilla_pedido_cancelado($req, $res){
  if ($req->query("id")) {
    $pedidos = new Pedidos();
    $r = $pedidos->eliminar_por_id($req->query("id"));
    $res->render_template("/cliente/pedido_cancelado.html");
  }else{
    $res->send("Debe pasar un id por la url para cancelar el pedido");
  }
}

function mostrar_plantilla_pedido_pagado($req, $res){
  $res->render_template("/cliente/pedido_pagado.html");
}

function mostrar_plantilla_tiendas($req, $res) {
  $tiendas = new Tiendas();
  $categorias_tiendas = new Categorias_tiendas();
  $r1 = $categorias_tiendas->buscar_todas();
  if($req->query("categoria")){
    $r = $tiendas->buscar_todas($req->query("categoria"), "habilitada");
  }else{
    $r = $tiendas->buscar_todas(null, "habilitada");
  }
  $p = array();
  $p['tiendas'] = $r["tiendas"];
  $p["categoria"] = $req->query("categoria");
  $p['categorias'] = $r1["categorias_tiendas"];
  $res->render_template("/cliente/tiendas.html", $p);
}

function mostrar_plantilla_catalogo_tienda($req, $res){
  if($req->query("nombre-tienda")){
    $tiendas = new Tiendas();
    $r = $tiendas->buscar_por_nombre($req->query("nombre-tienda"), "habilitada");
    if($r["error"] == null && $r["tienda"]){
      $productos_en_venta = new Productos_en_venta();
      if($req->query("categoria-productos")){
        $r2 = $productos_en_venta->buscar_por_id_tienda_y_categoria($r["tienda"]["id"], $req->query("categoria-productos"));
      }else{
        $r2 = $productos_en_venta->buscar_por_id_tienda($r["tienda"]["id"]);
      }
      
      $categorias_productos = new Categorias_productos();
      $r3 = $categorias_productos->buscar_por_categoria_tienda($r["tienda"]["categoria"]);
      // $res->json($r2["productos"]);
      $p = array(
        "tienda" => $r["tienda"],
        "productos" => $r2["productos"],
        "categorias_productos" => $r3["categorias_productos"],
        "categoria" => $req->query("categoria-productos")
      );
      if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
        $p["sesion_comprador_iniciada"] = true; 
        $nt = notificaciones_cliente($req->session("id_cliente"));
        $p["notificaciones"] = $nt;
      }
      if ($req->query("nombre-tienda")) {
        $p["nombre_tienda"] = $req->query("nombre-tienda");
      }
      if ($req->query("categoria-productos")) {
        $p["categoria_productos"] = $req->query("categoria-productos");
      }
      $res->render_template("/cliente/catalogo.html", $p);
    }else{
      $res->send("Hubo algun error");
    }
  }else{
    $res->send("Debe pasar un nombre de tienda como parametro en la url");
  }
}


?>