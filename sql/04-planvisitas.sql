create table tipoactividadvisita    (id serial not null primary key, descripcion text not null, orden integer not null, categoria integer not null);
create table respuestactividadvisita    (id serial not null primary key, idtipoactividad integer not null references tipoactividadvisita(id), columna1 float, columna2 float, columna3 float, columna4 float, columna5 float, columna6 float, columna7 float, columna8 float, columna9 float, columna10 float);

insert into tipoactividadvisita(descripcion, orden, categoria) values
    ('Socializar y caracterizar a los productores y a las unidades productivas beneficiarias del proyecto (RUAT)', 1, 1),
    ('Diseñar los planes de implementación de BPA.', 2, 1),
    ('Implementar gradualmente BPA Prediales', 3, 1),
    ('Ajustar a las necesidades locales los planes de manejo fitosanitario de cada uno de los cultivos', 4, 1),
    ('Disminuir las pérdidas de fruta por practicas inadecuadas de cosecha en cada uno de los renglones productivos del proyecto', 5, 1),
    ('Disminuir las pérdidas de fruta por practicas inadecuadas de poscosecha en cada uno de los renglonnes productivos del proyecto', 6, 1),
    ('Elaborar planes de negocio regionales por renglón productivo', 7, 1),
    ('Incrementar los rendimientos de cada uno de los renglones productivos del proyecto',8, 1),
    ('Determinar la disponibilidad de nutrientes del suelo en los huertos', 9, 1),
    ('Gestionar la consecución de recursos a través de líneas de crédito agropecuario (FINAGRO) a beneficiarios del proyecto', 10, 1),
    ('Elaborar propuestas asociativas para estudios de riego', 11, 1),
    ('Asistir técnicamente a los productores en implementación de prácticas de manejo agronómico tendientes a romper la estacionalidad de la cosecha', 12, 1),
    ('Renovar cultivos existentes y plantación de áreas nuevas con variedades promisorias', 13, 1),
    ('Mejorar las áreas actuales con semilla de PIÑA de alta calidad genética y productiva', 14, 1),
    ('Implementar modelos de producción óptima de MANGO con semilla de alta calidad', 15, 1),
    ('Mejorar las áreas actuales con semilla FRESA de alta calidad genética y productiva', 16, 1),
    ('Mejorar las áreas actuales con semilla de MORA de alta calidad genética y productiva', 17, 1),
  
    ('Socializar y caracterizar a los productores y a las unidades productivas beneficiarias del proyecto (RUAT)', 1, 2),
    ('Diseñar los planes de implementación de BPA', 2, 2),
    ('Implementar gradualmente BPA Prediales', 3, 2),
    ('Ajustar a las necesidades locales los planes de manejo fitosanitario de cada uno de los cultivos', 4, 2),
    ('Disminuir las pérdidas de fruta por practicas inadecuadas de cosecha en cada uno de los renglones productivos del proyecto', 5, 2),
    ('Disminuir las pérdidas de fruta por practicas inadecuadas de poscosecha en cada uno de los renglonnes productivos del proyecto', 6, 2),
    ('Elaborar planes de negocio regionales por renglón productivo', 7, 2),
    ('Incrementar los rendimientos de cada uno de los renglones productivos del proyecto', 8, 2),
    ('Determinar la disponibilidad de nutrientes del suelo en los huertos', 9, 2),
    ('Gestionar la consecución de recursos a través de líneas de crédito agropecuario (FINAGRO) a beneficiarios del proyecto', 10, 2),
    ('Elaborar propuestas asociativas para estudios de riego', 11, 2),
    ('Asistir técnicamente a los productores en implementación de prácticas de manejo agronómico tendientes a romper la estacionalidad de la cosecha', 12, 2),
    ('Renovar cultivos existentes y plantación de áreas nuevas con variedades promisorias', 13, 2),
    ('Mejorar las áreas actuales con semilla de PIÑA de alta calidad genética y productiva', 14, 2),
    ('Implementar modelos de producción óptima de MANGO con semilla de alta calidad', 15, 2),
    ('Mejorar las áreas actuales con semilla FRESA de alta calidad genética y productiva', 16, 2),
    ('Mejorar las áreas actuales con semilla de MORA de alta calidad genética y productiva', 17, 2),

    ('Talleres de capacitación a los beneficiarios en implementación de BPA', 1, 3),
    ('Demostraciones de Método para la implementación de planes de manejo fitosanitario', 2, 3),
    ('Demostraciones de método sobre practicas adecuadas de cosecha', 3, 3),
    ('Demostraciones de método sobre practicas adecuadas de poscosecha', 4, 3),
    ('Giras técnicas para el mejoramiento de los rendimientos productivos', 5, 3);

INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (16, 16, 250, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (17, 17, 600, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (18, 18, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (19, 19, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (39, 39, 54, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (20, 20, 3265, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (21, 21, 10882, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (22, 22, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (23, 23, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (24, 24, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (25, 25, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (1, 1, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (2, 2, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (3, 3, 3265, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (4, 4, 10882, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (5, 5, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (6, 6, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (7, 7, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (8, 8, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (9, 9, 10882, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (10, 10, 10882, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (11, 11, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (12, 12, 10882, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (13, 13, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (14, 14, 891, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (15, 15, 200, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (26, 26, 10882, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (27, 27, 10882, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (28, 28, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (29, 29, 10882, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (30, 30, 5441, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (31, 31, 891, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (32, 32, 200, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (33, 33, 250, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (34, 34, 600, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (35, 35, 109, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (36, 36, 109, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (37, 37, 109, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO respuestactividadvisita (id, idtipoactividad, columna1, columna2, columna3, columna4, columna5, columna6, columna7, columna8, columna9, columna10) VALUES (38, 38, 109, 0, 0, 0, 0, 0, 0, 0, 0, 0);
