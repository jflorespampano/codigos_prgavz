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

function construyeSentencia($baseDeDatos)
{
    $json_str = file_get_contents('php://input'); //obtener parametros de entrda 
    if (strlen($json_str) > 0) {
        $array = json_decode($json_str);
        $sentencia = $baseDeDatos->prepare("UPDATE tipo_parte SET descripcion=:descripcion  where id=:id;");
        # Debemos pasar a bindParam las variables, no podemos pasar el dato directamente
        # debido a que la llamada es por referencia
        $id = $array->id;
        $descripcion = $array->descripcion;
        $sentencia->bindParam(":id", $id);
        $sentencia->bindParam(":descripcion", $descripcion);
        return $sentencia;
    } else {
        //no hay datos
        return null;
    }
}
//programa principal
$baseDeDatos = abreDB();
if (!is_null($baseDeDatos)) {
    $sentencia = construyeSentencia($baseDeDatos);
    if (!is_null($sentencia)) {
        $resultado = $sentencia->execute();
        if ($sentencia->rowCount() > 0) {
            http_response_code(200);
            echo "registros afectados " . $sentencia->rowCount();
        } else {
            http_response_code(400);
            echo "No hubo registros afectados " . $sentencia->rowCount();
        }
    } else {
        http_response_code(400);
        echo "No se recibieron datos";
    }
} else {
    http_response_code(400);
    echo "no se pudo abrir la bd";
}


exit();
?>