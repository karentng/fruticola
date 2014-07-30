select concat(U.first_name,' ',U.last_name) as usuario,
    ruats, cosechas, bpas, vtps, postcosechas, creditos, cert0, cert1, cert2, cert3, cert4, cert5, cert6, cert7, cert8, cert9, cert10, cert12, cert13, cert11, cert30, cert31 
from users U
left join (
    select X.creador_id, count(*) as ruats 
    from ruat X join productor P on P.id=X.productor_id 
    group by X.creador_id) R on R.creador_id=U.id
left join (
    select X.creador_id, count(*) as cosechas 
    from cosecha X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id 
    group by X.creador_id) COS on COS.creador_id=U.id
left join (
    select X.creador_id, count(*) as bpas 
    from bpa X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id 
    where nro_visita=0 
    group by X.creador_id) BPA on BPA.creador_id=U.id
left join (
    select X.creador_id, count(*) as vtps 
    from visita_tipo_productor X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id 
    group by X.creador_id) VTP on VTP.creador_id=U.id
left join (
    select X.creador_id, count(*) as postcosechas 
    from postcosecha X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id 
    group by X.creador_id) POS on POS.creador_id=U.id

left join (
    select RU.creador_id, count(*) as creditos
    from solicitud_credito X join ruat RU on X.ruat_id=RU.id 
    group by RU.creador_id) CRED on CRED.creador_id=U.id
    



left join (
    select X.creador_id, count(*) as cert0
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=0
    group by X.creador_id) CERT0 on CERT0.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert1
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=1
    group by X.creador_id) CERT1 on CERT1.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert2
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=2
    group by X.creador_id) CERT2 on CERT2.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert3
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=3
    group by X.creador_id) CERT3 on CERT3.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert4
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=4
    group by X.creador_id) CERT4 on CERT4.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert5
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=5
    group by X.creador_id) CERT5 on CERT5.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert6
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=6
    group by X.creador_id) CERT6 on CERT6.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert7
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=7
    group by X.creador_id) CERT7 on CERT7.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert8
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=8
    group by X.creador_id) CERT8 on CERT8.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert9
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=9
    group by X.creador_id) CERT9 on CERT9.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert10
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=10
    group by X.creador_id) CERT10 on CERT10.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert12
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=12
    group by X.creador_id) CERT12 on CERT12.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert13
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=13
    group by X.creador_id) CERT13 on CERT13.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert11
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=11
    group by X.creador_id) CERT11 on CERT11.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert30
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=30
    group by X.creador_id) CERT30 on CERT30.creador_id=U.id
left join (
    select X.creador_id, count(*) as cert31
    from certificacion_visita X join ruat RU on X.ruat_id=RU.id join productor P on P.id=RU.productor_id
    where num_formulario=31
    group by X.creador_id) CERT31 on CERT31.creador_id=U.id
where coalesce(ruats,cosechas,bpas,vtps,postcosechas) is not null