<?php

$datos = [
    [
        "id" => 1,
        "descripcion" => "plomeria",
    ],
    [
        "id" => 2,
        "descripcion" => "electricidad",
    ],
    [
        "id" => 3,
        "descripcion" => "carpinteria",

    ],
    [
        "id" => 4,
        "descripcion" => "aluminio",

    ]
];
//print_r($datos); 
echo json_encode($datos, JSON_UNESCAPED_UNICODE);

?>