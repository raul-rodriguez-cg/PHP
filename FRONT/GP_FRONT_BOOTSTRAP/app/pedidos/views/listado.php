<head>
    <title>Listado</title>
</head>
<body class="bg-light text-dark">


    <form class="form-inline row g-9" action="" method="get">

        <div class="col-2">
<!--            <label class="form-label" for="txReferencia">Referencia: </label>
-->            <input id ="txReferencia" type="text" name="txReferencia" value="" class="form-control" placeholder="Referencia">
        </div>

        <div class="form-group col-2">
<!--            <label class="form-label">Rider: </label>
-->            <select class="form-select" name="selRider">

                <option value="">Rider: </option>
                <?php foreach($res_riders as $row_rider): ?> <!-- cuidado con los values y los selected. no son lo mismo -->
                    <option value="<?php echo $row_rider["PK_ID_RIDER"]?>"> <?php  echo $row_rider["NOMBRE"]?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col-2" >
<!--            <label class="form-label">Estado pedido: </label>
-->            <select class="form-select" name ="selEstado" aria-label="Estado del pedido">
                <option class=" bg-secondary" selected value="">Estado pedido:</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Recogido">Recogido</option>
                <option value="Entregado">Entregado</option>
            </select>
        </div>

        <div class="col-1">
            <input type="submit" value="Buscar" class="btn btn-primary">
        </div>

        <div class="col-2">
            <a href="ficha.php" class="btn btn-info " role="button">Crear nuevo pedido</a>
        </div>
    </form>

    <div>
        <?php if(!empty($res_pedidos)): ?>
            <table class="table table-striped">
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

    <div class="d-flex justify-content-center">
        <a class="btn btn-outline-primary" href="listado.php?numPagina=1"><<</a>
        <?php  for($i = 1; $i <= $num_paginas; $i++):?>
        <a <?php echo 'class="btn btn-outline-primary" href=listado.php?numPagina='. $i?> > <?php echo $i?> </a>
        <?php endfor; ?>
        <a <?php echo 'class="btn btn-outline-primary" href=listado.php?numPagina='. $num_paginas?> > >> </a>
    </div>

    <div class="d-flex justify-content-center">
        <label></label>
    </div>

    <!--Historias raras aqui-->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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


