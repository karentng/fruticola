create table meta_complementaria(
    id              serial primary key not null,
    total           integer,
    porcentaje      integer
); -- esto es por si son varios

create table meta_complementaria_pregunta(
    id              serial primary key not null,
    concepto        varchar(70) not null,
    meta            integer,
    orden           integer not null
);

create table meta_complementaria_respuesta(
    id              serial primary key not null,
    meta_id         integer not null references meta_complementaria(id),
    pregunta        integer not null references meta_complementaria_pregunta(id),
    mes             varchar(10)
);

insert into meta_complementaria_pregunta(concepto, orden) values
    ('Número de créditos planificados', 1),
    ('Número de proyectos crediticios aprobados', 2),
    ('Número de proyectos crediticios inscritos al ICR', 3),
    ('Propuestas asociativas para estudios de riego presentadas', 4),
    ('Predios inscritos ante el ICA para planes fitosanitarios', 5),
    ('Planes de implementación de BPA formulados', 6),
    ('Productores capacitados en BPA', 7),
    ('Predios con BPA implementadas', 8),
    ('Áreas nuevas establecidas (has)', 9),
    ('Incremento productivo en toneladas', 10),
    ('Planes de negocio regionales por cadena productiva', 11),
    ('Planes de manejo fitosanitario ajustados y socializados', 12),
    ('Análisis de suelos realizados (planes de fertilización)', 13),
    ('Registros de predios ante el ICA planes de manejo fitosanitario', 14);