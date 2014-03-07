<?php

class Perfilproductor extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {
        //TABLA
        $resultado=Productor::find_by_sql("SELECT avg( date_part('years', age(fecha_nacimiento))) as promedio_edad FROM productor");
        $promedio_edad = $resultado[0]->promedio_edad;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(ingagro)as promedio_ingagro
                                                               FROM(SELECT visita_id, SUM(valor) as ingagro
                                                               FROM visita_tipo_productor 
                                                               INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id
                                                               WHERE pregunta_c_id BETWEEN 16  AND 17 
                                                               GROUP BY visita_id) as ingresos");

        $promedio_ingagro = $resultado[0]->promedio_ingagro;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_totaling
                                                                FROM visita_tipo_productor
                                                                INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id
                                                                WHERE pregunta_c_id=19");
        $promedio_totaling = $resultado[0]->promedio_totaling;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_egresos
                                                                FROM visita_tipo_productor
                                                                INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id
                                                                WHERE pregunta_c_id=25");

        $promedio_egresos = $resultado[0]->promedio_egresos;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_utilidad
                                                                FROM visita_tipo_productor
                                                                INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id
                                                                WHERE pregunta_c_id=26");
        $promedio_utilidad = $resultado[0]->promedio_utilidad;

        $resultado=Productor::find_by_sql("SELECT COUNT(id) as cantidad_productores FROM productor");
        $cantidad_productores= $resultado[0]->cantidad_productores;


        $resultado=Producto::find_by_sql("SELECT SUM(area_cosechada) as total_hectareas FROM producto");
        $total_hectareas= $resultado[0]->total_hectareas;

        $promedio_hectareas=$total_hectareas/$cantidad_productores;

        //GRAFICAS

        $resultado=Productor::find_by_sql("SELECT COUNT(sexo) as cantidad_hombres FROM productor WHERE sexo='M'");
        $cantidad_hombres=$resultado[0]->cantidad_hombres;

