<?php

class Rider
{
    private $id;
    private $nombre;
    private $estado;

    public function __construct($id, $nombre)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->estado = EstadoRider::LIBRE;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function get_estado(): string
    {
        return $this->estado;
    }

    public function set_estado(EstadoRider $estado): void
    {
        $this->estado = $estado;
    }

    public function set_id($id): void
    {
        $this->id = $id;
    }

    public function get_nombre()
    {
        return $this->nombre;
    }

    public function set_nombre($nombre): void
    {
        $this->nombre = $nombre;
    }

    public function to_string(): string
    {
        return $this->id . " - " . $this->nombre . " ". $this->estado . PHP_EOL;
    }
}

class EstadoRider{
    const LIBRE = "libre";
    const OCUPADO = "ocupado";
}



