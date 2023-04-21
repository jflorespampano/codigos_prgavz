<?php
    if ((include __DIR__.'/sqlite-provpar.php') == false) {
        echo "no se pudo cargar el archivo php";
        exit;
    }
    header("Content-Type: text/html;charset=utf-8");
    if(! empty($_POST)){
        $objPP=new ProvPar();
        $objPP->datosParte["nombre"]= (isset($_POST["nombre"])?$_POST["nombre"]:"sin descripcion");
        $objPP->datosParte["anio"]= (isset($_POST["anio"])?$_POST["anio"]:"2023");
        $objPP->datosParte["costo"]= (isset($_POST["costo"])?$_POST["costo"]:"0");
        // $objPP->datosParte["nombre"]=$nombre;
        // $objPP->datosParte["anio"]=$anio;
        // $objPP->datosParte["costo"]=$costo;
        if($objPP->insertaDato($objPP->datosParte)){
            http_response_code(200);
            echo "se inserto el dato, salida: ok";
        }else{
            http_response_code(400);
            echo "No se pudo insertar el dato, salida: false";
        }
    }else{
        http_response_code(400);
        echo "No se recibieron datos, salida: false";
    }
    exit();
    

    
    
?>