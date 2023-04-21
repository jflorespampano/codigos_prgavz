<?php
if ((include __DIR__.'/sqlite-provpar.php') == false) {
    echo "no se pudo cargar el archivo php";
    exit;
}
$objPP=new ProvPar();
$result_json=$objPP->consultaPartes("select * from parte;");
echo $result_json;
?>