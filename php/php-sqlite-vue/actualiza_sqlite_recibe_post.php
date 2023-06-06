<?php
//recibe json: {"id":"33","descripcion":"descripcion"}
header("Content-Type: text/html;charset=utf-8");

function abreDB()
{
    $db = "sqlite:./prov-par-2.db";
    $sql = "select * from tipo_parte";
    try {
        $baseDeDatos = new PDO($db);
        $baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $baseDeDatos;
    } catch (PDOException $e) {
        echo "no se pudo conectar " . $e->getMessage();
        return null;
    }
}
function construyeSentencia()
{
    // var_dump($_POST);
    if (!empty($_POST)) {
        $id = (isset($_POST["id"]) ? $_POST["id"] : "");
        $descripcion = (isset($_POST["descripcion"]) ? $_POST["descripcion"] : "");
        $sql = "UPDATE tipo_parte SET descripcion='$descripcion' where id='$id';";
        return $sql;
    } else {
        echo "no hay datos post\n";
        return null;
    }
}
//programa principal
$sentencia = construyeSentencia();
if (!is_null($sentencia)) {
    $conexion = abreDB();
    if (!is_null($conexion)) {
        try {
            $query = $conexion->prepare($sentencia);
            $query->execute();
            $numReg = $query->rowCount();
            $query = NULL;
            $conexion = NULL;
            http_response_code(200);
            echo $numReg . " registros afectados";
        } catch (PDOException $e) {
            http_response_code(400);
            echo "error en la consulta " . $e->getMessage();
        }
    } else {
        //no se pudo conectar
        echo "no se pudo abrir la bd";
        http_response_code(400);
    }

} else {
    //no hay sentencia
    echo "No se se pudo construir la sentencia insert";
    http_response_code(400);
}

exit();
?>