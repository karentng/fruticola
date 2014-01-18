create table cosecha_pregunta(
    id              serial primary key not null,
    numero          integer not null,
    texto           text not null,
    tipo_respuesta  varchar(10) not null  -- 'UNICA' o 'MULTIPLE'
);

create table cosecha_opcionrespuesta(
    id               serial primary key not null,
    pregunta_id      integer not null references cosecha_pregunta(id),
    letra            char not null,
    texto            text not null
);

create table cosecha(
    id               serial primary key not null,
    ruat_id          integer not null references ruat(id),
    creado           timestamp not null default current_timestamp,
    creador_id       integer not null references users(id),
    fecha_visita     date not null,
    observaciones    text not null default ''
);

create table cosecha_respuesta(
    id               serial primary key not null,
    cosecha_id       integer not null references cosecha(id),
    pregunta_id      integer not null references cosecha_pregunta(id),
    opcion_id        integer not null references cosecha_opcionrespuesta(id),
    otro             text
);


insert into cosecha_pregunta(numero, texto, tipo_respuesta) values
    (1, 'HORA DE COSECHA', 'UNICA'),
    (2, 'CRITERIOS PARA DETERMINAR LA COSECHA', 'MULTIPLE'),
    (3, 'TIPOS DE HERRAMIENTAS PARA REALIZAR LA LABOR DE COSECHA', 'MULTIPLE'),
    (4, 'ELEMENTOS DE RECOLECCIÓN DURANTE LA LABOR DE COSECHA', 'MULTIPLE'),
    (5, 'PERSONAL DE COSECHA', 'UNICA'),
    (6, 'CUENTA CON UN SITIO ESPECÍFICO PARA ALMACENAR LA COSECHA EN SU FINCA (ZONA DE SOMBRA Ó CUARTO DE ALMACENAMIENTO TEMPORAL O ACOPIO)', 'UNICA'),
    (7, 'TIPO DE TRANSPORTE INTERNO UTILIZADO DURANTE LA COSECHA', 'MULTIPLE'),
    (8, 'PROTECCIÓN  DEL FRUTO', 'MULTIPLE');


insert into cosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (1, 'a', 'Mañana'),
    (1, 'b', 'Tarde'),
    (1, 'c', 'Mañana y tarde');

insert into cosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (2, 'a', 'Calendario de Cosecha'),
    (2, 'b', 'Color del Fruto'),
    (2, 'c', 'Olor del Fruto'),
    (2, 'd', 'Textura del Fruto'),
    (2, 'e', 'ºBrix'),
    (2, 'f', 'Turgencia del pedúnculo');

insert into cosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (3, 'a', 'Tijera'),
    (3, 'b', 'Tijera cosechadora'),
    (3, 'c', 'Cuchillo'),
    (3, 'd', 'Navaja'),
    (3, 'e', 'Varas (palos u horquetas)'),
    (3, 'f', 'Medialunas'),
    (3, 'g', 'Cuchillo Malayo'),
    (3, 'h', 'Lonas'),
    (3, 'i', 'Machete'),
    (3, 'j', 'Sacudido de árbol'),
    (3, 'k', 'Manual');

insert into cosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (4, 'a', 'Caja de madera'),
    (4, 'b', 'Canastillas de Plástico'),
    (4, 'c', 'Costales'),
    (4, 'd', 'Bolsas Plásticas'),
    (4, 'e', 'Canastilla de Mimbre'),
    (4, 'f', 'Tarros plástico'),
    (4, 'g', 'Recipientes metálicos'),
    (4, 'h', 'Canasta de Fibra Natural'),
    (4, 'i', 'Cajas de Cartón'),
    (4, 'j', 'Carretillas plásticas'),
    (4, 'k', 'Otro');

insert into cosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (5, 'a', 'Hombres'),
    (5, 'b', 'Mujeres'),
    (5, 'c', 'Mixto');

insert into cosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (6, 'a', 'Sí'),
    (6, 'b', 'No');

insert into cosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (7, 'a', 'Personal'),
    (7, 'b', 'Animales'),
    (7, 'c', 'Carros'),
    (7, 'd', 'Remolque'),
    (7, 'e', 'Carretilla de tracción  animal'),
    (7, 'f', 'Bandas transportadoras '),
    (7, 'g', 'Carretilla Manual'),
    (7, 'h', 'Cable aereo');

insert into cosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (8, 'a', 'Bolsa plástica'),
    (8, 'b', 'Bolsa de Malla'),
    (8, 'c', 'Cartón'),
    (8, 'd', 'Papel'),
    (8, 'e', 'Jumbolon (espuma plástica)'),
    (8, 'f', 'Ninguna');