        $resultado=Productor::find_by_sql("SELECT COUNT(sexo) as cantidad_mujeres FROM productor WHERE sexo='F'");
        $cantidad_mujeres=$resultado[0]->cantidad_mujeres;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as primaria FROM productor WHERE nivel_educativo_id='1'");
        $primaria =$resultado[0]->primaria;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as secundaria FROM productor WHERE nivel_educativo_id='2'");
        $secundaria =$resultado[0]->secundaria;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as tecnica FROM productor WHERE nivel_educativo_id='3'");
        $tecnica = $resultado[0]->tecnica;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as tecnologica FROM productor WHERE nivel_educativo_id='4'");
        $tecnologica = $resultado[0]->tecnologica;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as universitaria FROM productor WHERE nivel_educativo_id='5'");
        $universitaria = $resultado[0]->universitaria;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as ninguna FROM productor WHERE nivel_educativo_id='6'");
        $ninguna =$resultado[0]->ninguna;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id) as agua FROM finca_servicio WHERE servicio_id=1");
        $agua =$resultado[0]->agua;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id) as acueducto FROM finca_servicio WHERE servicio_id=2");
        $acueducto =$resultado[0]->acueducto;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id) as internet FROM finca_servicio WHERE servicio_id=3");
        $internet =$resultado[0]->internet;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id) as electricidad FROM finca_servicio WHERE servicio_id=4");
        $electricidad =$resultado[0]->electricidad;


        $resultado=Economia::find_by_sql("SELECT COUNT(credito) as credito_si FROM (SELECT CASE WHEN economia.credito_id IS NULL THEN 'No' ELSE 'Sí' END AS credito
                                            FROM economia) as c
                                            WHERE credito='Sí'");
        $credito_si =$resultado[0]->credito_si;
        
        $resultado=Economia::find_by_sql("SELECT COUNT(credito) as credito_no FROM (SELECT CASE WHEN economia.credito_id IS NULL THEN 'No' ELSE 'Sí' END AS credito
                                            FROM economia) as c
                                            WHERE credito='No'");
        $credito_no =$resultado[0]->credito_no;

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as bancos FROM economia WHERE credito_id=1");
        $bancos=$resultado[0]->bancos; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as agremiaciones FROM economia WHERE credito_id=2");
        $agremiaciones=$resultado[0]->agremiaciones; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as entidades FROM economia WHERE credito_id=3");
        $entidades=$resultado[0]->entidades; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as prestamistas FROM economia WHERE credito_id=4");
        $prestamistas=$resultado[0]->prestamistas; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as familiares FROM economia WHERE credito_id=5");
        $familiares=$resultado[0]->familiares; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as empresas FROM economia WHERE credito_id=6");
        $empresas=$resultado[0]->empresas; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as otro FROM economia WHERE credito_id=7");
        $otro=$resultado[0]->otro; 

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as agropecuaria FROM orgasociada_clase WHERE clase_id=1");
        $agropecuaria=$resultado[0]->agropecuaria;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as asistencial FROM orgasociada_clase WHERE clase_id=2");
        $asistencial=$resultado[0]->asistencial;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as comercial FROM orgasociada_clase WHERE clase_id=3");
        $comercial=$resultado[0]->comercial;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as cultural FROM orgasociada_clase WHERE clase_id=4");
        $cultural=$resultado[0]->cultural;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as deportiva FROM orgasociada_clase WHERE clase_id=5");
        $deportiva=$resultado[0]->deportiva;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as educativa FROM orgasociada_clase WHERE clase_id=6");
        $educativa=$resultado[0]->educativa;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as etnica FROM orgasociada_clase WHERE clase_id=7");
        $etnica=$resultado[0]->etnica;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as politica FROM orgasociada_clase WHERE clase_id=8");
        $politica=$resultado[0]->politica;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as salud FROM orgasociada_clase WHERE clase_id=9");
        $salud=$resultado[0]->salud;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as social FROM orgasociada_clase WHERE clase_id=10");
        $social=$resultado[0]->social;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as tecnologico FROM orgasociada_clase WHERE clase_id=11");
        $tecnologico=$resultado[0]->tecnologico;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as capacitacion FROM orgasociada_beneficio WHERE beneficio_id=1");
        $capacitacion=$resultado[0]->capacitacion;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as economico FROM orgasociada_beneficio WHERE beneficio_id=2");
        $economico=$resultado[0]->economico;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as especie FROM orgasociada_beneficio WHERE beneficio_id=3");
        $especie=$resultado[0]->especie;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as participacion FROM orgasociada_beneficio WHERE beneficio_id=4");
        $participacion=$resultado[0]->participacion;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as reconocimiento FROM orgasociada_beneficio WHERE beneficio_id=5");
        $reconocimiento=$resultado[0]->reconocimiento;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as recreacion FROM orgasociada_beneficio WHERE beneficio_id=6");
        $recreacion=$resultado[0]->recreacion;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as otro FROM orgasociada_beneficio WHERE beneficio_id=7");
        $otro=$resultado[0]->otro;


        $resultado=Economia::find_by_sql("SELECT COUNT(credito) as credito_si FROM (SELECT CASE WHEN economia.credito_id IS NULL THEN 'No' ELSE 'Sí' END AS credito
                                            FROM economia) as c
                                            WHERE credito='Sí'");
        $credito_si =$resultado[0]->credito_si;
        
        $resultado=Producto::find_by_sql("SELECT COUNT(asistencia)as asist_no
                                            FROM(
                                            SELECT CASE WHEN asistencia_programa IS NULL THEN 'No' ELSE 'Si' END as asistencia
                                            FROM producto) as a
                                            WHERE asistencia='No'");
        $asist_no =$resultado[0]->asist_no;

        $resultado=Producto::find_by_sql("SELECT COUNT(asistencia)as asist_si
                                            FROM(
                                            SELECT CASE WHEN asistencia_programa IS NULL THEN 'No' ELSE 'Si' END as asistencia
                                            FROM producto) as a
                                            WHERE asistencia='Si'");
        $asist_si =$resultado[0]->asist_si;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as animal FROM finca_transporte WHERE transporte_id=1");
        $animal=$resultado[0]->animal;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as camion FROM finca_transporte WHERE transporte_id=2");
        $camion=$resultado[0]->camion;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as bicicleta FROM finca_transporte WHERE transporte_id=3");
        $bicicleta=$resultado[0]->bicicleta;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as caminata FROM finca_transporte WHERE transporte_id=4");
        $caminata=$resultado[0]->caminata;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as tractor FROM finca_transporte WHERE transporte_id=5");
        $tractor=$resultado[0]->tractor;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as barco FROM finca_transporte WHERE transporte_id=6");
        $barco=$resultado[0]->barco;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as canoa FROM finca_transporte WHERE transporte_id=7");
        $canoa=$resultado[0]->canoa;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as kayak FROM finca_transporte WHERE transporte_id=8");
        $kayak=$resultado[0]->kayak;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as planchon FROM finca_transporte WHERE transporte_id=9");
        $planchon=$resultado[0]->planchon;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as otro FROM finca_transporte WHERE transporte_id=10");
        $otro=$resultado[0]->otro;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as herramientas FROM finca_maquinaria WHERE maquinaria_id=1");
        $herramientas=$resultado[0]->herramientas;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as utensilios FROM finca_maquinaria WHERE maquinaria_id=2");
        $utensilios=$resultado[0]->utensilios;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as equipos FROM finca_maquinaria WHERE maquinaria_id=3");
        $equipos=$resultado[0]->equipos;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as liviana FROM finca_maquinaria WHERE maquinaria_id=4");
        $liviana=$resultado[0]->liviana;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as pesada FROM finca_maquinaria WHERE maquinaria_id=5");
        $pesada=$resultado[0]->pesada;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as otros FROM finca_maquinaria WHERE maquinaria_id=6");
        $otros=$resultado[0]->otros;



        $this->twiggy->set('herramientas', $herramientas);
        $this->twiggy->set('utensilios', $utensilios);
        $this->twiggy->set('equipos', $equipos);
        $this->twiggy->set('liviana', $liviana);
        $this->twiggy->set('pesada', $pesada);
        $this->twiggy->set('otros', $otros);
        $this->twiggy->set('planchon', $planchon);
        $this->twiggy->set('kayak', $kayak);
        $this->twiggy->set('canoa', $canoa);
        $this->twiggy->set('barco', $barco);
        $this->twiggy->set('tractor', $tractor);
        $this->twiggy->set('caminata', $caminata);
        $this->twiggy->set('bicicleta', $bicicleta);
        $this->twiggy->set('camion', $camion);
        $this->twiggy->set('animal', $animal);
        $this->twiggy->set('asist_si', $asist_si);
        $this->twiggy->set('asist_no', $asist_no);
        $this->twiggy->set('otro', $otro);
        $this->twiggy->set('recreacion', $recreacion);
        $this->twiggy->set('reconocimiento', $reconocimiento);
        $this->twiggy->set('participacion', $participacion);
        $this->twiggy->set('especie', $especie);
        $this->twiggy->set('economico', $economico);
        $this->twiggy->set('capacitacion', $capacitacion);
        $this->twiggy->set('tecnologico', $tecnologico);
        $this->twiggy->set('social', $social);
        $this->twiggy->set('salud', $salud);
        $this->twiggy->set('politica', $politica);
        $this->twiggy->set('etnica', $etnica);
        $this->twiggy->set('educativa', $educativa);
        $this->twiggy->set('deportiva', $deportiva);
        $this->twiggy->set('cultural', $cultural);
        $this->twiggy->set('comercial', $comercial);
        $this->twiggy->set('asistencial', $asistencial);
        $this->twiggy->set('agropecuaria', $agropecuaria); 
        $this->twiggy->set('agremiaciones', $agremiaciones);
        $this->twiggy->set('entidades', $entidades);
        $this->twiggy->set('prestamistas', $prestamistas);
        $this->twiggy->set('familiares', $familiares);
        $this->twiggy->set('empresas', $empresas);
        $this->twiggy->set('otro', $otro);
        $this->twiggy->set('bancos', $bancos);
        $this->twiggy->set('credito_no', $credito_no);
        $this->twiggy->set('credito_si', $credito_si);
        $this->twiggy->set('electricidad', $electricidad);
        $this->twiggy->set('internet', $internet);
        $this->twiggy->set('acueducto', $acueducto);
        $this->twiggy->set('agua', $agua);
        $this->twiggy->set('ninguna', $ninguna);
        $this->twiggy->set('universitaria', $universitaria);
        $this->twiggy->set('tecnologica', $tecnologica);
        $this->twiggy->set('tecnica', $tecnica);
        $this->twiggy->set('secundaria', $secundaria);
        $this->twiggy->set('primaria', $primaria);
        $this->twiggy->set('cantidad_hombres',  $cantidad_hombres);
        $this->twiggy->set('cantidad_mujeres', $cantidad_mujeres);
        $this->twiggy->set('promedio_hectareas', $promedio_hectareas);
        $this->twiggy->set('promedio_utilidad', $promedio_utilidad);
        $this->twiggy->set('promedio_egresos', $promedio_egresos);
        $this->twiggy->set('promedio_totaling', $promedio_totaling);
        $this->twiggy->set('promedio_ingagro', $promedio_ingagro);
        $this->twiggy->set('promedio_edad', $promedio_edad);
        $this->twiggy->template("reportes/perfilproductor");
        $this->twiggy->display();
    }
}