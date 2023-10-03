<?php
require_once "Pedido.php";
class ListaPedidos
{
    private $lista_pedidos;
    private $indice_pedidos;

    public function __construct(){
        $this->lista_pedidos = array();
        $this->indice_pedidos = array();
    }

    public function get_lista_pedidos(): array{
        return $this->lista_pedidos;
    }
    public function set_lista_pedidos(Pedido $pedido){
        array_push($this->lista_pedidos, $pedido);
    }
    public function get_indice_pedidos(): array{
        return $this->indice_pedidos;
    }
    public function set_indice_pedidos(int $num_pedido){
        array_push($this->indice_pedidos, $num_pedido);
    }

    public function to_string(): String{
        foreach ($this->lista_pedidos as $pedido){
            $pedido->to_string();
        }
    }

}