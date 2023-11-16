<?php
// Este controlador se encarga de mostrar la vista de Listado de Pedidos
require_once "../../lib/basedatos.php";
$filtros = array();
if(isset($_GET['selRider']) && $_GET['selRider'] != "") $filtros["R.PK_ID_RIDER"] = $_GET['selRider'];
if(isset($_GET['selEstado']) && $_GET['selEstado'] != "") $filtros["P.ESTADO_PEDIDO"] = $_GET['selEstado'];
if(isset($_GET['txReferencia']) && $_GET['txReferencia'] != "") $filtros['P.REFERENCIA'] = $_GET['txReferencia'];
(!empty($_GET['numPagina'])) ? $pagina = $_GET['numPagina'] : $pagina = 1;
/**
 * 1. Obtener pedidos
 * Necesitaremos pasar de algún modo los filtros recibidos por $_GET para filtrar la búsqueda
 * Puede ser un array $filtros, enviar varios parámetros... lo que mejor te venga
 */
$patron = '/[A-Za-z0-9- ]+/';
foreach ($_GET as $key => $value) {
    if ($value != null && !preg_match($patron, $value)) {
        echo "<script>alert('Caracteres no validos en el campo Referencia.'); window.history.back()</script>";
        exit();
    }
}

$base_datos = new basedatos();

$num_paginas = $base_datos->num_paginas_bueno($filtros);
//var_dump($num_paginas);
$offset = ($pagina - 1) * $base_datos->get_limite();
$res_pedidos = $base_datos->get_listado_pedidos($filtros, $offset);


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

//Calcular la distancia del pedido:

if(!empty($_POST['calDistancia'])){
    $cord1 = $base_datos->obtener_coordenada($_POST['dRecogida']);
    $cord2 = $base_datos->obtener_coordenada($_POST['dEntrega']);
    $distancia = $base_datos->calcular_distancia($cord1, $cord2);
    $base_datos->guardar_distancias($distancia, $_POST['id_pedido']);
    return;
}


// Todas las variables creadas en controlador están disponibles en la vista HTML
// Estamos "incluyendo" el contenido de la vista en este script
require_once 'views/listado.php';