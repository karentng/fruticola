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
            var_dump($obj);
            $obj->set_attributes($data);
        }

        if($save) $obj->save();

        return $obj;
    }
}