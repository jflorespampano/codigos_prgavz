<?php
if ((include __DIR__ . '/sqlite-crea-tablas-provpar.php') == false) {
    echo "false";
    exit;
}
$db = abrirDB();
if ($db) {
    crearTabla($db);
    echo "tablas creadas, salida: ok";
    http_response_code(200);
} else {
    http_response_code(400);
    echo "no se pudieron crear las tablas, salida: false";
}
?>