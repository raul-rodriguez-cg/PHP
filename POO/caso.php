<?php
require_once 'Pedido.php';
require_once 'ListaPedidos.php';
require_once 'Rider.php';

$lista_pedidos = new ListaPedidos();
$id_pedido = 1;
$id_rider = 1;
$lista_riders = array();

menu($lista_pedidos, $id_pedido, $id_rider, $lista_riders );

function menu($lista_pedidos, &$id_pedido, &$id_rider, &$lista_riders): void{
    //EN vez de global, pasarlo por parámetro.

    while(true){
        $caso = listar_menu();
        //Todos estos if se pueden ahorrar, es copia-pega
        switch ($caso){
            case 1:
                listar_pedidos($lista_pedidos->get_lista_pedidos());
                break;
            case 2:
                listar_pedidos_pendientes($lista_pedidos->get_lista_pedidos());
                break;
            case 3:
                if (hay_pendiente($lista_pedidos->get_lista_pedidos())){
                    break;
                }
                registrar_pedido($lista_pedidos, $id_pedido);
                break;
            case 4:
                recoger_pedido($lista_pedidos->get_indice_pedidos(), $lista_pedidos->get_lista_pedidos(), $lista_riders);
                break;
            case 5:
                entregar_pedido($lista_pedidos->get_indice_pedidos(), $lista_pedidos->get_lista_pedidos(), $lista_riders);
                break;
            case 6:
                cambiar_distancia($lista_pedidos->get_lista_pedidos(), $lista_pedidos->get_indice_pedidos());
                break;

            case 7:
                dar_alta_rider($id_rider, $lista_riders);
                //Dar Rider de alta
                break;
            case 8:
                asignar_riders($lista_riders, $lista_pedidos->get_lista_pedidos(), $lista_pedidos->get_indice_pedidos());
                break;
            case 9:
                echo "Hasta la vista!".PHP_EOL;
                break 2;
        }
    }

}
function comp_pedidos(array $pedidos):bool{
    if(empty($pedidos)){
        echo "\tSin pedidos!".PHP_EOL;
        return false;
    }else{
        return true;
    }
}
function comp_riders(array $lista_riders):bool{
    if(empty($lista_riders)){
        echo "\tSin riders!".PHP_EOL;
        return false;
    }else{
        return true;
    }
}
function comp_distancia($pedido, $num_pedido): bool{
    if($pedido->get_distancia() == "No disponible" && $pedido->get_id() == $num_pedido){
        return false;
    }
    return true;
}
function cambiar_distancia($pedidos, $indice_pedidos): void{

    if(!comp_pedidos($pedidos)){
        return;
    }

    $num_pedido = readline("Numero de pedido: ");
    if(!in_array($num_pedido, $indice_pedidos)) {
        echo "\nPedido no valido." . PHP_EOL;
        return;
    }
    $pedido = $pedidos[$num_pedido - 1];
    if(!comp_distancia($pedido, $num_pedido)){
        $n_entrega = readline("Nueva Direccion de entrega: ");
        $n_recogida= readline("Nueva Direccion de recogida: ");

        $pedido->set_dir_recogida($n_recogida);
        $pedido->set_dir_entrega($n_entrega);
        $pedido->set_distancia($n_recogida, $n_entrega);
        //Podría comprobarse recursivamente
        if(!comp_distancia($pedido, $num_pedido)){
            echo "\tDistancia cambiada!".PHP_EOL;
        }else{
            echo "\tNo se pudo obtener la distancia.". PHP_EOL;
        }
    }else{
        echo "La distancia ya es correcta" . PHP_EOL;
    }
}

function hay_pendiente(array $pedidos): bool{
    foreach ($pedidos as $pedido){
        if($pedido->get_estado() != EstadosPedido::ENTREGADO){
            echo "\tYa hay un pedido en curso." . PHP_EOL;
            return true;
        }
    }
    return false;
}

function listar_menu(): int{
    echo "============================";
    echo PHP_EOL. "1.Listar todos los pedidos.". PHP_EOL;
    echo "2.Listar pedidos pendientes.". PHP_EOL;
    echo "3.Registrar nuevo pedido.". PHP_EOL;
    echo "4.Recoger pedido.". PHP_EOL;
    echo "5.Entregar pedido.". PHP_EOL;
    echo "6.Calcular distancia". PHP_EOL;
    echo "7.Dar Rider de alta". PHP_EOL;
    echo "8.Asignar rider a pedido". PHP_EOL;
    echo "7.Salir.". PHP_EOL;

    return readline("Escoja una opcion: ");
}

function listar_pedidos($pedidos): void{

    if(!comp_pedidos($pedidos)){
        return;
    }

    foreach ($pedidos as $pedido){
        echo $pedido->to_string();
    }

}

function listar_pedidos_pendientes($pedidos): void
{

    if (!comp_pedidos($pedidos)) {
        return;
    }

    foreach ($pedidos as $pedido) {
        if ($pedido->get_estado() == "Pendiente") {
            echo $pedido->to_string();
        }
    }
}
function registrar_pedido($lista_pedidos,&$id_pedido): void{

    echo "Registrando pedido: " . $id_pedido ." ...\n";
    $dir_rec = readline("   Direccion de entrega: ");
    $dir_ent = readline("   Direccion de recogida: ");

    $pedido = new Pedido($id_pedido, $dir_rec, $dir_ent);

    $lista_pedidos->set_lista_pedidos($pedido);
    $lista_pedidos->set_indice_pedidos($id_pedido);



    echo "Pedido " . $id_pedido . " registrado.".PHP_EOL.PHP_EOL;
    $id_pedido++;

}

