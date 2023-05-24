<?php
if ((include __DIR__ . '/sqlite-abrir-db-provpar.php') == false) {
	echo "false";
	exit;
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
	#Podemos usar $baseDeDatos porque incluimos el archivo que la crea
	$resultado = $baseDeDatos->exec($definicionTabla);
	http_response_code(200);
	//echo "Tablas creadas correctamente";

}
// crearTabla($baseDeDatos);
?>