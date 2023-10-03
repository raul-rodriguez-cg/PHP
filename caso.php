<?php
require_once 'Pedido.php';

$pedidos = array();
$indice_pedidos = array();
function menu(): int{
    global $pedidos;
    global $indice_pedidos;

    while(true){
        $caso = listar_menu();
        switch ($caso){
            case 1:
                if(empty($pedidos)){
                    echo "Sin pedidos!".PHP_EOL;
                }else{
                    listar_pedidos();
                }
                break;
            case 2:
                if(empty($pedidos)){
                    echo "Sin pedidos!".PHP_EOL;
                }else{
                    listar_pedidos_pendientes();
                }
                break;
            case 3:
                registrar_pedido();
                break;

            case 4:
                if(empty($pedidos)){
                    echo "Sin pedidos!".PHP_EOL;
                }else{
                    recoger_pedido();
                }
                break;
            case 5:
                if(empty($pedidos)){
                    echo "Sin pedidos!".PHP_EOL;
                }else{
                    entregar_pedido();
                }
                break;
            case 6:
                echo "Hasta la vista!".PHP_EOL;
                break 2;
        }
    }
    return 1;
}

function listar_menu(): int{
    echo "============================";
    echo PHP_EOL. "1.Listar todos los pedidos.". PHP_EOL;
    echo "2.Listar pedidos pendientes.". PHP_EOL;
    echo "3.Registrar nuevo pedido.". PHP_EOL;
    echo "4.Recoger pedido.". PHP_EOL;
    echo "5.Entregar pedido.". PHP_EOL;
    echo "6.Salir.". PHP_EOL;

    return readline("Escoja una opcion: ");
}

function listar_pedidos(){
    global $pedidos;

    foreach ($pedidos as $pedido){
        echo $pedido->to_string();
    }

}

function listar_pedidos_pendientes(){
    global $pedidos;

    foreach ($pedidos as $pedido){
        if($pedido->get_estado() == "Pendiente"){
            echo $pedido->to_string();
        }
    }

}

$id_pedido = 1;
function registrar_pedido(){
    global $id_pedido;
    global $indice_pedidos;
    global $pedidos;
    echo "Registrando pedido: " . $id_pedido ." ...\n";
    $dir_rec = readline("   Direccion de entrega: ");
    $dir_ent = readline("   Direccion de recogida: ");

    $pedido = new Pedido($id_pedido, $dir_rec, $dir_ent);
    array_push($pedidos, $pedido);
    array_push($indice_pedidos, $id_pedido);
    echo "Pedido " . $id_pedido . " registrado.".PHP_EOL.PHP_EOL;
    $id_pedido++;

}

function recoger_pedido(){
    global $indice_pedidos;
    global $pedidos;

    $num_pedido = readline("Numero de pedido: ");
    if(!in_array($num_pedido, $indice_pedidos)) {
        echo "Pedido no valido." . PHP_EOL;
        return;
    }
    foreach ($pedidos as $pedido){
        if($pedido->get_estado() != "Pendiente"){
            echo "Pedido no pendiente." .PHP_EOL;
            return;
        }
        if($pedido->id == $num_pedido ){
            $pedido->set_recoger_pedido();
            $pedido->set_h_recogida(date("d/m/Y H:i:s"));
            echo $pedido->to_string();
            echo "Pedido " . $num_pedido . " recogido." . PHP_EOL;
        }
    }

}

function entregar_pedido()
{
    global $indice_pedidos;
    global $pedidos;

    $num_pedido = readline("Numero de pedido: ");
    if (!in_array($num_pedido, $indice_pedidos)) {
        echo "Pedido no valido." . PHP_EOL;
        return;

    }
    foreach ($pedidos as $pedido) {
        if($pedido->get_estado() != "Recogido"){
            echo "Pedido no recogido.";
            return;

        }
        if ($pedido->id == $num_pedido) {
            $pedido->set_entregar_pedido();
            $pedido->set_h_entrega(date("d/m/Y H:i:s"));
            $pedido->set_t_entrega(calcular_tiempo(
                $pedido->get_h_recogida(), $pedido->get_h_entrega()
                )
            );
            echo $pedido->to_string();
            echo "Pedido " . $num_pedido . " entregado." . PHP_EOL;

        }
    }
}
function calcular_tiempo($t_inicio, $t_fin): String{
    $nuevaHora = strtotime($t_fin) - strtotime($t_inicio);
    $cadena = date("i", $nuevaHora) . " minutos " .
        date("s",$nuevaHora) . " segundos.";

    return $cadena;

}

menu();
