<?php
class DataBaseSQLite
{
    static string $db = "sqlite:./alumnos.s3db";
    static function abrirDB()
    {
        $conexion = new PDO(DataBaseSQLite::$db);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexion;
    }
}
class DataBaseMySql
{
    protected string $db;
    protected string $host;
    protected string $cadcon;
    protected string $user;
    protected string $pass;
    function __construct()
    {
        $this->host = "localhost";
        $this->db = "facultad";
        $this->cadcon = "mysql:host=$this->host;dbname=$this->db;charset=utf8";
        $this->user = "root";
        $this->pass = "";
    }

    function abrirDB()
    {
        try {
            //intenta la conexión
            $conexion = new PDO($this->cadcon, $this->user, $this->pass);
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        } catch (PDOException $e) {
            //atrapa el error
            http_response_code(400);
            echo "Falla al obtener un manejador de BD: " . $e->getMessage() . "\n";
            return null;
        }
    }
}
// class AlumnoDAO extends DataBaseSQLite
class AlumnoDAO extends DataBaseMySql
{
    function insert($alumno)
    {
        #ejecutamos una sentencia INSERT
        $baseDeDatos = $this->abrirDB();
        $sql = "insert into alumnos " .
            "(matricula, nombre, carrera) " .
            "values(:matricula, :nombre, :carrera)";
        $sentencia = $baseDeDatos->prepare($sql);
        $resultado = $sentencia->execute($alumno);
        $sentencia = null;
        $baseDeDatos = null;
        if ($resultado === true) {
            return true;
        } else {
            return false;
        }
    }
    function read($id)
    {
        #EJECUTAMOS SENTENCIA SELECT
        try {
            $baseDeDatos = $this->abrirDB();
            $sql = "select * from alumnos where id=$id;";
            $stm = $baseDeDatos->query($sql);
            $rows = $stm->fetch(PDO::FETCH_OBJ);
            //cerrar la conexion
            $stm = null;
            $baseDeDatos = null;
            return [json_encode($rows, JSON_UNESCAPED_UNICODE), null];
        } catch (PDOException $e) {
            http_response_code(400);
            $error = 'Excepcion error en la cosulta: ' . $sql . " descricion del error: " . $e->getMessage() . "\n";
            return [json_encode([]), $error];
        }
    }
    function readAll()
    {
        #EJECUTAMOS SENTENCIA SELECT
        try {
            $baseDeDatos = $this->abrirDB();
            $stm = $baseDeDatos->query('SELECT * FROM alumnos;');
            $rows = $stm->fetchAll(PDO::FETCH_OBJ);
            //cerrar la conexion
            $stm = null;
            $baseDeDatos = null;
            return [json_encode($rows, JSON_UNESCAPED_UNICODE), null];
        } catch (PDOException $e) {
            $error = 'Excepcion error en la cosulta: ' . " descricion del error: " . $e->getMessage() . "\n";
            return [json_encode([]), $error];
        }

    }
    function update($alumno)
    {
        $baseDeDatos = $this->abrirDB();
        $sql = "UPDATE alumnos " .
            "SET matricula=:matricula, " .
            "nombre=:nombre, " .
            "carrera=:carrera " .
            "WHERE id=:id;";
        $sentencia = $baseDeDatos->prepare($sql);
        $resultado = $sentencia->execute($alumno);
        $sentencia = null;
        $baseDeDatos = null;
        if ($resultado === true) {
            return true;
        } else {
            return false;
        }
    }
    function delete($alumno)
    {
        $baseDeDatos = $this->abrirDB();
        $sql = "delete  from alumnos " .
            "WHERE id=:id;";
        $sentencia = $baseDeDatos->prepare($sql);
        $resultado = $sentencia->execute($alumno);
        $sentencia = null;
        $baseDeDatos = null;
        if ($resultado === true) {
            return true;
        } else {
            return false;
        }
    }
}

///////////////////////////
// funciones
///////////////////////////
class ClsRequest
{
    function getOne($cual)
    {
        $objDB = new AlumnoDAO();
        list($data, $error) = $objDB->read($cual);
        if (is_null($error)) {
            http_response_code(200);
            echo $data;
        } else {
            http_response_code(400);
            echo $error;
        }
        $objDB = null;
    }
    function getAll()
    {
        $objDB = new AlumnoDAO();
        // $data = $objDB->read(1);
        list($data, $error) = $objDB->readAll();
        if (is_null($error)) {
            http_response_code(200);
            echo $data;
        } else {
            http_response_code(400);
            echo $error;
        }
        $objDB = null;
    }
    function get()
    {
        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            $this->getOne($id);
        } else {
            $this->getAll();
        }
    }
    //
    function update()
    {
        if ($_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded') {
            //recuperar datos
            parse_str(file_get_contents("php://input"), $put_vars);
            $id = $put_vars['id'];
            $matricula = $put_vars['matricula'];
            $nombre = $put_vars['nombre'];
            $carrera = $put_vars['carrera'];
            $alumno = [
                "id" => $id,
                "matricula" => $matricula,
                "nombre" => $nombre,
                "carrera" => $carrera
            ];
            //ejecutar sentencia
            $objDB = new AlumnoDAO();
            $respuesta = $objDB->update($alumno);
            if ($respuesta) {
                http_response_code(200);
                echo "datos actualizados";
            } else {
                http_response_code(400);
                echo "no se pudo actualizar el dato";
            }
            return true;
        } else {
            http_response_code(400);
            echo 'sin parametros';
            return false;
        }
    }
    function insert()
    {
        if ($_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded') {
            //recuperar datos
            parse_str(file_get_contents("php://input"), $put_vars);
            // $id = $put_vars['id'];
            $matricula = $put_vars['matricula'];
            $nombre = $put_vars['nombre'];
            $carrera = $put_vars['carrera'];
            $alumno = [
                "matricula" => $matricula,
                "nombre" => $nombre,
                "carrera" => $carrera
            ];
            //ejecutar sentencia
            $objDB = new AlumnoDAO();
            $respuesta = $objDB->insert($alumno);
            if ($respuesta) {
                http_response_code(200);
                echo "datos actualizados";
            } else {
                http_response_code(400);
                echo "no se pudo actualizar el dato";
            }
            return true;
        } else {
            http_response_code(400);
            echo 'sin parametros';
            return false;
        }
    }
    function delete()
    {
        if ($_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded') {
            //recuperar datos
            parse_str(file_get_contents("php://input"), $put_vars);
            $id = $put_vars['id'];
            $alumno = [
                "id" => $id
            ];
            //ejecutar sentencia
            $objDB = new AlumnoDAO();
            $respuesta = $objDB->delete($alumno);
            if ($respuesta) {
                http_response_code(200);
                echo "dato eliminado";
            } else {
                http_response_code(400);
                echo "no se pudo actualizar el dato";
            }
            return true;
        } else {
            http_response_code(400);
            echo 'sin parametros';
            return false;
        }
    }

}

///programa principal
function main()
{
    $or = new ClsRequest();
    switch ($_SERVER["REQUEST_METHOD"]) {
        case "PUT":
            $or->update();
            break;
        case "POST":
            $or->insert();
            break;
        case "GET":
            $or->get();
            break;
        case "DELETE":
            $or->delete();
            break;
        default:
            echo '<p>Error, no se recibio REQUEST_METHOD</p>';
            break;
    }

}
main();
?>