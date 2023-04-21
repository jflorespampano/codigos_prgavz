<?php
header("Content-Type: text/html;charset=utf-8");
// var_dump($_POST);
if (isset($_POST) && count($_POST) > 0) {
    //se enviarion parametros por el método _POST
    $id = (isset($_POST["id"]) ? $_POST["id"] : "");
    $descripcion = (isset($_POST["descripcion"]) ? $_POST["descripcion"] : "");
    echo "se recibio:" . $id . "," . $descripcion;
} else {
    echo "sin datos recibidos";
}
exit();
?>