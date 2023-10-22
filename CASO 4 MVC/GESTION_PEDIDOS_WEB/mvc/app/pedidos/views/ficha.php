<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>HTML</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="estilo.css">
    </head>

    <body>
        <p>
            Referencia: <input type="text" name="txReferencia" value="<?php echo $row_pedido["REFERENCIA"]?>" readonly><br />
            Estado: <input type="text" name="txReferencia" value="<?php echo $row_pedido["ESTADO_PEDIDO"]?>"><br />
            D. Recogida: <input type="text" name="txReferencia" value="<?php echo $row_pedido["DIR_RECOGIDA"]?>"><br />
            H. Recogida: <input type="text" name="txReferencia" value="<?php echo $row_pedido["H_RECOGIDA"]?>"><br />
            D.Entrega: <input type="text" name="txReferencia" value="<?php echo $row_pedido["DIR_ENTREGA"]?>"><br />
            H.Entrega: <input type="text" name="txReferencia" value="<?php echo $row_pedido["H_ENTREGA"]?>"><br />
            Rider: <input type="text" name="txReferencia" value="<?php echo $row_pedido["NOMBRE"]?>"><br />
            Tiempo: <input type="text" name="txReferencia" value="<?php echo $row_pedido["T_ENTREGA"]?>"><br />
            Creado: <input type="text" name="txReferencia" value="<?php echo $row_pedido["FECHA_CREACION"]?>"><br />
        </p>

        <p>
            <a href="javascript:history.back()" >Volver</a>
        </p>

    </body>
</html>
