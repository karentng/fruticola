
DROP TABLE IF EXISTS conyugue;
DROP TABLE IF EXISTS referencias_fam_per;
DROP TABLE IF EXISTS referencias_fin;
DROP TABLE IF EXISTS referencias_com;
DROP TABLE IF EXISTS descripcion_inv;
DROP TABLE IF EXISTS informacion_pre;
DROP TABLE IF EXISTS ingresos_adicionales;
DROP TABLE IF EXISTS descripcion_bienes;
DROP TABLE IF EXISTS descripcion_bienes_inmuebles;
DROP TABLE IF EXISTS solicitud_credito;
DROP TABLE IF EXISTS lista_chequeo;

create table solicitud_credito(
    id                              serial not null primary key,
    ruat_id                         integer references ruat(id),
    fecha                           timestamp,
    cod_beneficiario                varchar(30),
    nombre_oficina                  varchar(50),
    departamento_id                 integer not null references departamento(id),
    municipio_id                    integer not null references municipio(id),
    tipo_productor                  integer not null references tipoproductor(id),
    experiencia                     integer not null,
    calidad_de                      integer not null,
    rubros_fin_icr                  varchar(50),
    rubros_fin_dre                  varchar(50),
    descripcion_inv                 text,

    actividades_agro                text,
    otro_ingresos                   text,
    comercializacion                text,
    entidades_acomp                 text,

    forma_llegar_pred               text,
    tiempo_permanencia              double precision,
    experiencia_act                 double precision,
    responsable                     integer not null references users(id),
    observaciones                   text
);

create table conyugue(
    id                              serial not null primary key,
    solicitud_id                    integer references solicitud_credito(id),
    nombre1                         varchar(30),
    nombre2                         varchar(30),
    apellido1                       varchar(30),
    apellido2                       varchar(30),
    tipo_documento                  varchar(30),
    identificacion                  varchar(30),
    fecha_nacimiento                timestamp,
    telefono                        varchar(20),
    personas_cargo                  integer
);

create table referencias_fam_per(
    id                              serial not null primary key,
    tipo                            integer not null,-- 1:familiares; 2:personales
    solicitud_id                    integer references solicitud_credito(id),
    nombres			                varchar(30),
    apellido1                       varchar(30),
    apellido2                       varchar(30),
    parentesco                      varchar(30),
    direccion                       varchar(100),
    departamento_id                 integer not null references departamento(id),
    municipio_id                    integer not null references municipio(id),
    barrio                          varchar(100),
    indicativo1                     varchar(5),
    telefono1                       varchar(20),
    indicativo2                     varchar(5),
    telefono2                       varchar(20),
    indicativo3                     varchar(5),
    telefono3                       varchar(20)
);

create table referencias_fin(
    id                              serial not null primary key,
    solicitud_id                    integer references solicitud_credito(id),
    entidad                         varchar(30),
    clase                           varchar(30),
    nro_producto                    varchar(30),
    sucursal                        varchar(100),
    departamento_id                 integer not null references departamento(id),
    municipio_id                    integer not null references municipio(id)
);


create table referencias_com(
    id                              serial not null primary key,
    solicitud_id                    integer references solicitud_credito(id),
    nombre_est                      varchar(100),
    tipo_vinculo                    varchar(100),
    departamento_id                 integer not null references departamento(id),
    municipio_id                    integer not null references municipio(id),
    telefono                        varchar(20)
);


create table descripcion_inv(
    id                              serial not null primary key,
    solicitud_id                    integer references solicitud_credito(id),
    codigo_finagro                  varchar(20),
    destino_recursos_nombre_rubro   varchar(20),
    unidades_fin                    double precision,
    valor_proyecto                  double precision,
    valor_solicitud                 double precision,
    plazo_total                     integer,
    periodo_gracia                  integer,
    modalidad_pago                  integer,
    amortizacion_cap                integer,
    tasa_interes                    double precision
);


create table informacion_pre(
    id                              serial not null primary key,
    solicitud_id                    integer references solicitud_credito(id),    
    fuente_hid                      integer,
    fecha_ini                       timestamp,
    fecha_fin                       timestamp
);


create table ingresos_adicionales(
    id                              serial not null primary key,
    solicitud_id                    integer references solicitud_credito(id),
    actividad                       varchar(50),
    cantidad                        double precision,
    produccion                      varchar(50),
    precio_venta                    double precision,
    total_ingresos                  double precision,
    area_pre_inv                    double precision,
    tipo_pre_inv                    varchar(50),
    edad_meses                      double precision,
    nombre_predio                   varchar(50)
);

create table descripcion_bienes(
    id                              serial not null primary key,
    solicitud_id                    integer references solicitud_credito(id),

    marca                           varchar(50),
    placa                           varchar(50),
    modelo                          varchar(50),
    prenda_favor                    varchar(100),
    valor_deuda                     double precision,
    valor_comercial                 double precision,

    otros_bienes1                   varchar(50),
    otros_bienes2                   varchar(50),
    otros_bienes3                   varchar(50),
    otros_bienes4                   varchar(50),
    otros_cantidad1                 double precision,
    otros_cantidad2                 double precision,
    otros_cantidad3                 double precision,
    otros_cantidad4                 double precision,
    otros_valor1                    double precision,
    otros_valor2                    double precision,
    otros_valor3                    double precision,
    otros_valor4                    double precision
);

create table descripcion_bienes_inmuebles(
    id                              serial not null primary key,
    solicitud_id                    integer references solicitud_credito(id),

    tipo_inmueble                   integer,
    departamento_id                 integer not null references departamento(id),
    municipio_id                    integer not null references municipio(id),
    vereda                          varchar(100),
    direccion                       varchar(100),
    valor_comercial                 double precision
);

create table lista_chequeo(
    id                              serial not null primary key,
    solicitud_id                    integer references solicitud_credito(id),

    item1                           boolean,
    item2                           boolean,
    item3                           boolean,
    item4                           boolean,
    item5                           boolean,
    item6                           boolean,
    item7                           boolean,
    item8                           boolean,
    item9                           boolean,
    item10                          boolean
);