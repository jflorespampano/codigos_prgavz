# Actividad 1
Para las siguientes situaciones, realice una maqueta (diseñe la página html utilizando w3css).
* desea capturar los datos de un paciente: curp, nombre, fecha de nacimiento, sexo, con un conjunto de check box idique si es alergico a:(penicilina, sulfas, latex polen), el formulario debe tenr un botón aceptar, al presionar el botón debera revisar las alergias del paciente y si este tiene 2 o mas alergias, debera poner el nombre del paciente en rojo (ver tip 1 y tip 2).
* desea capturar los datos de un producto en un almacen: (id, descripción, existencia, stock máximo, stock mínimo, precio de compra, precio de venta, marge de utilidad),
al presionar el botón aceptar con código JS deberá calcular el margen de utilidad y ponerlo en el campo adecuado.
* desea mostrar los datos de los alumnos de programación avanzada, investigue sus matriculas, nombres y sexo y pongalos en una tabla con formato cw3css.
* desea hacer una página web que muestre su curriculum asi como los lenguajes que maneja, utilice estilos w3css para mostrar la página.
desea mostrar los datos de las carreras de la fci un una tabla con formato w3css, deb emostrar carrera, gestor, poblacion estudiantil, si esta certificada o no, fecha de creación y fecha de certificación.
```
Fecha de realización de esta actividad 15 febrero 2023.
Fecha de entrega 15 de febrero 2023.
Forma de entrega, el profesor la revisra mediante una lista de cotejo.
```

## tip 1
El siguiente código revisa los gustos seleccionado y los muestra en la cosola:
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>Document</title>
</head>
<body>
    <h3>gustos</h3>
    <form action="">
        fruta<input type="checkbox" name="gustos" id="fr" value="fruta">
        licor<input type="checkbox" name="gustos" id="lc" value="licor">
        cafe<input type="checkbox" name="gustos" id="ca" value="cafe">
        te<input type="checkbox" name="gustos" id="te" value="te">
        <input type="button" value="aceptar" onclick="mifuncion()">
    </form>
</body>
<script>
    function mifuncion(){
        
        let tagGustos=document.querySelectorAll('input[name="gustos"]:checked');
        tagGustos.forEach(element => {
                console.log("le gsuta: "+element.value);
        });
    }
</script>
</html>
```
tip 2, poner en color rojo un input text bajo cierta condición:
```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Document</title>
</head>
<body>
    <h3>gustos</h3>
    <form action="">
        <input type="text" id="txt_persona" value="juan perez">
        fruta<input type="checkbox" name="gustos" id="fr" value="fruta">
        licor<input type="checkbox" name="gustos" id="lc" value="licor">
        cafe<input type="checkbox" name="gustos" id="ca" value="cafe">
        te<input type="checkbox" name="gustos" id="te" value="te">
        <input type="button" value="aceptar" onclick="mifuncion()">
    </form>
</body>
<script>
    function mifuncion(){
        let tagGustos=document.querySelectorAll('input[name="gustos"]:checked');
        let numal=0;
        tagGustos.forEach(element => {
            numal++;
        });
        if(numal>1){
            let x=document.getElementById("txt_persona");
            x.classList.add("w3-red")
        }
    }
</script>
</html>
```

