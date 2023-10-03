<?php
$url = "https://633dc0417e19b17829155626.mockapi.io/api/employees";
$url_actual =   $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];


if(isset($_GET['page'])){
    $url = $url ."?page=". $_GET['page'] . "&";
}

if(isset($_GET['limit'])){
    $url = $url . "limit=". $_GET['limit']. "&";
}

if(isset($_GET['search'])){
    $url = $url ."search=".$_GET['search'];
}

$json = file_get_contents($url);
$datos = json_decode($json, true);
$usuarios[] = array();
foreach($datos as $dato){
    $fecha = $dato["createdAt"];

    echo "<h2>=========================</h2><br />";
    echo  "<img src = " . $dato["avatar"] ."><br />";
    echo "ID: ". $dato["id"] . "<br />";
    echo "Nombre: ". $dato["name"]. "<br />";
    echo "Ciudad: ". $dato["city"]. "<br />";
    echo "Empresa: ". $dato["company"]. "<br />";
    echo "Creacion: ". date("d M Y H:i:s", $fecha) ."<br />";
    echo "<br/>";
}
// [ID, imagen, nombre, ciudad, empresa, fecha de creación en formato Español]