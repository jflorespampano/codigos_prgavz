<?php
/**
 * Lista de datos de prueba
 * @access public
 * @var array
 */
$DatosTipoPartes = [
    0 => [
        "id" => 0,
        "descripcion" => "tornillo 1/2"
    ],
    1 => [
        "id" => 1,
        "descripcion" => "tuerca 3/4"
    ],
    2 => [
        "id" => 2,
        "descripcion" => "arandela 5/16'"
    ],
    3 => [
        "id" => 3,
        "descripcion" => "perno 3/8"
    ]
];

/**
 * Abre una base de datos de SQLite
 * @return object apuntador al manejadro de la BD
 */
function abrirDB()
{
    $baseDeDatos = new PDO("sqlite:./prov-par-2.db");
    $baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $baseDeDatos;
}

/**
 * crea la tabla partes si no existe
 * @param object $baseDeDatos manejador de base de datos de sqlite
 */
function crearTabla($baseDeDatos)
{
    $definicionTabla = "CREATE TABLE IF NOT EXISTS tipo_parte(
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		descripcion TEXT NOT NULL
	);";

    $resultado = $baseDeDatos->exec($definicionTabla);
    return $resultado;
}

/**
 * Inserta un datos recibe un arreglo de esta forma:
 * $datosParte=[
 *	"id" => 0,
 *	"descripcion" => "la descripcion"
 *];
 *@param array $tipoParte array
 *@param object $baseDeDatos apuntador al manejador de base de datos
 *@return boolean sucess o fail
 */
function insertaDato($baseDeDatos, $tipoParte)
{
    $sentencia = $baseDeDatos->prepare("INSERT INTO tipo_parte(descripcion)
	VALUES(:descripcion);");

    # Debemos pasar a bindParam las variables, no podemos pasar el dato directamente
    # debido a que la llamada es por referencia
    $descripcion = $tipoParte["descripcion"];
    $sentencia->bindParam(":descripcion", $descripcion);
    $resultado = $sentencia->execute();
    if ($resultado === true) {
        http_response_code(200);
        return true;
    } else {
        http_response_code(400);
        return false;
    }
}

/**
 * Inserta un conjunto de registros de ejemplo
 * @param object $baseDeDatos manejador de la bd 
 * @param array $DatosPartes arreglo asociativo con la lista de datos a insertar
 */
function insertaDatosEjemplo($baseDeDatos, $DatosTipoPartes)
{
    //insertar datos de ejeplo
    $tipoParte = [
        "id" => 0,
        "descripcion" => "la descripcion"
    ];
    foreach ($DatosTipoPartes as $valor) {
        $tipoParte["descripcion"] = $valor["descripcion"];
        insertaDato($baseDeDatos, $tipoParte);
    }
}

$db = abrirDB();
if ($db) {
    http_response_code(200);
    crearTabla($db);
    insertaDatosEjemplo($db, $DatosTipoPartes);
} else {
    http_response_code(400);
}

?>