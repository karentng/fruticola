create table plannegocio(
    id              serial primary key not null,
    ruat_id          integer not null references ruat(id),
    creado           timestamp not null default current_timestamp,
    creador_id       integer not null references users(id),
    negocio_anios    integer not null default 0,
    negocio_meses    integer not null default 0,
    cultivo_anios    integer not null default 0,
    cultivo_meses    integer not null default 0,
    fecha_siembra    date,
    area_cultivo     double precision,
    trabajadores     integer not null,

    fecha_visita     date not null,
    observaciones    text not null default ''
);

create table plannegocio_pregunta(
    id              serial primary key not null,
    numero          integer not null,
    texto           text not null,
    tipo_respuesta  varchar(10) not null  -- 'UNICA' o 'MULTIPLE'
);

create table plannegocio_opcionrespuesta(
    id               serial primary key not null,
    pregunta_id      integer not null references plannegocio_pregunta(id),
    letra            char not null,
    texto            text not null
);

create table plannegocio_respuesta(
    id               serial primary key not null,
    plannegocio_id   integer not null references cosecha(id),
    pregunta_id      integer not null references plannegocio_pregunta(id),
    opcion_id        integer not null references plannegocio_opcionrespuesta(id),
    otro             text
);

insert into plannegocio_pregunta(numero, texto, tipo_respuesta) values
    (4, 'Empezó a producir este frutal influido por:','UNICA'),
    (5, 'Escogió la siembra de ese frutal por:','UNICA'),
    (6, 'Piensa continuar con la siembra de ese cultivo?','UNICA'),
    (7, 'Durante cuántos ciclos más piensa continuar con el mismo cultivo','UNICA'),
    (8, 'Cuál cree que es la mejor opción de negocio para la producción que obtiene?','UNICA'),
    (9, 'Cuál cree usted que sería un factor clave para hacer más rentable su negocio?','UNICA'),
    (10,'Cuál considera usted que es el mejor canal para comercializar su producto','UNICA'),
    (11,'Cuando comercializa su producto que tipo de necesidad cree que satisface en quienes lo consumen?','UNICA'),
    (12,'Por qué cree usted que le compran su producto','UNICA'),
    (13,'Considera que el precio que recibe por su producto es','UNICA'),
    (14,'Cuál de los siguientes factores externos considera que afecta más su negocio','UNICA'),
    (15,'Cuál de los siguientes factores internos considera que afecta más su negocio','UNICA');



insert into plannegocio_opcionrespuesta(pregunta_id, letra, texto) values
    (1, 'a', 'Herencia'),
    (1, 'b', 'Recomendación de amigo o familiar'),
    (1, 'c', 'Algún programa de asistencia o beneficios'),
    (1, 'd', 'Iniciativa propia'),

    (2, 'a', 'Bajos costos de producción e inversión'),
    (2, 'b', 'Conocimiento y experiencia en este'),
    (2, 'c', 'Buenas posibilidades de venta'),
    (2, 'd', 'Aprovechar condiciones de la finca o zona'),

    (3, 'a', 'Si'),
    (3, 'b', 'No'),
    
    (4, 'a', '1'),
    (4, 'b', '2'),
    (4, 'c', '3'),
    (4, 'd', '4'),
    (4, 'e', '5'),
    (4, 'f', '6'),
    (4, 'g', '7'),
    (4, 'h', '8'),
    (4, 'i', '9'),
    (4, 'j', '10'),

    (5, 'a', 'Venta en la finca sin proceso'),
    (5, 'b', 'Venta en la finca con proceso de postcosecha'),
    (5, 'c', 'Fruta procesada'),

    (6, 'a', 'Obtener mayor productividad'),
    (6, 'b', 'Obtener mayor calidad de la fruta'),
    (6, 'c', 'Realizar transformación al producto'),
    (6, 'd', 'Organizarse con otros productores'),
    (6, 'e', 'Obtener certificación en BPA'),
    (6, 'f', 'Obtener financiación'),

    (7, 'a', 'Venta en la finca'),
    (7, 'b', 'Venta a almacenes de cadena'),
    (7, 'c', 'Venta a mayorista de plaza de mercado'),
    (7, 'd', 'Venta a plantas procesadoras'),
    (7, 'e', 'Mercado de exportación'),

    (8, 'a', 'Salud'),
    (8, 'b', 'Alimentación'),
    (8, 'c', 'Calidad de vida'),
    (8, 'd', 'Bienestar'),

    (9, 'a', 'Por calidad del mismo'),
    (9, 'b', 'Por su tamaño'),
    (9, 'c', 'Apariencia'),
    (9, 'd', 'Precio'),
    (9, 'e', 'Constancia en la producción'),
    (9, 'f', 'Por afinidad, familiaridad o amistad'),

    (10, 'a', 'Superior al de los otros productores'),
    (10, 'b', 'Igual al de otros productores'),
    (10, 'c', 'Inferior al de otros productores'),
    (10, 'd', 'Desconoce el precio de otros productores'),

    (11, 'a', 'El clima'),
    (11, 'b', 'La oferta y la demanda'),
    (11, 'c', 'La economía'),
    (11, 'd', 'Cambios en los gustos de los consumidores'),
    (11, 'e', 'Problemas de manejo fitosanitario en la zona'),
    (11, 'f', 'Intermediación en la venta de la fruta'),

    (12, 'a', 'Ausencia de recursos'),
    (12, 'b', 'Falta de asesoría sobre el proceso productivo'),
    (12, 'c', 'Altos costos de producción'),
    (12, 'd', 'Material vegetal de baja calidad'),
    (12, 'e', 'Condiciones inadecuadas del predio');

