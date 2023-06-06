<?php

$db = "sqlite:./prov-par-2.db";
$sql = "select * from tipo_parte";

try {
    $baseDeDatos = new PDO($db);
    $baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $resultado = $baseDeDatos->query($sql);
    $datos = $resultado->fetchAll(PDO::FETCH_ASSOC);
    $baseDeDatos = null;

    // var_dump($datos);
    http_response_code(200);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(400);
    echo 'Excepcion error en la cosulta: ' . $sql . " descricion del error: " . $e->getMessage() . "\n";
    echo json_encode([]);
}

?>