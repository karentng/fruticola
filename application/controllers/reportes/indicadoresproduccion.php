<?php

class Indicadoresproduccion extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index() {

        function to_array($model) {
            return $model->to_array();
        }

        $municipios = array_map('to_array', Municipio::find_all_by_departamento_id(30, array('select' => 'id,nombre', 'order' => 'nombre')));
        $renglonesProductivos = array_map('to_array', RenglonProductivo::sorted());

        $this->twiggy->set('municipios', $municipios);
        $this->twiggy->set('renglonesProductivos', $renglonesProductivos);

        $this->twiggy->template("reportes/indicadoresproduccion");
        $this->twiggy->display();
    }

    public function tabla() {

        $renglon = $this->input->post('renglon_productivo');
        $municipios = $this->input->post('municipios');

        if (!$municipios)
            $municipios = array();

        $dato = $this->consultarDatos($renglon, $municipios);
        $this->twiggy->set('datos', $dato);

        $this->twiggy->register_function('number_format');
        $this->twiggy->template("reportes/indicadoresproducciontabla");
        $this->twiggy->display();
    }

    private function consultarDatos($renglon, $municipios = array()) {

        $arr_condiciones = array(
            ':renglon' => $renglon
        );

        $aux = array();
        foreach ($municipios as $i => $municipio) {
            $arr_condiciones[":municipio_{$i}"] = (int) $municipio['id'];
            $aux[] = "finca.municipio_id = :municipio_{$i}";
        }

        $where_municipio = '';
        if (!empty($aux)) {
            $where_municipio = ' AND ( ' . implode(' OR ', $aux) . ' )';
        }

        ///Consulto los datos de los productos
        $query = "SELECT             
            SUM(producto.area_cosechada) AS total_area_cosechada,
            AVG(producto.area_cosechada) AS avg_area_cosechada,

            SUM(producto.prod_semestre_a) AS total_semestre_a,
            SUM(producto.prod_semestre_b) AS total_semestre_b,            

            SUM(producto.costo_establecimiento) AS total_costo_establecimiento,
            AVG(producto.costo_establecimiento) AS avg_costo_establecimiento,

            SUM(producto.costo_sostenimiento) AS total_costo_sostenimiento,
            AVG(producto.costo_sostenimiento) AS avg_costo_sostenimiento,

            SUM(producto.prod_mercado) AS total_prod_mercado,
            AVG(producto.prod_mercado) AS avg_prod_mercado,

            AVG(producto.precio_promedio) AS avg_precio_promedio,
            
            COUNT(DISTINCT producto.id) AS numero_productos

            FROM finca
            JOIN ruat ON finca.ruat_id = ruat.id
            JOIN productor ON ruat.productor_id = productor.id
            JOIN producto ON (ruat.id = producto.ruat_id)
            WHERE productor.renglon_productivo_id=:renglon $where_municipio";

        $result = Ruat::connection()->query($query, $arr_condiciones);

        $dato = array();
        foreach ($result as $row) {
            $dato = array_merge($dato, $row);
            
            $dato['total_produccion'] = $row['total_semestre_a'] + $row['total_semestre_b'];
            
            $dato['total_rendimiento'] = $dato['avg_rendimiento'] = $dato['avg_produccion'] = 0;

            if ($row['total_area_cosechada']){
                $dato['total_rendimiento'] = $dato['total_produccion'] / $row['total_area_cosechada'];
            }

            if ($row['numero_productos']){               
                $dato['avg_rendimiento'] = $dato['total_rendimiento'] / $row['numero_productos'];
                $dato['avg_produccion'] = $dato['total_produccion'] / $row['numero_productos'];
            }
        }       
        

        ///Consulto los datos de las fincas
        $query = "SELECT 
            SUM(finca.area_total) AS total_area_fincas,
            AVG(finca.area_total) AS avg_area_fincas,
            
            COUNT(DISTINCT productor.id) AS numero_productores

            FROM finca
            JOIN ruat ON finca.ruat_id = ruat.id
            JOIN productor ON ruat.productor_id = productor.id
            WHERE productor.renglon_productivo_id=:renglon $where_municipio";

        $result = Ruat::connection()->query($query, $arr_condiciones);

        foreach ($result as $row) {
            $dato = array_merge($dato, $row);
        }
        
        $id_egresos = 25;
        $id_ingresos = 19;
        
         ///Consulto los datos de las fincas
        $query = "SELECT 
            AVG(ingresos.valor / egresos.valor) avg_costo_beneficio
            FROM finca
            JOIN ruat ON finca.ruat_id = ruat.id
            JOIN productor ON ruat.productor_id = productor.id
            JOIN visita_tipo_productor vtp ON ruat.id = vtp.ruat_id
            JOIN tp_c_respuesta ingresos ON vtp.id = ingresos.visita_id
            JOIN (
                SELECT 
                visita_id,
                valor
                FROM finca
                JOIN ruat ON finca.ruat_id = ruat.id
                JOIN productor ON ruat.productor_id = productor.id
                JOIN visita_tipo_productor vtp ON ruat.id = vtp.ruat_id
                JOIN tp_c_respuesta ON vtp.id = tp_c_respuesta.visita_id
                WHERE tp_c_respuesta.pregunta_c_id = $id_egresos
            ) egresos ON egresos.visita_id = ingresos.visita_id
            WHERE ingresos.pregunta_c_id = $id_ingresos AND productor.renglon_productivo_id=:renglon $where_municipio";

        $result = Ruat::connection()->query($query, $arr_condiciones);

        foreach ($result as $row) {
            $dato = array_merge($dato, $row);
        }
        

        return $dato;
    }

}
