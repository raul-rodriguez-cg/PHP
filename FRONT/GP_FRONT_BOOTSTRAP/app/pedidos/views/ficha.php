<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <title>HTML</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body class="bg-light text-dark ">
     <?php if(!empty($errores)): ?>
        <div class="errores">
            Error al guardar: <br/>
            <?php foreach($errores as $error): ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form id="form_ficha" method="post" action="accion_ficha.php"> <!-- Sale error pero funciona -->
            <table class="table table-light table-striped  w-50">
                <tr>
                    <td>Referencia: <span class ="text-danger">*</span><br /></td>
                    <td><input class="form-control" type="text" name="txReferencia" value="<?php
                        if(!empty($errores)){
                            echo $datos["ref"];
                        }else{
                            echo (empty($row_pedido)) ?  '':  $row_pedido["REFERENCIA"];
                        }

                        ?>" >
                    </td>
                </tr>
                <tr>
                    <td>Estado:</td>
                    <td>
                        <select class="form-select" name="selEstado">
                            <option value="Pendiente" <?php if(!empty($row_pedido) && $row_pedido['ESTADO_PEDIDO'] == 'Pendiente') echo 'selected'?> >Pendiente</option>
                            <option value="Recogido" <?php if(!empty($row_pedido) && $row_pedido['ESTADO_PEDIDO'] == 'Recogido') echo 'selected'?>>Recogido</option>
                            <option value="Entregado" <?php if(!empty($row_pedido) && $row_pedido['ESTADO_PEDIDO'] == 'Entregado') echo 'selected'?>>Entregado</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Dirección de recogida: <span class ="text-danger">*</span><br /></td>
                    <td>
                        <input type="text" class="form-control" name="txDirRecogida" value="<?php
                            if(!empty($errores)){
                                echo $datos["drecogida"];
                            }else{
                                echo (empty($row_pedido)) ?  '':  $row_pedido["DIR_RECOGIDA"];
                            }
                        ?>">
                    </td>
                </tr>
                <tr>
                    <td>Hora de recogida</td>
                    <td>
                        <input class="form-control" type="time" name="txHRecogida" value="<?php
                        if(!empty($errores)){
                            echo $datos["hrecogida"];
                        }else{
                            echo (empty($row_pedido)) ?  '': $row_pedido["H_RECOGIDA"];
                        }
                        ?>"/>
                    </td>
                </tr>
                <tr>
                    <td>Dirección de Entrega: <span class ="text-danger">*</span><br /></td>
                    <td> <input class="form-control" type="text" name="txDirEntrega" value="<?php
                        if(!empty($errores)){
                            echo $datos["dentrega"];
                        }else{
                            echo (empty($row_pedido)) ?  '': $row_pedido["DIR_ENTREGA"];
                        }
                        ?>">
                    </td>
                </tr>
                <tr>
                    <td>H.Entrega:</td>
                    <td><input class="form-control" type="time" name="txHEntrega" value="<?php
                        if(!empty($errores)){
                            echo $datos["hentrega"];
                        }else{
                            echo (empty($row_pedido)) ?  '': $row_pedido["H_ENTREGA"];
                        }

                        ?>"/></td>
                </tr>
                <tr>
                    <td><label class="form-label">Rider: </label></td>
                    <td>
                        <label class="form-label"><?php echo (!empty($row_pedido['NOMBRE'])? $row_pedido['NOMBRE'] : 'Rider no asignado' ) ?></label>
                    </td>
                </tr>
                <tr>
                    <td>Distancia (Km):</td>
                    <td><input type="text" class="form-control" name="txDistancia" value=<?php echo !empty($row_pedido)? $row_pedido["DISTANCIA"] : "-" ?>></td>
                </tr>
                <tr>
                    <td>Tiempo:</td>
                    <td>
                        <input type="time" class="form-control" name="txTiempo" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["T_ENTREGA"]?>" readonly><br />
                    </td>
                </tr>
                <tr>
                    <td>Creado:</td>
                    <td>
                        <input type="datetime-local" class="form-control"name="txCreado" value="<?php echo (empty($row_pedido)) ?  "": $row_pedido["FECHA_CREACION"]?>" readonly><br />
                    </td>
                </tr>
            </table>
            <div class="inline">
                    <?php

                    if(!empty($row_pedido) && $row_pedido['ESTADO_PEDIDO'] != 'Entregado'){

                        if($row_pedido['ESTADO_PEDIDO'] == 'Pendiente'){
                            echo "<div><button class='btn btn-primary' id='cambioEstado' type='button' name ='cambioEstado' onclick=recogerPedido()>Recoger Pedido</button></div>";

                        }else if($row_pedido['ESTADO_PEDIDO'] == 'Recogido'){
                            echo "<div><button class='btn btn-primary' id='cambioEstado' type='button' name ='cambioEstado' onclick=entregarPedido()>Entregar Pedido</button></div>";
                            echo '<input type="hidden" name="hEntregar" value=1>';
                        }

                    }
                    if(empty($row_pedido['DISTANCIA'])){
                        echo "<div><button class='btn btn-primary' type='button' onclick=calcularDistancia()>Calcular Distancia</button></div>";
                    }
                    ?>

                <!-- MODAL -->
                <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">ASIGNAR RIDER</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <select class="form-select" name="selRider">
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button id="asignarRider" type="button" class="btn btn-primary">Confirmar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <a  role="button" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal"
                <?php echo (!empty( $row_pedido['ESTADO_PEDIDO'])&& $row_pedido['ESTADO_PEDIDO'] == 'Pendiente') ? 'class="btn btn-primary"' :'class="btn btn-primary disabled"' ?>
                >Asignar rider</a>

                <input class="btn btn-success" type="submit" value="Guardar cambios">


                <input type="hidden" name="horaActual" id="horaActual" value="">
                <input type="hidden" name="id_pedido" value="<?php if(!empty($id_pedido)) echo $id_pedido ?>">

            </div>
        </form>

        <div>
            <a class="btn btn-secondary" href="listado.php" role="button" >Volver</a><br/>
        </div>

     <div>
         <span class ="text-danger">* Campo obligatorio</span><br />
     </div>




     <!--FINAL DEL BODY-->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function entregarPedido(){
                //Me invento este hidden para que a la hora de procesar los datos comprobar que se ha pulsado este boton y solo guardar esto
                document.querySelector('form').innerHTML +='<input type="hidden" name="hEntregar" value=1>';
                document.querySelector('[name="selEstado"]').value = "Recogido";
                document.querySelector('form').submit();
               // window.location.reload();

                //todo: Quitar todo este jquery por enviar el formulario otra vez y coger los valores que necesito.
                //todo: crear una funcion en la base de datos que haga esto(hecha)
            }
            function recogerPedido(){

                document.querySelector('form').innerHTML +='<input type="hidden" name="hRecoger" value=1>';
                document.querySelector('[name="selEstado"]').value = "Recogido";
                document.querySelector('form').submit();
                //window.location.reload();
            }

            function asignarRider(){
                document.getElementById("form_ficha").action = "accion.php";
                document.getElementById("form_ficha").submit();
                //let rider = document.getElementById('selRider').value;

            }

            function calcularDistancia(){
                let dirRecogida = document.querySelector('[name="txDirRecogida"]').value;
                let dirEntrega = document.querySelector('[name="txDirEntrega"]').value;

                if(dirRecogida === "" || dirEntrega === ""){
                    alert("No se puede calcular la distancia sin dirección de recogida o de entrega.");
                    return;
                }
                document.querySelector('form').innerHTML +='<input type="hidden" name="hCalDistancia" value=1>';
                document.querySelector('form').innerHTML += '<div class="loader"></div>';
                document.querySelector('form').submit();

            }

            function obtenerHoraActual() {
                var ahora = new Date();
                var horas = ahora.getHours().toString().padStart(2, '0');
                var minutos = ahora.getMinutes().toString().padStart(2, '0');
                var segundos = ahora.getSeconds().toString().padStart(2,'0');
                return horas + ':' + minutos + ':' + segundos;
            }
            $('#asignarRider').on('click', function (e) {

                asignarRider();

            })


        </script>
    </body>
</html>
