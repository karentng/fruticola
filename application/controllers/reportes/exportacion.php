<?php

class Exportacion extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador","Coordinador"));

        $this->load->dbutil();
    }

    public function index()
    {
        $exportables = array(
            array("title" => "Productores", "url" => site_url("reportes/exportacion/productores")),
            array("title" => "Cosechas",    "url" => site_url("reportes/exportacion/cosechas")),
        );
        $this->twiggy->set("exportables", $exportables);
        $this->twiggy->template("reportes/exportacion");
        $this->twiggy->display();
    }

    public function productores()
    {
        $sql = "
            select P.nombre1, P.nombre2, P.apellido1, P.apellido2,  P.numero_documento, 
                REN.descripcion as renglon_productivo, C.telefono, C.celular, C.vereda, MUN.nombre as municipio
            from productor P join renglonproductivo REN on REN.id=P.renglon_productivo_id
            join contacto C on C.productor_id = P.id
            join municipio MUN on MUN.id=C.municipio_id
        ";

        $query = $this->db->query($sql);
        $this->headers_descargable("productores.csv");
        echo $this->dbutil->csv_from_result($query);
    }

    public function cosechas()
    {
        $sql = "
            select REN.descripcion as renglon,  MF.nombre as municipio,
                respuestas1, respuestas2, respuestas3, respuestas4, respuestas5, respuestas6, respuestas7, respuestas8
            from Cosecha C
            inner join ruat RUAT on RUAT.id=C.ruat_id 
            inner join productor P on P.id=RUAT.productor_id
            inner join renglonproductivo REN on REN.id = P.renglon_productivo_id
            inner join finca F on F.ruat_id=RUAT.id
            inner join municipio MF on MF.id=F.municipio_id
            left join
                (select COS.id, string_agg(O.letra,',') as respuestas1
                from cosecha COS
                left join cosecha_respuesta CR on CR.cosecha_id=COS.id and CR.pregunta_id=1
                left join cosecha_opcionrespuesta O on CR.opcion_id=O.id
                group by COS.id) R1 ON R1.id=C.id
            left join
                (select COS.id, string_agg(O.letra,',') as respuestas2
                from cosecha COS
                left join cosecha_respuesta CR on CR.cosecha_id=COS.id and CR.pregunta_id=2
                left join cosecha_opcionrespuesta O on CR.opcion_id=O.id
                group by COS.id) R2 ON R2.id=C.id
            left join
                (select COS.id, string_agg(O.letra,',') as respuestas3
                from cosecha COS
                left join cosecha_respuesta CR on CR.cosecha_id=COS.id and CR.pregunta_id=3
                left join cosecha_opcionrespuesta O on CR.opcion_id=O.id
                group by COS.id) R3 ON R3.id=C.id
            left join 
                (select COS.id, string_agg(O.letra,',') as respuestas4
                from cosecha COS
                left join cosecha_respuesta CR on CR.cosecha_id=COS.id and CR.pregunta_id=4
                left join cosecha_opcionrespuesta O on CR.opcion_id=O.id
                group by COS.id) R4 ON R4.id=C.id
            left join
                (select COS.id, string_agg(O.letra,',') as respuestas5
                from cosecha COS
                left join cosecha_respuesta CR on CR.cosecha_id=COS.id and CR.pregunta_id=5
                left join cosecha_opcionrespuesta O on CR.opcion_id=O.id
                group by COS.id) R5 ON R5.id=C.id
            left join
                (select COS.id, string_agg(O.letra,',') as respuestas6
                from cosecha COS
                left join cosecha_respuesta CR on CR.cosecha_id=COS.id and CR.pregunta_id=6
                left join cosecha_opcionrespuesta O on CR.opcion_id=O.id
                group by COS.id) R6 ON R6.id=C.id
            left join
                (select COS.id, string_agg(O.letra,',') as respuestas7
                from cosecha COS
                left join cosecha_respuesta CR on CR.cosecha_id=COS.id and CR.pregunta_id=7
                left join cosecha_opcionrespuesta O on CR.opcion_id=O.id
                group by COS.id) R7 ON R7.id=C.id
            left join
                (select COS.id, string_agg(O.letra,',') as respuestas8
                from cosecha COS
                left join cosecha_respuesta CR on CR.cosecha_id=COS.id and CR.pregunta_id=6
                left join cosecha_opcionrespuesta O on CR.opcion_id=O.id
                group by COS.id) R8 ON R8.id=C.id
        ";
        $query = $this->db->query($sql);
        $this->headers_descargable("cosechas.csv");
        echo $this->dbutil->csv_from_result($query);
    }


    private function headers_descargable($nombrearchivo)
    {
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="'. $nombrearchivo .'"');
    }
}