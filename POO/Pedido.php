<?php
/*
Pedido 123456
    Estado: Pendiente
    Dirección de recogida: "Calle Alemania 1"
    Hora de recogida: -
Dirección de entrega: "Calle Chile 2"
    Hora de entrega: -
Tiempo de entrega: -
*/

class Pedido
{
    public $id;
    private $estado;
    private $dir_recogida;
    private $h_recogida;
    private $h_entrega;
    private $dir_entrega;
    private $t_entrega;

    /**
     * @param $id
     * @param $estado
     * @param $dir_recogida
     * @param $h_recogida
     * @param $h_entrega
     * @param $dir_entrega
     * @param $t_entrega
     */
    public function __construct(int $id, String $dir_recogida, String $dir_entrega)
    {
        $this->id = $id;
        $this->estado = EstadosPedido::PENDIENTE;
        $this->dir_recogida = $dir_recogida;
        $this->h_recogida = "-";
        $this->h_entrega = "-";
        $this->dir_entrega = $dir_entrega;
        $this->t_entrega = "-";
    }


    function to_string(): String{
        return
            PHP_EOL . '****'. PHP_EOL .
            'Pedido: ' . $this->id . PHP_EOL .
            '   Estado: ' . $this->estado . PHP_EOL .
            '   Direccion de recogida: ' . $this->dir_recogida . PHP_EOL .
            '   Hora de recogida: ' . $this->h_recogida  . PHP_EOL .
            '   Direccion de entrega: ' . $this->dir_entrega . PHP_EOL.
            '   Hora de entrega: ' . $this->h_entrega  . PHP_EOL .
            '   Tiempo de entrega: ' . $this->t_entrega  . PHP_EOL .PHP_EOL ;
    }

    public function get_estado(): String
    {
        return $this->estado;
    }

    public function get_h_recogida(): String
    {
        return $this->h_recogida;
    }

    public function get_h_entrega(): String{
        return $this->h_entrega;
    }

    public function set_h_recogida(String $h_recogida): void
    {

        $this->h_recogida = $h_recogida;
    }

    public function set_h_entrega(String $h_entrega): void{
        $this->h_entrega = $h_entrega;
    }

    public function set_recoger_pedido(): void{
        $this->estado = EstadosPedido::RECOGIDO;
    }

    public function set_entregar_pedido(): void{
        $this->estado = EstadosPedido::ENTREGADO;
    }

    public function set_t_entrega(String $tiempo): void{
        $this->t_entrega = $tiempo;
    }

}

class EstadosPedido{
    const PENDIENTE = "Pendiente";
    const RECOGIDO = "Recogido";
    const ENTREGADO = "Entregado";
}