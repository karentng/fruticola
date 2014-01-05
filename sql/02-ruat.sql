create table tipodocumento          (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table niveleducativo         (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table renglonproductivo      (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipoproductor          (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipocredito            (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipoinnovacion         (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipoconfianza          (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table claseorganizacion      (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipobeneficio          (id serial not null primary key, descripcion varchar(50) not null, orden integer not null);
create table fuenteinnovacion       (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tiporazonnopertenecer  (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table periodicidad           (id serial not null primary key, descripcion varchar(30) not null, dias integer not null);

create table tenencia               (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tiposerviciopublico    (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipovia                (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipoestadovia          (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipomediotransporte    (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipomaquinaria         (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tiposemilla            (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipositioventa         (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipovende              (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);
create table tipoformapago          (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);

create table tipopregunta           (id serial not null primary key, descripcion text not null, orden integer not null);
create table tiporespuesta          (id serial not null primary key, descripcion varchar(30) not null, orden integer not null);

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
    renglon_productivo_id  integer not null references renglonproductivo(id),
    adjunto_cedula         text 
);



create table contacto(
    id                  serial not null primary key,
    productor_id        integer not null unique references productor(id),
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
    productor_id           integer not null unique references productor(id),
    ingreso_familiar       integer,
    personas_dependientes  integer,
    ingreso_agropecuario   integer,
    credito_id             integer references tipocredito(id),
    otro_credito           varchar(30)
);


create table procesoinnovacion(
    id                  serial not null primary key,
    productor_id        integer references productor(id),
    tipo_id             integer not null references tipoinnovacion(id),
    fuente_id           integer references fuenteinnovacion(id),
    otra_fuente         varchar(30),
    descripcion         varchar(100)
);



create table personaasociada(
    id                  serial not null primary key,
    nombre              varchar(50) not null,
    apellido            varchar(50) not null,
    vereda              varchar(100),
    confianza_id        integer references tipoconfianza(id)
);


create table ruat(
    id                  serial not null primary key,
    productor_id        integer not null unique references productor(id),

    asociado_id         integer references personaasociada(id),
    seguir_id           integer references personaasociada(id),

    creado              timestamp default current_timestamp,
    creador_id          integer references users(id),
    modificado          timestamp,
    modificador_id      integer references users(id)
);



create table orgasociada(
    id                  serial not null primary key,
    ruat_id             integer references ruat(id),
    nombre              varchar(100),
    periodicidad_id     integer references periodicidad(id),   
    membresia           varchar(20)
);

create table orgasociada_clase(
    id                  serial not null primary key,
    orgasociada_id      integer not null references orgasociada(id) on delete cascade,
    clase_id            integer not null references claseorganizacion(id),
                        unique(orgasociada_id, clase_id)
);

create table orgasociada_beneficio(
    id                  serial not null primary key,
    orgasociada_id      integer not null references orgasociada(id) on delete cascade,
    beneficio_id        integer not null references tipobeneficio(id),
                        unique(orgasociada_id, beneficio_id)
);

create table razonnopertenecer(
    id                  serial not null primary key,
    ruat_id             integer references productor(id),
    razon_id            integer not null references tiporazonnopertenecer(id)
);

create table finca(
    id                  serial not null primary key,
    ruat_id             integer not null references ruat(id),
    nombre              varchar(50) not null,
    identif_catastral   varchar(30) not null,
    tenencia_id         integer not null references tenencia(id),
    municipio_id        integer not null references municipio(id),
    vereda              varchar(50) not null,
    sector              varchar(50) not null,
    area_total          double precision ,

    residuos_ordinarios     varchar(100),
    residuos_peligrosos     varchar(100),
    residuos_otro           varchar(100),

    via_disponibilidad      boolean not null,
    via_tipo_id             integer references tipovia(id),
    via_estado_id           integer references tipoestadovia(id),

    dist_cabecera_mpal  double precision,
    forma_llegar        text,

    geo_latitud         double precision,
    geo_longitud        double precision,
    geo_altura          double precision,

    archivo_adjunto     text,
    observaciones       text
);

create table finca_servicio(
    id          serial not null primary key,
    finca_id    integer not null references finca(id),
    servicio_id integer not null references tiposerviciopublico(id)
);

create table finca_transporte(
    id                  serial not null primary key,
    finca_id            integer not null references finca(id),
    transporte_id       integer not null references tipomediotransporte(id)
);  


create table finca_maquinaria(
    id                  serial not null primary key,
    finca_id            integer not null references finca(id),
    maquinaria_id       integer not null references tipomaquinaria(id),
    descripcion         text not null
);


create table producto(
    id                              serial not null primary key,
    ruat_id                         integer not null references ruat(id),
    nombre                          varchar(100) not null,
    variedad                        varchar(100) not null,
    semilla_certificada             boolean not null,
    area_cosechada                  double precision not null,
    prod_semestre_a                 double precision,
    prod_semestre_b                 double precision,
    prod_total                      double precision,
    costo_establecimiento           double precision,
    costo_sostenimiento             double precision,
    prod_mercado                    double precision,
    prod_mercado_porcentaje         double precision,
    sitio_venta_id                  integer not null references tipositioventa(id),
    vende_tipo_id                   integer not null references tipovende(id),
    vende_nombre                    varchar(100),
    precio_promedio                 double precision,
    forma_pago_id                   integer not null references tipoformapago(id),
    subproducto                     varchar(50),
    subproducto_uso                 varchar(50),
    asistencia_programa             varchar(50),
    asistencia_entidad              varchar(50)
);


create table aprendizaje_respuesta(
    id              serial not null primary key,
    ruat_id         integer not null references ruat(id),
    pregunta_id     integer not null references tipopregunta(id),
    respuesta_id    integer not null references tiporespuesta(id)
);

create table observacion(
    id                  serial not null primary key,
    ruat_id             integer not null references ruat(id),
    observacion         text not null,
    ruta_formulario     varchar(50)
);
