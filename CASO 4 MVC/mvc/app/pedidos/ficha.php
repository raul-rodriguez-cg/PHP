<?php
// Este controlador se encarga de mostrar la vista de una ficha de un Pedido
require_once "../../lib/basedatos.php";

//Controlar si venimos a crear un registro o a editarlo
if(isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];
}
$datos_guardar = array();
$errores = array();

//CHequear todos los post
if(isset($_POST['txDirRecogida']) && !empty($_POST['txDirRecogida'])){
    $datos_guardar['txDirRecogida'] = $_POST['txDirRecogida'];
}else{
    array_push($errores, "· Direccion de recogida obligatoria.");
}

if(isset($_POST['txDirEntrega']) && !empty($_POST['txDirEntrega'])){
    $datos_guardar['txDirEntrega'] = $_POST['txDirEntrega'];
}else{
    array_push($errores, "· Direccion de entrega obligatoria.");
}

if(!empty($errores)){
    header("Location: views/ficha.php");
}
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
