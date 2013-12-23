create table tipodocumento          (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table niveleducativo         (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table renglonproductivo      (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipoproductor          (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipocredito            (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipoinnovacion         (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipoconfianza          (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table claseorganizacion      (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipobeneficio          (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table fuenteinnovacion       (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tiporazonnopertenecer  (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table periodicidad           (id serial not null primary key, descripcion varchar(30) not null, dias integer not null);


create table departamento(
    id                  serial not null primary key,
    nombre              varchar(100)
);

create table municipio(
    id                  serial not null primary key,
    nombre              varchar(100),
    departamento_id     integer not null references departamento(id)
);



create table productor(
    id                     serial not null primary key,
    nombre1                varchar(30) not null,
    nombre2                varchar(30),
    apellido1              varchar(30) not null,
    apellido2              varchar(30),
    tipo_documento_id      integer not null references tipodocumento(id),
    numero_documento       varchar(20) not null,
    fecha_nacimiento       date,
    sexo                   char not null,
    nivel_educativo_id     integer references niveleducativo(id),
    tipo_productor_id      integer references tipoproductor(id),
    renglon_productivo_id  integer not null references renglonproductivo(id)
);



create table ubicacion(
    id                  serial not null primary key,
    productor_id        integer unique references productor(id),
    telefono            varchar(20),
    celular             varchar(20),
    email               varchar(60),
    departamento_id     integer references departamento(id),
    municipio_id        integer references municipio(id),
    vereda              varchar(50),
    direccion           varchar(100)
);



create table economia(
    id                     serial not null primary key,
    ingreso_familiar       integer,
    personas_dependientes  integer,
    ingreso_agropecuario   integer,
    credito_id             integer references tipocredito(id),
    otro_credito           varchar(30)
);


create table procesoinnovacion(
    id                  serial not null primary key,
    tipo_id             integer not null references tipoinnovacion(id),
    fuente_id           integer references tipoinnovacion(id),
    otra_fuente         varchar(30),
    descripcion         varchar(100)
);



create table personaasociada(
    id                  serial not null primary key,
    nombre              varchar(50) not null,
    apellido            varchar(50) not null,
    vereda              varchar(100),
    grado_confianza     integer references tipoconfianza(id)
);


create table ruat(
    id                  serial not null primary key,
    productor_id        integer unique references productor(id),

    asociado_id         integer references personaasociada(id),
    seguir_id           integer references personaasociada(id),

    creado              timestamp default current_timestamp,
    creador_id          integer references users(id),
    modificado          timestamp,
    modificador_id      integer references users(id)
);



create table orgasociada(
    id                  serial not null primary key,
    ruat_id             integer not null references ruat(id),
    nombre              varchar(100),
    periodicidad_id     integer references periodicidad(id),   
    directivo           boolean not null,
    participante        boolean not null     
);

create table orgasociada_clases(
    id                  serial not null primary key,
    orgasociada_id      integer not null references orgasociada(id),
    clase_id            integer not null references claseorganizacion(id),
                        unique(orgasociada_id, tipo_id)
);

create table orgasociada_beneficios(
    id                  serial not null primary key,
    orgasociada_id      integer not null references orgasociada(id),
    beneficio_id        integer not null references tipobeneficio(id),
                        unique(orgasociada_id, beneficio_id)
);

create table razones_nopertenecer(
    id                  serial not null

);