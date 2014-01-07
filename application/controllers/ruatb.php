<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RuatB extends CI_Controller {

    public function index($ruat_id)
    {
        if(!$ruat_id) die("Invalid URL");
        //check_profile($this,"Administrador");

        function to_array($model) { return $model->to_array(); }

        $combos = array();
        $combos['tiposTenencia']        = array_map('to_array',Tenencia::sorted());
        $combos['tiposServicio']        = array_map('to_array',TipoServicioPublico::sorted());
        $combos['tiposVia']             = array_map('to_array',TipoVia::sorted());
        $combos['tiposEstadoVia']       = array_map('to_array',TipoEstadoVia::sorted());
        $combos['tiposMedioTransporte'] = array_map('to_array',TipoMedioTransporte::sorted());
        $combos['tiposSemilla']         = array_map('to_array',TipoSemilla::sorted());
        $combos['tiposSitioVenta']      = array_map('to_array',TipoSitioVenta::sorted());
        $combos['tiposVende']           = array_map('to_array',TipoVende::sorted());
        $combos['tiposFormaPago']       = array_map('to_array',TipoFormaPago::sorted());
        $combos['tiposMaquinaria']      = array_map('to_array',TipoMaquinaria::sorted());
        //municipios del valle
        $combos['municipios'] = array_map('to_array',Municipio::find_all_by_departamento_id(30, array('select'=>'id,nombre' , 'order'=>'nombre')));
        
        $this->twiggy->set('ruat_id', $ruat_id);
        $ruat = Ruat::find($ruat_id);
        $finca = Finca::find_by_ruat_id($ruat_id);
        if($finca) {
            $scope = $this->cargar($ruat_id);
            $this->twiggy->set('scope', $scope);
        }

        $productor = $ruat->productor->to_array();
        $productor['nombre_completo'] = $ruat->productor->nombre_completo();
        $productor['tipo_documento']  = $ruat->productor->tipo_documento->descripcion;
        $this->twiggy->set('productor', $productor);
        $this->twiggy->set('combos', $combos);

        $this->twiggy->set('ruat_id', $ruat_id);

        $this->twiggy->template("ruat/ruatb");
        $this->twiggy->display();
    }

    public function guardar($ruat_id)
    {
        $input = json_decode(file_get_contents("php://input"));
        
        $ruat = Ruat::find($ruat_id);
        unset($input->finca->esFinca);
        $input->finca->ruat_id = $ruat->id;
        $input->finca->via_disponibilidad = $input->finca->via_disponibilidad ? 't' : 'f';
        if($input->finca->via_disponibilidad=='f') {
            $input->finca->via_tipo_id=null;
            $input->finca->via_estado_id=null;
        }
        $finca = Finca::create_or_update((array)$input->finca);

        FincaServicio::table()->delete(array('finca_id' => $finca->id));
        foreach($input->servicios as $s)
            FincaServicio::create(array('finca_id' => $finca->id, 'servicio_id' => $s));

        FincaTransporte::table()->delete(array('finca_id' => $finca->id));
        foreach($input->mediosTransporte as $mt)
            FincaTransporte::create(array('finca_id' => $finca->id, 'transporte_id' => $mt));

        FincaMaquinaria::table()->delete(array('finca_id' => $finca->id));
        foreach($input->maquinaria as $tipo => $maq)  if($maq->usa) {
            FincaMaquinaria::create(array('finca_id' => $finca->id, 
                'maquinaria_id' => $tipo, 'descripcion' => $maq->descripcion));
        }
        
        //borrar productos que se hallan removido en el formulario
        $conds = count($input->productos)
            ? array('ruat_id = ? AND id NOT IN (?)', $ruat_id, extract_prop($input->productos, 'id'))
            : array('ruat_id = ?', $ruat_id);

        Producto::delete_all($conds);

        
        foreach($input->productos as $p){
            $p->ruat_id = $ruat_id;
            $producto = Producto::get_or_create($p);

            /*
            $producto = new Producto();
            $producto->ruat_id = $ruat_id;
            $producto->nombre = $p->nombre;
            $producto->variedad = $p->variedad;

            $producto->semilla_certificada = $p->semillaCertif ? 't' : 'f';
            $producto->area_cosechada = $p->areaCosechada;
            $producto->prod_semestre_a = $p->semestreA;
            $producto->prod_semestre_b = $p->semestreB;
            $producto->prod_total = $p->semestreA + $p->semestreB;
            $producto->costo_establecimiento = $p->costoEstablecimiento;
            $producto->costo_sostenimiento = $p->costoSostenimiento;
            $producto->prod_mercado = $p->destMercado;
            $producto->prod_mercado_porcentaje = ($p->destMercado * 100)/($p->semestreA + $p->semestreB);
            $producto->sitio_venta_id = $p->sitioVenta;
            $producto->vende_tipo_id = $p->vende;
            $producto->vende_nombre = $p->nombreVende;
            $producto->precio_promedio = $p->precioVentaPromedio;

            $producto->forma_pago_id = $p->formaPago;
            $producto->subproducto = $p->subproducto;
            $producto->subproducto_uso = $p->usoSubproducto;

            $progAsistencia = $p->perteneceProgAsistencia ? 't' : 'f';
            if($progAsistencia === 't'){
                $producto->asistencia_programa = $p->progAsistencia;
                $producto->asistencia_entidad = $p->entidadAsistencia;
            }else{
                $producto->asistencia_programa = '';
                $producto->asistencia_entidad = '';
            }

            $producto->save();
            */
        }

        
                
        $response = array(
            'success'=>true, 
            'message'=> array('type'=>'success', 'text'=>'Guardado Exitoso'),
            'scope'=>$this->cargar($ruat->id)
        );

        echo json_encode($response);
    }


    function cargar($ruat_id, $do_echo=false)
    {
        $finca = Finca::find_by_ruat_id($ruat_id);
        $output = new StdClass;
        $output->finca = $finca->to_array();
        $output->servicios = extract_prop(FincaServicio::find_all_by_finca_id($finca->id),'servicio_id');
        $output->productos = array();
        $output->mediosTransporte = extract_prop(FincaTransporte::find_all_by_finca_id($finca->id), 'transporte_id');
        $output->maquinaria = array();
        foreach(FincaMaquinaria::find_all_by_finca_id($finca->id) as $m)
            $output->maquinaria[$m->maquinaria_id] = array('usa'=> true, 'descripcion'=>$m->descripcion);
        //foreach(TipoMaquinaria::all() as $t) $output->maquinaria[$t->id] = array('usa'=>false);

        //$output->residuos = array('ordinarios' => array('maneja' => false),
        //                          'peligrosos' => array('maneja' => false),
        //                          'otros'      => array('maneja' => false));
        /*
        productos: [{}],
            maquinaria: {},
            residuos: {
                ordinarios: { maneja: false },
                peligrosos: { maneja: false },
                otros: { maneja: false },
            }
        */
        if($do_echo) echo json_encode($output);
        return $output;
    }




}