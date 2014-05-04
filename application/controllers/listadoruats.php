 <?php


class ListadoRuats extends CI_Controller {

    public function index()
    {
        check_profile(array("Administrador", "Coordinador", "Digitador", "Consultas"));
        $this->twiggy->set("puedeCrearRuats", Ruat::puedeCrear());
        $this->twiggy->template("ruat/listadoruats");
        $this->twiggy->display();
    }

    public function eliminar_ruat($ruat_id=NULL) {
        if(!$ruat_id) show_404();
        check_profile(array("Administrador", "Coordinador"));

        Ruat::find($ruat_id)->eliminar();
        
        echo json_encode(array('type'=>'success', 'text' => 'RUAT eliminado exitosamente'));
    }

    
    public function datatable()
    {
        check_profile(array("Administrador", "Coordinador", "Digitador","Consultas"));

        $offset    = $this->input->get('iDisplayStart');
        $limit     = $this->input->get('iDisplayLength');
        $search    = $this->input->get('sSearch');
        $order_idx = (int)$this->input->get('iSortCol_0');
        $order_dir = $this->input->get('sSortDir_0');
        $limit     = $this->input->get('iDisplayLength');
        $limit     = $this->input->get('iDisplayLength');
        $sEcho     = (int)$this->input->get('sEcho');

        $numTotalResults = ViewListadoRuats::count();
        $options = array();
       
        if($search) {
            $options['conditions'] = array('numero_formulario = ? or nombre_productor ilike ? or creado::text like ? or ingresado_por ilike ?',
                $search, "%$search%", "%$search%", "%$search%");
            $numFilteredResults = ViewListadoRuats::count($options);
        }
        else {
            $numFilteredResults = $numTotalResults;
        }

        if($order_idx>3 || ($order_dir!='asc' && $order_dir!='desc')) show_404();

        $orders = array('numero_formulario','nombre_productor','creado','ingresado_por');
        $options['offset'] = $offset;
        $options['limit']  = $limit;
        $options['order'] = $orders[$order_idx].' '.$order_dir;

        $puedeEliminar = check_profile(array("Administrador","Coordinador"), false);
        $puedeCrearForms = check_profile(array("Administrador","Coordinador","Digitador"),false);

        $puedeVerEstudioSuelo = $puedeEliminar;

        $rows = array();
        foreach(ViewListadoRuats::all($options) as $item) {
            $url = site_url("ruata/index/".$item->id);
            $actions = "<div class='btn-group'>".
                "<a class='btn btn-xs btn-warning tip' href='$url' title='Registro Único de Usuarios de Asitencia Técnica'>RUAT <i class='i-arrow-right-3'></i></a>";

            $url = site_url("ruatImprimible/index/".$item->id);
            $actions .= "<a class='btn btn-xs btn-info tip' href='$url' title='Versión Imprimible RUAT' target='_blank'><i class='i-print'></i></a>";

            /*if($item->ruta_formulario) {
                $url = site_url("uploads/". $item->ruta_formulario);
                $actions .= "<a class='btn btn-xs btn-info tip' title='Descargar RUAT Escaneado' href='$url' target='_blank'><i class='i-file-download'></i></a>";
            }
            else $actions .= '<a class="btn btn-xs" disabled="disabled"><i class="i-file-download"></i></a>';*/

            $actions .="</div>&nbsp;";

            
            $cls = $item->cosecha_id ? 'btn-warning' : 'btn-default';
            if($puedeCrearForms || $item->cosecha_id) {
                $url = site_url("diagnosticosecha/index/$item->id");
                $url2 = site_url("cosechaImprimible/index/$item->id");
                $disabled = '';
            } else {
                $url = "";
                $url2="";
                $disabled = 'disabled="disabled"';
            }

            if($item->cosecha_id){
                $disabled1 = '';
            } else {
                $disabled1 = 'disabled';
            }

            $actions .= "<div class='btn-group'>";
            $actions .= " <a class='btn btn-xs $cls tip' href='$url' $disabled title='Diagnóstico Manejo de Cosecha'>Cosecha <i class='i-arrow-right-3'></i></a>";
            $actions .= "<a class='btn btn-xs btn-info tip' href='$url2' $disabled1 title='Versión Imprimible Cosecha' target='_blank'><i class='i-print'></i></a>";
            $actions .="</div>&nbsp;";


            $cls = $item->bpa_id ? 'btn-warning' : 'btn-default';
            if($puedeCrearForms || $item->bpa_id) {
                $url = site_url("bpa/index/$item->id");
                $disabled = '';
            } else {
                $url = "";
                $disabled = 'disabled="disabled"';
            }

            $actions .= "<a class='btn btn-xs $cls tip' href='$url' $disabled title='Buenas Prácticas Agropecuarias'>BPA</a> ";
            
            $cls = $item->vtp_id ? 'btn-warning' : 'btn-default';
            if($puedeCrearForms || $item->vtp_id) {
                $url = site_url("vtp/index/$item->id");
                $url3 = site_url("tipoProductorImprimible/index/$item->id");
                $disabled = '';
            } else {
                $url = "";
                $url3 = "";
                $disabled = 'disabled="disabled"';
            }

            if($item->vtp_id){
                $disabled1 = '';
            } else {
                $disabled1 = 'disabled';
            }

            $actions .= "<div class='btn-group'>";
            $actions .= " <a class='btn btn-xs $cls tip' href='$url' $disabled title='Clasificación Productor'>C. Prod<i class='i-arrow-right-3'></i></a>";
            $actions .= "<a class='btn btn-xs btn-info tip' href='$url3' $disabled1 title='Versión Imprimible C.Tipo Productor' target='_blank'><i class='i-print'></i></a>";
            $actions .="</div>&nbsp;";

            $cls = $item->postcosecha_id ? 'btn-warning' : 'btn-default';
            if($puedeCrearForms || $item->postcosecha_id) {
                $url = site_url("poscosecha/index/$item->id");
                $url2 = site_url("poscosechaimprimible/index/$item->id");
                $disabled = '';
            } else {
                $url = '';
                $url2 = '';
                $disabled = 'disabled="disabled"';
            }

            if($item->postcosecha_id){
                $disabled1 = '';
            } else {
                $disabled1 = 'disabled';
            }

            $actions .= "<div class='btn-group'>";
            $actions .= "<a class='btn btn-xs $cls tip' href='$url' $disabled title='Manejo de Poscosecha'>Poscosecha<i class='i-arrow-right-3'></i></a>";
            $actions .= "<a class='btn btn-xs btn-info tip' href='$url2' $disabled1 title='Versión Imprimible Poscosecha' target='_blank'><i class='i-print'></i></a>";
            $actions .="</div>&nbsp;";

            if($puedeVerEstudioSuelo) {
                $est = RuatEstudioSuelo::find_by_ruat_id($item->id);
                if($est) {
                    $url = site_url("suelos/imprimible/$item->id");
                    $actions .= "<a class='btn btn-xs btn-info' href='$url' target='_blank'>E. Suelo</a>";
                }
                else {
                    $actions .= "<button disabled='disabled' class='btn btn-xs btn-info'>E. Suelo</button>";
                }
            }

            $crd = SolicitudCredito::find_by_ruat_id($item->id);
            
            if($crd)
                $cls = "btn btn-xs btn-warning";
            else
                $cls = "btn btn-xs btn-default";
            
            $url = site_url("creditoagropecuario/index/$item->id");
            $actions .= " <div class='btn-group'>";
            $actions .= "<a class='$cls tip' href='$url' title='Solicitud de Credito Agropecuario'>Crédito</a>";
            if($crd) {
                $impr = site_url("creditoagropecuario/imprimible/$item->id");
                $actions .= "<a class='btn btn-xs btn-info' href='$impr' target='_blank'><i class='i-print'></i></a>";
            }
            else {
                $actions .= "<a class='btn btn-xs btn-info' disabled='disabled'><i class='i-print'></i></a>";
            }
            $actions .= "</div";
            



            $btnEliminar = $puedeEliminar? "<button class='btn btn-danger btn-xs tip' title='Eliminar RUAT' onclick='eliminarRuat({$item->id})'>-</button> " :"";

            $row = array($btnEliminar . $item->numero_formulario, 
                $item->nombre_productor, 
                $item->creado->format('Y-m-d  H:i:s'),
                $item->ingresado_por,
                $actions,
            );
            $rows[] = $row;
        }

        $output = array(
            'iTotalRecords' => $numTotalResults,
            'iTotalDisplayRecords' => $numFilteredResults,
            'sEcho' => $sEcho,
            'aaData' => $rows,
        );
        
        echo json_encode($output);
    }
}
