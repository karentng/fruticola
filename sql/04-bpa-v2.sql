CREATE TABLE bpa (
	id serial NOT NULL PRIMARY KEY,
	ruat_id integer REFERENCES ruat(id),
	creado timestamp NOT NULL default current_timestamp ,
	creador_id integer NOT NULL references users(id),
	fecha_visita date NOT NULL,
	conclusion text,
	nivel_bpa double precision,
	recomendacion text
);

CREATE TABLE bpa_pregunta(
	id serial NOT NULL PRIMARY KEY,
	numeral INTEGER NOT NULL,
	enunciado text NOT NULL,
	seccion char(1) NOT NULL, -- 'B', 'C'
	seccionNumero INTEGER,
	padre integer REFERENCES bpa_pregunta(id)
);

CREATE TABLE bpa_respuesta(
	id serial NOT NULL PRIMARY KEY,
	bpa_id  integer NOT NULL REFERENCES bpa(id),
    pregunta_id  integer NOT NULL REFERENCES bpa_pregunta(id),
    puntaje float NOT NULL,
    observacion text
);


INSERT INTO bpa_pregunta(seccionNumero,numeral, enunciado, seccion, padre) VALUES -- Inserto los de la seccion C
	(null, 1, '<b>1. ÁREAS E INSTALACIONES.</b> Este elemento tiene un papel fundamental para evitar la contaminación (mantenimiento de las instalaciones y las herramientas en condiciones adecuadas, y particularmente, ordenadas). Debemos revisar si la agroempresa de frutas cuenta con las siguientes instalaciones para cumplir con las BPA:', 'C', null),
	(1, 2, '1.1 Dispone de baño para los trabajadores con papel higiénico, jabón líquido y toallas limpias para el secado de manos.', 'C', 1),
	(1, 3, '1.2 Dispone de área para el almacenamiento de insumos agrícolas, alejada de la vivienda, en la que los plaguicidas están separados de los fertilizantes y bioinsumos. Esta área permanece con llave y tiene avisos informativos para prevención de los peligros relacionados con el manejo de los insumos agrícolas, el uso de elementos de protección personal, extintor multiuso en un lugar visible y un kit para usar en caso de derrame que consta de aserrín o arena, recogedor, bolsa y guantes.', 'C', 1),
	(1, 4, '1.3 Dispone de área para dosificación y preparación de mezclas de insumos agrícolas.', 'C' ,1),
	(1, 5, '1.4 Dispone de área de acopio transitorio de frutas cosechadas que cuente con techo, estibas, canastillas, lavamanos, jabón, mesa y cercado.', 'C', 1),
	(1, 6, '1.5 Dispone de área para el consumo de alimentos y descanso de los trabajadores con canecas para la disposición de basuras.', 'C', 1),
	(1, 7, '1.6 Dispone de área para disposición de residuos donde garantiza que no hay contaminación del alimento, tiene clasificados los residuos en recipientes debidamente tapados y protegidos de aguas lluvias, con iluminación y ventilación adecuada.', 'C', 1),


	(null, 8, '<b>2. EQUIPOS Y UTENSILIOS.</b> Debemos revisar si la agroempresa de frutas cuenta con lo siguiente para cumplir con las BPA:', 'C', null),
	(2, 9, '2.1 Revisar si todos los equipos, utensilios y herramientas que se utilizan en las labores de campo, cosecha y poscosecha se encuentran en buenas condiciones de limpieza y están organizados.', 'C', 8),
	(2, 10, '2.2 Revisar si tiene un programa de mantenimiento y calibración preventivo para cada uno de los equipos, utensilios y herramientas que se utilizan en las labores de campo, cosecha y poscosecha.', 'C', 8),
	(2, 11, '2.3 Revisar si se llevan registros de todas las actividades de mantenimiento y calibración con los procedimientos e instructivos para su manejo.', 'C', 8),


	(null, 12, '<b>3. CALIDAD Y MANEJO DEL AGUA.</b> Debemos revisar si la agroempresa de frutas cuenta con lo siguiente para cumplir con las BPA:', 'C', null),
	(3, 13, '3.1  Revisar si la unidad productiva dispone de un programa para obtener la calidad y hacer un buen manejo del agua que incluya:
    			<ul>
    				<li>
    					Cuidado y prevención de la contaminación de las fuentes de agua de la unidad productiva.
    				</li>
    				<li>
    					Realización de mínimo dos (2) veces al año de los análisis fisicoquímicos y microbiológicos del agua para verificar su calidad, según las condiciones del clima (época de seca y lluviosa).
    				</li>
    			</ul>', 'C', 12),


	(null, 14, '<b>4. MANEJO INTEGRADO DEL CULTIVO (MIC).</b> La clave está en realizar las labores en el momento oportuno, de acuerdo con las condiciones agroecológicas de la región y con la asesoría de un ingeniero agrónomo competente que nos garanticen la productividad e inocuidad de las frutas producidas. Debemos revisar si la agroempresa de frutas cuenta con lo siguiente para cumplir con las BPA:', 'C', null),

	(4, 15, '<b>4.1 BPA EN EL MANEJO DE SUELOS.</b>', 'C', 14),

	(41, 16, '4.1.1 Se hace labranza mínima e incorpora la materia orgánica', 'C', 15),
	(41, 17, '4.1.2 Se hacen siembras en contorno o a través de la pendiente para formar una barrera que disminuya la fuerza de arrastre del agua y su efecto en la pérdida del suelo.', 'C', 15),
	(41, 18, '4.1.3 Se usan distancias de siembra recomendadas según el clima, el cultivo, la pendiente del terreno, el tipo de suelo para facilitar las labores del cultivo, permitir la ventilación y contribuir a reducir problemas de plagas y enfermedades.', 'C', 15),
	(41, 19, '4.1.4 Se hace rotación de cultivos (cultivos anuales) o se justifica cuando no se puede hacer.', 'C', 15),
	(41, 20, '4.1.5 Se mantien protegido el suelo con coberturas inertes o con arvenses nobles para reducir la aplicación de herbicidas o evitar el movimiento de suelo.', 'C', 15),
	(41, 21, '4.1.6 Se utilizan barreras vivas para evitar la erosión.', 'C', 15),
	(41, 22, '4.1.7 Se colocan barreras muertas como los sacos con arena o muros de contención, trinchos en guadua o bambú en lugares donde la escorrentía del agua es muy fuerte.', 'C', 15),
	(41, 23, '4.1.8 Se cultivan plantan fijadoras de nitrógeno entre los surcos del cultivo principal si es permitido por el técnico.', 'C', 15),
	(41, 24, '4.1.9 Se hacen drenajes en suelos con problemas de saturación hídrica y se les hace mantenimiento al igual que los desagues naturales.', 'C', 15),
	(41, 25, '4.1.10 Se mantiene un registro de todas estas prácticas.', 'C', 15),

	(4, 26, '<b>4.2 BPA EN EL MATERIAL DE PROPAGACIÓN.</b>', 'C', 14),

	(42, 27, '4.2.1 Se registra e identifican las plantas madres o el campo del cultivo de origen.', 'C', 26),
	(42, 28, '4.2.2 Se cuenta con certificado de calidad fitosanitaria de la semilla con la fecha de vencimiento, origen, lote, variedad, tasa de germinación, y empresa responsable.', 'C', 26),
	(42, 29, '4.2.3 Se dispone del registro expedido por el ICA del vivero donde se compró la semilla o material de propagación.', 'C', 26),
	(42, 30, '4.2.4 El material de propagación adquirido está libre de signos visibles de plagas y enfermedades.', 'C', 26),

	(4, 31, '<b>4.3 BPA EN LA NUTRICIÓN DE LAS PLANTAS</b>', 'C', 14),

	(43, 32, '4.3.1 Toma de muestras de suelo y foliares y se gestiona el análisis fisico-químico con las recomendaciones de fertilización de Ingeniero Agrónomo.', 'C', 31),
	(43, 33, '4.3.2 Se conocen los requerimientos nutricionales del cultivo.', 'C', 31),
	(43, 34, '4.3.3 Se dispone de un plan de fertilización con las dosis y las frecuencias recomendadas por el Ingeniero Agrónomo.', 'C', 31),
	(43, 35, '4.3.4 Los insumos agrícolas que se utilizan para la fertilización del cultivo tienen registro otorgado por el ICA y se adquieren en almacenes autorizados igualmente por el ICA.', 'C', 31),
	(43, 36, '4.3.5 Los abonos orgánicos que se usan tienen las siguientes características: A) Están registrados ante el ICA y son comprados en los establecimientos autorizados, B) El abono que se prepara en la unidad productiva tiene registros que incluyen información sobre el origen del material, los procedimientos de transformación y los controles realizados, C) En la preparación de los abonos orgánicos no se utilizan heces humanas tratadas o sin tratar, desechos urbanos sin clasificar, ni cualquier otro material que presente contaminación microbiológica, metales pesados u otros productos químicos', 'C', 31),


	(null, 37, '<b>5. MANEJO INTEGRADO DE PLAGAS (MIP).</b> Es una estrategia que usa una gran variedad de métodos complementarios: físicos, mecánicos, químicos, biológicos, genéticos, legales y culturales para el control de las plagas. Estos métodos se aplican en tres etapas: prevención, monitoreo-evaluación e intervención. El MIP es un método que aspira a reducir o eliminar el uso de plaguicidas y a minimizar el impacto en el medio ambiente. Debemos revisar si la agroempresa de frutas cuenta con lo siguiente para cumplir con las BPA:', 'C', null),

	(5, 38, '<b>5.1 PREVENCIÓN.</b> Realiza una revisión completa de cada plaga para conocer su nombre común y científico, su ciclo biológico y la evaluación de la magnitud del daño con el fin hacer un uso racional de las medidas de manejo especialmente el químico', 'C', 37),
	(5, 39, '<b>5.2 MONITOREO y EVALUACIÓN.</b> Realiza monitoreos y evaluaciones de las poblaciones y niveles de daño causados por las plasgas (% de infestación, % de incidencia y % de severidad), y consulta a un Ingeniero Agrónomo para realizar el manejo de las plagas que afectan el cultivo.', 'C', 37),
	(5, 40, '<b>5.3 INTERVENCION.</b> Las prácticas dirigidas a disminuir la población de las plagas con el fin de reducirlas a niveles aceptables mediante el Manejo Integrado Plagas (MIP), se planea y ejecuta bajo la responsabilidad de un ingeniero agrónomo competente en el cultivo.', 'C', 37),


	(null, 41, '6. BIENESTAR DE LOS TRABAJADORES. Entre los elementos de las BPA también se encuentra el bienestar de los trabajadores. Se enfoca en promover la calidad de vida a través de una existencia tranquila, productiva y satisfecha. Debemos revisar si la agroempresa de frutas cuenta con lo siguiente para cumplir con las BPA:', 'C', null),

	(6, 42, '6.1 Se realizan contratos de capacitación frecuentemente sobre: A) Uso y manejo responsable de agroquímicos, B) Manejo de herramientas peligrosas, C) Curso de primeros auxilios, D) Manejo de extintores.', 'C', 41),
	(6, 43, '6.2 Se mantiene por escrito y en lugares visibles los procedimientos en caso de emergencia y unos trabajadores responsables que estén entrenados para actuar en caso de derrames de agroquímicos, incendios o intoxicaciones o cualquier riesgo potencial para ellos.', 'C', 41),
	(6, 44, '6.3 Se mantiene la higiene en las viviendas e instalaciones.', 'C', 41),
	(6, 45, '6.4 Los trabajadores permanentes están afiliados a una Empresa Prestadora de servicios de Salud (EPS) y a una Administradora de Riesgos Laborales (ARL) y a los trabajadores ocasionales se les solicitamos el certificado de afiliación como independientes a la EPS y ARL, en caso de no tenerlos se abstienen de contratarlos.', 'C', 41),
	(6, 46, '6.5 Se mantienen letreros visibles con información importante (policía nacional, defensa civil, hospital, bomberos, etc).', 'C', 41),
	(6, 47, '6.6 Dispone de equipo de protección para el manejo de plaguicidas y herramientas peligrosas.', 'C', 41),


	(null, 48, '<b>7. PROTECCIÓN AMBIENTAL.</b> Entre los elementos de las BPA también se encuentra la protección ambiental. Evalúa la cultura actual de protección y el buen mantenimiento para colaborar en nuestra propia vida futura.', 'C', null),

	(7, 49, '7.1 Los sobrantes de las aplicaciones de plaguicidas y las aguas de lavado de las aspersoras, las asperjamos en un sitio de barbecho debidamente identificado y alejado de las fuentes de agua.', 'C', 48),
	(7, 50, '7.2 Hacemos el triple lavado de los envases de plaguicidas cuando están vacíos, además se perforan sin destruir la etiqueta y se guardan en un sitio aparte y restringido hasta entregarlos al representante de la empresa CAMPOLIMPIO que promueve esta actividad en el País.', 'C', 48),
	(7, 51, '7.3 Cuando hay material vegetal resultante de podas fitosanitarias, lo retiramos del lote o lo enterramos.', 'C', 48),
	(7, 52, '7.4 Los productos de desecho los identificamos y cuantificamos en todas las áreas de la unidad productiva (como papel, cartón, rastrojos de cosecha, aceite, combustibles, roca, lana, etc.) con el fin de definir la gestión de cada uno de los residuos.', 'C', 48),
	(7, 53, '7.5 Los residuos orgánicos provenientes de los baños y cocinas de las viviendas e instalaciones de la unidad productiva van al pozo séptico construido técnicamente.', 'C', 48),


	(null, 54, '<b>8. Documentación, Registros y Trazabilidad.</b> Se entiende como trazabilidad aquellos procedimientos que permiten conocer el histórico, la ubicación, y la trayectoria de un producto o lote de productos a los largo de la cadena de suministros. Gracias a la trazabilidad poodemos saber cuales fueron los insumos que se utilizaron en el proceso productivo para la obtención de las frutas, hasta que se vendió al consumidor.', 'C', null),

	(8, 55, '8.1 Se lleva un registro de la evaluación de las características y recursos de la zona, del predio y de los riesgos asociados.', 'C', 54),
	(8, 56, '8.2 Existe documentación sobre el material de siembra (Procedimiento de sanidad y calidad del material de propagación; Instructivo para desinfección de material de propagación o tratamiento de semillas; Certificado del material de siembra; Registro del control de calidad en viveros; Registro de siembra).', 'C', 54),
	(8, 57, '8.3 Existen registros de análisis de agua y suelos (Físico-químicos y microbiológicos).', 'C', 54),
	(8, 58, '8.4 Existen registros de mantenimiento y calibración de equipos (Equipos de aplicación de fertilizantes foliares y de plaguicidas).', 'C', 54),
	(8, 59, '8.5 Registro de aplicación de fertilizantes (PLAN DE FERTILIZACION, Kárdex de fertilizantes, Fichas técnicas de los fertilizantes y abonos).', 'C', 54),
	(8, 60, '8.6 Registro sobre la preparación de los abonos orgánicos (en caso de elaborarse en la unidad productiva) - Evaluación de riesgos, Registros.', 'C', 54),
	(8, 61, '8.7 Plan de Manejo Integrado de Plagas (MIP). Listado de plaguicidas permitidos y prohibidos en Colombia, Hojas de seguridad de cada plaguicida y límite máximo de residuos según el Codex alimentarius; Procedimiento de manejo de plagas; Kárdex actualizado; Registro de aplicación de plaguicidas.', 'C', 54);

INSERT INTO bpa_pregunta(numeral, enunciado, seccion) VALUES -- Inserto los de la seccion B
	(62, '1. Conoce los antecedentes de la unidad productiva como historial de cultivos, agroquímicos aplicados, plagas que se presentan, industrias o producciones anteriores, etc.', 'B'),
	(63, '2. Cuenta con un Certificado de uso del suelo de su unidad productiva expedido por la oficina de planeación de su municipio.', 'B'),
	(64, '3. Revisa la calidad y cantidad de agua disponible para el cultivo en mi unidad productiva y pido el permiso de uso de agua a la Corporación Autónoma si lo requiero.', 'B'),
	(65, '4. Evalúa las condiciones climáticas (temperaturas, humedad, precipitación, etc.) y los recursos de la zona (vías, servicios de salud, disponibilidad de personal para las labores de campo, comunicaciones, etc)', 'B'),
	(66, '5. Dibuja el mapa de su unidad productiva o consigue el plano de la misma para ubicar las instalaciones, lotes agrícolas y pecuarios, forestales, zonas de conservación, linderos, vecinos, fuentes de agua, carreteras, pozo séptico, etc)', 'B'),
	(67, '6. Invierte en análisis de las características fisicoquímicas y microbiológicas del suelo de su unidad productiva.', 'B'),
	(68, '7. Busca la asesoría de un ingeniero agrónomo competente en el cultivo que va a sembrar, con el fin de determinar material de siembra adecuado, fertilización, manejo de posibles plagas y enfermedades, etc.', 'B'),
	(69, '8. Evalúa las características agroecológicas de su unidad productiva para determinar si son favorables para el cultivo que va a sembrar, además de los PELIGROS que se pueden presentar (análisis de riesgos).', 'B');