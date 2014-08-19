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
            $options['conditions'] = array('numero_formulario = ? or nombre_productor ilike ? or creado::text like ? or ingresado_por ilike ? or numero_documento=?',
                $search, "%$search%", "%$search%", "%$search%", $search);
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

        function boton($texto, $titulo, $clase, $url, $blank=false)
        {
            $res = "<a class='btn btn-xs btn-$clase' title='$titulo' "
                    . ($url ? "href='$url' " : "disabled='disabled' ") 
                    . ($blank ? "target='_blank'" : "") . " >";

            if(strpos($texto, "i-")===0) // icono
                $res .= "<i class='$texto'></i>";
            else
                $res .= str_replace("_", "<i class='i-arrow-right-3'></i>", $texto);

            $res .= "</a> ";
            return $res;
        }

        $rows = array();
        foreach(ViewListadoRuats::all($options) as $item) {
            $actions = "<div class='btn-group'>"
                . boton("RUAT _", 'Registro Único Asistencia Técnica', 'warning', site_url("ruata/index/".$item->id))
                . boton("i-file-download", 'Descargar RUAT Escaneado', 'info', $item->ruta_formulario ? site_url("uploads/$item->ruta_formulario") : null, true)
                . boton("i-print", "Imprimible RUAT", 'info', site_url("ruatImprimible/index/".$item->id), true)
                . "</div> ";

            $actions .= "<div class='btn-group'>"
                . boton("Cosecha _", 'Diagnóstico Manejo de Cosecha', $item->cosecha_id ? 'warning' : 'default', $puedeCrearForms ? site_url("diagnosticosecha/index/$item->id") : null)
                . boton("i-print", "Imprimible Cosecha", 'info', $item->cosecha_id ? site_url("cosechaImprimible/index/$item->id") : null, true)
                . "</div> ";

            $actions .= boton("BPA", "Buenas Prácticas Agropecuarias", $item->bpa_id ? 'warning':'default', $puedeCrearForms ? site_url("bpa/index/$item->id") : null);

            $actions .= "<div class='btn-group'>"
                . boton('C. Prod _', 'Clasificación Productor', $item->vtp_id ? 'warning':'default', $puedeCrearForms ? site_url("vtp/index/$item->id") : null)
                . boton('i-print', "Imprimible Clasificación Productor", 'info', $item->vtp_id ? site_url("tipoProductorImprimible/index/$item->id") : null, true)
                . "</div> ";

            $actions .= "<div class='btn-group'>"
                . boton("Post _", 'Manejo de Poscosecha', $item->cosecha_id ? 'warning' : 'default', $puedeCrearForms ? site_url("poscosecha/index/$item->id") : null)
                . boton("i-print", "Imprimible Poscosecha", 'info', $item->cosecha_id ? site_url("poscosechaimprimible/index/$item->id") : null, true)
                . "</div> ";

            $actions .= "<br>";            

            if($puedeVerEstudioSuelo) {
                $est = RuatEstudioSuelo::find_by_ruat_id($item->id);
                $actions .= boton('E. Suelo', "Imprimible Estudio de Suelo", 'info', $est ? site_url("suelos/imprimible/$item->id") : null, true);
            }

            $crd = SolicitudCredito::find_by_ruat_id($item->id);
            
            $actions .= "<div class='btn-group'>"
                . boton("Crédito _", 'Solicitud de Crédito Agropecuario', $crd ? 'warning' : 'default', $puedeCrearForms ? site_url("creditoagropecuario/index/$item->id") : null)
                . boton("i-print", "Imprimible Solicitud Crédito", 'info', $crd ? site_url("creditoagropecuario/imprimible/$item->id") : null, true)
                . "</div> ";


            $actions .= $this->selectorVisitas($item);

            $negocio = PlanNegocio::find_by_ruat_id($item->id);
            $actions .= boton("P. Negocio", 'Plan de Negocio', $negocio? 'warning': 'default', $puedeCrearForms ? site_url("plandenegocio/index/$item->id") : null);


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

    
    function selectorVisitas($item)
    {
        $renglon = Productor::find_by_id(Ruat::find_by_id($item->id)->productor_id)->renglon_productivo_id;
        $res = "<div class='btn-group'>"
            . '<button class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown"> Certif. Visita <span class="caret"></span></button>'
            . '<ul class="dropdown-menu">';

        foreach(CertificacionVisit::formularios_renglon($renglon) as $idx) {
            $titulo = str_replace("  ","<br>",CertificacionVisit::$TITULO_FORMULARIO[$idx]);
            $url = site_url("certificacionvisita/index/$item->id/$idx");
            $res .= "<li><a href='$url'><small>$titulo</small></a></li>";
        }

        $res .= "</ul></div> ";
        return $res;
    }
}
