<head>
    <link rel="stylesheet" type="text/css" href="views/estilo.css">
    <title>Listado</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>

    <form action="" method="get">
        <div>
            Referencia: <input type="text" name="txReferencia"
                               value="<?php echo (empty($filtros['P.REFERENCIA']))? '' : $filtros['P.REFERENCIA'] ?>">
            Rider:
            <select name="selRider">
                <option value="">-</option>
                <?php foreach($res_riders as $row_rider): ?> <!-- cuidado con los values y los selected. no son lo mismo -->
                    <option value="<?php echo $row_rider["PK_ID_RIDER"]?>"
                        <?php echo (!empty($filtros['R.PK_ID_RIDER']) && $filtros['R.PK_ID_RIDER'] == $row_rider['PK_ID_RIDER'] ) ? 'selected' : '' ?>>
                        <?php  echo $row_rider["NOMBRE"]?></option>
                <?php endforeach; ?>
            </select>

            Estado: <!-- CREAR UNA CLASE CON CONSTANTES PARA HACER ESTO MEJOR-->
            <select name="selEstado">
                <option value="">-</option>
                <option value="Pendiente" <?php echo (!empty($filtros['P.ESTADO_PEDIDO']) && $filtros['P.ESTADO_PEDIDO'] == 'Pendiente' ) ? 'selected' : '' ?> >Pendiente</option>
                <option value="Recogido" <?php echo (!empty($filtros['P.ESTADO_PEDIDO']) && $filtros['P.ESTADO_PEDIDO'] == 'Recogido' ) ? 'selected' : '' ?> >Recogido</option>
                <option value="Entregado" <?php echo (!empty($filtros['P.ESTADO_PEDIDO']) && $filtros['P.ESTADO_PEDIDO'] == 'Entregado' ) ? 'selected' : '' ?> >Entregado</option>
            </select>
            <input type="submit" value="Buscar">
            <a href="ficha.php">Crear nuevo pedido</a>
        </div>
        <div>
            <?php if(!empty($res_pedidos)): ?>
                <table>
                    <thead>
                    <th scope="col">Referencia &emsp;</th>
                    <th scope="col">Rider &emsp;</th>
                    <th scope="col">Fecha creacion &emsp;</th>
                    <th scope="col">Estado &emsp;</th>
                    <th scope="col">Distancia</th>
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
            <a href="listado.php?<?php echo limpiarQueryString($_SERVER['QUERY_STRING']) . '&numPagina=1'; ?>"><<</a>

            <?php for ($i = 1; $i <= $num_paginas; $i++): ?>
                <a href="listado.php?<?php echo limpiarQueryString($_SERVER['QUERY_STRING']) . '&numPagina=' . $i; ?>"<?php echo $i == $pagina ? ' class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <a href="listado.php?<?php echo limpiarQueryString($_SERVER['QUERY_STRING']) . '&numPagina=' . $num_paginas; ?>">>></a>
        </div>

        <?php
        function limpiarQueryString($queryString) {
            // Parse la cadena de consulta en un array
            parse_str($queryString, $queryArray);
            // Elimina el parámetro numPagina si ya está presente
            unset($queryArray['numPagina']);
            // Construye la nueva cadena de consulta
            return http_build_query($queryArray);
        }
        ?>
        <input type="hidden" value=" .<?php limpiarQueryString($_SERVER['QUERY_STRING']) ?> ." name="txUrl">
    </form>
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


