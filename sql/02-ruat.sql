create table renglonproductivo(
    id                  serial not null primary key,
    nombre              varchar(30) not null
);



create table productor(
    id                  serial not null primary key,
    nombre1             varchar(30) not null,
    nombre2             varchar(30),
    apellido1           varchar(30) not null,
    apellido2           varchar(30),
    tipo_documento      varchar(10) not null,
    numero_documento    varchar(20) not null,
    fecha_nacimiento    date,
    sexo                char not null,
    nivel_educativo     varchar(20),
    tipo_productor      smallint,  -- 1=pequeno, 2=mediano
    renglon_productivo  integer not null references renglonproductivo(id)
);

create table departamento(
    id                  serial not null primary key,
    nombre              varchar(100)
);

create table municipio(
    id                  serial not null primary key,
    nombre              varchar(100),
    departamento_id     integer not null references departamento(id)
);

create table ubicacion(
    id                  serial not null primary key,
    productor_id        integer references productor(id),
    telefono            varchar(20),
    celular             varchar(20),
    email               varchar(60),
    departamento_id     integer references departamento(id),
    municipio_id        integer references municipio(id),
    vereda              varchar(50),
    direccion           varchar(100)
);

create table ruat(
    id                  serial not null primary key,
    productor_id        integer unique references productor(id),

    creado              timestamp default current_timestamp,
    creador             integer references users(id),
    modificado          timestamp,
    modificador         integer references users(id)
);

create table tipocredito(
    id                  serial not null primary key,
    nombre              varchar(20) not null
);

create table economia(
    id                     serial not null primary key,
    ingreso_familiar       integer,
    personas_dependientes  integer,
    ingreso_agropecuario   integer,
    credito                integer references tipocredito(id),
    otro_credito           varchar(30)
);

create table fuenteinnovacion(
    id                  serial not null primary key,
    nombre              varchar(20) not null
);

create table tipoinnovacion(
    id                  serial not null primary key,
    nombre              varchar(20) not null
);

create table procesoinnovacion(
    id                  serial not null primary key,
    tipo_id             integer not null references tipoinnovacion(id),
    fuente_id           integer references tipoinnovacion(id),
    otra_fuente         varchar(30),
    descripcion         varchar(100)
);

