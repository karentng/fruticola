ALTER TABLE meta_complementaria_pregunta ALTER COLUMN concepto TYPE text;

insert into meta_complementaria_pregunta(meta, concepto, orden) values
    (20,'Disminución de las pérdidas de fruta en cosecha', 15),
    (5,'Disminución de las pérdidas de fruta en poscosecha', 16),
    (5441,'Planes de fertilización elaborados', 17);

insert into meta_complementaria_pregunta(meta, concepto, orden) values
    (10,'Número de parcelas demostrativas en Mango', 18),
    (1,'Caracterizaciones agroecológicas para la siembra de mango realizadas', 19),
    (25,'Áreas renovadas con semilla de Fresa de alta calidad genética y productiva (has)', 20),
    (300000,'Estolones de semilla de Fresa de alta calidad genética y productiva importada y entregada', 21),
    (1200,'Estolones de semilla de Fresa de alta calidad genética y productiva por productor entregados', 22),
    (18,'Áreas renovadas con semilla de Mora de reconocida calidad productiva (has)', 23),
    (38462,'Plántulas de semilla de Mora de reconocida calidad productiva entregada', 24),
    (64,'Plántulas de semilla de Mora de reconocida calidad productiva por productor entregada', 25),

    (360,'Número de extensionistas mejorados y actualizados tecnológicamente', 26),
    (13,'Renglones productivos con extensionistas actualizados tecnológicamente', 27),
    (5441,'Productores beneficiados con la actualización tecnológica de los extensionistas', 28),
    (6138,'Hectáreas de cultivos beneficiadas con la actualización de los extensionistas', 29);



insert into meta_complementaria(fila, total, porcentaje) values
    (14,0,0),
    (15,0,0),
    (16,0,0),
    (17,0,0),
    (18,0,0),
    (19,0,0),
    (20,0,0),
    (21,0,0),
    (22,0,0),
    (23,0,0),
    (24,0,0),
    (25,0,0),
    (26,0,0),
    (27,0,0),
    (28,0,0);