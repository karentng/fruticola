CREATE TABLE certificacion_visita(
	id serial NOT NULL PRIMARY KEY,
	ruat_id integer REFERENCES ruat(id),
	num_formulario integer NOT NULL,
	fecha date,
	descripcion text,
	observaciones text
);