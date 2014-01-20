<?php

class Ruat extends MyModel
{
    static $table_name = "ruat";

    static $belongs_to = array(
        array('productor', 'class_name'=>'Productor', 'foreign_key'=>'productor_id'),
        array('asociado', 'class_name'=>'PersonaAsociada', 'foreign_key'=>'asociado_id'),
        array('seguir', 'class_name'=>'PersonaAsociada', 'foreign_key'=>'seguir_id'),
        array('creador', 'class_name'=>'Usuario', 'foreign_key'=>'creador_id'),
    );

    static $has_one = array(
        array('observacion', 'class_name'=>'Observacion', 'foreign_key' => 'ruat_id'),
        array('bpa', 'class_name'=>'BuenasPracticas', 'foreign_key' => 'ruat_id'),
        array('cosecha', 'class_name'=>'Cosecha', 'foreign_key' => 'ruat_id'),
        array('visita_tipo_productor', 'class_name'=>'VisitaTipoProductor', 'foreign_key' => 'ruat_id'),
        array('finca', 'class_name'=>'Finca', 'foreign_key' => 'ruat_id'),
    );

    public function eliminar()
    {
        function cond($field, $value) {
            $res = array('conditions' => array());
            $res['conditions'][$field] = $value;
        }

        //eliminar finca
        if($this->finca) {
            FincaMaquinaria::delete_all(cond('finca_id', $this->finca->id));
            FincaServicio::delete_all(cond('finca_id', $this->finca->id));
            FincaTransporte::delete_all(cond('finca_id', $this->finca->id));
            $this->finca->delete();
        }

        //eliminar orgs asociadas
        $orgs_ids = extract_prop(Orgasociada::find_all_by_ruat_id($this->id), 'id');
        if(count($orgs_ids)) {
            OrgasociadaClase::delete_all(array('conditions' => array('orgasociada_id IN (?)',$orgs_ids)));
            OrgasociadaBeneficio::delete_all(array('conditions' => array('orgasociada_id IN (?)',$orgs_ids)));
        }
        OrgAsociada::delete_all(cond('ruat_id', $this->id));

        //eliminar productos, procesos innovacion, razones no pertenecer
        Producto::delete_all(cond('ruat_id', $this->id));
        Innovacion::delete_all(cond('ruat_id', $this->id));
        RazonNoPertenecer::delete_all(cond('ruat_id', $this->id));


        

        //eliminar aprendizaje
        AprendizajeRespuesta::delete_all(array('conditions' => array('ruat_id' => $this->id)));

        //eliminar observacion
        if($this->observacion) $this->observacion->delete();

        //eliminar bpa
        if($this->bpa) $this->bpa->eliminar();

        //eliminar cosecha
        if($this->cosecha) $this->cosecha->eliminar();

        //eliminar visita tipo productor
        if($this->visita_tipo_productor) $this->visita_tipo_productor->eliminar();


        //eliminar ruat como tal
        $this->delete();

        if($this->asociado) $this->asociado->delete(); //ya se pueden eliminar estos
        if($this->seguir) $this->seguir->delete();

        //elimnar productor

        if($this->productor->contacto) $this->productor->contacto->delete();
        if($this->productor->economia) $this->productor->economia->delete();
        $this->productor->delete();
    }
}