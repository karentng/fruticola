insert into tipodocumento(descripcion, orden) VALUES
    ('C.C', 1),
    ('T.I', 2),
    ('C.E', 3),
    ('NIT', 4);

insert into niveleducativo(descripcion, orden) VALUES
    ('Primaria', 1),
    ('Secundaria', 2),
    ('Técnica', 3),
    ('Tecnológica', 4),
    ('Universitaria', 5),
    ('Ninguna', 100);

insert into claseorganizacion(descripcion, orden) values
    ('Agropecuaria',1),
    ('Asistencial',2),
    ('Comercial',3),
    ('Cultural',4),
    ('Deportiva',5),
    ('Educativa',6),
    ('Etnica',7),
    ('Político',8),
    ('Salud',9),
    ('Social',10),
    ('Tecnológico',11);


insert into tipoproductor(descripcion,orden) values 
    ('Pequeño',1),
    ('Mediano',2);

insert into renglonproductivo(descripcion,orden) values
    ('Aguacate',1),
    ('Bananito',2),
    ('Cítricos',3),
    ('Chontaduro',4),
    ('Fresa',5),
    ('Lulo',6),
    ('Mango',7)
    ('Maracuya',8),
    ('Melon',9),
    ('Mora',10),
    ('Papaya',11),
    ('Piña',12),
    ('Uva',13);

insert into tipobeneficio(descripcion, orden) values
    ('Capacitación', 1),
    ('Económico', 2),
    ('En Especie',3),
    ('Participación en la Toma de Decisiones', 4),
    ('Reconocimiento de la Comunidad',5),
    ('Recreación y Deporte', 6),
    ('Otro', 7);


insert into tipocredito(descripcion,orden) values
    ('Bancos',1),
    ('Agremiaciones',2),
    ('Entidades Estatales',3),
    ('Prestamistas',4),
    ('Familiares',5),
    ('Empresas de Insumos',6),
    ('Otro',100);


insert into periodicidad(descripcion, dias) values
    ('Semanal',7),
    ('Quincenal',15),
    ('Mensual',30),
    ('Bimestral',60),
    ('Trimestral',90),
    ('Semestral',180),
    ('Anual',365);


insert into tipoconfianza(descripcion, orden) values
    ('Siempre',1),
    ('Casi Siempre',2),
    ('A Veces',3),
    ('Casi Nunca',4),
    ('Nunca',5);