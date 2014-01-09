
create table bpa(
    id serial not null primary key,
    id_contacto integer not null references contacto(id),
    conclusion text,
    nivel_bpa double precision
);

create table tipopregunta_bpa     (id serial not null primary key, descripcion text not null, seccion varchar(1) not null, orden integer not null);
create table bpa_respuesta        (id serial not null primary key, bpa_id integer not null references bpa(id), pregunta_id integer not null references tipopregunta_bpa(id), observacion text not null);
