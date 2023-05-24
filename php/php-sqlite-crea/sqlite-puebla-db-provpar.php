<?php
if ((include __DIR__ . '/sqlite-inserta-db-provpar.php') == false) {
	echo "false";
	exit;
}

$datosParte = [
	0 => [
		"id" => 0,
		"anio" => 2022,
		"nombre" => "tuerca 1/2",
		"costo" => 10.0
	],
	1 => [
		"id" => 1,
		"anio" => 2022,
		"nombre" => "tornillo 3/4",
		"costo" => 15.0
	],
	2 => [
		"id" => 2,
		"anio" => 2022,
		"nombre" => "arandela 1/2",
		"costo" => 9.0
	],
	3 => [
		"id" => 3,
		"anio" => 2022,
		"nombre" => "pija 3/8",
		"costo" => 8.0
	],
	4 => [
		"id" => 4,
		"anio" => 2022,
		"nombre" => "tuerca union 1/2",
		"costo" => 12.0
	],
];
/**
 * Inserta un conjuntod e registros de ejemplo
 * @param object $baseDeDatos manejador de la bd 
 * @param array $DatosPartes arreglo asociativo con la lista de datos a insertar
 */
function insertaDatosEjemplo($baseDeDatos, $DatosPartes)
{
	//insertar datos de ejeplo
	$datosParte = [
		"id" => 0,
		"anio" => 2022,
		"nombre" => "",
		"costo" => 0.0
	];
	foreach ($DatosPartes as $valor) {
		$datosParte["anio"] = $valor["anio"];
		$datosParte["nombre"] = $valor["nombre"];
		$datosParte["costo"] = $valor["costo"];
		insertaDato($baseDeDatos, $datosParte);
	}

}

function poblarTablas($DatosPartes)
{
	$db = abrirDB();
	if ($db) {
		insertaDatosEjemplo($db, $DatosPartes);
	} else {
		http_response_code(400);
	}
}
poblarTablas($datosParte);

?>