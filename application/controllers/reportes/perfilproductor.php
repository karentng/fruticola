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

        $cantidad_hombres=Productor::find_by_sql("SELECT COUNT(sexo) as cantidad_hombres FROM productor WHERE sexo='M'")[0]->cantidad_hombres;

        $cantidad_mujeres=Productor::find_by_sql("SELECT COUNT(sexo) as cantidad_mujeres FROM productor WHERE sexo='F'")[0]->cantidad_mujeres;
        $primaria =Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as primaria FROM productor WHERE nivel_educativo_id='1'")[0]->primaria;
        $secundaria =Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as secundaria FROM productor WHERE nivel_educativo_id='2'")[0]->secundaria;
        $tecnica =Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as tecnica FROM productor WHERE nivel_educativo_id='3'")[0]->tecnica;
        $tecnologica =Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as tecnologica FROM productor WHERE nivel_educativo_id='4'")[0]->tecnologica;
        $universitaria =Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as universitaria FROM productor WHERE nivel_educativo_id='5'")[0]->universitaria;
        $ninguna =Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as ninguna FROM productor WHERE nivel_educativo_id='6'")[0]->ninguna;


        $this->twiggy->set('ninguna', $ninguna);
        $this->twiggy->set('universitaria', $universitaria);
        $this->twiggy->set('tecnologica', $tecnologica);
        $this->twiggy->set('tecnica', $tecnica);
        $this->twiggy->set('secundaria', $secundaria);
        $this->twiggy->set('primaria', $primaria);
        $this->twiggy->set('cantidad_hombres',  $cantidad_hombres);
        $this->twiggy->set('cantidad_mujeres', $cantidad_mujeres);
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