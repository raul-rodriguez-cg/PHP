<?php
class basedatos{

    private $conn;

    /**
     * @param $conn
     */
    public function __construct()
    {
        $this->conn = self::conn_sql();

    }

    public static function conn_sql(): mysqli{
        return new mysqli("172.17.0.1", 'root', 'test1234', 'GESTION_PEDIDOS');
    }

    public static function get_listado_pedidos(array $filtros): bool|mysqli_result
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

}
