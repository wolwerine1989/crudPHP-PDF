<?php
//AQUI COMENZAMOS LA CONEXION PARA LA BASE DE DATOS
//NOTA: LOS DATOS ESTAN CONECTADOS CON OTRAS PARTES DE OTROS FORMULARIOS
$host="localhost";
$bd="sitio";
$usuario="root";
$contrasenia="";

try {
    $conexion=new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);
    if ($conexion) {
        //echo "Conectado..... al sistema";
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}

?>