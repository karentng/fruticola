create table meta_complementaria(
    id              serial primary key not null,
    fila            serial not null,
    total           integer not null,
    porcentaje      float not null
);

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
    valor           integer not null
);

insert into meta_complementaria_pregunta(meta, concepto, orden) values
    (2176, 'Número de créditos planificados', 1),
    (1088, 'Número de proyectos crediticios aprobados', 2),
    (544, 'Número de proyectos crediticios inscritos al ICR', 3),
    (10, 'Propuestas asociativas para estudios de riego presentadas', 4),
    (2176, 'Predios inscritos ante el ICA para planes fitosanitarios', 5),
    (4353, 'Planes de implementación de BPA formulados', 6),
    (4353, 'Productores capacitados en BPA', 7),
    (544, 'Predios con BPA implementadas', 8),
    (610, 'Áreas nuevas establecidas (has)', 9),
    (54033, 'Incremento productivo en toneladas', 10),
    (13, 'Planes de negocio regionales por cadena productiva', 11),
    (13, 'Planes de manejo fitosanitario ajustados y socializados', 12),
    (3459, 'Análisis de suelos realizados (planes de fertilización)', 13),
    (2176, 'Registros de predios ante el ICA planes de manejo fitosanitario', 14);