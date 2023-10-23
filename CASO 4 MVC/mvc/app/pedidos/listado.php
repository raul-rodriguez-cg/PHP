<?php
// Este controlador se encarga de mostrar la vista de Listado de Pedidos
require_once "../../lib/basedatos.php";
$filtros = array();

if(isset($_GET['selRider']) && $_GET['selRider'] != "") $filtros["R.PK_ID_RIDER"] = $_GET['selRider'];

if(isset($_GET['selEstado']) && $_GET['selEstado'] != "") $filtros["P.ESTADO_PEDIDO"] = $_GET['selEstado'];

if(isset($_GET['txReferencia']) && $_GET['txReferencia'] != "") $filtros['P.REFERENCIA'] = $_GET['txReferencia'];


/**
 * 1. Obtener pedidos
 * Necesitaremos pasar de algún modo los filtros recibidos por $_GET para filtrar la búsqueda
 * Puede ser un array $filtros, enviar varios parámetros... lo que mejor te venga
 */

$base_datos = new basedatos();
$res_pedidos = $base_datos->get_listado_pedidos($filtros);

/**
 * 2. Obtener riders para el seleccionable del filtro
 */
//$res_riders = $base_datos->get_riders();
$res_riders = $base_datos->get_riders();

/**
 * 3. Obtener estados para el seleccionable del filtro
 */
//$res_estados = $base_datos->get_estados_pedidos();
$res_estados = array();


// Todas las variables creadas en controlador están disponibles en la vista HTML
// Estamos "incluyendo" el contenido de la vista en este script
require_once 'views/listado.php';