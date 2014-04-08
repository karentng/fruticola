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

    public function index2()
    {
        //header("Content-Type: text/plain");
        $inputFilename="/var/www/file.xlsx";
        $errores = $this->procesarArchivo($inputFilename);
        if(count($errores)) {
            echo "Se han presentado errores en el procesamiento. Corrija el archivo y vuelva a intentar:\n";
            foreach($errores as $mensaje => $_)
                echo $mensaje;
        }
    }

    private function procesarArchivo($inputFileName)
    {
        set_time_limit(1200);
        setlocale(LC_CTYPE, 'es_ES.UTF8');

        function dbl($num)
        {
            if(!is_numeric($num)) return null;
            return (double)$num;
        }

        $municipios_ids = array();
        foreach(Municipio::find_all_by_departamento_id(30) as $mun)
            $municipios_ids[mb_strtolower($mun->nombre)] = $mun->id;
        
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch(Exception $e) {
            $err = 'Error cargando archivo "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage(); 
            return array($err => true);
        }

        $sheet = $objPHPExcel->getSheet(0); 
        $highestRow = $sheet->getHighestRow(); 
        $highestColumn = $sheet->getHighestColumn();

        $errores = array();
        $this->registros_procesados = 0;
        for ($cnt = 1; $cnt <= $highestRow; $cnt++) { 
            /*
            if($cnt%100==0) {
                    unset($sheet);
                    $objPHPExcel->disconnectWorksheets();
                    unset($objPHPExcel);
                    unset($objReader);
                    gc_collect_cycles();
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objReader->setReadDataOnly(true);
                    $objPHPExcel = $objReader->load($inputFileName);
                    $sheet = $objPHPExcel->getSheet(0); 
            }
            */

            //  Read a row of data into an array
            $row = $sheet->rangeToArray('A' . $cnt . ':' . $highestColumn . $cnt,
                                            NULL,
                                            TRUE,
                                            FALSE);
            $row = $row[0];
            //  Insert row data array into your database of choice here
            if(is_numeric($row[1]) && $row[2]) {
                $attr = array();
                $attr['codigo_laboratorio'] = $row[2];
                $attr['fecha_llegada'] = PHPExcel_Style_NumberFormat::toFormattedString($row[3], "YYYY-MM-DD");
                $attr['fecha_entrega'] = PHPExcel_Style_NumberFormat::toFormattedString($row[4], "YYYY-MM-DD");
                $attr['nombre_usuario'] = $row[5];
                $attr['cedula'] = $row[6];
                $attr['direccion'] = $row[7];
                $attr['telefono'] = $row[8];
                $attr['email'] = $row[9];
                //$attr['departamento'] = $row[10];
                if(empty($row[11]) || $row[11]=='NO INDICA') {
                    $attr['municipio_id'] = null;
                }
                else {
                    if(empty($municipios_ids[mb_strtolower($row[11])])) {
                        $errores["ERROR: no se reconoce municipio '".$row[11]."'. Revise que el nombre del municipio esté escrito correctamente (incluido tildes)"]=true;
                        continue;
                    }
                    $attr['municipio_id'] = $municipios_ids[mb_strtolower($row[11])];
                }
                $attr['vereda']                  = ($row[12]);
                $attr['finca']                   = ($row[13]);
                $attr['altura']                  = dbl($row[14]);
                $attr['cultivo']                 = ($row[15]);
                $attr['estado']                  = ($row[16]);
                $attr['tiempo_establecido']      = ($row[17]);
                $attr['identificacion_muestra']  = ($row[18]);
                $attr['profundidad']             = dbl($row[19]);
                $attr['topografia']              = ($row[20]);
                $attr['superficie']              = dbl($row[21]);
                $attr['drenaje']                 = ($row[22]);
                $attr['riesgo']                  = ($row[23]);
                $attr['fertilizantes']           = ($row[24]);
                $attr['ultimo_cultivo']          = ($row[25]);
                $attr['rendimiento']             = ($row[26]);
                $attr['textura_tacto']           = ($row[27]);
                $attr['interp_textura']          = ($row[28]);
                $attr['ph_agua_suelo']           = dbl($row[29]);
                $attr['interp_ph']               = ($row[30]);
                $attr['materia_organica']        = dbl($row[31]);
                $attr['interp_materia']          = ($row[32]);
                $attr['fosforo']                 = dbl($row[33]);
                $attr['interp_fosforo']          = ($row[34]);
                $attr['azufre']                  = dbl($row[35]);
                $attr['interp_azufre']           = ($row[36]);
                $attr['acidez']                  = dbl($row[37]);
                $attr['aluminio']                = dbl($row[38]);
                $attr['interp_aluminio']         = ($row[39]);
                $attr['calcio']                  = dbl($row[40]);
                $attr['interp_calcio']           = ($row[41]);
                $attr['magnesio']                = dbl($row[42]);
                $attr['interp_magnesio']         = ($row[43]);
                $attr['potasio']                 = dbl($row[44]);
                $attr['interp_potasio']          = ($row[45]);
                $attr['sodio']                   = dbl($row[46]);
                $attr['interp_sodio']            = ($row[47]);
                $attr['cice']                    = dbl($row[48]);
                $attr['cica']                    = dbl($row[49]);
                $attr['interp_cic']              = ($row[50]);
                $attr['conductividad_electrica'] = dbl($row[51]);
                $attr['interp_conductividad']    = ($row[52]);
                $attr['hierro']                  = dbl($row[53]);
                $attr['interp_hierro']           = ($row[54]);
                $attr['cobre']                   = dbl($row[55]);
                $attr['interp_cobre']            = ($row[56]);
                $attr['manganeso']               = dbl($row[57]);
                $attr['interp_manganeso']        = ($row[58]);
                $attr['zinc']                    = dbl($row[59]);
                $attr['interp_zinc']             = ($row[60]);
                $attr['boro']                    = dbl($row[61]);
                $attr['interp_boro']             = ($row[62]);
                
                $attr['saturacion_calcio']                       = dbl($row[63]);
                $attr['interp_saturacion_calcio']                = ($row[64]);
                $attr['saturacion_magnesio']                     = dbl($row[65]);
                $attr['interp_saturacion_magnesio']              = ($row[66]);
                $attr['saturacion_potasio']                      = dbl($row[67]);
                $attr['interp_saturacion_potasio']               = ($row[68]);
                $attr['saturacion_sodio']                        = dbl($row[69]);
                $attr['interp_saturacion_sodio']                 = ($row[70]);
                $attr['saturacion_aluminio']                     = dbl($row[71]);
                $attr['interp_saturacion_aluminio']              = ($row[72]);
                $attr['relacion_calcio_boro']                    = dbl($row[73]);
                $attr['interp_relacion_calcio_boro']             = ($row[74]);
                $attr['relacion_calcio_magnesio']                = dbl($row[75]);
                $attr['interp_relacion_calcio_magnesio']         = ($row[76]);
                $attr['relacion_magnesio_potasio']               = dbl($row[77]);
                $attr['interp_relacion_magnesio_potasio']        = ($row[78]);
                $attr['relacion_calcio_potasio']                 = dbl($row[79]);
                $attr['interp_relacion_calcio_potasio']          = ($row[80]);
                $attr['relacion_calcio_magnesio_potasio']        = dbl($row[81]);
                $attr['interp_relacion_calcio_magnesio_potasio'] = ($row[82]);
                
                $est = EstudioSuelo::find_by_codigo_laboratorio($attr['codigo_laboratorio']);
                if(!$est) $est = new EstudioSuelo();
                $est->set_attributes($attr);
                $est->save();

                
                $this->registros_procesados++;
                
            }
        }
        return $errores;
    }


    private function do_upload()
    {
        $config['upload_path'] = './uploads/suelos/';
        $config['allowed_types'] = 'xls|xlsx';
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
            $ruat_asociar = Ruat::find_by_numero_formulario($nro_formulario);
            if(!$ruat_asociar)
                $this->twiggy->set("notif", array('type'=>'error', 'text' => "Ruat con número $nro_formulario no encontrado"));
            
            else if(RuatEstudioSuelo::find_by_ruat_id($ruat_asociar->id))
                $this->twiggy->set("notif", array('type'=>'warning', 'text' => "El RUAT ya se encuentra asociado a un estudio"));
            else {
                $prev = RuatEstudioSuelo::find_by_estudio_id($estudio->id, array('order' => 'numero DESC'));
                $numero = $prev ? $prev->numero+1 : 1;
                $ruat_est = new RuatEstudioSuelo();
                $ruat_est->ruat_id = $ruat_asociar->id;
                $ruat_est->estudio_id = $estudio->id;
                $ruat_est->numero = $numero;
                $ruat_est->save();
                $this->twiggy->set("notif", array('type'=>'success', 'text' => "Asociado exitosamente"));
            }
        }
        elseif($this->input->post('accion')=='guardar_observacion') {
            $estudio->observacion=$this->input->post('observacion');
            $estudio->save();
            $this->twiggy->set("guardado_observacion", true);
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
    
    public function imprimible($estudio_id)
    {
        if(!$estudio_id) show_404();
        
        $estudio = EstudioSuelo::find_by_id($estudio_id);
        if(!$estudio) show_404();
        
        $this->twiggy->set("estudio", $estudio);
        $this->twiggy->set("departamento", $estudio->municipio->departamento);
        $this->twiggy->set("municipio", $estudio->municipio);
        
//        var_dump($estudio->municipio->departamento->nombre);
        
        $this->twiggy->template("suelos/suelos_imprimible");
        $this->twiggy->display();
    }
}
