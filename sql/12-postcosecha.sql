create table postcosecha_pregunta(
    id              serial primary key not null,
    numero          integer not null,
    texto           text not null,
    tipo_respuesta  varchar(10) not null  -- 'UNICA' o 'MULTIPLE'
);

create table postcosecha_opcionrespuesta(
    id               serial primary key not null,
    pregunta_id      integer not null references postcosecha_pregunta(id),
    letra            char not null,
    texto            text not null
);

create table postcosecha(
    id               serial primary key not null,
    ruat_id          integer not null references ruat(id),
    creado           timestamp not null default current_timestamp,
    creador_id       integer not null references users(id),
    fecha_visita     date not null,
    observaciones    text not null default ''
);

create table postcosecha_respuesta(
    id               serial primary key not null,
    postcosecha_id   integer not null references postcosecha(id),
    pregunta_id      integer not null references postcosecha_pregunta(id),
    opcion_id        integer not null references postcosecha_opcionrespuesta(id),
    otro             text
);


insert into postcosecha_pregunta(numero, texto, tipo_respuesta) values
    (2, '1 y 2 : Criterios de Selección al Producto que Maneja en la Actualidad', 'MULTIPLE'),
    (4, '3 y 4 : Criterios de Clasificación del Producto Según la Calidad', 'MULTIPLE'),
    (6, '5 y 6 : Tipos de Limpieza al Aplicadas al Fruto', 'MULTIPLE'),
    (8, '7 y 8 : Tipos de Desinfectante Utilizado en la Fruta', 'MULTIPLE'),
    (10,'9 y 10 : Tipo de Secado del Fruto', 'MULTIPLE'),
    (11,'11 : Realiza Proceso de Encerado en Frutas', 'UNICA'),
    (12,'12 : Tipo de Recipiente Utiliado para el Almacenamiento del Producto', 'MULTIPLE'),
    (13,'13 : Cuenta con un Cuarto para el Almacenamiento del Producto', 'UNICA'),
    (14,'14 : En el Cuarto de Almacenamiento hace uso de Estibas para Apilar los Recipientes con Producto', 'UNICA'),
    (15,'15 : Con qué Frecuencia Realiza Procesos de Limpieza y Desinfección a las Estibas y Recipientes de Almacenamiento', 'UNICA'),
    (16,'16 : El lugar donde almacena la fruta fresca, actualmente cuenta con sistema de control de temperatura. ', 'UNICA'),
    (17,'17 : Tiempo de entrega del producto al comprador (Horas) ', 'MULTIPLE'),
    (19,'18 y 19 : Tipo de Presentación Comercial ', 'MULTIPLE');


insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (1, 'a', 'Daño Mecánico (golpes, magulladuras, perforaciones)'),
    (1, 'b', 'Daño Biológicos  (Insectos, Hongos, Bacterias)'),
    (1, 'c', 'Daño Químico (quemaduras, manchas)'),
    (1, 'd', 'NINGUNO');

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (2, 'a', 'Por Color (Madurez del Fruto)'),
    (2, 'b', 'Por tamaño del Fruto'),
    (2, 'c', 'Aspecto del Fruto'),
    (2, 'd', 'Forma del Fruto'),
    (2, 'e', 'Peso de Fruto'),
    (2, 'f', 'NINGUNO');

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (3, 'a', 'Agua'),
    (3, 'b', 'Agua y Jabón de barra'),
    (3, 'c', 'Agua y detergente'),
    (3, 'd', 'Detergente Biodegradable, grado alimenticio'),
    (3, 'e', 'NINGUNO');
    

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (4, 'a', 'Hipoclorito de sodio '),
    (4, 'b', 'Bactericidas'),
    (4, 'c', 'Fungicidas'),
    (4, 'd', 'Biológico'),
    (4, 'e', 'Extractos Vegetales'),
    (4, 'f', 'NINGUNO');

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (5, 'a', 'Secado Aire Caliente '),
    (5, 'b', 'Secado Manual'),
    (5, 'c', 'NINGUNO');

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (6, 'a', 'Sí'),
    (6, 'b', 'No');

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (7, 'a', 'Canastillas plásticas'),
    (7, 'b', 'Tarros plásticos'),
    (7, 'c', 'Cajas de Madera'),
    (7, 'd', 'Costales'),
    (7, 'e', 'NINGUNO');

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (8, 'a', 'Sí'),
    (8, 'b', 'No');

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (9, 'a', 'Sí'),
    (9, 'b', 'No');

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (10, 'a', 'Diario'),
    (10, 'b', 'Semanal'),
    (10, 'c', 'Quincenal'),
    (10, 'd', 'Mensual'),
    (10, 'e', 'Nunca');
    
insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (11, 'a', 'Sí'),
    (11, 'b', 'No');

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (12, 'a', 'Horas');

insert into postcosecha_opcionrespuesta(pregunta_id, letra, texto) values
    (13, 'a', 'Mallalon'),
    (13, 'b', 'Papel encerado'),
    (13, 'c', 'Bolsa plástica'),
    (13, 'd', 'Bandeja de Icopor'),
    (13, 'e', 'Cajas de Cartón'),
    (13, 'f', 'Malla plástica'),
    (13, 'g', 'NINGUNO');


drop view listadoruats;

create or replace view listadoruats as
select R.id, R.numero_formulario,
    concat(P.nombre1,' ',nullif(P.nombre2||' ',' '), P.apellido1,' ',P.apellido2) as nombre_productor,
    P.numero_documento,
    R.creado,
    concat(U.first_name,' ',U.last_name) as ingresado_por,
    CO.id as cosecha_id,
    BPA.id as bpa_id,
    VTP.id as VTP_id,
    OB.ruta_formulario,
    POS.id  as postcosecha_id
from ruat R inner join productor P on R.productor_id = P.id
    inner join users U on R.creador_id = U.id
    left join cosecha CO on R.id = CO.ruat_id
    left join bpa BPA on R.id = BPA.ruat_id and BPA.nro_visita=0
    left join visita_tipo_productor VTP on R.id = VTP.ruat_id
    left join observacion OB on R.id = OB.ruat_id
    left join postcosecha POS on R.id = POS.ruat_id
