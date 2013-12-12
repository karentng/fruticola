<?php
class Prueba extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
            redirect('auth/login');

        $this->twiggy->set('usuario', $this->ion_auth->user()->row(), true);
        $this->twiggy->set('perfil', $this->ion_auth->get_users_groups()->row(), true);

    }

    public function test($nombre="Foraneo") 
    {
        
        //$this->load->database();

        //$news = $this->db->get('news')->row_array();

        //$this->load->model("new");

        //$res = Noticia::find_by_title("noticia 1");

        //var_dump($res);

        //var_dump($news);

        //$this->load->spark('Twiggy/0.8.5');
        //$this->twiggy
        $this->twiggy->set("usuario", $nombre);
        $this->twiggy->template("auth/login");
        $this->twiggy->display();
    }

    public function ensayo()
    {
        //$this->load->spark('Twiggy/0.8.5');
        $this->twiggy->template("ruat/datos_personales");
        $this->twiggy->display();
    }

    public function finca()
    {
        //$this->load->spark('Twiggy/0.8.5');
        $this->twiggy->template("ruat/datos_finca");
        $this->twiggy->display();
    }


}