<?php
// Este controlador se encarga de mostrar la vista de una ficha de un Pedido
require_once "../../lib/basedatos.php";


$basedatos = new basedatos();

if(!empty($_POST['id_pedido']) && !empty($_POST['selRider'])){
    $basedatos->asignar_rider($_POST['id_pedido'], $_POST['selRider']);
}


header("Location: ficha.php?id_pedido=" . $_POST['id_pedido']); // Redirige a la ficha del pedido
exit();
