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
    
    public function tabla(){
        
        $renglon = 1;
        
        $datos = $this->consultarDatos($renglon);
        foreach ($datos as $dato) {
//            var_dump($dato);
            $this->twiggy->set('datos', $dato);
        }
        
        $this->twiggy->template("reportes/indicadoresproducciontabla");
        $this->twiggy->display();
    }

    private function consultarDatos($renglon, $municipios = array()) {


        $arr_condiciones = array(
            ':renglon' => $renglon
        );

        $aux = array();
        foreach ($municipios as $i => $municipio) {
            $arr_condiciones[":municipio_{$i}"] = $municipio;
            $aux[] = "finca.municipio_id = :municipio_{$i}";
        }

        $where_municipio = '';
        if (!empty($aux)) {
            $where_municipio = ' AND ( ' . implode(' OR ', $aux) . ' )';
        }

        $query = "SELECT 
            SUM(finca.area_total) AS total_area_fincas,
            AVG(finca.area_total) AS avg_area_fincas,

            SUM(producto.area_cosechada) AS total_area_cosechada,
            AVG(producto.area_cosechada) AS avg_area_cosechada,

            SUM(producto.prod_total) AS total_produccion,
            AVG(producto.prod_total) AS avg_produccion,

            SUM(producto.costo_establecimiento) AS total_costo_establecimiento,
            AVG(producto.costo_establecimiento) AS avg_costo_establecimiento,

            SUM(producto.costo_sostenimiento) AS total_costo_sostenimiento,
            AVG(producto.costo_sostenimiento) AS avg_costo_sostenimiento,

            SUM(producto.prod_mercado) AS total_prod_mercado,
            AVG(producto.prod_mercado) AS avg_prod_mercado,

            AVG(producto.precio_promedio) AS avg_precio_promedio

            FROM finca
            JOIN ruat ON finca.ruat_id = ruat.id
            JOIN productor ON ruat.productor_id = productor.id
            LEFT JOIN producto ON (ruat.id = producto.ruat_id)
            WHERE productor.renglon_productivo_id=:renglon $where_municipio";


        return Ruat::connection()->query($query, $arr_condiciones);
    }

}
