<?php
class basedatos{

    static private $limite = 4;

    public function get_limite(): int
    {
        return $this->limite;
    }

    public function __construct()
    {
        $this->conn = self::conn_sql();

    }

    public static function conn_sql(): mysqli{
        return new mysqli("172.17.0.1", 'root', 'test1234', 'GESTION_PEDIDOS');
    }

    public static function get_listado_pedidos(array $filtros, $offset=0): bool|mysqli_result
    {
        $conn = self::conn_sql();
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
        $consulta = "SELECT P.PK_ID_PEDIDO,P.REFERENCIA, R.NOMBRE, P.FECHA_CREACION, P.ESTADO_PEDIDO 
            FROM PEDIDOS P LEFT JOIN RIDERS R on R.PK_ID_RIDER = P.FK_ID_RIDER";

        $condiciones = array();

        if(!empty($filtros)){
            foreach ($filtros as $campo => $valor) {
                if ($campo === 'R.PK_ID_RIDER') {
                    $condiciones[] = " $campo = '$valor'";
                } else {
                    $condiciones[] = " $campo LIKE '%$valor%'";
                }
            }
        }
        if (!empty($condiciones) ) {
            if(count($condiciones) == 1){
                $consulta .= " WHERE ". $condiciones[0];
            }else{
                $consulta .= " WHERE " . implode(" AND ", $condiciones);

            }
        }
        $consulta .= " LIMIT " . $offset . ", " . self::$limite;
        echo $consulta;
        return mysqli_query($conn, $consulta);
    }
    public static function get_riders(): bool|mysqli_result{
        $sql = "SELECT PK_ID_RIDER, NOMBRE FROM RIDERS";
        $conn = self::conn_sql();

        return mysqli_query($conn, $sql);
    }
    public function get_pedido($id_pedido): array{
        $conn = self::conn_sql();
        $sql = "SELECT *
            FROM PEDIDOS P LEFT JOIN RIDERS R on R.PK_ID_RIDER = P.FK_ID_RIDER WHERE P.PK_ID_PEDIDO = $id_pedido";

        echo $sql .PHP_EOL;
        return mysqli_query($conn, $sql)->fetch_assoc();

    }

    public function guardar_pedido($referencia, $estado, $dir_recogida, $h_recogida, $dir_entrega,
            $h_entrega, $id_rider){

        //Formatear los tiempos se podria meter
        (!empty($h_entrega) && !empty($h_recogida))? $tiempo = self::calcular_tiempo($h_recogida, $h_entrega) : $tiempo = null;
        if(!empty($h_recogida)) $h_recogida = date('Y-m-d H:i:s', strtotime($h_recogida));
        if(!empty($h_entrega)) $h_entrega = date('Y-m-d H:i:s', strtotime($h_entrega));

        $conn = self::conn_sql();
        $sql = "INSERT INTO PEDIDOS(ESTADO_PEDIDO, DIR_RECOGIDA, H_RECOGIDA, DIR_ENTREGA, H_ENTREGA, T_ENTREGA, DISTANCIA, FK_ID_RIDER, FECHA_CREACION, REFERENCIA) 
                    VALUES('$estado', '$dir_recogida', '$h_recogida', '$dir_entrega', '$h_entrega', '$tiempo', null, $id_rider, NOW(), '$referencia')";
        echo $sql;
        try{

            mysqli_query($conn, $sql);
            $conn->close();
        }catch(Exception $e){
            echo $e->getMessage();
        }

    }

    public function editar_pedido($id_pedido, $referencia, $estado, $dir_recogida, $h_recogida, $dir_entrega,
                                  $h_entrega, $id_rider){
        //Formatear los tiempos
        (!empty($h_entrega) || !empty($h_recogida))? $tiempo = self::calcular_tiempo($h_recogida, $h_entrega) : $tiempo = null;
        if(!empty($h_recogida)) $h_recogida = date('Y-m-d H:i:s', strtotime($h_recogida));
        if(!empty($h_entrega)) $h_entrega = date('Y-m-d H:i:s', strtotime($h_entrega));

        $conn = self::conn_sql();
        $sql = "UPDATE PEDIDOS SET 
                   ESTADO_PEDIDO = '$estado', 
                   DIR_RECOGIDA = '$dir_recogida', 
                   H_RECOGIDA = '$h_recogida',
                   DIR_ENTREGA= '$dir_entrega',
                   H_ENTREGA = '$h_entrega',
                   T_ENTREGA = '$tiempo',
                   DISTANCIA = null,
                   FK_ID_RIDER =  $id_rider,
                   REFERENCIA = '$referencia'
               WHERE PK_ID_PEDIDO = $id_pedido";
        echo $sql;
        try{
            mysqli_query($conn, $sql);
            $conn->close();
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public static function calcular_tiempo($h_recogida, $h_entrega): String{
        $nuevaHora = strtotime($h_entrega) - strtotime($h_recogida) ;
        return date("H:i:s", $nuevaHora);
    }

    public static function cantidad_pedidos(): int{
        try{
            $conn = self::conn_sql();
            $sql = "SELECT COUNT(PK_ID_PEDIDO) as 'TOTAL' FROM PEDIDOS";
            $res = mysqli_query($conn, $sql);
        }catch(Exception $e){
            echo "ERROR: ".PHP_EOL;

        }
        $res = $res->fetch_assoc();
        echo var_dump($res);

        return $res['TOTAL'];
    }

    public static function num_paginas(): int{
        $cantidad_pedidos = self::cantidad_pedidos();
        $tamano_pagina = self::$limite;
        return  ceil($cantidad_pedidos/$tamano_pagina);
    }

}
