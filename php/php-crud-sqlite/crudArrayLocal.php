<?php
//declara un arreglo asociativo
$userData = [
    0 => [
        'id' => '01',
        'descripcion' => 'hogar'
    ],
    1 => [
        'id' => '02',
        'descripcion' => 'jardineria'
    ],
    2 => [
        'id' => '03',
        'descripcion' => 'plomeria'
    ]
];
// $headers = apache_request_headers();

// foreach ($headers as $header => $value) {
//     echo "$header: $value <br />\n";
// }
// var_dump($_SERVER);
// echo ('url:' . $_SERVER["REQUEST_URI"]);
// echo ('request:' . $_SERVER["REQUEST_METHOD"]);
//echo ('Content-Type: es ->' . $_SERVER["CONTENT_TYPE"]);

function update()
{

}
function insert($userData)
{
    if (isset($_POST) && count($_POST) > 0) {
        if ($_SERVER["CONTENT_TYPE"] == 'application/x-www-form-urlencoded') {
            //se recibieron  por el método _POST
            $id = (isset($_POST["id"]) ? $_POST["id"] : "1");
            $descripcion = (isset($_POST["descripcion"]) ? $_POST["descripcion"] : "2");
            $array = [
                'id' => $id,
                'descripcion' => $descripcion
            ];
            // var_dump($array);
            array_push($userData, $array);
            echo json_encode($userData, JSON_UNESCAPED_UNICODE);
        }
        return true;
    } elseif ($_SERVER["CONTENT_TYPE"] == 'application/json') {
        // echo ('json recibido');
        $json_str = file_get_contents('php://input'); //obtener parametros de entrda 
        if (strlen($json_str) > 0) {
            $array = json_decode($json_str);
            array_push($userData, $array);
            echo json_encode($userData, JSON_UNESCAPED_UNICODE);
            return true;
        } else {
            echo "sin parametros";
        }
        exit();
    } else {
        echo 'sin parametros';
        return false;
    }
}
function get($userData)
{
    if (!empty($_GET)) {
        //se recibieron parametros por el método _GET, devolvemos el dato copn id=
        //http://localhost/prgavz/prueba.php?id=55
        $id = (isset($_GET["id"]) ? $_GET["id"] : "");
        $row = [];
        for ($i = 0; $i < count($userData); $i++) {
            if ($userData[$i]['id'] == $id) {
                $row = $userData[$i];
                // var_dump($row);
                break;
            }
        }
        echo json_encode($row, JSON_UNESCAPED_UNICODE);
    } else {
        //devolvemos todos
        echo json_encode($userData, JSON_UNESCAPED_UNICODE);
    }
}
switch ($_SERVER["REQUEST_METHOD"]) {
    case "PUT":
        update();
        break;
    case "POST":
        insert($userData);
        break;
    case "GET":
        get($userData);
        break;
    default:
        echo '<p>Error, no se recibieron datos</p>';
        break;
}


//convierte el arreglo asociativo a formato json y lo devulve al cliente
//JSON_UNESCAPED_UNICODE es para el manejo de caracteres especiales como acentos
//echo json_encode($userData, JSON_UNESCAPED_UNICODE);
?>