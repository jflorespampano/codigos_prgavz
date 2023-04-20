<?php
//crear la conexion a la base de datos
$conexion = new PDO("mysql:host=localhost;dbname=provpar2;charset=utf8", "root", "");
$query = $conexion->prepare("SELECT * FROM tipoparte");
$query->execute();
$datos = $query->fetchAll(PDO::FETCH_ASSOC);
unset($conexion);
echo json_encode($datos, JSON_UNESCAPED_UNICODE);

?>