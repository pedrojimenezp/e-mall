<?php
$urls_array = array(
  "^/$" => "default@mostrar_plantilla_inicio",
  "^/iniciar-sesion$" => "default@mostrar_plantilla_iniciar_sesion",
  "^/registrar/tienda$" => "default@mostrar_plantilla_registrar_tienda",
  "^/registrar/cliente$" => "default@mostrar_plantilla_registrar_cliente",
  "^/tiendas$" => "default@mostrar_plantilla_tiendas",
  "^/tiendas/catalogo$" => "default@mostrar_plantilla_catalogo_tienda",

  "^/dashboard/mis-productos/en-venta$" => "default@mostrar_plantilla_productos_en_venta",
  "^/dashboard/mis-configuraciones$" => "default@mostrar_plantilla_configuraciones",
  
  "^/busqueda/productos$" => "default@mostrar_plantilla_resultado_productos",
  "^/cliente/mi-carrito-de-compras$" => "default@mostrar_plantilla_carrito_de_compras",
  
  // API
  "^/api/registrar/tienda$" => "api@registrar_tienda",
  "^/api/registrar/cliente$" => "api@registrar_cliente",
  "^/api/iniciar-sesion/admin$" => "api@iniciar_sesion_admin",
  "^/api/iniciar-sesion/cliente$" => "api@iniciar_sesion_cliente",
  "^/api/cerrar-sesion$" => "api@cerrar_sesion",

  "^/api/agregar-a-productos-en-venta$" => "api@agregar_a_productos_en_venta",
  "^/api/obtener-productos-en-venta$" => "api@obtener_productos_en_venta",
  "^/api/obtener-datos-admin$" => "api@obtener_datos_admin",
  "^/api/obtener-datos-tienda$" => "api@obtener_datos_tienda",
  "^/api/agregar-producto-al-carrito$" => "api@agregar_producto_al_carrito",
  "^/api/eliminar-producto-del-carrito$" => "api@eliminar_producto_del_carrito",

  "^/obtener-tienda$" => "default@obtener_tienda",
  "^/pgn$" => "default@pgn",
  "^/guardar-tiendas$" => "default@guardarTienda", 
  "^/guardar-admin$" => "default@guardar_admin", 
  "^/esta-registrado$" => "default@esta_registrado", 
  "^/prueba$" => "default@prueba",
  "^/prueba/prueba$" => "default@prueba"
);
?>