insert into tipodocumento(descripcion, orden) VALUES
    ('C.C', 1),
    ('T.I', 2),
    ('C.E', 3),
    ('NIT', 4);

insert into niveleducativo(descripcion, orden) VALUES
    ('Primaria', 1),
    ('Secundaria', 2),
    ('Técnica', 3),
    ('Tecnológica', 4),
    ('Universitaria', 5),
    ('Ninguna', 100);

insert into claseorganizacion(descripcion, orden) values
    ('Agropecuaria',1),
    ('Asistencial',2),
    ('Comercial',3),
    ('Cultural',4),
    ('Deportiva',5),
    ('Educativa',6),
    ('Etnica',7),
    ('Político',8),
    ('Salud',9),
    ('Social',10),
    ('Tecnológico',11);


insert into tipoproductor(descripcion,orden) values 
    ('Pequeño',1),
    ('Mediano',2);

insert into renglonproductivo(descripcion,orden) values
    ('Aguacate',1),
    ('Bananito',2),
    ('Cítricos',3),
    ('Chontaduro',4),
    ('Fresa',5),
    ('Lulo',6),
    ('Mango',7),
    ('Maracuya',8),
    ('Melon',9),
    ('Mora',10),
    ('Papaya',11),
    ('Piña',12),
    ('Uva',13);

insert into tipobeneficio(descripcion, orden) values
    ('Capacitación', 1),
    ('Económico', 2),
    ('En Especie',3),
    ('Participación en la Toma de Decisiones', 4),
    ('Reconocimiento de la Comunidad',5),
    ('Recreación y Deporte', 6),
    ('Otro', 7);


insert into tipocredito(descripcion,orden) values
    ('Bancos',1),
    ('Agremiaciones',2),
    ('Entidades Estatales',3),
    ('Prestamistas',4),
    ('Familiares',5),
    ('Empresas de Insumos',6),
    ('Otro',100);


insert into periodicidad(descripcion, dias) values
    ('Semanal',7),
    ('Quincenal',15),
    ('Mensual',30),
    ('Bimestral',60),
    ('Trimestral',90),
    ('Semestral',180),
    ('Anual',365);


insert into tipoconfianza(descripcion, orden) values
    ('Siempre',1),
    ('Casi Siempre',2),
    ('A Veces',3),
    ('Casi Nunca',4),
    ('Nunca',5);

insert into tenencia (descripcion, orden) values
    ('Propiedad sin título', 1),
    ('En arrendamiento', 2),
    ('Comodacto', 3),
    ('Usufructo', 4),
    ('Aparcería', 5),
    ('Colectiva', 6),
    ('Otro', 7);

insert into tiposerviciopublico (descripcion, orden) values
    ('Agua Propia', 1),
    ('Acueducto', 2),
    ('Acceso a Internet', 3),
    ('Energía Eléctrica', 4);

insert into tipovia (descripcion, orden) values
    ('Pavimentada', 1),
    ('No Pavimentada', 2);

insert into tipoestadovia (descripcion, orden) values
    ('Buena', 1),
    ('Regular', 2),
    ('Mala', 3);

insert into tipomediotransporte (descripcion, orden) values
    ('Animal', 1),
    ('Camión', 2),
    ('Bicicleta', 3),
    ('Caminata', 4),
    ('Tractor', 5),
    ('Barco', 6),
    ('Canoa', 7),
    ('Kayak', 8),
    ('Planchón', 9),
    ('Otro', 10);

insert into tiposemilla (descripcion, orden) values
    ('Certificada', 1),
    ('No Certificada', 2);

insert into tipositioventa (descripcion, orden) values
    ('Finca', 1),
    ('Plaza', 2),
    ('Super Mercado', 3),
    ('Centro de Acopio', 4),
    ('Mercado Pueblo', 5),
    ('Otro', 6);

insert into tipovende (descripcion, orden) values
    ('Acopiador', 1),
    ('Transportador', 2),
    ('Detallista', 3),
    ('Transformador', 4),
    ('Cooperativa', 5),
    ('Consumidor Final', 6),
    ('Otro', 7);

insert into tipoformapago (descripcion, orden) values
    ('Efectivo', 1),
    ('Transferencia', 2),
    ('Cheque', 3),
    ('Crédito', 4),
    ('Trueque', 5);

insert into tipoinnovacion(descripcion, orden) values
    ('Producción', 1),
    ('Transformación', 2),
    ('Comercialización', 3),
    ('Organizacional', 4);


insert into fuenteinnovacion(descripcion, orden) values
    ('Propia', 1),
    ('EPSAGRO', 2),
    ('Asociaciones', 3),
    ('Organizacional', 4),
    ('Entidad Privada', 5),
    ('Otra', 6);


insert into tiporazonnopertenecer(descripcion, orden) values
    ('Desconocimiento', 1),
    ('Falta de Interés', 2),
    ('Falta de Tiempo', 3),
    ('Falta de Oportunidad', 4);

insert into tipomaquinaria (descripcion, orden) values
    ('Herramientas', 1),
    ('Utensilios', 2),
    ('Equipos', 3),
    ('Maquinaría Liviana', 4),
    ('Maquinaría Pesada', 5),
    ('Otros', 6);