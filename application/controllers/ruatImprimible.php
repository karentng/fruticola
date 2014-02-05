<?php

class RuatImprimible extends CI_Controller {
    public function index($ruat_id) 
    {
    	$ruat = Ruat::find($ruat_id);
    	$innovaciones = Innovacion::find('all', array('conditions' => 'ruat_id ='.$ruat_id));
    	//var_dump($inno); die();
    	$this->twiggy->set('ruat',$ruat);
    	$this->twiggy->set('innovaciones',$innovaciones);
    	$this->twiggy->template("ruat/ruatImprimible");
        $this->twiggy->display();
    }
}