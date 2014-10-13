
select nombre_productor, REN.descripcion as renglon, cosechas, bpas, vtps, poscosechas, creditos,
    cert0  as "Elaboración de propuestas asociativas  para estudio de riego",
    cert1  as "Implementación de prácticas de manejo agronómico  tendientes a romper la estacionalidad",
    cert2  as "Renovación de cultivos existentes y plantación  de áreas nuevas con variedades promisorias",
    cert3  as "Mejoramiento de las áreas actuales con semilla  de PIÑA de alta calidad genética y productiva",
    cert4  as "Implementación de modelos de producción óptima  de MANGO",
    cert5  as "Mejoramiento de las áreas actuales con semilla  de FRESA de alta calidad genética y productiva",
    cert6  as "Mejoramiento de las áreas actuales con semilla  de MORA de alta calidad genética y productiva",
    cert7  as "Planes de fertilidad",
    cert8  as "Visitas de seguimiento y/o acompañamiento",
    cert9  as "Elaboración de los planes de negocio regional",
    cert10 as "Incremento de los rendimientos de cultivo",
    cert12 as "Manejo Fitosanitario 1",
    cert13 as "Manejo Fitosanitario 2",
    cert11 as "Estacionalidad de la Producción",
    cert30 as "Estacionalidad de la Producción 2",
    cert31 as "Estacionalidad de la Producción 3"
from listadoruats LR join ruat R on LR.id=R.id join productor P on R.productor_id = P.id
join renglonproductivo REN on REN.id = P.renglon_productivo_id
left join ( select ruat_id, count( id ) as cosechas from cosecha group by ruat_id ) COS on COS.ruat_id = R.id
left join ( select ruat_id, count( id ) as bpas from bpa group by ruat_id ) BP on BP.ruat_id = R.id
left join ( select ruat_id, count( id ) as vtps from visita_tipo_productor group by ruat_id ) VTP on VTP.ruat_id = R.id
left join ( select ruat_id, count( id ) as poscosechas from postcosecha group by ruat_id ) POS on POS.ruat_id = R.id
left join ( select ruat_id, count( id ) as creditos from solicitud_credito group by ruat_id ) CRE on CRE.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert0 from certificacion_visita where num_formulario=0 group by ruat_id ) C0 on C0.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert1 from certificacion_visita where num_formulario=1 group by ruat_id ) C1 on C1.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert2 from certificacion_visita where num_formulario=2 group by ruat_id ) C2 on C2.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert3 from certificacion_visita where num_formulario=3 group by ruat_id ) C3 on C3.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert4 from certificacion_visita where num_formulario=4 group by ruat_id ) C4 on C4.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert5 from certificacion_visita where num_formulario=5 group by ruat_id ) C5 on C5.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert6 from certificacion_visita where num_formulario=6 group by ruat_id ) C6 on C6.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert7 from certificacion_visita where num_formulario=7 group by ruat_id ) C7 on C7.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert8 from certificacion_visita where num_formulario=8 group by ruat_id ) C8 on C8.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert9 from certificacion_visita where num_formulario=9 group by ruat_id ) C9 on C9.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert10 from certificacion_visita where num_formulario=10 group by ruat_id ) C10 on C10.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert11 from certificacion_visita where num_formulario=11 group by ruat_id ) C11 on C11.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert12 from certificacion_visita where num_formulario=12 group by ruat_id ) C12 on C12.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert13 from certificacion_visita where num_formulario=13 group by ruat_id ) C13 on C13.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert30 from certificacion_visita where num_formulario=30 group by ruat_id ) C30 on C30.ruat_id = R.id
left join ( select ruat_id, count( id ) as cert31 from certificacion_visita where num_formulario=31 group by ruat_id ) C31 on C31.ruat_id = R.id
