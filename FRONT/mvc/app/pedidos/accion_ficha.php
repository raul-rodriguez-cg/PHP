<?php
require_once "../../lib/basedatos.php";
$errores = [];
$basedatos = new basedatos();
$msqli = basedatos::conn_sql();
$datos = [];


//INSERT INTO TABLE VALUES(FORMAT(TIME, 'yyyy-MM-ddTHH:mm:ss'))
(!empty($_POST['selEstado'])) ? $estado = mysqli_real_escape_string($msqli, $_POST['selEstado']) : $estado = null;
(!empty($_POST['txHRecogida'])) ? $h_recogida = mysqli_real_escape_string($msqli, $_POST['txHRecogida']) : $h_recogida = null;
(!empty($_POST['txHEntrega'])) ? $h_entrega= mysqli_real_escape_string($msqli, $_POST['txHEntrega']) : $h_entrega = null;
(!empty($_POST['selRider'])) ? $id_rider = intval($_POST['selRider']) : $id_rider = null;
(!empty($_POST['id_pedido'])) ? $id_pedido = intval($_POST['id_pedido']) : $id_pedido = null;

if($id_rider == "") $id_rider = null ;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty($_POST['txReferencia'])) $errores[] = "- Referencia obligatoria.";
    if(empty($_POST['txDirEntrega'])) $errores[] = "- Direccion de entrega obligatoria.";
    if(empty($_POST['txDirRecogida'])) $errores[] = "- Direccion de recogida obligatoria.";

}

//Como ya he validado que si están vacíos no haga nada, se pasan directamente.
$referencia = mysqli_real_escape_string($msqli, $_POST['txReferencia']);
$dir_recogida = mysqli_real_escape_string($msqli, $_POST['txDirRecogida']);
$dir_entrega = mysqli_real_escape_string($msqli, $_POST['txDirEntrega']);


//Formatear fechas
$datos["ref"] = $referencia;
$datos["estado"] = $estado;
$datos["dentrega"] = $dir_entrega;
$datos["hentrega"] = $h_entrega;
$datos["drecogida"] = $dir_recogida;
$datos["hrecogida"] = $h_recogida;
$datos["id_rider"] = $id_rider;
$datos["drecogida"] = $dir_recogida;

echo $h_entrega.PHP_EOL;
echo $h_recogida.PHP_EOL;

$res_riders = $basedatos->get_riders();

if(!empty($errores)){
    include "views/ficha.php";
    return;
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
    //echo'<script type="text/javascript"> alert("Tarea Guardada");window.location.href="listado.php";</script>';


}
//GUARDAR PEDIDOS

if(empty($errores)){
    header("Location: listado.php");
    return;
}

//require "views/ficha.php";



/*
 * No deja cambiar cuando quiero poner el rider nulo.
 *
 * */





