/*DROP TABLE tp_b_respuesta;
DROP TABLE tp_c_respuesta;
DROP TABLE tp_d_respuesta;
DROP TABLE tp_c_pregunta;
DROP TABLE visita_tipo_productor;*/

CREATE TABLE visita_tipo_productor (
	id serial NOT NULL PRIMARY KEY,
	ruat_id integer REFERENCES ruat(id),
	fecha date NOT NULL,
	observaciones text,
        credito_agricola bit,
	archivo_fisico text,
        creado timestamp NOT NULL default current_timestamp,
	creador_id integer NOT NULL references users(id)
);

CREATE TABLE tp_b_respuesta(
	id serial NOT NULL PRIMARY KEY,
	visita_id  integer NOT NULL REFERENCES visita_tipo_productor(id),
    valor_uaf double precision NOT NULL,
    tipo_productor_uaf int NOT NULL,
    clasificacion_productor_uaf int NOT NULL
);

CREATE TABLE tp_c_pregunta(
	id serial NOT NULL PRIMARY KEY,
	ordenamiento INTEGER NOT NULL,
	enunciado text NOT NULL,    
	categoria char(1) NOT NULL,
	editable boolean NOT NULL
);

CREATE TABLE tp_c_respuesta(
	id serial NOT NULL PRIMARY KEY,
	visita_id  integer NOT NULL REFERENCES visita_tipo_productor(id),
    pregunta_c_id  integer NOT NULL REFERENCES tp_c_pregunta(id),
    valor double precision NOT NULL
);

CREATE TABLE tp_d_respuesta(
	id serial NOT NULL PRIMARY KEY,
	visita_id  integer NOT NULL REFERENCES visita_tipo_productor(id),
    criterio1 int NOT NULL,
    criterio2 int NOT NULL,
    criterio3 int NOT NULL,
    criterio4 int NOT NULL
);

INSERT INTO tp_c_pregunta(ordenamiento, enunciado, categoria, editable) VALUES
	(1, 'Dinero en Caja y/o Bancos', 'A', true),
    (2, 'Cuentas x cobrar (deudores)', 'A', true),
    (3, 'Inventario en mercancías y/o insumos', 'A', true),
    (4, 'Propiedades urbanos', 'A', true),
    (5, 'Propiedades rurales', 'A', true),
    (6, 'Inversiones en Cultivos', 'A', true),
    (7, 'Inversiones en Semovientes (Animales)', 'A', true),
    (8, 'Inversiones en Maquinaria', 'A', true),
    (9, 'Inversiones en Equipos y Herramientas', 'A', true),
    (10, 'Inversiones en Vehículos', 'A', true),
    (11, 'Muebles y enseres', 'A', true),
    (12, 'Otros Activos', 'A', true),
    (13, 'TOTAL ACTIVOS ($)', 'A', false),
    
	(1, 'Cantidad de producto vendido (kg/Año) del renglón productivo', 'B', true),
	(2, 'Precio de venta promedio en el año anterior ($/Kg)', 'B', true),
	(3, 'INGRESOS RENGLON PRODUCTIVO ($/Año)', 'B', true),
	(4, 'INGRESOS OTRAS ACTIVIDADES AGROPECUARIAS ($/Año)', 'B', true),
	(5, 'OTROS INGRESOS ($/Año)', 'B', true),
	(6, 'TOTAL INGRESOS DEL PRODUCTOR ($/Año)', 'B', false),
    
	(1, 'Costos de producción del renglón productivo ($/Año)', 'C', true),
    (2, 'Préstamos personales y/o créditos con bancos ($/Año)', 'C', true),
    (3, 'Servicios públicos y arriendos ($/Año)', 'C', true),
    (4, 'Gastos familiares ($/Año)', 'C', true),
    (5, 'Otros egresos ($/Año)', 'C', true),
    (6, 'TOTAL EGRESOS ($/Año)', 'C', false),

    (1, 'UTILIDAD ANUAL del productor según sus ingresos y egresos ( $/Año )', 'D', false),
    (2, 'RELACION BENEFICIO - COSTO del productor según sus ingresos y egresos es', 'D', false);

