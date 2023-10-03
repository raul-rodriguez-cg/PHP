<?php
/**
 * Supongamos que María, Juan, Enrique, Ana y Silvia comparten los gastos de un viaje
 * En $arr_pagos tenemos los pagos realizados por cada persona y su importe
 * Para simplificar el ejercicio, todos los pagos son compartidos entre todos
 *
 * Realiza un algoritmo que reparta los gastos por igual, indicando qué deudas han de saldarse. Ejemplo:
 * - María debe X a Ana
 * - Enrique debe X a Silvia
 * - Juan debe X a Silvia
 */


$participantes = array("Maria", "Juan", "Enrique", "Ana", "Silvia");

$arr_pagos = array(
    array(
        "paga" => "Maria",
        "importe" => 15.30
    ),
    array(
        "paga" => "Enrique",
        "importe" => 11.25
    ),
    array(
        "paga" => "Ana",
        "importe" => 3.6
    ),
    array(
        "paga" => "Maria",
        "importe" => 21.35
    ),
    array(
        "paga" => "Silvia",
        "importe" => 17.5
    ),
    array(
        "paga" => "Silvia",
        "importe" => 1.2
    ),
    array(
        "paga" => "Enrique",
        "importe" => 19.1
    ),
    array(
        "paga" => "Maria",
        "importe" => 2.9
    ),
    array(
        "paga" => "Enrique",
        "importe" => 23
    ),
    array(
        "paga" => "Ana",
        "importe" => 7.1
    ),
);

function suma_total(): float
{
    global $arr_pagos;
    $total_gastos = 0;
    foreach ($arr_pagos as $pagos) {
        $total_gastos += ($pagos['importe']);
    }
    return $total_gastos;
}

function calcular_media(): float{
    global $participantes;
    return  round(suma_total()/sizeof($participantes), 2);
}


//Crear un array con el total q ha puesto cada uno
function array_total_cada_participante(): array{
    global $participantes;
    $array_totales = array();
    foreach($participantes as $part){
        $array_totales += array(
            $part => 0.0
        );
    }
    return $array_totales;
}

//Rellena lo que paga cada uno
function cada_uno_paga(): array{
    global $arr_pagos;
    $array_totales = array_total_cada_participante();
    foreach($arr_pagos as $pagos){
        $array_totales[$pagos["paga"]] += round($pagos["importe"],2);
    }
    return $array_totales;
}

function array_deben(): array{
    global $participantes;
    $arr_deben = array();
    $array_totales = cada_uno_paga();

    foreach($participantes as $parti){
        $resultado = $array_totales[$parti] - calcular_media();
        if($resultado < 0){
            $arr_deben += array(
                $parti => round($resultado, 2)
            );
        }
    }
    return $arr_deben;
}

function array_prestan(): array{
    global $participantes;
    $array_totales = cada_uno_paga();
    $arr_les_deben = array();

    foreach($participantes as $parti){
        $resultado = $array_totales[$parti] - calcular_media();

        if($resultado > 0){
            $arr_les_deben += array(
                $parti => round($resultado, 2)
            );
        }

    }
    return $arr_les_deben;
}
function arreglar_deudas(){
    $ar_deben = array_deben();
    $ar_prestan = array_prestan();

    foreach($ar_deben as $p_debe => $debe){

        foreach($ar_prestan as $p_presta => $presta){

            if($presta > 0){
                if($ar_deben[$p_debe] + $presta < 0){//Todavia debe pasta
                    echo $p_debe . " debe " . abs($presta) . " a " . $p_presta."\n";
                    //echo $p_debe . " aun debe pagar " . $debe + $presta. "\n";

                     $ar_deben[$p_debe] = $debe + $presta;
                     $ar_prestan[$p_presta] = 0;


                }else if($ar_deben[$p_debe] + $presta >= 0){ //Ya no debe pasta
                    echo $p_debe . " debe " . abs($ar_deben[$p_debe]) . " a " . $p_presta."\n";
                    //echo "A ".$p_presta . " aun le deben " . $ar_deben[$p_debe] + $presta . "\n";

                    $ar_prestan[$p_presta] = $ar_deben[$p_debe] + $presta;
                    $ar_deben[$p_debe] = 0;

                }
            }
        }

    }

}
arreglar_deudas();

