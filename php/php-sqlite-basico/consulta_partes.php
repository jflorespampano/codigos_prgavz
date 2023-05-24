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
 * Consulta la tabla partes
 * @param object $baseDeDatos manejador de la BD
 * @param string sql sentencia select...
 * @return string devuelve un json con el resultado de la consulta
 */
function consultaPartes($baseDeDatos, $sql)
{
    try {

        $resultado = $baseDeDatos->query($sql);
        $datos = $resultado->fetchAll(PDO::FETCH_OBJ);
        // var_dump($videojuegos);
        http_response_code(200);
        return json_encode($datos, JSON_UNESCAPED_UNICODE);

    } catch (Exception $e) {
        http_response_code(400);
        echo 'Excepcion error en la cosulta: ' . $sql . " descricion del error: " . $e->getMessage() . "\n";
        return json_encode([]);
    }
}
//programa principal
$db = abrirDB();
if ($db) {
    $sql = "SELECT * FROM parte;";
    echo consultaPartes($db, $sql);
} else {
    http_response_code(400);
    echo json_encode([]);
}
?>