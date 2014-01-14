/*
DROP TABLE clasificacion_visita_respuesta;
DROP TABLE clasificacion_visita_pregunta;
DROP TABLE cetificacion_visita;
*/

CREATE TABLE cetificacion_visita (
	id serial NOT NULL PRIMARY KEY,
	ruat_id integer REFERENCES ruat(id),
	fecha date NOT NULL,
	obervaciones text,
	actividades_realizadas text,
    funcionario_responsable int not null,
    tipo_responsable int,
	archivo_fisico text
);

CREATE TABLE clasificacion_visita_pregunta(
	id serial NOT NULL PRIMARY KEY,
	ordenamiento INTEGER NOT NULL,
	enunciado text NOT NULL
);

CREATE TABLE clasificacion_visita_respuesta(
	id serial NOT NULL PRIMARY KEY,
	cetificacion_id  integer NOT NULL REFERENCES cetificacion_visita(id),
    pregunta_clasificacion_visita_id  integer NOT NULL REFERENCES clasificacion_visita_pregunta(id),
    valor boolean,
    CONSTRAINT clasificacion_visita_respuesta_constraint UNIQUE (id, pregunta_clasificacion_visita_id)
);


INSERT INTO clasificacion_visita_pregunta(ordenamiento, enunciado) VALUES
	(1, '01. Socialización y caracterización del productor'),
    (2, '02. Diseño del plan de implementación de BPA.'),
    (3, '03. Implementación gradual de las Buenas Prácticas Agrícolas (BPA) en el predio.'),
    (4, '04. Implementación del plan de manejo fitosanitario del renglón productivo.'),
    (5, '05. Disminución de pérdidas de fruta por practicas inadecuadas de cosecha.'),
    (6, '06. Disminución de pérdidas de fruta por practicas inadecuadas de pos-cosecha.'),
    (7, '07. Elaboración de los planes de negocio regional.'),
    (8, '08. Incremento de los rendimientos del cultivo.'),
    (9, '09. Determinación de la disponibilidad de nutrientes del suelo.'),
    (10, '10. Gestión para la consecución de recursos a través de líneas de crédito agropecuario (FINAGRO).'),
    (11, '11. Elaboración de propuestas asociativas para estudios de riego.'),
    (12, '12. Implementación de prácticas de manejo agronómico tendientes a romper la estacionalidad.'),
    (13, '13. Renovación de cultivos existentes y plantación de áreas nuevas con variedades promisorias'),
    (14, '14. Mejoramiento de las áreas actuales con semilla de PIÑA de alta calidad genética y productiva'),
    (15, '15. Implementación de modelos de producción óptima de MANGO'),
    (16, '16. Mejoramiento de las áreas actuales con semilla FRESA de alta calidad genética y productiva'),
    (17, '17. Mejoramiento de las áreas actuales con semilla de MORA de reconocida calidad productiva'),
    (18, '18. VISITAS DE SEGUIMIENTO Y/O ACOMPAÑAMIENTO');
