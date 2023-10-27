<?php
// Este controlador se encarga de mostrar la vista de una ficha de un Pedido
require_once "../../lib/basedatos.php";

$datos_guardar = array();
$errores = array();
//Controlar si venimos a crear un registro o a editarlo
if(isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];
}


//CHequear todos los post

$basedatos = new basedatos();
// Comprobar que exista el pedido recibido...
// ¿Qué hacemos si queremos crear un pedido nuevo?

if(empty($id_pedido)){
    echo 'Registrando Nuevo pedido';
}else{
    $row_pedido = $basedatos->get_pedido($id_pedido);
}
$res_riders = $basedatos->get_riders();

require_once 'views/ficha.php';
