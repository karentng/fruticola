<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RuatC extends CI_Controller {

	public function index()
	{
		$this->load->library('form_validation');
		$preguntas = TipoPregunta::sorted();
		$respuestas = TipoRespuesta::sorted();

		for( $i = 0; $i < count($preguntas); $i++ )
		{
			$this->form_validation->set_rules('radio_pregunta'.$i, 'Pregunta'.$i, 'required');			
		}
		
		if($this->form_validation->run())
		{
			for( $i = 0; $i < count($preguntas); $i++ )
			{

			try
			{
				$tmpRespuesta = explode("_", $this->input->post( 'radio_pregunta'.$i ));
				$respuestaRuat = new AprendizajeRespuesta;
				$respuestaRuat->ruat_id = 1;
				$respuestaRuat->respuesta_id = $tmpRespuesta[0];
				$respuestaRuat->pregunta_id = $tmpRespuesta[1];
				$respuestaRuat->save();					
			}
			catch( Exception $e)
			{ echo( $e );}
			}
		}
		/*else
		{
		echo('campos sin llenar'); die;
		}*/
		
		$this->twiggy->set('preguntas', $preguntas);
		$this->twiggy->set('respuestas', $respuestas);
		$this->twiggy->template("ruat/apropiacion_aprendizajes");
		$this->twiggy->display();
	}
}
