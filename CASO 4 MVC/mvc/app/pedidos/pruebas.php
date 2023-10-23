<?php
require_once "../../lib/basedatos.php";
$basedatos = new basedatos();
$arre = array();
$arre["R.PK_ID_RIDER"] = 1;
$arre["P.REFERENCIA"] = "REF";

$pedidos = $basedatos->get_listado_pedidos($arre);

foreach($pedidos as $pedido){
    echo $pedido['NOMBRE'] .PHP_EOL;
}