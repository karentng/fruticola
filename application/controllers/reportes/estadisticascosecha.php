<?php

class EstadisticasCosecha extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {

        $filtro_renglon ="true";
        if($this->input->post('renglonProduc'))
        $filtro_renglon = "renglon_productivo_id=".$this->db->escape($this->input->post('renglonProduc'));

       /* $filtro_munic ="true";
        if($this->input->post('municCos'))
        $filtro_munic = "renglon_productivo_id=".$this->db->escape($this->input->post('municCos'));
        //TABLA
        $resultado=Productor::find_by_sql("SELECT avg( date_part('years', age(fecha_nacimiento))) as promedio_edad FROM productor WHERE $filtro_renglon");*/
        $promedio_edad = $resultado[0]->promedio_edad;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(ingagro)as promedio_ingagro
                                                    FROM
                                                    (SELECT visita_tipo_productor.id as visita_id, SUM(valor)as ingagro
                                                    FROM productor
                                                    INNER JOIN ruat ON productor_id=productor.id
                                                    INNER JOIN visita_tipo_productor ON ruat_id=ruat.id
                                                    INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id AND pregunta_c_id BETWEEN 16  AND 17
                                                    WHERE $filtro_renglon
                                                    GROUP BY visita_tipo_productor.id) as A");

        $promedio_ingagro = $resultado[0]->promedio_ingagro;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_totaling
                                                    FROM (
                                                        SELECT valor
                                                        FROM productor
                                                        INNER JOIN ruat ON productor_id=productor.id
                                                        INNER JOIN visita_tipo_productor ON ruat_id=ruat.id
                                                        INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id AND pregunta_c_id=19
                                                        WHERE $filtro_renglon)as B");

        $promedio_totaling = $resultado[0]->promedio_totaling;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_egresos
                                                        FROM(
                                                            SELECT valor
                                                            FROM productor
                                                            INNER JOIN ruat ON productor_id=productor.id
                                                            INNER JOIN visita_tipo_productor ON ruat_id=ruat.id
                                                            INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id AND pregunta_c_id=25
                                                            WHERE $filtro_renglon)as B");

        $promedio_egresos = $resultado[0]->promedio_egresos;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_utilidad
                                                        FROM(
                                                            SELECT valor
                                                            FROM productor
                                                            INNER JOIN ruat ON productor_id=productor.id
                                                            INNER JOIN visita_tipo_productor ON ruat_id=ruat.id
                                                            INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id AND pregunta_c_id=26
                                                            WHERE $filtro_renglon)as C");
        $promedio_utilidad = $resultado[0]->promedio_utilidad;

        $resultado=Productor::find_by_sql("SELECT COUNT(id) as cantidad_productores FROM productor WHERE $filtro_renglon");
        $cantidad_productores= $resultado[0]->cantidad_productores;


        $resultado=Producto::find_by_sql("SELECT SUM(area_cosechada) as total_hectareas
                                            FROM productor
                                            INNER JOIN ruat ON productor_id=productor.id
                                            INNER JOIN producto ON ruat_id=ruat.id
                                            WHERE $filtro_renglon");
        $total_hectareas= $resultado[0]->total_hectareas;

        $promedio_hectareas=$total_hectareas/$cantidad_productores;


        $renglones = assoc(RenglonProductivo::sorted(), 'id', 'descripcion');
        $renglones = array('' => '(Todos)') + $renglones;

       /* $municipios = assoc(Municipio::sorted(), 'id', 'nombre');
        $municipios = array('' => '(Todos)') + $municipios;*/

        
        $this->twiggy->set('cantidad_hombres',  $cantidad_hombres);
        $this->twiggy->set('cantidad_mujeres', $cantidad_mujeres);
        $this->twiggy->set('promedio_hectareas', $promedio_hectareas);
        $this->twiggy->set('promedio_utilidad', $promedio_utilidad);
        $this->twiggy->set('promedio_egresos', $promedio_egresos);
        $this->twiggy->set('promedio_totaling', $promedio_totaling);
        $this->twiggy->set('promedio_ingagro', $promedio_ingagro);
        $this->twiggy->set('promedio_edad', $promedio_edad);
        $this->twiggy->template("reportes/estadisticascosecha");
        $this->twiggy->display();
    }
}