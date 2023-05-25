# Instrucciones
## Requerimientos
1. Tener instalado XAMP server.
2. Opcinalmente tener un software para manejo de bases de datos SQLite, esto solo es necesario si quiere revisar, alterar o modificar la base de datos, le recomiendo el software SQLite Administrator que puede descargar desde: https://sqliteadmin.orbmu2k.de 

## Ejecución
Para probar este ejercicio debe
1. Descargar esta carpeta (php-sqlite-basico)
2. Copiar esta carpeta en la carpeta c:/xamp/htdocs
3. Arrancar el servidor apache de Xamp
4. Cargar la aplicación en su navegador así:
5. http://localhost/php-sqlite-basico/index.html

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

Para mostrar los datos en una tabla en su archivo index.html, use este código:
HTML
```
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
```
En el mismo archivo index.html necesitará el código JS:
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

Para insertar un registro, agregue este código a su archivo index.html:
Código HTML:
```
<form id="frm_alta_parte" class="w3-container">
    <input name="id" class="w3-input" type="text" placeholder="id">
    <input name="nombre" class="w3-input" type="text" placeholder="nombre de la parte">
    <input name="anio" class="w3-input" type="text" placeholder="año de entrada al almacen">
    <input name="costo" class="w3-input" type="text" placeholder="costo de venta">
    <input id="btn_agregar" type="button" class="w3-button w3-white w3-border w3-border-blue" value="Agregar parte">
</form>
```
Agregue esté código a su script del archivo index.html 
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
                console.log('muestro respuesta: ', txt);
                consultaPartes();
                    ponMensaje(txt);
            });
        }
    }).catch(function (err) {
        console.log(err);
    });
})
```

