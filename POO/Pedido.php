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
    public $distancia;

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
        $this->set_distancia($dir_entrega, $dir_recogida);
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
            '   Tiempo de entrega: ' . $this->t_entrega  . PHP_EOL .
            '   Distancia: ' . $this->distancia . PHP_EOL . PHP_EOL;
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

    public function calcular_distancia(array $dist1,array $dist2):String{
        if(empty($dist2) || empty($dist1)){
            return "No disponible";
        }

        $lat1 = $dist1[0];
        $lon1 = $dist1[1];
        $lat2 = $dist2[0];
        $lon2 = $dist2[1];

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return round(($miles * 1.609344),2) . " Km". PHP_EOL;
    }

    public function set_distancia(String $dist1,String $dist2): void{
        $this->distancia = self::calcular_distancia(
            $this->obtener_coordenada($dist1),
            $this->obtener_coordenada($dist2));
    }

    public function obtener_coordenada(String $direccion): array{
        $queryString = http_build_query([
            'access_key' => '7bbd5e1a511c2d307f8acb8cf17e46be',
            'query' => $direccion . ", LO",
            'region' => 'España',
            'output' => 'json',
            'limit' => 1,
        ]);

        $ch = curl_init(sprintf('%s?%s', 'http://api.positionstack.com/v1/forward', $queryString));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $json = curl_exec($ch);

        curl_close($ch);

        $apiResult = json_decode($json, true);
        return array(
            $apiResult["data"][0]["latitude"],
            $apiResult["data"][0]["longitude"]
        );
    }

}

class EstadosPedido{
    const PENDIENTE = "Pendiente";
    const RECOGIDO = "Recogido";
    const ENTREGADO = "Entregado";
}