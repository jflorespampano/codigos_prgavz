<?php
if ((include __DIR__.'/sqlite-provpar.php') == false) {
    echo "no se pudo cargar el archivo php";
    exit;
}
$objPP=new ProvPar();

$objPP->datosParte["id"]="6";
$objPP->datosParte["nombre"]="cpu intel";
$objPP->datosParte["anio"]="2021";
$objPP->datosParte["costo"]="900.0";
$objPP->actualizaDato($objPP->datosParte);


?>