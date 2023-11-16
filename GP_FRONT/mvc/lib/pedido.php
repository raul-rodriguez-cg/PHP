<?php

require_once "../app/pedidos/listado.php";
class Pedido{

    private $id;
    private $estado;
    private $fecha_creacion;
    private $dir_recogida;
    private $h_recogida;
    private $h_entrega;
    private $dir_entrega;
    private $t_entrega;
    private $distancia;
    private $rider_asignado;

    /**
     * @param $dir_recogida
     * @param $dir_entrega
     */
    public function __construct($dir_recogida, $dir_entrega)
    {
        $this->dir_recogida = $dir_recogida;
        $this->dir_entrega = $dir_entrega;
    }

    /**
     * @return mixed
     */
    public function get_estado()
    {
        return $this->estado;
    }

    /**
     * @return mixed
     */
    public function get_fecha_creacion()
    {
        return $this->fecha_creacion;
    }

    /**
     * @param mixed $fecha_creacion
     */
    public function set_fecha_creacion($fecha_creacion)
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    /**
     * @param mixed $estado
     */
    public function set_estado($estado)
    {
        $this->estado = $estado;
    }

    /**
     * @return mixed
     */
    public function get_dir_recogida()
    {
        return $this->dir_recogida;
    }

    /**
     * @param mixed $dir_recogida
     */
    public function set_dir_recogida($dir_recogida)
    {
        $this->dir_recogida = $dir_recogida;
    }

    /**
     * @return mixed
     */
    public function get_h_recogida()
    {
        return $this->h_recogida;
    }

    /**
     * @param mixed $h_recogida
     */
    public function set_h_recogida($h_recogida)
    {
        $this->h_recogida = $h_recogida;
    }

    /**
     * @return mixed
     */
    public function get_h_entrega()
    {
        return $this->h_entrega;
    }

    /**
     * @param mixed $h_entrega
     */
    public function set_h_entrega($h_entrega)
    {
        $this->h_entrega = $h_entrega;
    }

    /**
     * @return mixed
     */
    public function get_dir_entrega()
    {
        return $this->dir_entrega;
    }

    /**
     * @param mixed $dir_entrega
     */
    public function set_dir_entrega($dir_entrega)
    {
        $this->dir_entrega = $dir_entrega;
    }

    /**
     * @return mixed
     */
    public function get_t_entrega()
    {
        return $this->t_entrega;
    }

    /**
     * @param mixed $t_entrega
     */
    public function set_t_entrega($t_entrega)
    {
        $this->t_entrega = $t_entrega;
    }

    /**
     * @return mixed
     */
    public function get_distancia()
    {
        return $this->distancia;
    }

    /**
     * @param mixed $distancia
     */
    public function set_distancia($distancia)
    {
        $this->distancia = $distancia;
    }

    /**
     * @return mixed
     */
    public function get_rider_asignado()
    {
        return $this->rider_asignado;
    }

    /**
     * @param mixed $rider_asignado
     */
    public function set_rider_asignado($rider_asignado)
    {
        $this->rider_asignado = $rider_asignado;
    }


}