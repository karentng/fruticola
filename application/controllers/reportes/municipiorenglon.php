<?php

class MunicipioRenglon extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador", "Coordinador", "Consultas"));
    }



    public function index()
    {
        $total = 0;

        $renglones = RenglonProductivo::sorted();
        $municipios = Municipio::find('all', array('conditions' => array('departamento_id = ?', 30), 'order' => 'nombre'));

        $tabla = array();
        foreach($municipios as $i => $_)
            foreach($renglones as $j => $_)
                $tabla[$i][$j] = 0;

        //die(json_encode($tabla));

        $totalMunicipios = array_fill(0, count($municipios), 0);
        $totalRenglones = array_fill(0, count($renglones), 0);

        $indexMunicipio = array();
        foreach($municipios as $idx => $mun) $indexMunicipio[$mun->id] = $idx;
        $indexRenglon = array();
        foreach($renglones as $idx => $ren) $indexRenglon[$ren->id] = $idx;

        $sql = "
            select F.municipio_id, P.renglon_productivo_id, count(R.id) as conteo
            from ruat R join finca F on F.ruat_id=R.id
            join productor P on P.id=R.productor_id
            group by F.municipio_id, P.renglon_productivo_id
        ";

        $dbres = $this->db->query($sql);
        foreach($dbres->result() as $row) {
            $mun = $indexMunicipio[$row->municipio_id];
            $ren = $indexRenglon[$row->renglon_productivo_id];
            $tabla[$mun][$ren] = $row->conteo;
            $totalRenglon[$ren] += $row->conteo;
            $totalMunicipios[$mun] += $row->conteo;
            $total += $row->conteo;
        }

        $this->twiggy->set('renglones', $renglones);
        $this->twiggy->set('municipios', $municipios);
        $this->twiggy->set('info', $tabla);
        $this->twiggy->set('total', $total);
        $this->twiggy->set('totalM', $totalMunicipios);
        $this->twiggy->set('totalR', $totalRenglon);

        $this->twiggy->set('infoJSON', json_encode($tabla));
        $aux = array();
        $aux2 = array();
        foreach($renglones as $reng){
            array_push($aux, $reng->to_array());
        }
        foreach($municipios as $mun){
            array_push($aux2, $mun->to_array());
        }
        $this->twiggy->set('renglonesJSON', json_encode($aux));
        $this->twiggy->set('municipiosJSON', json_encode($aux2));
        $this->twiggy->set('totalRenglonJSON', json_encode($totalRenglon));

        $this->twiggy->template("reportes/municipiorenglon");
        $this->twiggy->display();
    }
}