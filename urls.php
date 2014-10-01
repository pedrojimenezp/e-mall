<?php
$urls_array = array(
  // ^/$ es la url que se ingresa en el navegador
  //default es el nombre de la aplicacion
  //mostrar_plantilla_inicio es el nombre del controlador dentro del archivo controllers.php 
  "^/$" => "default@mostrar_plantilla_inicio",
  
  // PLANTILLAS LOGIN Y SIGN UP
  "^/iniciar-sesion$" => "default@mostrar_plantilla_iniciar_sesion",
  "^/registrar/tienda$" => "default@mostrar_plantilla_registrar_tienda",
  "^/registrar/cliente$" => "default@mostrar_plantilla_registrar_cliente",
  // PLANTILLAS LOGIN Y SIGN UP


  // PLANTILLAS DE VENDEDORES
  "^/dashboard/mis-productos/en-venta$" => "default@mostrar_plantilla_productos_en_venta",
  "^/dashboard/mis-productos/vendidos$" => "default@mostrar_plantilla_productos_vendidos",
  "^/dashboard/mis-configuraciones$" => "default@mostrar_plantilla_configuraciones",
  "^/dashboard/mis-pedidos/nuevos$" => "default@mostrar_plantilla_pedidos_nuevos",
  "^/dashboard/mis-pedidos/enviados$" => "default@mostrar_plantilla_pedidos_enviados",
  "^/dashboard/mis-pedidos/entregados$" => "default@mostrar_plantilla_pedidos_entregados",
  // FIN PLANTILLAS VENDEDORES

  
  // PLANTILAS DE CLIENTES
  "^/tiendas$" => "clientes@mostrar_plantilla_tiendas",
  "^/tiendas/catalogo$" => "clientes@mostrar_plantilla_catalogo_tienda",
  "^/busqueda/productos$" => "default@mostrar_plantilla_resultado_productos",
  
  "^/cliente/mi-carrito-de-compras$" => "clientes@mostrar_plantilla_carrito_de_compras",
  
  "^/cliente/mis-pedidos/sin-confirmar$" => "clientes@mostrar_plantilla_pedidos_sin_confirmar",
  "^/cliente/mis-pedidos/pagados$" => "clientes@mostrar_plantilla_pedidos_pagados",
  "^/cliente/mis-pedidos/enviados$" => "clientes@mostrar_plantilla_pedidos_enviados",
  "^/cliente/mis-pedidos/entregados$" => "clientes@mostrar_plantilla_pedidos_entregados",
  
  "^/cliente/mis-configuraciones$" => "clientes@mostrar_plantilla_configuraciones",
  
  "^/redireccion/paypal/cancelado$" => "default@mostrar_plantilla_pedido_cancelado",
  "^/redireccion/paypal/pagado$" => "default@mostrar_plantilla_pedido_pagado",  
  // FIN PLANTILLAS CLIENTES

  
  // API
  "^/api/registrar/tienda$" => "api@registrar_tienda",
  "^/api/registrar/cliente$" => "api@registrar_cliente",

  "^/api/iniciar-sesion/admin$" => "api@iniciar_sesion_admin",
  "^/api/iniciar-sesion/cliente$" => "api@iniciar_sesion_cliente",
  "^/api/cerrar-sesion$" => "api@cerrar_sesion",

  "^/api/agregar-a-productos-en-venta$" => "api@agregar_a_productos_en_venta",
  "^/api/actualizar-productos-en-venta$" => "api@actualizar_productos_en_venta",
  "^/api/eliminar-productos-en-venta$" => "api@eliminar_productos_en_venta",
  
  "^/api/obtener-productos-en-venta$" => "api@obtener_productos_en_venta",
  "^/api/obtener-productos-vendidos$" => "api@obtener_productos_vendidos",
  
  "^/api/obtener-datos-admin$" => "api@obtener_datos_admin",
  "^/api/obtener-datos-tienda$" => "api@obtener_datos_tienda",
  "^/api/agregar-producto-al-carrito$" => "api@agregar_producto_al_carrito",
  "^/api/eliminar-producto-del-carrito$" => "api@eliminar_producto_del_carrito",


  "^/api/obtener-categorias-productos$" => "api@obtener_categorias_productos",

  "^/api/obtener-datos-cliente$" => "api@obtener_datos_cliente",

  "^/api/admin/actualizar-info-personal$" => "api@actualizar_info_personal_admin",
  "^/api/admin/actualizar-password$" => "api@actualizar_password_admin",
  "^/api/admin/actualizar-datos-tienda$" => "api@actualizar_datos_tienda",
  "^/api/admin/agregar-sub-admins$" => "api@agregar_sub_admins",
  "^/api/admin/eliminar-sub-admins$" => "api@eliminar_sub_admins",

  "^/api/cliente/actualizar-datos-cliente$" => "api@actualizar_datos_cliente",

  "^/api/pedidos/crear-pedido$" => "api@crear_pedido",
  "^/api/pedidos/enviar-pedido$" => "api@enviar_pedido",
  "^/api/pedidos/entregar-pedido$" => "api@entregar_pedido",
  "^/api/pedidos/eliminar-pedido$" => "api@eliminar_pedido",
  "^/api/pedidos/confirmar-entrega$" => "api@confirmar_entrega",
  // FIN API
  

  // PAYPAL
  "^/api/paypal/ipn$" => "api@notificacion_pago_inmediato", // paypal notifica inmediatamente al servidor un pago mediante esta url
  // FIN PAYPAL

);
?>