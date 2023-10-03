<?php

$salida = false;
$array_elementos = array();
while(!$salida){
    casos(printar());
}

function printar(): int{
    echo "
    1.Invertir cadena
    2.Invertir mayus/minus
    3.Encolar
    4.Desencolar
    5.Desapilar
    6.Salir\n";

    return readline("Escoja una opcion: ");
}
function casos($caso){
    switch($caso){
        case 1:
            invertir_cadena();
            break;
        case 2:
            invertir_mayus_minus();
            break;
        case 3;
            encolar();
            break;
        case 4:
            desencolar();
            break;
        case 5:
            desapilar();
            break;
        case 6:
            global $salida;
            echo "Hasta la vista!\n";
            $salida = true;
            break;
    }
}

function invertir_cadena(){
    $cadena = readline("Introduce una frase: ");
    echo strrev($cadena);
}

function invertir_mayus_minus(){
    $cadena = readline("Introduce una frase: ");
    $letras = array();
    $letras = str_split($cadena);
    foreach ($letras as $letra){
        if(preg_match("/[a-z]/", $letra)){
            echo strtoupper($letra);
        }else if(preg_match("/[A-Z]/", $letra)){
            echo strtolower($letra);
        }else{
            echo $letra;
        }
    }
}

function encolar(){
    global $array_elementos;
    $elemento = readline("Elemento a insertar: ");
     array_push($array_elementos, $elemento);
     foreach($array_elementos as $ael){
         echo $ael."\n";
     }
}

function desencolar(){
    global $array_elementos;
    if(!empty($array_elementos)) echo array_shift($array_elementos) . " desencolado.";
    else echo "No hay elementos.";

}

function desapilar(){
    global $array_elementos;
    if(!empty($array_elementos)) echo array_pop($array_elementos) . " desapilado";
    else echo "No hay elementos.";

}