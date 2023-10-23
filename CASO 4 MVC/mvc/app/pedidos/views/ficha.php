<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>HTML</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="views/estilo.css">
    </head>

    <body>
        <?php
        if(!empty($errores)){
            foreach($errores as $error){
                echo $error . PHP_EOL;
            }
        }
        ?>
        <form method="post" action="javascript:history.back()">
            Referencia: *<input type="text" name="txReferencia" value="<?php echo (empty($row_pedido)) ?  "":  $row_pedido["REFERENCIA"];?>" ><br />
            Estado: <input type="text" name="txEstado" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["ESTADO_PEDIDO"]?>"><br />
            D. Recogida: *<input type="text" name="txDirRecogida" value="<?php echo (empty($row_pedido)) ?  "":  $row_pedido["DIR_RECOGIDA"]?>"><br />
            H. Recogida: *<input type="datetime-local" name="txHRecogida" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["H_RECOGIDA"]?>"><br />
            D.Entrega: <input type="text" name="txDirEntrega" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["DIR_ENTREGA"]?>"><br />
            H.Entrega: <input type="datetime-local" name="txHEntrega" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["H_ENTREGA"]?>"><br />
            Rider:
            <select name="selRider">
                <option value="<?php echo (empty($row_pedido)) ?  "":$row_pedido["PK_ID_RIDER"]?>" selected><?php echo (empty($row_pedido)) ? "-" : "SEL: ".$row_pedido["NOMBRE"]?></option>
                <?php foreach($res_riders as $row_rider): ?> <!-- cuidado con los values y los selected. no son lo mismo -->
                    <option value="<?php echo $row_rider["PK_ID_RIDER"]?>"> <?php  echo $row_rider["NOMBRE"]?></option>
                <?php endforeach; ?>
            </select> <br />
            <!--<input type="text" name="txRider" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["NOMBRE"]?>"><br /> -->
            Tiempo: <input type="time" name="txTiempo" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["T_ENTREGA"]?>"><br />
            Creado: <input type="datetime-local" name="txCreado" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["FECHA_CREACION"]?>"><br />
            <br /><input type="submit" value="Guardar cambios">
        </form>

        <p>
            <a href="javascript:history.back()" >Volver</a><br/>
            *: campos obligatorios.
        </p>

    </body>
</html>
