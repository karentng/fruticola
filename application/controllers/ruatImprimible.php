<?php

class RuatImprimible extends CI_Controller {
    public function index($ruat_id) 
    {
    	$ruat = Ruat::find($ruat_id);
    	$innovaciones = Innovacion::find('all', array('conditions' => 'ruat_id ='.$ruat_id));
    	$asociaciones = Orgasociada::find('all', array('conditions'=> 'ruat_id='.$ruat_id));
        $organizaciones = json_decode($ruat->orgs_apoyan);
    	//var_dump($organizaciones); die();
    	$this->twiggy->set('ruat',$ruat);
    	$this->twiggy->set('innovaciones',$innovaciones);
    	$this->twiggy->set('asociaciones', $asociaciones);
        $this->twiggy->set('organizaciones', $organizaciones);
    	$this->twiggy->template("ruat/ruatImprimible");
        $this->twiggy->display();
    }
}