function recoger_pedido($indice_pedidos, $pedidos, $lista_riders): void{
    if(!comp_pedidos($pedidos)){
        return;
    }

    $num_pedido = readline("Numero de pedido: ");
    if(!in_array($num_pedido, $indice_pedidos)) {
        echo "\nPedido no valido." . PHP_EOL;
        return;
    }
    foreach ($pedidos as $pedido){
        if($pedido->get_estado() != "Pendiente"){
            echo "\nPedido no pendiente." .PHP_EOL;
            return;
        }
        if($pedido->get_rider_asignado() == "-"){
            echo "  Pedido no asignado a ningun rider." .PHP_EOL;
            return;
        }

        if($pedido->id == $num_pedido ){
            $pedido->set_recoger_pedido();
            $pedido->set_h_recogida(date("d/m/Y H:i:s"));
            foreach($lista_riders as $rider){
                if($rider->get_nombre() == $pedido->get_rider_asignado()){
                    $rider->set_estado(EstadoRider::OCUPADO);
                }
            }
            echo $pedido->to_string();
            echo "Pedido " . $num_pedido . " recogido." . PHP_EOL;
        }
    }

}

function entregar_pedido($indice_pedidos, $pedidos, $lista_riders): void
{
    if(!comp_pedidos($pedidos)){
        return;
    }

    $num_pedido = readline("Numero de pedido: ");
    if (!in_array($num_pedido, $indice_pedidos)) {
        echo "\tPedido no valido." . PHP_EOL;
        return;

    }
    foreach ($pedidos as $pedido) {
        if($pedido->get_estado() == "Pendiente"){
            echo "\tPedido no recogido." . PHP_EOL;
            return;
        }
        if($pedido->get_estado() == "Entregado"){
            echo "\tPedido ya entregado." . PHP_EOL;
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
        foreach ($lista_riders as $rider){
            if($rider->get_nombre() == $pedido->get_rider_asignado()){
                $rider->set_estado(EstadoRider::LIBRE);
            }
        }
    }
}
function calcular_tiempo($t_inicio, $t_fin): String{
    $nuevaHora = strtotime($t_fin) - strtotime($t_inicio);
    return date("i", $nuevaHora) . " minutos " .
        date("s",$nuevaHora) . " segundos.";
}

function dar_alta_rider(&$id_rider, &$lista_riders): void{
    $nombre_rider = readline("  Nombre del rider: ");
    $rider = new Rider($id_rider, $nombre_rider);
    array_push($lista_riders, $rider);
    echo "Rider " . $rider->get_nombre() . " dado de alta." . PHP_EOL . PHP_EOL;
    $id_rider++;
}

function asignar_riders($lista_riders, $lista_pedidos, $indice_pedidos)
{
    if (!comp_riders($lista_riders)) {
        return;
    }

    listar_riders($lista_riders);
    if (!comp_pedidos($lista_pedidos)) {
        return;
    }

    $id_rider = readline("Selecciona id de rider: ");
    foreach ($lista_riders as $rider) {
        if ($rider->get_id() == $id_rider && $rider->get_estado() == EstadoRider::OCUPADO) {
            echo "  Rider no disponible" . PHP_EOL;
            return;
        }
    }

    $num_pedido = readline("Selecciona id de pedido: ");
        if (!in_array($num_pedido, $indice_pedidos)) {
            echo "\n    Pedido no valido." . PHP_EOL;
            return;
        }

    $pedido_asignado = $lista_pedidos[$num_pedido - 1];
    if($pedido_asignado->get_estado() != EstadosPedido::PENDIENTE){
        echo "\n    Pedido no valido." . PHP_EOL;
        return;
    }
    if($pedido_asignado->get_rider_asignado() != "-"){
        echo "    El pedido ya ha sido asignado." . PHP_EOL;
        return;
    }

    foreach ($lista_riders as $rider) {
        if ($rider->get_id() == $id_rider && $rider->get_estado() == EstadoRider::LIBRE) {
            echo "  Rider asignado" . PHP_EOL;
            //$rider->set_estado(EstadoRider::OCUPADO);

        } else {
            echo "  Rider no válido" . PHP_EOL;
            return;
        }
    }
    $rider_asignado = $lista_riders[$id_rider - 1];

    $pedido_asignado->set_rider_asignado($rider_asignado->get_nombre());
    foreach($lista_pedidos as $pedido){
        if($pedido->id == $num_pedido ){
            $pedido->set_rider_asignado($rider_asignado->get_nombre());
            echo $pedido->to_string();
            //echo "Pedido " . $num_pedido . " asignado a ". $rider_asignado->get_nombre() . PHP_EOL;
        }

    }

}
function listar_riders($lista_riders)
{
    foreach ($lista_riders as $rider) {
        echo "\t" . $rider->to_string() . PHP_EOL;
    }
}







