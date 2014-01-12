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


    /* 
     * Ejemplos (Sintaxis Twiggy):
     *  my_select('sexo', {'':'-', 'F':'Femenino', 'M':'Masculino'})
     *  my_select('ciudad', dict_ciudades, {filter:true, placeholder:'Seleccione la ciudad'})
     *  my_select('servicios', $servicios, {multiple:true})
     */
    function my_select($name, $options, array $attrs=array())
    {
        $error = form_error($name);
        if(empty($attrs['class'])) $attrs['class']='';

        $attrs['id'] = $attrs['name'] = $name;
        $attrs['class'] .= ' select2 form-control'. ($error?' error':'');
        if(isset($attrs['filter']))
        {
            $attrs['class'].=' with-filter';
            unset($attrs['filter']);
        }
        else
            $attrs['class'].=' without-filter';

        if(isset($attrs['placeholder']))
        {
            $attrs['data-placeholder'] = $attrs['placeholder']; // used by select2 plugin
            unset($attrs['placeholder']);
        }

        $extra = "";
        foreach($attrs as $key => $val) $extra.=" $key='$val'";

        $options = array(''=>'')+$options;
        $val = set_value($name);
        if($val==="") $val=-123456; //evitar seleccion de la opcion vacia



        $output = form_dropdown($name, $options, $val, $extra);

        if($error) $output .= "<label class='error'>$error</label>";
        
        return $output;
    }


    

    if(!isset($_current_user_))
        $_current_user_=null;

    function current_user($prop=null)
    {
        global $_current_user_;

        if(empty($_current_user_))
        {
            $CI =& get_instance();
            $_current_user_= $CI->ion_auth->user()->row();
        }
        if($prop==null) return $_current_user_;
        else return $_current_user_->$prop;
    }

    function check_profile(& $controller, $profile1, $profile2=null, $profile3=null)
    {
        if(!$controller->ion_auth->logged_in()) 
            redirect("auth/login");

        if(!current_user()->active)
            show_error('Su usuario se encuentra desactivado. Consulte con el administrador.');

        if($controller->ion_auth->in_group($profile1)) return;

        if($profile2 && $controller->ion_auth->in_group($profile2)) return;
        
        if($profile3 && $controller->ion_auth->in_group($profile3)) return;


        show_error('No tiene acceso a esta seccion del sistema. Consulte al administrador.');
    }


    function assoc(array $arreglo, $keyField="id", $valueField="descripcion")
    {
        $res = array();
        foreach($arreglo as $obj)
            $res[$obj->$keyField] = $obj->$valueField;
        return $res;
    }

    function extract_prop($objs, $prop) {
        $res = array();
        if(is_array($objs[0]))
            foreach($objs as $obj) 
                $res[] = $obj[$prop];
        else
            foreach($objs as $obj) 
                $res[] = $obj->$prop;
        return $res;
    }

    function ruat_breadcrumbs($step, $ruat_id)
    {
        $output = "<li>RUAT</li>";
        $cond = array('ruat_id'=> $ruat_id ?: "0");
        $linkA = site_url("ruata/index/$ruat_id");
        $linkB = $ruat_id ? site_url("ruatb/index/$ruat_id") : null;
        $linkC = Finca::exists($cond) ? site_url("ruatc/index/$ruat_id") : null;
        $linkD = AprendizajeRespuesta::exists($cond) ? site_url("ruatd/index/$ruat_id") : null;

        $sections = array(
            array('step'=>1, 'title'=>'Sección A', 'link' => $linkA),
            array('step'=>2, 'title'=>'Sección B', 'link' => $linkB),
            array('step'=>3, 'title'=>'Sección C', 'link' => $linkC),
            array('step'=>4, 'title'=>'Sección D', 'link' => $linkD),
        );


        foreach($sections as $section)
            if($step==$section['step'])
                $output .= "<li><strong class='text-success'>$section[title]</strong></a>";
            elseif($section['link'])
                $output .= "<li><a href='$section[link]'>$section[title]</a></li>";

        return $output;
    }
}