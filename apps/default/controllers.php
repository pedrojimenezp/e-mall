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
    $categorias_tiendas = new Categorias_tiendas();
    $r2 = $categorias_tiendas->buscar_todas();
    $params = array("categorias_tiendas"=>$r2["categorias_tiendas"]);
    $res->render_template("inicio.html", $params);
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

function mostrar_plantilla_productos_en_venta($req, $res){
  if($req->session("sesion_iniciada")){
    if ($req->session("tipo_usuario") == "admin") {
      $admins = new Admins();
      $r = $admins->buscar_por_id($req->session("id_admin"));
      
      $tiendas = new Tiendas();
      $r2 = $tiendas->buscar_por_id($req->session("id_tienda"));
      
      $categorias_productos = new Categorias_productos();
      $r3 = $categorias_productos->buscar_por_categoria_tienda($r2["tienda"]["categoria"]);
      
      if ($r["admin"]) {
        $params = array(
          "admin"=>$r["admin"], 
          "tienda"=>$r2["tienda"], 
          "categorias_productos"=>$r3["categorias_productos"]
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

function mostrar_plantilla_configuraciones($req, $res){
  if($req->session("sesion_iniciada")){
    if ($req->session("tipo_usuario") == "admin") {
      $admins = new Admins();
      $r = $admins->buscar_por_id($req->session("id_admin"));
      $tiendas = new Tiendas();
      $r2 = $tiendas->buscar_por_id($req->session("id_tienda"));

      if ($r["admin"]) {
        $params = array("admin"=>$r["admin"], "tienda"=>$r2["tienda"]);
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

function mostrar_plantilla_tiendas($req, $res) {
  $tiendas = new Tiendas();
  if($req->query("categoria")){
    $r = $tiendas->buscar_todas($req->query("categoria"), "habilitada");
  }else{
    $r = $tiendas->buscar_todas(null, "habilitada");
  }
  $p = array();
  $p['tiendas'] = $r["tiendas"];
  $res->render_template("tiendas.html", $p);
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
        "categorias_productos" => $r3["categorias_productos"]
      );
      if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
        $p["sesion_comprador_iniciada"] = true; 
      }
      if ($req->query("nombre-tienda")) {
        $p["nombre_tienda"] = $req->query("nombre-tienda");
      }
      if ($req->query("categoria-productos")) {
        $p["categoria_productos"] = $req->query("categoria-productos");
      }
      $res->render_template("catalogo.html", $p);
    }else{
      $res->send("Hubo algun error");
    }
  }else{
    $res->send("Debe pasar un nombre de tienda como parametro en la url");
  }
}

function mostrar_plantilla_carrito_de_compras($req, $res) {
  if ($req->session("sesion_iniciada") && $req->session("tipo_usuario") == "cliente") {
    $carro_de_compras = new Carro_de_compras();
    $r = $carro_de_compras->buscar_por_id_cliente($req->session("id_cliente"));
    $p = array("productos"=>$r["productos"]);
    $res->render_template("carro_de_compras.html", $p);
  }
}

?>