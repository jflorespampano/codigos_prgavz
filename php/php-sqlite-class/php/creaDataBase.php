<?php
/**
 * Lista de datos de prueba
 * @access public
 * @var array
 */
$DatosPartes = [
	0 => [
		"id" => 0,
		"anio" => 2023,
		"nombre" => "tornillo 1/2",
		"costo" => 3.0
	],
	1 => [
		"id" => 1,
		"anio" => 2023,
		"nombre" => "tuerca 1/2",
		"costo" => 5.5
	],
	2 => [
		"id" => 2,
		"anio" => 2022,
		"nombre" => "tornillo 3/8",
		"costo" => 2.0
	],
	3 => [
		"id" => 3,
		"anio" => 2022,
		"nombre" => "taquete 1/2",
		"costo" => 1.0
	]
];

/**
 * Abre una base de datos de SQLite
 * @return object apuntador al manejadro de la BD
 */
function abrirDB()
{
	$baseDeDatos = new PDO("sqlite:./prov-par.db");
	$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $baseDeDatos;
}

/**
 * crea la tabla partes si no existe
 * @param object $baseDeDatos manejador de base de datos de sqlite
 */
function crearTabla($baseDeDatos)
{
	$definicionTabla = "CREATE TABLE IF NOT EXISTS parte(
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		anio INTEGER NOT NULL,
		nombre TEXT NOT NULL,
		costo REAL NOT NULL
	);";

	$resultado = $baseDeDatos->exec($definicionTabla);

}

/**
 * Inserta un datos recibe un arreglo de esta forma:
 * $datosParte=[
 *	"id" => 0,
 *	"anio" => 2022,
 *	"nombre" => "",
 *	"costo" => 0.0
 *];
 *@param array $datosParte array
 *@param object $baseDeDatos apuntador al manejador de base de datos
 *@return boolean sucess o fail
 */
function insertaDato($baseDeDatos, $datosParte)
{
	$sentencia = $baseDeDatos->prepare("INSERT INTO parte(anio, nombre, costo)
	VALUES(:anio, :nombre, :costo);");

	# Debemos pasar a bindParam las variables, no podemos pasar el dato directamente
	# debido a que la llamada es por referencia
	$anio = $datosParte["anio"];
	$nombre = $datosParte["nombre"];
	$costo = $datosParte["costo"];
	$sentencia->bindParam(":anio", $anio);
	$sentencia->bindParam(":nombre", $nombre);
	$sentencia->bindParam(":costo", $costo);
	$resultado = $sentencia->execute();
	if ($resultado === true) {
		return true;
	} else {
		http_response_code(400);
		return false;
	}
}

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

$db = abrirDB();
if ($db) {
	http_response_code(200);
	crearTabla($db);
	insertaDatosEjemplo($db, $DatosPartes);
} else {
	http_response_code(400);
}

?>