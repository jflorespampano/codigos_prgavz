CREATE Table
    if not exists carreras(
        id integer PRIMARY KEY autoincrement,
        alias VARCHAR(10),
        nombre VARCHAR(30),
        creditos integer
    );

insert into
    carreras(alias, nombre, creditos)
values
(
        'ico',
        'ingenieria en computación',
        100
    );

insert into
    carreras(alias, nombre, creditos)
values
(
        'isc',
        'ingenieria en sistemas computacionales',
        110
    );

insert into
    carreras(alias, nombre, creditos)
values
(
        'itcc',
        'ingenieria en tcnoligias de computo y comunicaciones',
        105
    );
CREATE Table
    if not exists alumnos(
        id integer PRIMARY KEY autoincrement,
        matricula VARCHAR(10),
        nombre VARCHAR(30),
        carrera integer,
        foreign key (carrera) references carreras
    );
    insert into alumnos(matricula, nombre, carrera) values('222120','juan perez',1);
    insert into alumnos(matricula, nombre, carrera) values('232122','ana puc',1);
    insert into alumnos(matricula, nombre, carrera) values('232124','rosa uc',2);
    insert into alumnos(matricula, nombre, carrera) values('222126','luis ruiz',3);
    insert into alumnos(matricula, nombre, carrera) values('232127','paco lin',2);
    insert into alumnos(matricula, nombre, carrera) values('202127','rita xa',3);