<head>
    <link rel="stylesheet" type="text/css" href="views/estilo.css">
    <title>Listado</title>
</head>
<body>


    <form action="" method="get">
        <div>
            Referencia: <input type="text" name="txReferencia" value="">
            Rider:
            <select name="selRider">
                <option value="">-</option>
                <?php foreach($res_riders as $row_rider): ?> <!-- cuidado con los values y los selected. no son lo mismo -->
                    <option value="<?php echo $row_rider["PK_ID_RIDER"]?>"> <?php  echo $row_rider["NOMBRE"]?></option>
                <?php endforeach; ?>
            </select>
            Estado: <!-- CREAR UNA CLASE CON CONSTANTES PARA HACER ESTO MEJOR-->
            <select name="selEstado">
                <option value="">-</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Recogido">Recogido</option>D
                <option value="Entregado">Entregado</option>
                <?php foreach($res_estados as $row_estado): ?>
                    <option value="" selected>Nombre estado</option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="Buscar">
            <a href="ficha.php">Crear nuevo pedido</a>
        </div>
        <div>
            <?php if(!empty($res_pedidos)): ?>
                <table>
                    <thead>
                    <th>Referencia &emsp;</th>
                    <th>Rider &emsp;</th>
                    <th>Fecha creacion &emsp;</th>
                    <th>Estado &emsp;</th>
                    <th>Distancia</th>
                    </thead>
                    <tbody>
                    <?php foreach($res_pedidos as $row_pedido): ?>
                        <tr>
                            <td><a href="<?php echo "ficha.php?id_pedido=" .$row_pedido['PK_ID_PEDIDO']?>" > <?php echo $row_pedido['REFERENCIA']?></a></td>
                            <td> <?php echo $row_pedido['NOMBRE']?></td>
                            <td><?php echo $row_pedido['FECHA_CREACION']?></td>
                            <td><?php echo $row_pedido['ESTADO_PEDIDO']?></td>
                            <?php
                                if(!empty($row_pedido['DISTANCIA'])){
                                    echo '<td id="distancia_'. $row_pedido['PK_ID_PEDIDO'].' ">'.$row_pedido['DISTANCIA'] .' Km</td>';
                                }
                                if(empty($row_pedido['DISTANCIA'])){
                                    $id_pedido = $row_pedido['PK_ID_PEDIDO'];
                                    $dEntrega = $row_pedido['DIR_ENTREGA'];
                                    $dRecogida = $row_pedido['DIR_RECOGIDA'];

                                    echo '<td id="distancia_' . $id_pedido .'" class="clicable" 
                                        title="Calcular distancia" 
                                        data-id = "'.$id_pedido .'"
                                        data-recogida ="' .$dRecogida . '"
                                        data-entrega ="' .$dEntrega . '"
                                        onclick=calcularDistancia('.$id_pedido .')>
                                        <span class="material-symbols-outlined">distance</span></td>';

                                }
                            ?>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="loader"></div>
            <?php else: ?>
                No se han encontrado pedidos
            <?php endif;?>
        </div>
        <div class="paginador">
            <a href="listado.php?numPagina=1"><<</a>
            <?php  for($i = 1; $i <= $num_paginas; $i++):?>
            <a <?php echo 'href=listado.php?numPagina='. $i?> > <?php echo $i?> </a>
            <?php endfor; ?>
            <a <?php echo 'href=listado.php?numPagina='. $num_paginas?> > >> </a>
        </div>
    </form>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function calcularDistancia(id_pedido){
            let coso = document.querySelector('#distancia_' + id_pedido);
            let entrega = $(coso).data('entrega');
            let recogida = $(coso).data('recogida');
            alert("Se calculará la distancia entre "+entrega +" y " +recogida);
            mostrarLoader();
            $.ajax({
                url: 'listado.php',
                type: 'POST',
                data: {
                    id_pedido: id_pedido,
                    dEntrega: entrega,
                    dRecogida: recogida,
                    calDistancia: true

                }, // Los datos que enviarás al servidor
                success: function (distancia) {
                    $('#distancia_' + id_pedido).text(distancia + ' Km'); // Actualiza la celda con el resultado
                    //alert(distancia);

                },
                error: function () {
                    // Esta función se ejecuta si hay un error en la petición
                    alert('Error al calcular la distancia'); // Muestra una alerta de error
                },
                complete: function (){
                    ocultarLoader();
                }
            });
        }

        function mostrarLoader() {
            $('.loader').css('display', 'block');
        }

        function ocultarLoader() {
            $('.loader').css('display', 'none');
        }
    </script>
</body>


