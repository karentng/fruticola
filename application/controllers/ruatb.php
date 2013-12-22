<?php defined('BASEPATH') OR exit('No direct script access allowed');

class RuatB extends CI_Controller {

    public function index()
    {
        //Arreglos que se requieren mostrar en los select del RUAT-B
        $servicios = array("agua"=>"Agua Propia","acueducto"=>"Acueducto","internet"=>"Acceso a Internet","energia"=>"Energía Eléctrica");
        $tenencia = array("propiedad"=>"Propiedad sin título","arrendamiento"=>"En arrendamiento","comodacto"=>"Comodacto",
            "usufructo"=>"Usufructo","aparceria"=>"Aparcería","colectiva"=>"Colectiva","otro"=>"Otro");
        $municipio = array("andres"=>"Andres","buga"=>"Buga","cali"=>"Cali");

        $tipo_via = array("pavimentada"=>"Pavimentada","no_pavimentada"=>"No Pavimentada");
        $estado_via = array("buena"=>"Buena","regular"=>"Regular","mala"=>"Mala");
        $programa_residuos = array("ordinarios"=>"Ordinarios","peligrosos"=>"Peligrosos","otro"=>"Otro");

        $medios_transporte = array("animal"=>"Animal","camion"=>"Camión","bicicleta"=>"Bicicleta","caminata"=>"Caminata","tractor"=>"Tractor",
            "barco"=>"Barco","canoa"=>"Canoa","kayak"=>"Kayak","planchon"=>"Planchón","otro"=>"Otro");

        $semilla = array("cerficicada"=>"Certificada", "no_certificada"=>"No Certificada");
        $sitio_venta = array("finca"=>"Finca","plaza"=>"Plaza","super_mercado"=>"Super Mercado","centro"=>"Centro de Acopio",
            "mercado_pueblo"=>"Mercado Pueblo","otro"=>"Otro");
        $quien_vende_tipo = array("acopiador"=>"Acopiador","transportador"=>"Transportador","detallista"=>"Detallista",
            "transformador"=>"Transformador","cooperativa"=>"Cooperativa","consumidor_final"=>"Consumidor Final","otro"=>"Otro");
        $forma_pago = array("efectivo"=>"Efectivo","transferencia"=>"Transferencia","cheque"=>"Cheque","credito"=>"Crédito","trueque"=>"Trueque");
        //Fin Arreglos

        //Seteo para el uso de arreglos en la plantilla twiggy
        $this->twiggy->set('servicios',$servicios);
        $this->twiggy->set('tenencia',$tenencia);
        $this->twiggy->set('municipio',$municipio);

        $this->twiggy->set('tipo_via',$tipo_via);
        $this->twiggy->set('estado_via',$estado_via);
        $this->twiggy->set('programa_residuos',$programa_residuos);
        $this->twiggy->set('medios_transporte',$medios_transporte);

        $this->twiggy->set('semilla',$semilla);
        $this->twiggy->set('sitio_venta',$sitio_venta);
        $this->twiggy->set('quien_vende_tipo',$quien_vende_tipo);
        $this->twiggy->set('forma_pago',$forma_pago);
        //Fin Seteo

        $this->load->library('form_validation');

        //Inicio de Validaciones Parte 1
        $this->form_validation->set_rules('radio_finca','Finca','required');
        $this->form_validation->set_rules('nombre_finca','Nombre de la Finca','required');
        $this->form_validation->set_rules('id_catastral');
        $this->form_validation->set_rules('tenencia','Tenencia','required');
        $this->form_validation->set_rules('municipio','Municipio','required');
        $this->form_validation->set_rules('vereda','Vereda','required');
        $this->form_validation->set_rules('sector','Sector','required');
        $this->form_validation->set_rules('area_total','Área Total del Predio','required|numeric|greater_than[0]');
        $this->form_validation->set_rules('servicios_publicos');
        $this->form_validation->set_rules('vias_acceso','Disponibilidad de Vias de Acceso','required');
        $this->form_validation->set_rules('tipo_via');
        $this->form_validation->set_rules('estado_via');

        $this->form_validation->set_rules('ordinariosOP');
        $this->form_validation->set_rules('ordinarios');
        $this->form_validation->set_rules('peligrososOP');
        $this->form_validation->set_rules('peligrosos');
        $this->form_validation->set_rules('otrosOP');
        $this->form_validation->set_rules('otros');

        $this->form_validation->set_rules('distancia_finca_cabecera','Distancia de la Finca a la Cabecera Municipal','required|numeric');
        $this->form_validation->set_rules('medios_transporte','Medios de Transporte para la Actividad Productiva','required');
        $this->form_validation->set_rules('forma_llegar_predio','Forma de Llegar al Predio','required');
        //Fin validaciones Parte 1
        
        //Inicio de Validaciones Parte 2
        $this->form_validation->set_rules('herramientasOP');
        $this->form_validation->set_rules('herramientas');
        $this->form_validation->set_rules('utenciliosOP');
        $this->form_validation->set_rules('utencilios');
        $this->form_validation->set_rules('equiposOP');
        $this->form_validation->set_rules('equipos');
        $this->form_validation->set_rules('livianaOP');
        $this->form_validation->set_rules('maquinaria_liviana');
        $this->form_validation->set_rules('pesadaOP');
        $this->form_validation->set_rules('maquinaria_pesada');
        $this->form_validation->set_rules('otrosOP');
        $this->form_validation->set_rules('otros');
        //Fin validaciones Parte 2

        //Inicio de Validaciones Parte 3 (Cultivos con Perspectiva Comercial)
        $this->form_validation->set_rules('producto_1');
        $this->form_validation->set_rules('producto_2');
        $this->form_validation->set_rules('producto_3');
        $this->form_validation->set_rules('producto_4');

        $this->form_validation->set_rules('variedad_1');
        $this->form_validation->set_rules('variedad_2');
        $this->form_validation->set_rules('variedad_3');
        $this->form_validation->set_rules('variedad_4');

        $this->form_validation->set_rules('semilla1');
        $this->form_validation->set_rules('semilla2');
        $this->form_validation->set_rules('semilla3');
        $this->form_validation->set_rules('semilla4');

        $this->form_validation->set_rules('total_1');
        $this->form_validation->set_rules('total_2');
        $this->form_validation->set_rules('total_3');
        $this->form_validation->set_rules('total_4');
        
        $this->form_validation->set_rules('area_cosechada_1','Área Cosechada del Producto 1','numeric');
        $this->form_validation->set_rules('area_cosechada_2','Área Cosechada del Producto 2','numeric');
        $this->form_validation->set_rules('area_cosechada_3','Área Cosechada del Producto 3','numeric');
        $this->form_validation->set_rules('area_cosechada_4','Área Cosechada del Producto 4','numeric');

        $this->form_validation->set_rules('semestreA_1','Producción del Semestre A del Producto 1','numeric');
        $this->form_validation->set_rules('semestreA_2','Producción del Semestre A del Producto 2','numeric');
        $this->form_validation->set_rules('semestreA_3','Producción del Semestre A del Producto 3','numeric');
        $this->form_validation->set_rules('semestreA_4','Producción del Semestre A del Producto 4','numeric');
        
        $this->form_validation->set_rules('semestreB_1','Producción del Semestre B del Producto 1','numeric');
        $this->form_validation->set_rules('semestreB_2','Producción del Semestre B del Producto 2','numeric');
        $this->form_validation->set_rules('semestreB_3','Producción del Semestre B del Producto 3','numeric');
        $this->form_validation->set_rules('semestreB_4','Producción del Semestre B del Producto 4','numeric');
        //Fin validaciones Parte 3 (Cultivos con Perspectiva Comercial)

        //Inicio de Validaciones Parte 4
        $this->form_validation->set_rules('establecimiento_1','Establecimiento del Producto 1','numeric');
        $this->form_validation->set_rules('establecimiento_2','Establecimiento del Producto 2','numeric');
        $this->form_validation->set_rules('establecimiento_3','Establecimiento del Producto 3','numeric');
        $this->form_validation->set_rules('establecimiento_4','Establecimiento del Producto 4','numeric');

        $this->form_validation->set_rules('sostenimiento_1','Sostenimiento del Producto 1','numeric');
        $this->form_validation->set_rules('sostenimiento_2','Sostenimiento del Producto 2','numeric');
        $this->form_validation->set_rules('sostenimiento_3','Sostenimiento del Producto 3','numeric');
        $this->form_validation->set_rules('sostenimiento_4','Sostenimiento del Producto 4','numeric');

        $this->form_validation->set_rules('kg_1','Kgs del Producto 1','numeric');
        $this->form_validation->set_rules('kg_2','Kgs del Producto 2','numeric');
        $this->form_validation->set_rules('kg_3','Kgs del Producto 3','numeric');
        $this->form_validation->set_rules('kg_4','Kgs del Producto 4','numeric');
        
        $this->form_validation->set_rules('porcentaje_1');
        $this->form_validation->set_rules('porcentaje_2');
        $this->form_validation->set_rules('porcentaje_3');
        $this->form_validation->set_rules('porcentaje_4');

        $this->form_validation->set_rules('sitio_venta1');
        $this->form_validation->set_rules('sitio_venta2');
        $this->form_validation->set_rules('sitio_venta3');
        $this->form_validation->set_rules('sitio_venta5');

        $this->form_validation->set_rules('quien_vende_tipo1');
        $this->form_validation->set_rules('quien_vende_tipo2');
        $this->form_validation->set_rules('quien_vende_tipo3');
        $this->form_validation->set_rules('quien_vende_tipo4');

        $this->form_validation->set_rules('nombre_apellido_1');
        $this->form_validation->set_rules('nombre_apellido_2');
        $this->form_validation->set_rules('nombre_apellido_3');
        $this->form_validation->set_rules('nombre_apellido_4');
        //Fin validaciones Parte 4

        //Inicio de Validaciones Parte 5
        $this->form_validation->set_rules('precio_venta_promedio_1','Precio de Venta Promedio del Producto 1','numeric');
        $this->form_validation->set_rules('precio_venta_promedio_2','Precio de Venta Promedio del Producto 2','numeric');
        $this->form_validation->set_rules('precio_venta_promedio_3','Precio de Venta Promedio del Producto 3','numeric');
        $this->form_validation->set_rules('precio_venta_promedio_4','Precio de Venta Promedio del Producto 4','numeric');

        $this->form_validation->set_rules('forma_pago1');
        $this->form_validation->set_rules('forma_pago2');
        $this->form_validation->set_rules('forma_pago3');
        $this->form_validation->set_rules('forma_pago4');
        
        $this->form_validation->set_rules('subproducto_cultivo_cual_1');
        $this->form_validation->set_rules('subproducto_cultivo_cual_2');
        $this->form_validation->set_rules('subproducto_cultivo_cual_3');
        $this->form_validation->set_rules('subproducto_cultivo_cual_4');
        
        $this->form_validation->set_rules('subproducto_cultivo_uso_1');
        $this->form_validation->set_rules('subproducto_cultivo_uso_2');
        $this->form_validation->set_rules('subproducto_cultivo_uso_3');
        $this->form_validation->set_rules('subproducto_cultivo_uso_4');

        $this->form_validation->set_rules('pertenece_1');
        $this->form_validation->set_rules('pertenece_2');
        $this->form_validation->set_rules('pertenece_3');
        $this->form_validation->set_rules('pertenece_4');
        
        $this->form_validation->set_rules('pertenencia_programa_1');
        $this->form_validation->set_rules('pertenencia_programa_2');
        $this->form_validation->set_rules('pertenencia_programa_3');
        $this->form_validation->set_rules('pertenencia_programa_4');
        
        $this->form_validation->set_rules('pertenencia_entidad_1');
        $this->form_validation->set_rules('pertenencia_entidad_2');
        $this->form_validation->set_rules('pertenencia_entidad_3');
        $this->form_validation->set_rules('pertenencia_entidad_4');
        //Fin validaciones Parte 5

        //Inicio de Validaciones Parte 6 (GEORREFERENCIACION DEL PREDIO DE UBICACIÓN DEL CULTIVO)
        $this->form_validation->set_rules('latitud_norte','Latitud Norte','required|numeric');
        $this->form_validation->set_rules('longitud_occidente','Longitud Occidente','required|numeric');
        $this->form_validation->set_rules('altura_nivel_mar','Altura sobre el Nivel del Mar Occidente','required|numeric');
        $this->form_validation->set_rules('subir_archivo');
        //Fin validaciones Parte 6 (GEORREFERENCIACION DEL PREDIO DE UBICACIÓN DEL CULTIVO)

        if($this->form_validation->run())
        {
            //las validaciones pasaron, aqui iria la logica de insertar en la BD...
        }

        $this->twiggy->template("ruat/datos_finca");
        $this->twiggy->display();
    }
}