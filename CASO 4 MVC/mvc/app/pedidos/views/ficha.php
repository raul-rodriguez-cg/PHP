<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>HTML</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="views/estilo.css">
    </head>

    <body>
     <?php if(!empty($errores)): ?>
        <div class="errores">
            Error al guardar: <br/>
            <?php foreach($errores as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="post" action="accion_ficha.php"> <!-- Sale error pero funciona -->
            <table>
                <tr>
                    <td>Referencia:</td>
                    <td><input type="text" name="txReferencia" value="<?php
                        if(!empty($errores)){
                            echo $datos["ref"];
                        }else{
                            echo (empty($row_pedido)) ?  '':  $row_pedido["REFERENCIA"];
                        }

                        ?>" ><span class ="obligatorio">*</span><br />
                    </td>
                </tr>
                <tr>
                    <td>Estado:</td>
                    <td>
                        <select name="selEstado">
                            <option value="Pendiente" <?php if(!empty($row_pedido) && $row_pedido['ESTADO_PEDIDO'] == 'Pendiente') echo 'selected'?> >Pendiente</option>
                            <option value="Recogido" <?php if(!empty($row_pedido) && $row_pedido['ESTADO_PEDIDO'] == 'Recogido') echo 'selected'?>>Recogido</option>
                            <option value="Entregado" <?php if(!empty($row_pedido) && $row_pedido['ESTADO_PEDIDO'] == 'Entregado') echo 'selected'?>>Entregado</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Dirección de recogida: </td>
                    <td>
                        <input type="text" name="txDirRecogida" value="<?php
                            if(!empty($errores)){
                                echo $datos["drecogida"];
                            }else{
                                echo (empty($row_pedido)) ?  '':  $row_pedido["DIR_RECOGIDA"];
                            }
                        ?>"><span class ="obligatorio">*</span>
                    </td>
                </tr>
                <tr>
                    <td>Hora de recogida</td>
                    <td>
                        <input type="time" name="txHRecogida" value="<?php
                        if(!empty($errores)){
                            echo $datos["hrecogida"];
                        }else{
                            echo (empty($row_pedido)) ?  '': $row_pedido["H_RECOGIDA"];
                        }
                        ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Dir.Entrega:</td>
                    <td> <input type="text" name="txDirEntrega" value="<?php
                        if(!empty($errores)){
                            echo $datos["dentrega"];
                        }else{
                            echo (empty($row_pedido)) ?  '': $row_pedido["DIR_ENTREGA"];
                        }
                        ?>"><span class ="obligatorio">*</span>
                    </td>
                </tr>
                <tr>
                    <td>H.Entrega:</td>
                    <td><input type="time" name="txHEntrega" value="<?php
                        if(!empty($errores)){
                            echo $datos["hentrega"];
                        }else{
                            echo (empty($row_pedido)) ?  '': $row_pedido["H_ENTREGA"];
                        }

                        ?>"/></td>
                </tr>
                <tr>
                    <td>Rider:</td>
                    <td>
                        <select name="selRider">
                            <option value="">-</option>
                            <?php foreach($res_riders as $row_rider): ?>
                                <option value="<?php echo $row_rider["PK_ID_RIDER"]?>"
                                    <?php
                                    if(!empty($errores)){
                                        if($datos["id_rider"] == $row_rider["PK_ID_RIDER"]) echo 'selected';
                                    }else{
                                        if(!empty($row_pedido) && $row_pedido["PK_ID_RIDER"] == $row_rider["PK_ID_RIDER"]) echo 'selected';
                                    }
                                    ?>>
                                    <?php echo $row_rider["NOMBRE"]?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Tiempo:</td>
                    <td>
                        <input type="time" name="txTiempo" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["T_ENTREGA"]?>" readonly><br />
                    </td>
                </tr>
                <tr>
                    <td>Creado:</td>
                    <td>
                        <input type="datetime-local" name="txCreado" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["FECHA_CREACION"]?>" readonly><br />
                    </td>
                </tr>
                <tr>
                    <td><input type="submit" value="Guardar cambios"></td>
                </tr>
            </table>
            <input type="hidden" name="id_pedido" value="<?php if(!empty($id_pedido)) echo $id_pedido ?>">
        </form>

        <p>
            <a href="listado.php" >Volver</a><br/>
            <span class ="obligatorio">* campos obligatorios</span>
        </p>

    </body>
</html>
