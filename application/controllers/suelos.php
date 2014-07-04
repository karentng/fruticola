<?php

require_once dirname(__FILE__) . "/../third_party/PHPExcel/IOFactory.php";
class Suelos extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador", "Coordinador"));
    }


    public function index()
    {
        if(isset($_FILES["archivo_suelos"]) /*&& !empty($_FILES["archivo_suelos"]["name"])*/) {
            $upload_result = $this->do_upload();
            if(isset($upload_result['error'])) {
                $this->twiggy->set("notif", array('type'=>'error', 'text' => $upload_result['error']));
            }
            else {
                $inputFilename = $upload_result['upload_data']['full_path'];
                $errores = $this->procesarArchivo($inputFilename);
                if(count($errores)) {
                    $alerta_error = "Se han presentado errores en el procesamiento. Corrija el archivo y vuelva a intentar:";
                    foreach($errores as $mensaje => $_)
                        $alerta_error .= "<br>".$mensaje;
                    $this->twiggy->set("alerta_error", $alerta_error);
                }

                $this->twiggy->set("registros_procesados", $this->registros_procesados);
            }
        }

        $estudios = EstudioSuelo::all(array('include' => array('ruats', "municipio"), 'order' => 'id'));
        $this->twiggy->set("estudios", $estudios);
        $this->twiggy->template("suelos/suelos");
        $this->twiggy->display();
    }

    private function procesarArchivo($inputFilename)
    {
        function dbl($num)
        {
            if(!is_numeric($num)) return null;
            return (double)$num;
        }

        function hash_municipio($str) {
            $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
            return mb_strtolower(strtr(trim($str), $unwanted_array));
        }

        $columns = array(  // column name => is_number
            '-unused-' => NULL, // primera columna es ignorada
            'codigo_laboratorio' => false,
            'fecha_llegada' => false,
            'fecha_entrega' => false,
            'nombre_usuario' => false,
            'cedula' => false,
            'direccion' => false,
            'telefono' => false,
            'email' => false,
            'departamento' => NULL,
            'municipio_id' => false,
            'vereda'  => false,                  
            'finca'  => false,                   
            'altura'  => TRUE,                  
            'cultivo'  => false,                 
            'estado'  => false,                  
            'tiempo_establecido'  => false,      
            'identificacion_muestra'  => false,  
            'profundidad'  => TRUE,             
            'topografia'  => false,              
            'superficie'  => TRUE,              
            'drenaje'  => false,                 
            'riesgo'  => false,                  
            'fertilizantes'  => false,           
            'ultimo_cultivo'  => false,          
            'rendimiento'  => false,             
            'textura_tacto'  => false,           
            'interp_textura'  => false,          
            'ph_agua_suelo'  => TRUE,           
            'interp_ph'  => false,               
            'materia_organica'  => TRUE,        
            'interp_materia'  => false,          
            'fosforo'  => TRUE,                 
            'interp_fosforo'  => false,          
            'azufre'  => TRUE,                  
            'interp_azufre'  => false,           
            'acidez'  => TRUE,                  
            'aluminio'  => TRUE,                
            'interp_aluminio'  => false,         
            'calcio'  => TRUE,                  
            'interp_calcio'  => false,           
            'magnesio'  => TRUE,                
            'interp_magnesio'  => false,         
            'potasio'  => TRUE,                 
            'interp_potasio'  => false,          
            'sodio'  => TRUE,                   
            'interp_sodio'  => false,            
            'cice'  => TRUE,                    
            'cica'  => TRUE,                    
            'interp_cic'  => false,              
            'conductividad_electrica'  => TRUE, 
            'interp_conductividad'  => false,    
            'hierro'  => TRUE,                  
            'interp_hierro'  => false,           
            'cobre'  => TRUE,                   
            'interp_cobre'  => false,            
            'manganeso'  => TRUE,               
            'interp_manganeso'  => false,        
            'zinc'  => TRUE,                    
            'interp_zinc'  => false,             
            'boro'  => TRUE,                    
            'interp_boro'  => false,             
            'saturacion_calcio'  => TRUE,                       
            'interp_saturacion_calcio'  => false,                
            'saturacion_magnesio'  => TRUE,                     
            'interp_saturacion_magnesio'  => false,              
            'saturacion_potasio'  => TRUE,                      
            'interp_saturacion_potasio'  => false,               
            'saturacion_sodio'  => TRUE,                        
            'interp_saturacion_sodio'  => false,                 
            'saturacion_aluminio'  => TRUE,                     
            'interp_saturacion_aluminio'  => false,              
            'relacion_calcio_boro'  => TRUE,                    
            'interp_relacion_calcio_boro'  => false,             
            'relacion_calcio_magnesio'  => TRUE,                
            'interp_relacion_calcio_magnesio'  => false,         
            'relacion_magnesio_potasio'  => TRUE,               
            'interp_relacion_magnesio_potasio'  => false,        
            'relacion_calcio_potasio'  => TRUE,                 
            'interp_relacion_calcio_potasio'  => false,          
            'relacion_calcio_magnesio_potasio'  => TRUE,        
            'interp_relacion_calcio_magnesio_potasio'  => false, 
        );

        $municipios_ids = array();
        foreach(Municipio::find_all_by_departamento_id(30) as $mun)
            $municipios_ids[hash_municipio($mun->nombre)] = $mun->id;

        $municipios_ids["guacari"] = 384;
        $municipios_ids['buga'] = 129;

        $handle = fopen($inputFilename, "r");
        fgetcsv($handle, 5000); // ignore header
        for ($rowNumber=1; ($data = fgetcsv($handle, 5000)) !== FALSE; $rowNumber++) {
            $cnt = count($data);
            //assert($num==82, "Invalid column count at row $rowNumber");
            if($cnt!=82) {
                $errores["Linea numero $rowNumber es invalida ($cnt columnas)"] = true;
                continue;
            }

            $attributes = array();
            $column_index = 0; 
            foreach($columns as $field => $to_number) { 
                if($to_number!==null) { // if null, ignore column
                    $val = $data[$column_index];
                    if($to_number) $val = dbl($val);
                    $attributes[$field] = $val;
                }
                $column_index++;
            }
            $mun = $attributes['municipio_id'];
            //var_dump($attributes);
            //die();
            if($mun && empty($municipios_ids[hash_municipio($mun)])) {
                $errores["ERROR: no se reconoce municipio '$mun'. Revise que el nombre del municipio esté escrito correctamente (incluido tildes)"]=true;
                $attributes['municipio_id'] = null;
            }
            else $attributes['municipio_id'] = $municipios_ids[hash_municipio($mun)];

            $est = EstudioSuelo::find_by_codigo_laboratorio($attributes['codigo_laboratorio']);
            if(!$est) $est = new EstudioSuelo();
            $est->set_attributes($attributes);
            $est->save();

            if($attributes['cedula']) {
                $prod = Productor::find_by_numero_documento($attributes['cedula']);
                if($prod) {
                    $ruat = Ruat::find_by_productor_id($prod->id);
                    if($ruat)
                        RuatEstudioSuelo::asociar($ruat->id, $est->id);
                }
            }
            $this->registros_procesados++;
        }
    
        fclose($handle);
        return $errores;
    }

    private function do_upload()
    {
        $config['upload_path'] = './uploads/suelos/';
        $config['allowed_types'] = '*';
        $config['max_size'] = '10240';
        $config['overwrite'] = true;/// 10MiB

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('archivo_suelos'))
            return array('error' => $this->upload->display_errors());
        else
            return array('upload_data' => $this->upload->data());
    }


    public function asociar($estudio_id)
    {
        if(!$estudio_id) show_404();

        $estudio = EstudioSuelo::find_by_id($estudio_id);
        if(!$estudio) show_404();

        $nro_formulario = $this->input->post('numero_formulario');

        if($nro_formulario) {
            $ruat_asociar = null;
            $ruat_asociar = Ruat::find_by_numero_formulario($nro_formulario);
            if(!$ruat_asociar) {
                $prod = Productor::find_by_numero_documento($nro_formulario);
                if($prod) 
                    $ruat_asociar = Ruat::find_by_productor_id($prod->id);
            }

            if(!$ruat_asociar) {   
                $this->twiggy->set("notif", array('type'=>'error', 'text' => "No se encontró un Ruat con este número de formulario o cédula"));
            }

            else if(RuatEstudioSuelo::asociar($ruat_asociar->id, $estudio->id))
                $this->twiggy->set("notif", array('type'=>'success', 'text' => "Asociado exitosamente"));
            else
                $this->twiggy->set("notif", array('type'=>'warning', 'text' => "El RUAT ya se encuentra asociado a un estudio"));
        }
        
        $ruats = extract_prop($estudio->ruats, 'ruat');

        function num($x) {
            if($x===null) return "-";
            else return str_replace(".",",","".round($x,2));
        }
        $this->twiggy->register_filter('num');

        $this->twiggy->set("ruats", $ruats);
        $this->twiggy->set("estudio", $estudio);
        $this->twiggy->template("suelos/asociar");
        $this->twiggy->display();
    }

    public function desasociar($estudio_id, $ruat_id)
    {
        RuatEstudioSuelo::delete_all(array('conditions' => array('estudio_id = ?  AND  ruat_id = ?', $estudio_id, $ruat_id)));
        $this->session->set_flashdata("notif", array('type' => 'info', 'text' => 'Asociación removida'));
        redirect("suelos/asociar/$estudio_id");
    }
    
    public function imprimible($ruat_id)
    {
        $ruat = Ruat::find($ruat_id);
        $rel = RuatEstudioSuelo::find_by_ruat_id($ruat_id);
        if(!$rel) show_404();
        $estudio = EstudioSuelo::find($rel->estudio_id);
        if(!$estudio) show_404();
        

        $this->twiggy->set("rel",$rel);
        $this->twiggy->set("ruat",$ruat);
        $this->twiggy->set("estudio", $estudio);
        $this->twiggy->set("departamento", $estudio->municipio->departamento);
        $this->twiggy->set("municipio", $estudio->municipio);
        
//        var_dump($estudio->municipio->departamento->nombre);
        
        $this->twiggy->template("suelos/suelos_imprimible");
        $this->twiggy->display();
    }
}
