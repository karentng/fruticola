<?php

class MyModel extends ActiveRecord\Model
{
    public static function create_or_update(array $data, $save=true)
    {
        if(empty($data['id'])) {
            $algo = self;
            $obj = new static($data);
        }
        else {
            $obj = static::find($data[id]);
            $obj->set_attributes($data);
        }

        if($save) $obj->save();

        return $obj;
    }

    public function soloLectura(&$controller)
    {
        if($controller->ion_auth->in_group('Administrador') || $controller->ion_auth->in_group('Coordinador')) {
            return false;
        }
        if($controller->ion_auth->in_group('Digitador')) {
            if($this->creador_id != current_user('id')) return true;
            
            $dt = new DateTime();
            $tm2 = $dt->getTimestamp();
            $tm1 = $this->creado->getTimestamp();
            $dif_horas = ($tm2-$tm1)/60.0/60.0;
            return ($dif_horas>5.0);
        }
        return true;
    }
}