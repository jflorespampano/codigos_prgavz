<?php
//http://localhost/ejercicios/etc/php/devuelve_json_simple.php
//crear la conexion a la base de datos
try {
	//intenta la conexión
	$conexion = new PDO("mysql:host=localhost;dbname=provpar2;charset=utf8", "root", "");
} catch (PDOException $e) {
	//atrapa el error
	http_response_code(400);
	echo "Falla al obtener un manejador de BD: " . $e->getMessage() . "\n";
	exit();
}

// realizar la consulta y llenar la tabla
$query = $conexion->prepare("SELECT * FROM tipoparte");
$query->execute();

if ($query->rowCount() > 0) {
	$userData = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
	$userData = NULL;
}
unset($conexion);
http_response_code(200);
echo json_encode($userData);
// var_dump($userData);

?>