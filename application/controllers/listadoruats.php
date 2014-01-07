<?php


class ListadoRuats extends CI_Controller {

    public function index()
    {
        check_profile($this, "Administrador", "Coordinador", "Digitador");
        if($this->ion_auth->in_group(3))
            $ruats = Ruat::find_all_by_creador_id(current_user('id'));
        else
            $ruats = Ruat::all();


        $this->twiggy->set("ruats", $ruats);
        $this->twiggy->template("ruat/listado");
        $this->twiggy->display();
    }

}
