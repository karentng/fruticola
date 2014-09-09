<?php

class MyModel extends ActiveRecord\Model
{
    public static function create_or_update(array $data, $save=true)
    {
        if(empty($data['id'])) {
            $obj = new static($data);
        }
        else {
            $obj = static::find($data['id']);
            $obj->set_attributes($data);
        }

        if($save) $obj->save();

        return $obj;
    }


    private function getRenglon()
    {
        try {
            $ruat = Ruat::find_by_id($this->ruat_id);
            $res = $ruat->productor->renglon_productivo_id;
            //die("PRIMERO");
            return $res;
        }
        catch(Exception $ex) {
            try { 
                $res = $this->productor->renglon_productivo_id; // es un ruat
                //die("SEGUNDO");
                return $res;
            }
            catch(Exception $ex) {
                return null;
            }
        }
    }


    public function soloLectura(&$controller)
    {
        if($controller->ion_auth->in_group('Administrador') || $controller->ion_auth->in_group('Coordinador')) {
            return false;
        }
        $user_id = current_user("id");
        if(in_array($user_id, array(5, 6, 8, 9, 10, 11, 13, 14) )) { // jefes de renglon
            $renglon = $this->getRenglon();
            if($user_id==5 && in_array($renglon, array(9,11))) return false; 
            if($user_id==6 && in_array($renglon, array(5,6))) return false;  
            if($user_id==8 && in_array($renglon, array(8,13))) return false;  
            if($user_id==9 && in_array($renglon, array(3))) return false;  
            if($user_id==10 && in_array($renglon, array(4,2))) return false;  
            if($user_id==11 && in_array($renglon, array(10))) return false;  
            if($user_id==13 && in_array($renglon, array(12))) return false;  
            if($user_id==14 && in_array($renglon, array(1,7))) return false;  
        }
        if($controller->ion_auth->in_group('Digitador')) {
            if($this->creador_id != current_user('id')) return true;
            
            $dt = new DateTime();
            $tm2 = $dt->getTimestamp();
            $tm1 = $this->creado->getTimestamp();
            $dif_horas = ($tm2-$tm1)/60.0/60.0;
            //return ($dif_horas>5.0);
            return false;
        }
        if($controller->ion_auth->in_group('Consultas')) {
            return true;
        }
        return true;
    }
}