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
  
  "^/dashboard/mis-pedidos/nuevos$" => "default@mostrar_plantilla_pedidos_nuevos",
  "^/dashboard/mis-pedidos/enviados$" => "default@mostrar_plantilla_pedidos_enviados",
  "^/dashboard/mis-pedidos/entregados$" => "default@mostrar_plantilla_pedidos_entregados",
  
  "^/busqueda/productos$" => "default@mostrar_plantilla_resultado_productos",
  "^/cliente/mi-carrito-de-compras$" => "default@mostrar_plantilla_carrito_de_compras",
  "^/cliente/mis-pedidos$" => "default@mostrar_plantilla_pedidos",
  "^/cliente/mis-configuraciones$" => "default@mostrar_plantilla_configuraciones_cliente",
  
  "^/redireccion/paypal/cancelado$" => "default@mostrar_plantilla_pedido_cancelado",
  "^/redireccion/paypal/pagado$" => "default@mostrar_plantilla_pedido_pagado",
  
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

  "^/api/obtener-datos-cliente$" => "api@obtener_datos_cliente",


  "^/api/admin/actualizar-info-personal$" => "api@actualizar_info_personal_admin",
  "^/api/admin/actualizar-password$" => "api@actualizar_password_admin",
  "^/api/admin/agregar-sub-admins$" => "api@agregar_sub_admins",

  "^/api/cliente/actualizar-datos-cliente$" => "api@actualizar_datos_cliente",

  "^/api/pedidos/crear-pedido$" => "api@crear_pedido",
  "^/api/pedidos/enviar-pedido$" => "api@enviar_pedido",
  "^/api/pedidos/entregar-pedido$" => "api@entregar_pedido",
  "^/api/pedidos/eliminar-pedido$" => "api@eliminar_pedido",
  
  "^/api/pedidos/estado/cancelado$" => "api@cambiar_estado_pedido_cancelado",
  "^/api/pedidos/estado/pagado$" => "api@cambiar_estado_pedido_pagado",
  "^/api/pedidos/estado/enviado$" => "api@cambiar_estado_pedido_enviado",
  "^/api/pedidos/estado/entregado$" => "api@cambiar_estado_pedido_entregado",
  
  

  "^/api/paypal/ipn$" => "api@notificacion_pago_inmediato",







  "^/obtener-tienda$" => "default@obtener_tienda",
  "^/pgn$" => "default@pgn",
  "^/guardar-tiendas$" => "default@guardarTienda", 
  "^/guardar-admin$" => "default@guardar_admin", 
  "^/esta-registrado$" => "default@esta_registrado", 
  "^/prueba$" => "default@prueba",
  "^/prueba/prueba$" => "default@prueba"
);
?>