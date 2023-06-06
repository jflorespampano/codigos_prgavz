# DAO

En software, un objeto de acceso a datos (en inglés: data access object, abreviado **DAO**) es un componente de software que suministra una interfaz común entre la aplicación y uno o más dispositivos de almacenamiento de datos, tales como una Base de datos o un archivo.

En este ejemplo se muestra una interfaz comun para acceso a la "misma" base de datos implementada en **SQLite** y **MySQl**.

El archivo **alumnoDAO.php** conteien 3 clases:

Las primeras tienen solo la función estatica **abrirDB()** que abre la base de datos, ya sea en **SQLite** o **MySQl**.

```php
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
```

La tercera clase es la clase que realiza las taresa de insercion, recuperacion de datos etcétera, y hereda de alguna de estas clases anteriores.

Comentando o descomentando las líneas de código:
```php
class AlumnoDAO extends DataBaseSQLite
```
```php
class AlumnoDAO extends DataBaseMySql
```
Tarabajarenos con la base de datos en **SQLite** o **MySQl**, sin modificar el código de la clase AlumnoDAO.

```php
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
```