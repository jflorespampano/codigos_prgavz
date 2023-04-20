<?php
//crear la conexion a la base de datos
try {
	//intenta la conexión
	$conexion = new PDO("mysql:host=localhost;dbname=provpar2;charset=utf8", "root", "");
} catch (PDOException $e) {
	$error = $e->getMessage();
	$datos = NULL;
	echo json_encode($datos);
	exit();
}
// realizar la consulta y llenar la tabla
try {
	$query = $conexion->prepare("SELECT * FROM tipoparte");
	$query->execute();
	//$cuantos=$query->rowCount();
	$datos = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
	$datos = NULL;
}
unset($conexion);
echo json_encode($datos, JSON_UNESCAPED_UNICODE);

?>