<?php
header("Content-Type: text/html;charset=utf-8");
class DataBase
{
    protected string $db;
    protected string $user;
    protected string $pass;
    function __construct()
    {
        //nombre del archivo de la bd
        $this->db = "mysql:host=localhost;dbname=provpar2;charset=utf8";
        $this->user = "root";
        $this->pass = "";
    }
    /**
     * Abre una base de datos de MySql
     * @return object apuntador al manejador de la BD
     */
    function abrirDB()
    {
        try {
            //intenta la conexión
            $conexion = new PDO($this->db, $this->user, $this->pass);
            # se puede poner la siguiente linea
            # $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            # sin embargo a partir de php 8 el modo de error x defecto es: ERRMODE_EXCEPTION
            return $conexion;
        } catch (PDOException $e) {
            //atrapa el error
            http_response_code(400);
            echo "Falla al obtener un manejador de BD: " . $e->getMessage() . "\n";
            exit();
        }

    }
}
class ProvPar extends DataBase
{
    /**
     * arreglo con un dato
     * @access public
     * @var array
     */
    public $datosTipoParte = [
        "id" => 0,
        "descripcion" => ""
    ];
    /**
     * Consulta la tabla tipoPartes
     * @param string sql sentencia select...
     * @return string devuelve un json con el resultado de la consulta
     */
    function consultaTipoPartes($sql)
    {
        try {
            $baseDeDatos = $this->abrirDB();
            $resultado = $baseDeDatos->query($sql);
            $datos = $resultado->fetchAll(PDO::FETCH_OBJ);
            $baseDeDatos = null;
            http_response_code(200);
            return json_encode($datos, JSON_UNESCAPED_UNICODE);

        } catch (PDOException $e) {
            http_response_code(400);
            echo 'Excepcion error en la cosulta: ' . $sql . " descricion del error: " . $e->getMessage() . "\n";
            return json_encode([]);
        }
    }
    /**
     * Inserta un datos recibe un arreglo de esta forma:
     * $datosParte=[
     *	"id" => 0,
     *	"descripcion" => "la descripcion"
     *];
     *@param array $tipoParte array 
     *@return boolean sucess o fail
     */
    function insertaDato($tipoParte)
    {
        $baseDeDatos = $this->abrirDB();
        $sentencia = $baseDeDatos->prepare("INSERT INTO tipoparte(descripcion)
        VALUES(:descripcion);");

        # Debemos pasar a bindParam las variables, no podemos pasar el dato directamente
        # debido a que la llamada es por referencia
        $descripcion = $tipoParte["descripcion"];
        $sentencia->bindParam(":descripcion", $descripcion);
        //var_dump($sentencia);
        $resultado = $sentencia->execute();
        $baseDeDatos = null;
        if ($resultado === true) {
            http_response_code(200);
            return true;
        } else {
            http_response_code(400);
            return false;
        }
    }
    /**
     * actualiza un dato recibe un arreglo de esta forma:
     * $datosParte=[
     *	"id" => 0,
     *	"descripcion" => "la descripcion"
     *];
     *@param array $tipoParte array
     *@return boolean sucess o fail
     */
    function actualizaDato($tipoParte)
    {
        $baseDeDatos = $this->abrirDB();
        $sentencia = $baseDeDatos->prepare("UPDATE tipoparte SET descripcion=:descripcion WHERE id=:id;");

        # Debemos pasar a bindParam las variables, no podemos pasar el dato directamente
        # debido a que la llamada es por referencia
        $id = $tipoParte["id"];
        $descripcion = $tipoParte["descripcion"];

        $sentencia->bindParam(":id", $id);
        $sentencia->bindParam(":descripcion", $descripcion);
        $resultado = $sentencia->execute();
        $baseDeDatos = null;
        if ($resultado === true) {
            http_response_code(200);
            echo $sentencia->rowCount() . " registros afectados";
            return true;
        } else {
            http_response_code(400);
            return false;
        }
    }
    /**
     * eliminaDato un dato recibe un arreglo de esta forma:
     * $datosParte=[
     *	"id" => 0,
     *	"descripcion" => ""
     *];
     *@param array $tipoParte array
     *@return boolean sucess o fail
     */
    function eliminaDato($tipoParte)
    {
        $baseDeDatos = $this->abrirDB();
        $sentencia = $baseDeDatos->prepare("DELETE FROM tipoparte WHERE id=:id;");

        # Debemos pasar a bindParam las variables, no podemos pasar el dato directamente
        # debido a que la llamada es por referencia
        $id = $tipoParte["id"];
        $sentencia->bindParam(":id", $id);
        $resultado = $sentencia->execute();
        $baseDeDatos = null;
        if ($resultado === true) {
            http_response_code(200);
            return true;
        } else {
            http_response_code(400);
            return false;
        }
    }
} //fin de class ProvPar

/////////////////////////////////////////////// funciones ////////////////////////////
// funciones que recuperan los datos desde el request y llaman a la funcion 
//adecuada de la clase ProvPar.
//////////////////////////////////////////////////////////////////////////////////////
function update()
{
    if ($_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded') {
        //recuperar datos
        parse_str(file_get_contents("php://input"), $put_vars);
        $id = $put_vars['id'];
        $descripcion = $put_vars['descripcion'];
        $tipoParte = [
            "id" => $id,
            "descripcion" => $descripcion
        ];
        //ejecutar sentencia
        $objPP = new ProvPar();
        $respuesta = $objPP->actualizaDato($tipoParte);
        echo $respuesta;
        return true;
    } else {
        echo 'sin parametros';
        return false;
    }
}
function delete_()
{
    if ($_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded') {
        //recuperar datos
        parse_str(file_get_contents("php://input"), $put_vars);
        $id = $put_vars['id'];
        $tipoParte = [
            "id" => $id,
            "descripcion" => ""
        ];
        //ejecutar sentencia
        $objPP = new ProvPar();
        $respuesta = $objPP->eliminaDato($tipoParte);
        echo $respuesta;
        return true;
    } else {
        echo 'sin parametros';
        return false;
    }
}

function insert()
{
    if (isset($_POST) && count($_POST) > 0) {
        if ($_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded') {
            //se recibieron  por el método _POST
            $id = (isset($_POST["id"]) ? $_POST["id"] : "1");
            $descripcion = (isset($_POST["descripcion"]) ? $_POST["descripcion"] : "2");

            $tipoParte = [
                "id" => $id,
                "descripcion" => $descripcion
            ];
            //ejecutar sentencia
            $objPP = new ProvPar();
            $respuesta = $objPP->insertaDato($tipoParte);
            echo $respuesta;
        }
        return;
    } elseif ($_SERVER["CONTENT_TYPE"] == 'application/json') {
        // se recibieron datos en formato json
        $json_str = file_get_contents('php://input'); //obtener parametros de entrda 
        if (strlen($json_str) > 0) {
            $array = json_decode($json_str);
            $tipoParte = [
                "id" => $array->id,
                "descripcion" => $array->descripcion
            ];
            //ejecutar sentencia
            $objPP = new ProvPar();
            $respuesta = $objPP->insertaDato($tipoParte);
            echo $respuesta;
        } else {
            echo "[]";
        }
        exit();
    } else {
        echo 'sin parametros';
    }
}
function get()
{
    if (!empty($_GET)) {
        //se recibieron parametros por el método _GET en este formato:
        //http://localhost/prgavz/mi_script.php?id=55
        $id = (isset($_GET["id"]) ? $_GET["id"] : "");
        $objPP = new ProvPar();
        $data = $objPP->consultaTipoPartes("select * from tipoparte where id=" . $id);
        echo $data;
    } else {
        //devolvemos todos
        $objPP = new ProvPar();
        $data = $objPP->consultaTipoPartes("select * from tipoparte");
        echo $data;
    }
}

//////////////////////////////// main////////////////////////
function main()
{
    switch ($_SERVER["REQUEST_METHOD"]) {
        case "PUT":
            update();
            break;
        case "POST":
            insert();
            break;
        case "GET":
            get();
            break;
        case "DELETE":
            echo "fue delete";
            delete_();
            break;
        default:
            echo '<p>Error, no se recibio REQUEST_METHOD</p>';
            break;
    }
}

//llamado
main();

?>