<?php


class ListadoRuats extends CI_Controller {

    public function index()
    {
        check_profile($this, "Administrador", "Coordinador", "Digitador");
        //if($this->ion_auth->in_group("Digitador"))
        //    $ruats = Ruat::find_all_by_creador_id(current_user('id'), array('include' => array('bpa','cosecha','observacion')));
        //else
            $ruats = Ruat::all(array('include' => array('bpa','cosecha', 'visita_tipo_productor'  ,'observacion', 'creador')));

        //foreach($ruats as $r) echo $r->observacion->ruta_formulario;
        $this->twiggy->set('isAdmin', $this->ion_auth->in_group('Administrador'));

        $this->twiggy->set("ruats", $ruats);
        $this->twiggy->template("ruat/listado");
        $this->twiggy->display();
    }

    public function eliminar_ruat($ruat_id=NULL) {
        if(!$ruat_id) show_404();
        check_profile($this, "Administrador");


        Ruat::find($ruat_id)->eliminar();
        $this->session->set_flashdata("notif", array('type'=>'success', 'text' => 'RUAT eliminado exitósamente'));
        redirect("listadoruats");
    }
}
