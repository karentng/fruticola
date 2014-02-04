<?php

class RuatImprimible extends CI_Controller {
    public function index($ruat_id) 
    {
    	$ruat = Ruat::find($ruat_id);
    	//var_dump($ruat); die();
    	$this->twiggy->set('ruat',$ruat);
    	$this->twiggy->template("ruat/ruatImprimible");
        $this->twiggy->display();
    }
}