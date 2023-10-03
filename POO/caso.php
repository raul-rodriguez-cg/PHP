<?php
require_once 'Pedido.php';
require_once 'ListaPedidos.php';
$lista_pedidos = new ListaPedidos();
$id_pedido = 1;
menu($lista_pedidos, $indice_pedidos, $id_pedido);

function menu(&$lista_pedidos, &$indice_pedidos, &$id_pedido): void{
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
                recoger_pedido($lista_pedidos->get_indice_pedidos(), $lista_pedidos->get_lista_pedidos());
                break;
            case 5:
                entregar_pedido($lista_pedidos->get_indice_pedidos(), $lista_pedidos->get_lista_pedidos());
                break;
            case 6:
                echo "Hasta la vista!".PHP_EOL;
                break 2;
        }
    }

}
function comp_pedidos(array &$pedidos):bool{
    if(empty($pedidos)){
        echo "Sin pedidos!".PHP_EOL;
        return false;
    }else{
        return true;
    }
}

function hay_pendiente(array &$pedidos): bool{
    foreach ($pedidos as $pedido){
        if($pedido->get_estado() != EstadosPedido::ENTREGADO){
            echo "Ya hay un pedido en curso." . PHP_EOL;
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
    echo "6.Salir.". PHP_EOL;

    return readline("Escoja una opcion: ");
}

function listar_pedidos(&$pedidos){

    if(!comp_pedidos($pedidos)){
        return;
    }

    foreach ($pedidos as $pedido){
        echo $pedido->to_string();
    }

}

function listar_pedidos_pendientes(&$pedidos){

    if(!comp_pedidos($pedidos)){
        return;
    }

    foreach ($pedidos as $pedido){
        if($pedido->get_estado() == "Pendiente"){
            echo $pedido->to_string();
        }
    }

}

function registrar_pedido(&$lista_pedidos,&$id_pedido){

    echo "Registrando pedido: " . $id_pedido ." ...\n";
    $dir_rec = readline("   Direccion de entrega: ");
    $dir_ent = readline("   Direccion de recogida: ");

    $pedido = new Pedido($id_pedido, $dir_rec, $dir_ent);

    $lista_pedidos->set_lista_pedidos($pedido);
    $lista_pedidos->set_indice_pedidos($id_pedido);



    echo "Pedido " . $id_pedido . " registrado.".PHP_EOL.PHP_EOL;
    $id_pedido++;

}

function recoger_pedido(&$indice_pedidos, &$pedidos){
    if(!comp_pedidos($pedidos)){
        return;
    }

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

function entregar_pedido(&$indice_pedidos, &$pedidos)
{
    if(!comp_pedidos($pedidos)){
        return;
    }

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


