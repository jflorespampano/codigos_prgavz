# Instrucciones
## Requerimientos
1. Tener instalado XAMP server.
2. Opcinalmente tener un software para manejo de bases de datos SQLite, esto solo es necesario si quiere revisar, alterar o modificar la base de datos, le recomiendo el software SQLite Administrator que puede descargar desde: https://sqliteadmin.orbmu2k.de , es software libre.

## Ejecución
Para probar este ejercicio debe
1. Descargar esta carpeta (php-sqlite-basico)
2. Copiar esta carpeta en la carpeta c:/xamp/htdocs
3. Arrancar el servidor apache de Xamp
4. Cargar la aplicación en su navegador así:
5. http://localhost/php-sqlite-basico/index.html

La aplicación le permitirá mostrar datos e insertar registros.

# Explicación
En la carpeta tiene una base de datos de SQLite llamada **prov-par.db** con la tabla:
parte que fue creada con esta estructura:
```
CREATE TABLE parte(
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		anio INTEGER NOT NULL,
		nombre TEXT NOT NULL,
		costo REAL NOT NULL
	)
```
Este ejercicio ya funciona, sin embargo a continuación le describo los elementos mínimos para crear una aplicación que consulte e inserte datos, necesitaremos 3 archivos:
1. consulta_partes.php
2. inserta_parte.php
3. index.html

Para leer los datos de la tabla **parte** use el código PHP: **consulta_partes.php**
```
<?php
$baseDeDatos = new PDO("sqlite:./prov-par.db");
$baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM parte;";
$resultado = $baseDeDatos->query($sql);
$datos = $resultado->fetchAll(PDO::FETCH_OBJ);
http_response_code(200);
echo json_encode($datos, JSON_UNESCAPED_UNICODE);
?>
```
Para insertar un registro en la tabla parte use el código **inserta_parte.php**:
```
<?php

$datosParte = [
    "anio" => 2022,
    "nombre" => "",
    "costo" => 0.0
];
function insertaDato($datosParte)
{
    $baseDeDatos = new PDO("sqlite:./prov-par.db");
    $baseDeDatos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sentencia = $baseDeDatos->prepare("INSERT INTO parte(anio, nombre, costo)
	VALUES(:anio, :nombre, :costo);");
    $resultado = $sentencia->execute($datosParte);
    if ($resultado === true) {
        return true;
    } else {
        return false;
    }
}
//programa principal
if (!empty($_POST)) {
    //leemos los datos enviado por el front end
    $nombre = $_POST["nombre"];
    $anio = $_POST["anio"];
    $costo = $_POST["costo"];
    $datosParte["anio"] = $anio;
    $datosParte["nombre"] = $nombre;
    $datosParte["costo"] = $costo;
    if (insertaDato($datosParte)) {
        http_response_code(200);
        echo "se inserto el dato, salida: ok";
    } else {
        http_response_code(400);
        echo "No se pudo insertar el dato, salida: false";
    }
} else {
    http_response_code(400);
    echo "No se recibieron datos, salida: false";
}
exit();
?>
?>
    
```

Su archivo index.html debera tener la forma:
```
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>prov-par</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body class="w3-padding">
    <div id="App" class="w3-container w3-row">
        <div class="w3-col m4 w3-padding">
            <div class="w3-panel w3-card w3-grey">
                <p class="w3-dark-grey w3-padding">Alta</p>
            </div>
            <form id="frm_alta_parte" class="w3-container">
	       <input name="id" class="w3-input" type="text" placeholder="id">
    	       <input name="nombre" class="w3-input" type="text" placeholder="nombre de la parte">
               <input name="anio" class="w3-input" type="text" placeholder="año de entrada al almacen">
               <input name="costo" class="w3-input" type="text" placeholder="costo de venta">
               <input id="btn_agregar" type="button" class="w3-button w3-white w3-border w3-border-blue" value="Agregar parte">
            </form>
	</div>
        <div class="w3-col m8  w3-padding">
            <div class="w3-panel w3-card w3-grey">
                <p class="w3-dark-grey w3-padding">Lista</p>
            </div>
            <div id="mi_div" class="w3-panel w3-card w3-padding-16">
                <table id="tbl_partes" class="w3-table-all">
		   <thead>
                      <tr class="w3-grey">
                         <td>ID</td>
                         <td>Nombre</td>
                         <td>Año</td>
                         <td>Costo</td>
                      </tr>
                   </thead>
                   <tbody id="datos_tabla"></tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<script>

</script>
</html>
```

Para mostrar los datos, en el mismo archivo index.html necesitará el código JS en el área de script:
```
<script>
window.onload = function () {
    fetch("consulta_partes.php")
        .then(function (data) {
            if (data.ok) {
                data.json().then(function (dataJ) {
                    let s = ``;
                    dataJ.forEach(e => {
                        s += `<tr>
                        <td>${e.id}</td><td>${e.nombre}</td>
                        <td>${e.anio}</td><td>${e.costo}</td>
                    </tr>`;
                    });
                    document.getElementById("datos_tabla").innerHTML = s;
                });
            } else {
                data.text().then(data => console.log(data));
            }
        })
}
</script>
```

Para insertar un registro, agregue esté código a su script del archivo index.html 
Código JS:
```
const boton = document.querySelector("#btn_agregar");
boton.addEventListener("click", function () {
    fetch('inserta_parte.php', {
        method: 'post',
        body: new FormData(document.getElementById('frm_alta_parte'))
    }).then(function (returnedValue) {
        if (returnedValue.ok) {
            console.log("operacion correcta");
            returnedValue.text().then((txt) => {
                console.log('Respuesta del servidor: ', txt);
            });
        }
    }).catch(function (err) {
        console.log(err);
    });
})
```

