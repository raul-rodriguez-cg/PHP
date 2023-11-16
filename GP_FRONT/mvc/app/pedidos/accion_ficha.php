<?php
require_once "../../lib/basedatos.php";
$errores = [];
$basedatos = new basedatos();
$msqli = basedatos::conn_sql();
$datos = [];


//Escapar datos
//var_dump($_POST);
(!empty($_POST['selEstado'])) ? $estado = ($_POST['selEstado']) : $estado = null;
(!empty($_POST['txHRecogida'])) ? $h_recogida =  $_POST['txHRecogida'] : $h_recogida = null;
(!empty($_POST['txHEntrega'])) ? $h_entrega=  $_POST['txHEntrega'] : $h_entrega = null;
(!empty($_POST['selRider'])) ? $id_rider = intval($_POST['selRider']) : $id_rider = null;
(!empty($_POST['id_pedido'])) ? $id_pedido = intval($_POST['id_pedido']) : $id_pedido = null;
(!empty($_POST['txUrl'])) ? $url = $_POST['txUrl'] : $url = null;

//var_dump($_POST); die;

//if($id_rider == "") $id_rider = null ;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST['txReferencia'])) $errores[] = "- Referencia obligatoria.";
    if(empty($_POST['txDirEntrega'])) $errores[] = "- Direccion de entrega obligatoria.";
    if(empty($_POST['txDirRecogida'])) $errores[] = "- Direccion de recogida obligatoria.";

}
//var_dump($_POST);
$patron = '/[A-Za-z0-9-]+/';
foreach ($_POST as $key => $value) {
    if ($value != null && !preg_match($patron, $value)) {
        $errores[] = "- $value no valido.";
        $value = preg_replace($patron, '', $value );
    }
}

//Como ya he validado que si están vacíos no haga nada, se pasan directamente.
$referencia = ( $_POST['txReferencia']);
$dir_recogida = ( $_POST['txDirRecogida']);
$dir_entrega = ( $_POST['txDirEntrega']);


//Formatear fechas
$datos["ref"] = $referencia;
$datos["estado"] = $estado;
$datos["dentrega"] = $dir_entrega;
$datos["hentrega"] = $h_entrega;
$datos["drecogida"] = $dir_recogida;
$datos["hrecogida"] = $h_recogida;
$datos["id_rider"] = $id_rider;
$datos["drecogida"] = $dir_recogida;

$res_riders = $basedatos->get_riders();

if(!empty($errores)){
    include "views/ficha.php";
    exit();
}

//RECOGER PEDIDO
if(!empty($_POST['hRecoger'])){
    $basedatos->recoger_pedido($id_pedido);
    header("Location: ficha.php?id_pedido=" . $id_pedido); // Redirige a la ficha del pedido
    exit();
}else if(!empty($_POST['hEntregar'])){
    $basedatos->entregar_pedido($id_pedido);
    header("Location: ficha.php?id_pedido=" . $id_pedido); // Redirige a la ficha del pedido
    exit();
}

//Calcular distancias
if(!empty($_POST['hCalDistancia'])){
    $cord1 = $basedatos->obtener_coordenada($_POST['txDirRecogida']);
    $cord2 = $basedatos->obtener_coordenada($_POST['txDirEntrega']);
    $distancia = $basedatos->calcular_distancia($cord1, $cord2);
    $basedatos->guardar_distancias($distancia, $id_pedido);

    header("Location: ficha.php?id_pedido=" . $id_pedido); // Redirige a la ficha del pedido
    exit();
}



//Prefiero no hacer esto pero por probar
if(empty($id_pedido)){
    $basedatos->guardar_pedido($referencia, $estado, $dir_recogida, $h_recogida, $dir_entrega, $h_entrega, $id_rider);
}else{
    $basedatos->editar_pedido($id_pedido, $referencia, $estado, $dir_recogida, $h_recogida, $dir_entrega, $h_entrega, $id_rider);
    //return;
    //echo'<script type="text/javascript"> alert("Tarea Guardada");window.location.href="listado.php";</script>';

}
//GUARDAR PEDIDOS

if(empty($errores)){
    echo'<script> alert("Exito al guardar");window.history.go(-2)</script>';
    return;
}

//require "views/ficha.php";



/*
 * No deja cambiar cuando quiero poner el rider nulo.
 *
 * */





