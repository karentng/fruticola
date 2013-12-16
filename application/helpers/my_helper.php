<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(! function_exists('my_input'))
{
    function my_input($name, array $attrs=array())
    {
        $error = form_error($name);
        if(empty($attrs['class'])) $attrs['class']=''; 
        if(empty($attrs['type'])) $attrs['type']='text';

        $attrs['id'] = $attrs['name'] = $name;
        $attrs['value'] = set_value($name);

        $attrs['class'] .= ' form-control'. ($error?' error':'');
        $output = form_input($attrs);
        if($error) $output .= "<label class='error'>$error</label>";
        
        return $output;
    }



}