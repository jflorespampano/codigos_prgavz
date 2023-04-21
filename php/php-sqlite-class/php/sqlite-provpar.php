<?php

class DataBase {
    protected string $db;
    function __construct() {
        //print "En el constructor BaseClass\n";
        $this->db="sqlite:./prov-par.db";
    }
    /**
     * Abre una base de datos de SQLite
     * @return object apuntador al manejadro de la BD
     */
    function abrirDB(){
        $baseDeDatos = new PDO($this->db);
        $baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $baseDeDatos;
    }
}
class ProvPar extends DataBase{
    /**
    * arreglo con un dato
    * @access public
    * @var array
    */
   public $datosParte=[
        "id" => 0,
        "anio" => 2022,
        "nombre" => "",
        "costo" => 0.0
    ];
    /**
     * Consulta la tabla partes
     * @param string sql sentencia select...
     * @return string devuelve un json con el resultado de la consulta
     */
    function consultaPartes($sql){
        try{
            $baseDeDatos=$this->abrirDB();
            $resultado = $baseDeDatos->query($sql);
            $videojuegos = $resultado->fetchAll(PDO::FETCH_OBJ);
            $baseDeDatos=null;
            http_response_code(200);
            return json_encode($videojuegos, JSON_UNESCAPED_UNICODE);
            
        }catch(PDOException $e){
            http_response_code(400);
            echo 'Excepcion error en la cosulta: '.$sql." descricion del error: ".$e->getMessage()."\n";
            return json_encode([]);
        }
    }
    /**
     * Inserta un datos recibe un arreglo de esta forma:
     * $datosParte=[
     *	"id" => 0,
    *	"anio" => 2022,
    *	"nombre" => "",
    *	"costo" => 0.0
    *];
    *@param array $datosParte array
    *@return boolean sucess o fail
    */
    function insertaDato($datosParte){
        $baseDeDatos=$this->abrirDB();
        $sentencia = $baseDeDatos->prepare("INSERT INTO parte(anio, nombre, costo)
        VALUES(:anio, :nombre, :costo);");

        # Debemos pasar a bindParam las variables, no podemos pasar el dato directamente
        # debido a que la llamada es por referencia
        $anio=$datosParte["anio"];
        $nombre=$datosParte["nombre"];
        $costo=$datosParte["costo"];
        $sentencia->bindParam(":anio", $anio);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":costo", $costo);
        $resultado = $sentencia->execute();
        $baseDeDatos=null;
        if($resultado === true){
            return true;
        }else{
            return false;
        }
    }
     /**
     * actualiza un dato recibe un arreglo de esta forma:
     * $datosParte=[
     *	"id" => 0,
    *	"anio" => 2022,
    *	"nombre" => "",
    *	"costo" => 0.0
    *];
    *@param array $datosParte array
    *@return boolean sucess o fail
    */
    function actualizaDato($datosParte){
        $baseDeDatos=$this->abrirDB();
        $sentencia = $baseDeDatos->prepare("UPDATE parte SET anio=:anio, nombre=:nombre, costo=:costo WHERE id=:id;");

        # Debemos pasar a bindParam las variables, no podemos pasar el dato directamente
        # debido a que la llamada es por referencia
        $id=$datosParte["id"];
        $anio=$datosParte["anio"];
        $nombre=$datosParte["nombre"];
        $costo=$datosParte["costo"];
        $sentencia->bindParam(":id", $id);
        $sentencia->bindParam(":anio", $anio);
        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":costo", $costo);
        $resultado = $sentencia->execute();
        $baseDeDatos=null;
        if($resultado === true){
            return true;
        }else{
            return false;
        }
    }
}

?>