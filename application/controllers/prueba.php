<?php
class Prueba extends CI_Controller {
    public function test($nombre="Foraneo") 
    {
        
        //$this->load->database();

        //$news = $this->db->get('news')->row_array();

        //$this->load->model("new");

        //$res = Noticia::find_by_title("noticia 1");

        //var_dump($res);

        //var_dump($news);

        $this->load->spark('Twiggy/0.8.5');
        //$this->twiggy
        $this->twiggy->set("usuario", $nombre);
        $this->twiggy->template("auth/login");
        $this->twiggy->display();
    }

    public function ensayo()
    {
        $this->load->spark('Twiggy/0.8.5');
        $this->twiggy->template("ruat/datos_personales");
        $this->twiggy->display();
    }


}