<?php


class ListadoRuats extends CI_Controller {

    public function index()
    {
        check_profile($this, "Administrador", "Digitador");
        $ruats = Ruat::all();


        $this->twiggy->set("ruats", $ruats);
        $this->twiggy->template("ruat/listado");
        $this->twiggy->display();
    }

}