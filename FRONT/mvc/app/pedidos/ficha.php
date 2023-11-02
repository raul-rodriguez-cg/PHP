<?php
// Este controlador se encarga de mostrar la vista de una ficha de un Pedido
require_once "../../lib/basedatos.php";

$datos_guardar = array();
$errores = array();
$basedatos = new basedatos();

//Controlar si venimos a crear un registro o a editarlo
if(isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];
    $row_pedido = $basedatos->get_pedido($id_pedido);

}
//CHequear todos los post

// Comprobar que exista el pedido recibido...
// ¿Qué hacemos si queremos crear un pedido nuevo?

if(empty($id_pedido)){
    echo 'Registrando Nuevo pedido';
}
$res_riders = $basedatos->get_riders();

require_once 'views/ficha.php';
