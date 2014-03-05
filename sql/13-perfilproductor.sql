CREATE view perfil_productor as
SELECT productor.id as productor, sexo, nivel_educativo_id, renglon_productivo_id, age(fecha_nacimiento) as edad, finca_servicio.servicio_id, tpp.ingagro, r19.valor as ingresostotal,
r25.valor as egresos, r26.valor as utilidad, CASE WHEN economia.credito_id IS NULL THEN 'No' ELSE 'Sí' END AS credito, economia.credito_id as procedencia, orgasociada_clase.clase_id,
orgasociada_beneficio.beneficio_id, finca.tenencia_id, producto.area_cosechada, (producto.prod_semestre_a + producto.prod_semestre_b)as produccion_total, producto.precio_promedio as precio_venta, CASE WHEN producto.asistencia_programa IS NULL THEN 'No' ELSE 'Sí' END AS asistencia_tecnica, finca_maquinaria.maquinaria_id, finca_transporte.transporte_id
FROM productor
INNER JOIN ruat ON productor_id=productor.id
INNER JOIN finca ON ruat_id=ruat.id
INNER JOIN finca_servicio ON finca_id=finca.id
INNER JOIN finca_maquinaria ON finca_maquinaria.finca_id=finca.id
INNER JOIN finca_transporte ON finca_transporte.finca_id=finca.id
INNER JOIN visita_tipo_productor ON visita_tipo_productor.ruat_id=ruat.id
INNER JOIN ( SELECT visita_id, SUM(valor) as ingagro
   FROM visita_tipo_productor 
   INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id
   WHERE pregunta_c_id BETWEEN 16  AND 17 
   GROUP BY visita_id) as tpp
ON tpp.visita_id=visita_tipo_productor.id
INNER JOIN tp_c_respuesta as r19 ON r19.visita_id=visita_tipo_productor.id and pregunta_c_id=19
INNER JOIN tp_c_respuesta as r25 ON r25.visita_id=visita_tipo_productor.id and r25.pregunta_c_id=25
INNER JOIN tp_c_respuesta as r26 ON r26.visita_id=visita_tipo_productor.id and r26.pregunta_c_id=26
INNER JOIN economia ON economia.productor_id=productor.id
INNER JOIN orgasociada ON orgasociada.ruat_id=ruat.id
INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id
INNER JOIN orgasociada_beneficio ON orgasociada_beneficio.orgasociada_id=orgasociada.id
INNER JOIN producto ON producto.ruat_id=ruat.id;