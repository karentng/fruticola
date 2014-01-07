<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tp extends CI_Controller {

    public function index()
    {
        $this->twiggy->template("ruat/tp");
        $this->twiggy->display();
    }
}
