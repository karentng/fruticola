create table tipoactividadvisita    (id serial not null primary key, descripcion varchar(150) not null, orden integer not null);

insert into tipoactividadvisita(descripcion, orden) values
    ('Socializar y caracterizar a los productores y a las unidades productivas beneficiarias del proyecto (RUAT)', 1),
    ('Diseñar los planes de implementación de BPA.', 2),
    ('Implementar gradualmente BPA Prediales', 3),
    ('Ajustar a las necesidades locales los planes de manejo fitosanitario de cada uno de los cultivos', 4),
    ('Disminuir las pérdidas de fruta por practicas inadecuadas de cosecha en cada uno de los renglones productivos del proyecto', 5),
    ('Disminuir las pérdidas de fruta por practicas inadecuadas de poscosecha en cada uno de los renglonnes productivos del proyecto', 6),
    ('Elaborar planes de negocio regionales por renglón productivo', 7),
    ('Incrementar los rendimientos de cada uno de los renglones productivos del proyecto',8),
    ('Determinar la disponibilidad de nutrientes del suelo en los huertos', 9),
    ('Gestionar la consecución de recursos a través de líneas de crédito agropecuario (FINAGRO) a beneficiarios del proyecto', 10),
    ('Elaborar propuestas asociativas para estudios de riego', 11),
    ('Asistir técnicamente a los productores en implementación de prácticas de manejo agronómico tendientes a romper la estacionalidad de la cosecha', 12),
    ('Renovar cultivos existentes y plantación de áreas nuevas con variedades promisorias', 13),
    ('Mejorar las áreas actuales con semilla de PIÑA de alta calidad genética y productiva', 14),
    ('Implementar modelos de producción óptima de MANGO con semilla de alta calidad', 15),
    ('Mejorar las áreas actuales con semilla FRESA de alta calidad genética y productiva', 16),
    ('Mejorar las áreas actuales con semilla de MORA de alta calidad genética y productiva', 17),
    ('Totales', 18);