<?php

class EstadisticasCosecha extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }
    
    public static function to_array($model){
        return $model->to_array();
    }

    public function index() {

        $renglones = array_map(array('EstadisticasCosecha', 'to_array'), RenglonProductivo::sorted());
        $municipios = array_map(array('EstadisticasCosecha', 'to_array'), Municipio::find_all_by_departamento_id(30, array('select' => 'id,nombre', 'order' => 'nombre')));
        $pregunta = array_map(array('EstadisticasCosecha', 'to_array'), CosechaPregunta::sorted());


        $this->twiggy->set('renglones', $renglones);
        $this->twiggy->set('municipios', $municipios);
        $this->twiggy->set('preguntas', $pregunta);

        $this->twiggy->template("reportes/estadisticascosecha");
        $this->twiggy->display();
    }

    public function data(){
        $renglones= $this->input->post('renglones');
        $municipios= $this->input->post('municipios');
        $pregunta= $this->db->escape($this->input->post('pregunta'));

        $filtro_renglones = " ";
        $filtro_municipios = " ";
        //var_dump($renglones);

        if($renglones and $renglones) $filtro_renglones = "and P.renglon_productivo_id in (". $renglones .")";
        if($municipios) $filtro_municipios = "and F.municipio_id in (". $municipios .")";
        $filtro_pregunta = "and O.pregunta_id=$pregunta";

        $sql = "
            select O.letra, O.texto, count(CR.id) as conteo
            from cosecha_opcionrespuesta O  
            left join cosecha_respuesta CR on CR.opcion_id=O.id
            join cosecha C ON CR.cosecha_id=C.id
            join ruat R on C.ruat_id=R.id
            join finca F on F.ruat_id=R.id
            join productor P on R.productor_id=P.id
            where true $filtro_municipios $filtro_renglones $filtro_pregunta
            group by O.letra, O.texto
            order by O.letra
        ";

        $query = $this->db->query($sql);
        $data = array();
        foreach($query->result() as $row) {
            $data[] = array('clase' => $row->texto, 'valor' => (int)$row->conteo);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));


    }

}
