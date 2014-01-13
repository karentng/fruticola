<?php


class ListadoRuats extends CI_Controller {

    public function index()
    {
        check_profile($this, "Administrador", "Coordinador", "Digitador");
        if($this->ion_auth->in_group("Digitador"))
            $ruats = Ruat::find_all_by_creador_id(current_user('id'), array('include' => array('bpa','cosecha')));
        else
            $ruats = Ruat::all();

        foreach($ruats as $r) {
            //$r->tiene_cosecha = (bool)$r->cosecha;
            //$r->tiene_bpa = (bool)$r->bpa;
        }

        $this->twiggy->set("ruats", $ruats);
        $this->twiggy->template("ruat/listado");
        $this->twiggy->display();
    }

}
