create or replace view listadoruats as
    select R.id, R.numero_formulario,
        concat(P.nombre1,' ',nullif(P.nombre2||' ',' '), P.apellido1,' ',P.apellido2) as nombre_productor,
        R.creado,
        concat(U.first_name,' ',U.last_name) as ingresado_por,
        CO.id as cosecha_id,
        BPA.id as bpa_id,
        VTP.id as VTP_id,
        OB.ruta_formulario
    from ruat R inner join productor P on R.productor_id = P.id
        inner join users U on R.creador_id = U.id
        left join cosecha CO on R.id = CO.ruat_id
        left join bpa BPA on R.id = BPA.ruat_id
        left join visita_tipo_productor VTP on R.id = VTP.ruat_id
        left join observacion OB on R.id = OB.ruat_id
