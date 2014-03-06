<?php

class ProgresoBPA extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        $sql = "
        select concat(P.nombre1,' ',nullif(P.nombre2||' ',' '), P.apellido1,' ',P.apellido2) as nombre_productor,
            P.numero_documento, MUN.nombre as municipio, F.vereda, CON.telefono, CON.celular, REN.descripcion as renglon,
            case when V0.nivel_bpa is null then null else round(V0.nivel_bpa::numeric, 2) end as puntaje0, 
            case when V1.nivel_bpa is null then null else round(V1.nivel_bpa::numeric, 2) end as puntaje1, 
            case when V2.nivel_bpa is null then null else round(V2.nivel_bpa::numeric, 2) end as puntaje2, 
            case when V3.nivel_bpa is null then null else round(V3.nivel_bpa::numeric, 2) end as puntaje3, 
            case when V4.nivel_bpa is null then null else round(V4.nivel_bpa::numeric, 2) end as puntaje4, 
            case when V5.nivel_bpa is null then null else round(V5.nivel_bpa::numeric, 2) end as puntaje5, 
            case when V6.nivel_bpa is null then null else round(V6.nivel_bpa::numeric, 2) end as puntaje6
        from ruat R join productor P on R.productor_id=P.id
        join contacto CON on CON.productor_id=P.id
        join finca F on F.ruat_id=R.id
        join municipio MUN on MUN.id=F.municipio_id
        join renglonproductivo REN on REN.id=P.renglon_productivo_id
        left join bpa V0 on V0.ruat_id=R.id and V0.nro_visita=0
        left join bpa V1 on V1.ruat_id=R.id and V1.nro_visita=1
        left join bpa V2 on V2.ruat_id=R.id and V2.nro_visita=2
        left join bpa V3 on V3.ruat_id=R.id and V3.nro_visita=3
        left join bpa V4 on V4.ruat_id=R.id and V4.nro_visita=4
        left join bpa V5 on V5.ruat_id=R.id and V5.nro_visita=5
        left join bpa V6 on V6.ruat_id=R.id and V6.nro_visita=6
        ";

        $result = $this->db->query($sql);
        
        $this->load->library('table');
        $this->table->set_heading("Productor","Identificación", "Municipio", "Vereda", "Teléfono", "Celular", "Renglón", "BPA Inicial", "Visita 1", "Visita 2", "Visita 3", "Visita 4", "Visita 5", "Visita 6");
        $this->table->set_template(array('table_open'=>"<table id='progresoBPA' class='table table-bordered'>"));
        $tabla = $this->table->generate($result);
        $this->twiggy->set("tabla", $tabla);

        $this->twiggy->template("reportes/progresobpa");
        $this->twiggy->display();
    }

}