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
        $municipios_ids = array();
        foreach(Municipio::find_all_by_departamento_id(30) as $mun)
            $municipios_ids[strtoupper($mun->nombre)] = $mun->id;
        
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
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
                if(empty($municipios_ids[strtoupper($row[11])])) {
                    $errores["ERROR: no se reconoce municipio '".$row[11]."'. Revise que el nombre del municipio esté escrito correctamente (incluido tildes)"]=true;
                    continue;
                }
                
                $attr['municipio_id'] = $municipios_ids[strtoupper($row[11])];

                $attr['vereda'] = $row[12];
                $attr['finca'] = $row[13];
                $attr['altura'] = $row[14];
                $attr['cultivo'] = $row[15];
                $attr['estado'] = $row[16];
                $attr['tiempo_establecido'] = $row[17];
                $attr['identificacion_muestra'] = $row[18];
                $attr['profundidad'] = $row[19];
                $attr['topografia'] = $row[20];
                $attr['superficie'] = $row[21];
                $attr['drenaje'] = $row[22];
                $attr['riesgo'] = $row[23];
                $attr['fertilizantes'] = $row[24];
                $attr['ultimo_cultivo'] = $row[25];
                $attr['rendimiento'] = $row[26];
                $attr['textura_tacto'] = $row[27];
                $attr['ph_agua_suelo'] = $row[28];
                $attr['materia_organica'] = $row[29];
                $attr['fosforo'] = $row[30];
                $attr['azufre'] = $row[31];
                $attr['acidez'] = $row[32];
                $attr['aluminio'] = $row[33];
                $attr['calcio'] = $row[34];
                $attr['magnesio'] = $row[35];
                $attr['potasio'] = $row[36];
                $attr['sodio'] = $row[37];
                $attr['cice'] = $row[38];
                $attr['cica'] = $row[39];
                $attr['conductividad_electrica'] = $row[40];
                $attr['hierro'] = $row[41];
                $attr['cobre'] = $row[42];
                $attr['manganeso'] = $row[43];
                $attr['zinc'] = $row[44];
                $attr['boro'] = $row[45];
                
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
                $ruat_est = new RuatEstudioSuelo();
                $ruat_est->ruat_id = $ruat_asociar->id;
                $ruat_est->estudio_id = $estudio->id;
                $ruat_est->save();
                $this->twiggy->set("notif", array('type'=>'success', 'text' => "Asociado exitosamente"));
            }
        }


        $ruats = extract_prop($estudio->ruats, 'ruat');



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
}