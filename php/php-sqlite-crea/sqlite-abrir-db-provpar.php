<?php
/**
 * Abre una base de datos de SQLite
 * @return object apuntador al manejadro de la BD
 */
function abrirDB(){
        $baseDeDatos = new PDO("sqlite:./prov-par.db");
        $baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $baseDeDatos;
}
?>