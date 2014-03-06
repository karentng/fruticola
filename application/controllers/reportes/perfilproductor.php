<?php

class Perfilproductor extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        $promedio_edad = Productor::find_by_sql("SELECT avg( date_part('years', age(fecha_nacimiento))) as promedio_edad FROM productor")[0]->promedio_edad;

        $promedio_ingagro = VisitaTipoProductor::find_by_sql("SELECT avg(ingagro)as promedio_ingagro
                                                               FROM(SELECT visita_id, SUM(valor) as ingagro
                                                               FROM visita_tipo_productor 
                                                               INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id
                                                               WHERE pregunta_c_id BETWEEN 16  AND 17 
                                                               GROUP BY visita_id) as ingresos")[0]->promedio_ingagro;

        $promedio_totaling = VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_totaling
                                                                FROM visita_tipo_productor
                                                                INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id
                                                                WHERE pregunta_c_id=19")[0]->promedio_totaling;

        $promedio_egresos = VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_egresos
                                                                FROM visita_tipo_productor
                                                                INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id
                                                                WHERE pregunta_c_id=25")[0]->promedio_egresos;

        $promedio_utilidad = VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_utilidad
                                                                FROM visita_tipo_productor
                                                                INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id
                                                                WHERE pregunta_c_id=26")[0]->promedio_utilidad;

        $cantidad_productores= Productor::find_by_sql("SELECT COUNT(id) as cantidad_productores FROM productor")[0]->cantidad_productores;
        $total_hectareas= Producto::find_by_sql("SELECT SUM(area_cosechada) as total_hectareas FROM producto")[0]->total_hectareas;

        $promedio_hectareas=$total_hectareas/$cantidad_productores;


        $this->twiggy->set('promedio_hectareas', $promedio_hectareas);
        $this->twiggy->set('promedio_utilidad', $promedio_utilidad);
        $this->twiggy->set('promedio_egresos', $promedio_egresos);
        $this->twiggy->set('promedio_totaling', $promedio_totaling);
        $this->twiggy->set('promedio_ingagro', $promedio_ingagro);
        $this->twiggy->set('promedio_edad', $promedio_edad);
        $this->twiggy->template("reportes/perfilproductor");
        $this->twiggy->display();
    }
}