# codigos_prgavz
## Objetivo
Este material esta orientado a los alumnos del curso de programación avanzada de ITCC de la UNACAR
## HTML
Conjunto básico de ejercicios html pra el curso.  
Las ligas de referencia de este tema son:

[referencia HTML](https://www.w3schools.com/html/)

[referencia html *mozila en español*](https://developer.mozilla.org/es/docs/Web/HTML)

## CSS
Ejemplos de uso de CSS

[referencia css](https://www.w3schools.com/css/)

[referencia css *mozila español*](https://developer.mozilla.org/es/docs/Web/CSS)

## W3CSS
Ejemplos de uso de W3CSS

[referencia w3cc ](https://www.w3schools.com/w3css/defaulT.asp)

## JS
Sentencias básicas de JS con promesas y Fetch

[referencia JS](https://www.w3schools.com/js/)

[referencia JS mozilla español](https://developer.mozilla.org/es/docs/Web/JavaScript)

## PHP
Sintaxis básica de php

[referencia php](https://developer.mozilla.org/es/docs/Glossary/PHP)

[referencia php](https://www.w3schools.com/php/)

## WebSql
es una especificación con un conjunto de APIs para utilizar una base de datos de SQL.
Web SQL Database es una API de página web para almacenar datos en bases de datos que pueden consultarse utilizando una variante de SQL.

La API es compatible con Google Chrome,3 Opera,4 y el navegador integrado de Android .

El Grupo de Trabajo de Aplicaciones Web de la W3C dejó de trabajar en la especificación en noviembre de 2010, citando la falta de implementaciones independientes (es decir, el uso de un sistema de base de datos que no sea SQLite como back-end) como la razón por la cual la especificación no pudo avanzar para convertirse en una Recomendación del W3C.1

Mozilla Corporation fue una de las principales voces detrás de la ruptura de las negociaciones y la desaprobación de la norma, mientras que al mismo tiempo fueron los principales defensores de una norma de 'almacenamiento alternativo', IndexedDB.
 
No obstante es soportado por los navegadores arriba mencioonados, a continución, veremos su uso.
### Abrir/crear base de datos
```
    function abrirDB(){
        var db=openDatabase("personaDB","1.0","mis alumnos",5*1024*1024);
        return db;
    }
```
### Crear una tabla
```
    function crear_tabla_persona(db){
        var sql="CREATE TABLE IF NOT EXISTS persona (id real uniqute, nombre text)";
        db.transaction(function(tx){
            tx.executeSql(sql,[],function(tx,result){
                console.log("tabla creada");
            });
            },
            function(tx){
                //error callback
                console.log("Error en la transaccion");
            },
            function(tx){
                //succes call back
                console.log("Transaccion ok");
        });
    }

```
### borrar datos
```
    function borrar_tabla_persona(db){
        db.transaction(function(tx){
            tx.executeSql("DROP TABLE persona"(),[],function(tx,result){
                console.log(result.rows);
            });
            },
            function(tx){
                //error callback
                console.log("Error en la transaccion drop");
            },
            function(tx){
                //succes call back
                console.log("tabla eliminada ok");
        });
    }
```
### insertar datos
```
    function insertar_persona(db,{id,nombre}){
        //insertar datos
        var sql="INSERT INTO persona(id, nombre) values(?,?)";
        db.transaction(function(tx){
            tx.executeSql(sql,[id,nombre],function(tx,result){
                console.log("dato insertado");
            });
            },
            function(tx){
                //error callback
                console.log("Error en la transaccion insert");
            },
            function(tx){
                //succes call back
                console.log("Transaccion ok");
        });
    }

```
### consultar datos
```
    function get_peronas(db){
        //leer datos
        let sql="select * from persona;";
        db.transaction(function(tx){
            tx.executeSql(sql,[],function(tx,result){
                //console.log(result.rows);
                let rows=result.rows;
                for(let i=0;i<rows.length;i++){
                    console.log(rows[i].nombre);
                }

            });
            },
            function(tx){
                //error callback
                console.log("Error en la consulta select");
            },
            function(tx){
                //succes call back
                console.log("Transaccion ok");
        });
    }
```

Veamos como insertar una persona usando estas funciones, necesitaremos un formulario:
```
    <form id="frm_parsona">
        <input type="text" name="id" placehholder="id">
        <input type="text" name="nombre" placeholder="nombre">
        <input type="button" value="agregar" onclick="agregar_persona()">
    </form>
    <input type="button" value="crear base de datos" onclick="crear_db()">
```
La función de craer_db
```
    function crear_db(){
        //
        var db=abrirDB();
    }
```
La función agregar_persona sería:
```
    function agregar_persona(){
        let f=document.getElementById("frm_parsona");
        data=new FormData(f);
        var object = {};
        data.forEach((value, key) => object[key] = value);
        var json = JSON.stringify(object);
        console.log("mi json tiene:"+json);
        var db=abrirDB();
        //insertar_persona(db,{"id":2,"nombre":"roxana"});
        insertar_persona(db,object);
        get_peronas(db);
    }
```

## MySql
Crear una base de datos en mysql y consultarla desde php

[Referencia MySql](https://dev.mysql.com/doc/refman/8.0/en/)

