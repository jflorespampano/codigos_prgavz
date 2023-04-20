<?php
function abreConexion()
{
    //crear la conexion a la base de datos
    try {
        //intenta la conexi�n
        $conexion = new PDO("mysql:host=localhost;dbname=provpar2;charset=utf8", "root", "");
        return $conexion;
    } catch (PDOException $e) {
        //atrapa el error
        echo "Error al realizar la conexion a la BD" . $e->getMessage();
        http_response_code(400);
        return null;
    }
}
function construyeSentenciaInsert()
{
    if (!empty($_POST)) {
        $id = (isset($_POST["id"]) ? $_POST["id"] : "");
        $descripcion = (isset($_POST["descripcion"]) ? $_POST["descripcion"] : "");
        $insert = "INSERT INTO tipoparte (id, descripcion)";
        $values = " VALUES ('$id','$descripcion')";
        $sql = $insert . $values;
        return $sql;
    } else {
        //echo "no hay datos post";
        return null;
    }
}
//programa principal
$sentencia = construyeSentenciaInsert();
if (!is_null($sentencia)) {
    $conexion = abreConexion();
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
        http_response_code(400);
    }

} else {
    //no hay sentencia
    echo "No se recibieron datos a insertar";
    http_response_code(400);
}

exit();
?>