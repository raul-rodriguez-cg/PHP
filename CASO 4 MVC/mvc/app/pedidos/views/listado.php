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
                    </thead>
                    <tbody>
                    <?php foreach($res_pedidos as $row_pedido): ?>
                        <tr>
                            <td><a href="<?php echo "ficha.php?id_pedido=" .$row_pedido['PK_ID_PEDIDO']?>" > <?php echo $row_pedido['REFERENCIA']?></a></td>
                            <td> <?php echo $row_pedido['NOMBRE']?></td>
                            <td><?php echo $row_pedido['FECHA_CREACION']?></td>
                            <td><?php echo $row_pedido['ESTADO_PEDIDO']?></td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
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
</body>


