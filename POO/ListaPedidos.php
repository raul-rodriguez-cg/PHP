<?php
require_once "Pedido.php";
class ListaPedidos
{
    private $lista_pedidos;

    public function __construct(){
        $this->lista_pedidos = array();
    }

    public function get_lista_pedidos(){
        return $this->lista_pedidos;
    }
    public function set_lista_pedidos(Pedido $pedido){
        array_push($this->lista_pedidos, $pedido);
    }

}