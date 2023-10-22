<?php
// Este controlador se encarga de mostrar la vista de una ficha de un Pedido
require_once "../../lib/basedatos.php";

if(isset($_GET['id_pedido'])) $id_pedido = $_GET['id_pedido'];

// Comprobar que exista el pedido recibido...
// ¿Qué hacemos si queremos crear un pedido nuevo?

if(empty($id_pedido)){
    echo 'Pedido no encontrado';
    http_response_code(404);
    return;
}
$basedatos = new basedatos();
$row_pedido = $basedatos->get_pedido($id_pedido);

require_once 'views/ficha.php';
//require_once 'views/listado.php';