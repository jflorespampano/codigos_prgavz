<?php

$datosParte = [
    "anio" => 2022,
    "nombre" => "",
    "costo" => 0.0
];
function insertaDato($datosParte)
{
    $baseDeDatos = new PDO("sqlite:./prov-par.db");
    $baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sentencia = $baseDeDatos->prepare("INSERT INTO parte(anio, nombre, costo)
	VALUES(:anio, :nombre, :costo);");
    $resultado = $sentencia->execute($datosParte);
    if ($resultado === true) {
        return true;
    } else {
        return false;
    }
}
//programa principal
if (!empty($_POST)) {
    //leemos los datos enviado por el front end
    $nombre = $_POST["nombre"];
    $anio = $_POST["anio"];
    $costo = $_POST["costo"];
    $datosParte["anio"] = $anio;
    $datosParte["nombre"] = $nombre;
    $datosParte["costo"] = $costo;
    if (insertaDato($datosParte)) {
        http_response_code(200);
        echo "se inserto el dato, salida: ok";
    } else {
        http_response_code(400);
        echo "No se pudo insertar el dato, salida: false";
    }
} else {
    http_response_code(400);
    echo "No se recibieron datos, salida: false";
}
exit();
?>