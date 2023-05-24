<?php
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
 * arreglo con un dato
 * @access public
 * @var array
 */
$datosParte = [
    "id" => 0,
    "anio" => 2022,
    "nombre" => "",
    "costo" => 0.0
];


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
        return false;
    }
}


//programa principal
if (!empty($_POST)) {
    //
    $nombre = (isset($_POST["nombre"]) ? $_POST["nombre"] : "sin descripcion");
    $anio = (isset($_POST["anio"]) ? $_POST["anio"] : "2023");
    $costo = (isset($_POST["costo"]) ? $_POST["costo"] : "0");
    $datosParte["anio"] = $anio;
    $datosParte["nombre"] = $nombre;
    $datosParte["costo"] = $costo;
    $db = abrirDB();
    if ($db) {
        if (insertaDato($db, $datosParte)) {
            http_response_code(200);
            echo "se inserto el dato, salida: ok";
        } else {
            http_response_code(400);
            echo "No se pudo insertar el dato, salida: false";
        }
    } else {
        http_response_code(400);
        echo json_encode("no se pudo abrir la base de datos, salida: false");
    }
} else {
    http_response_code(400);
    echo "No se recibieron datos, salida: false";
}
exit();
?>