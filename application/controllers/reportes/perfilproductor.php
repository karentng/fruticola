<?php

class Perfilproductor extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index()
    {

        $filtro_renglon ="true";
        $filtro_municipio ="true";
        if($this->input->post('renglonProduc')){
            $filtro_renglon = "renglon_productivo_id=".$this->db->escape($this->input->post('renglonProduc'));
        }
        if($this->input->post('municipio')){
            $filtro_municipio = "municipio_id=".$this->db->escape($this->input->post('municipio'));
        }

        //TABLA
        $resultado=Productor::find_by_sql("SELECT avg( date_part('years', age(fecha_nacimiento))) as promedio_edad FROM productor WHERE $filtro_renglon");
        $promedio_edad = $resultado[0]->promedio_edad;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(ingagro)as promedio_ingagro
                                                    FROM
                                                    (SELECT visita_tipo_productor.id as visita_id, SUM(valor)as ingagro
                                                    FROM productor
                                                    INNER JOIN ruat ON productor_id=productor.id
                                                    INNER JOIN visita_tipo_productor ON ruat_id=ruat.id
                                                    INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id AND pregunta_c_id BETWEEN 16  AND 17
                                                    WHERE $filtro_renglon
                                                    GROUP BY visita_tipo_productor.id) as A");

        $promedio_ingagro = $resultado[0]->promedio_ingagro;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_totaling
                                                    FROM (
                                                        SELECT valor
                                                        FROM productor
                                                        INNER JOIN ruat ON productor_id=productor.id
                                                        INNER JOIN visita_tipo_productor ON ruat_id=ruat.id
                                                        INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id AND pregunta_c_id=19
                                                        WHERE $filtro_renglon)as B");

        $promedio_totaling = $resultado[0]->promedio_totaling;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_egresos
                                                        FROM(
                                                            SELECT valor
                                                            FROM productor
                                                            INNER JOIN ruat ON productor_id=productor.id
                                                            INNER JOIN visita_tipo_productor ON ruat_id=ruat.id
                                                            INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id AND pregunta_c_id=25
                                                            WHERE $filtro_renglon)as B");

        $promedio_egresos = $resultado[0]->promedio_egresos;

        $resultado=VisitaTipoProductor::find_by_sql("SELECT avg(valor) as promedio_utilidad
                                                        FROM(
                                                            SELECT valor
                                                            FROM productor
                                                            INNER JOIN ruat ON productor_id=productor.id
                                                            INNER JOIN visita_tipo_productor ON ruat_id=ruat.id
                                                            INNER JOIN tp_c_respuesta ON visita_id=visita_tipo_productor.id AND pregunta_c_id=26
                                                            WHERE $filtro_renglon)as C");
        $promedio_utilidad = $resultado[0]->promedio_utilidad;

        $resultado=Productor::find_by_sql("SELECT COUNT(id) as cantidad_productores FROM productor WHERE $filtro_renglon");
        $cantidad_productores= $resultado[0]->cantidad_productores;


        $resultado=Producto::find_by_sql("SELECT SUM(area_cosechada) as total_hectareas
                                            FROM productor
                                            INNER JOIN ruat ON productor_id=productor.id
                                            INNER JOIN producto ON ruat_id=ruat.id
                                            WHERE $filtro_renglon");
        $total_hectareas= $resultado[0]->total_hectareas;

        $promedio_hectareas=$total_hectareas/$cantidad_productores;

        //GRAFICAS

        // tenencias

        $resultado = Productor::find_by_sql("SELECT count(tenencia_id) as propiedad
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND finca.tenencia_id = 1 AND $filtro_municipio
                                        WHERE $filtro_renglon
                                        GROUP BY tenencia_id ORDER BY tenencia_id");
        $cantidad_tenencia_propiedad = $resultado[0]->propiedad;
        $cantidad_tenencia_propiedad = $cantidad_tenencia_propiedad ? $cantidad_tenencia_propiedad : 0;
        
        $resultado = Productor::find_by_sql("SELECT count(tenencia_id) as propiedad
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND finca.tenencia_id = 2 AND $filtro_municipio
                                        WHERE $filtro_renglon
                                        GROUP BY tenencia_id ORDER BY tenencia_id");
        $cantidad_tenencia_sinpropiedad = $resultado[0]->propiedad;
        $cantidad_tenencia_sinpropiedad = $cantidad_tenencia_sinpropiedad ? $cantidad_tenencia_sinpropiedad : 0;

        $resultado = Productor::find_by_sql("SELECT count(tenencia_id) as propiedad
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND finca.tenencia_id = 3 AND $filtro_municipio
                                        WHERE $filtro_renglon
                                        GROUP BY tenencia_id ORDER BY tenencia_id");
        $cantidad_tenencia_arriendo = $resultado[0]->propiedad;
        $cantidad_tenencia_arriendo = $cantidad_tenencia_arriendo ? $cantidad_tenencia_arriendo : 0;

        $resultado = Productor::find_by_sql("SELECT count(tenencia_id) as propiedad
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND finca.tenencia_id = 4 AND $filtro_municipio
                                        WHERE $filtro_renglon
                                        GROUP BY tenencia_id ORDER BY tenencia_id");
        $cantidad_tenencia_comodato = $resultado[0]->propiedad;
        $cantidad_tenencia_comodato = $cantidad_tenencia_comodato ? $cantidad_tenencia_comodato : 0;

        $resultado = Productor::find_by_sql("SELECT count(tenencia_id) as propiedad
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND finca.tenencia_id = 5 AND $filtro_municipio
                                        WHERE $filtro_renglon
                                        GROUP BY tenencia_id ORDER BY tenencia_id");
        $cantidad_tenencia_usufructo = $resultado[0]->propiedad;
        $cantidad_tenencia_usufructo = $cantidad_tenencia_usufructo ? $cantidad_tenencia_usufructo : 0;

        $resultado = Productor::find_by_sql("SELECT count(tenencia_id) as propiedad
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND finca.tenencia_id = 6 AND $filtro_municipio
                                        WHERE $filtro_renglon
                                        GROUP BY tenencia_id ORDER BY tenencia_id");
        $cantidad_tenencia_aparceria = $resultado[0]->propiedad;
        $cantidad_tenencia_aparceria = $cantidad_tenencia_aparceria ? $cantidad_tenencia_aparceria : 0;

        $resultado = Productor::find_by_sql("SELECT count(tenencia_id) as propiedad
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND finca.tenencia_id = 7 AND $filtro_municipio
                                        WHERE $filtro_renglon
                                        GROUP BY tenencia_id ORDER BY tenencia_id");
        $cantidad_tenencia_colectiva = $resultado[0]->propiedad;
        $cantidad_tenencia_colectiva = $cantidad_tenencia_colectiva ? $cantidad_tenencia_colectiva : 0;

        $resultado = Productor::find_by_sql("SELECT count(tenencia_id) as propiedad
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND finca.tenencia_id = 8 AND $filtro_municipio
                                        WHERE $filtro_renglon
                                        GROUP BY tenencia_id ORDER BY tenencia_id");
        $cantidad_tenencia_otro = $resultado[0]->propiedad;
        $cantidad_tenencia_otro = $cantidad_tenencia_otro ? $cantidad_tenencia_otro : 0;
        // fin tenencias

        // vias de acceso
        $resultado = Productor::find_by_sql("SELECT count(via_disponibilidad) as pavimentadabuena
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND via_disponibilidad = 't' AND via_tipo_id = 1 AND via_estado_id = 1 AND $filtro_municipio
                                        WHERE $filtro_renglon");
        $cantidad_vias_pavB = $resultado[0]->pavimentadabuena;

        $resultado = Productor::find_by_sql("SELECT count(via_disponibilidad) as pavimentadaregular
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND via_disponibilidad = 't' AND via_tipo_id = 1 AND via_estado_id = 2 AND $filtro_municipio
                                        WHERE $filtro_renglon");
        $cantidad_vias_pavR = $resultado[0]->pavimentadaregular;

        $resultado = Productor::find_by_sql("SELECT count(via_disponibilidad) as pavimentadamala
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND via_disponibilidad = 't' AND via_tipo_id = 1 AND via_estado_id = 3 AND $filtro_municipio
                                        WHERE $filtro_renglon");
        $cantidad_vias_pavM = $resultado[0]->pavimentadamala;

        $resultado = Productor::find_by_sql("SELECT count(via_disponibilidad) as nopavimentadabuena
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND via_disponibilidad = 't' AND via_tipo_id = 2 AND via_estado_id = 1 AND $filtro_municipio
                                        WHERE $filtro_renglon");
        $cantidad_vias_nopavB = $resultado[0]->nopavimentadabuena;

        $resultado = Productor::find_by_sql("SELECT count(via_disponibilidad) as nopavimentadaregular
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND via_disponibilidad = 't' AND via_tipo_id = 2 AND via_estado_id = 2 AND $filtro_municipio
                                        WHERE $filtro_renglon");
        $cantidad_vias_nopavR = $resultado[0]->nopavimentadaregular;

        $resultado = Productor::find_by_sql("SELECT count(via_disponibilidad) as nopavimentadamala
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND via_disponibilidad = 't' AND via_tipo_id = 2 AND via_estado_id = 3 AND $filtro_municipio
                                        WHERE $filtro_renglon");
        $cantidad_vias_nopavM = $resultado[0]->nopavimentadamala;
        //vias de acceso

        $resultado=Productor::find_by_sql("SELECT COUNT(sexo) as cantidad_hombres
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                        WHERE sexo='M' AND $filtro_renglon");
        $cantidad_hombres=$resultado[0]->cantidad_hombres;

        $resultado=Productor::find_by_sql("SELECT COUNT(sexo) as cantidad_mujeres
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                        WHERE sexo='F' AND $filtro_renglon");
        $cantidad_mujeres=$resultado[0]->cantidad_mujeres;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as primaria
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                        WHERE nivel_educativo_id='1' AND $filtro_renglon");
        $primaria =$resultado[0]->primaria;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as secundaria
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                        WHERE nivel_educativo_id='2' AND $filtro_renglon");
        $secundaria =$resultado[0]->secundaria;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as tecnica
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                        WHERE nivel_educativo_id='3' AND $filtro_renglon");
        $tecnica = $resultado[0]->tecnica;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as tecnologica
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                        WHERE nivel_educativo_id='4' AND $filtro_renglon");
        $tecnologica = $resultado[0]->tecnologica;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as universitaria
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                        WHERE nivel_educativo_id='5' AND $filtro_renglon");
        $universitaria = $resultado[0]->universitaria;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as ninguna
                                        FROM productor
                                        INNER JOIN ruat ON productor_id=productor.id
                                        INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                        WHERE nivel_educativo_id='6' AND $filtro_renglon");
        $ninguna =$resultado[0]->ninguna;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id)as agua
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_servicio ON finca_id=finca.id AND servicio_id=1
                                                WHERE $filtro_renglon");
        $agua =$resultado[0]->agua;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id)as acueducto
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_servicio ON finca_id=finca.id AND servicio_id=2
                                                WHERE $filtro_renglon");
        $acueducto =$resultado[0]->acueducto;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id)as internet
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_servicio ON finca_id=finca.id AND servicio_id=3
                                                WHERE $filtro_renglon");
        $internet =$resultado[0]->internet;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id)as electricidad
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_servicio ON finca_id=finca.id AND servicio_id=4
                                                WHERE $filtro_renglon");
        $electricidad =$resultado[0]->electricidad;


        $resultado=Economia::find_by_sql("SELECT COUNT(credito) as credito_si
                                            FROM
                                            (SELECT CASE WHEN economia.credito_id IS NULL THEN 'No' ELSE 'Sí' END AS credito
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as X
                                            INNER JOIN economia ON productor_id=X.id
                                            WHERE $filtro_renglon)as D
                                            WHERE credito='Sí'");
        $credito_si =$resultado[0]->credito_si;
        
        $resultado=Economia::find_by_sql("SELECT COUNT(credito) as credito_no
                                            FROM
                                            (SELECT CASE WHEN economia.credito_id IS NULL THEN 'No' ELSE 'Sí' END AS credito
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as X
                                            INNER JOIN economia ON productor_id=X.id
                                            WHERE $filtro_renglon)as D
                                            WHERE credito='No'");
        $credito_no =$resultado[0]->credito_no;

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as bancos
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as X                                            
                                            INNER JOIN economia ON productor_id=X.id AND credito_id=1
                                            WHERE $filtro_renglon");
        $bancos=$resultado[0]->bancos; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as agremiaciones
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as X
                                            INNER JOIN economia ON productor_id=X.id AND credito_id=2
                                            WHERE $filtro_renglon");
        $agremiaciones=$resultado[0]->agremiaciones; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as entidades
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as X
                                            INNER JOIN economia ON productor_id=X.id AND credito_id=3
                                            WHERE $filtro_renglon");
        $entidades=$resultado[0]->entidades; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as prestamistas
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as X
                                            INNER JOIN economia ON productor_id=X.id AND credito_id=4
                                            WHERE $filtro_renglon");
        $prestamistas=$resultado[0]->prestamistas; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as familiares
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as X
                                            INNER JOIN economia ON productor_id=X.id AND credito_id=5
                                            WHERE $filtro_renglon");
        $familiares=$resultado[0]->familiares; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as empresas
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as X
                                            INNER JOIN economia ON productor_id=X.id AND credito_id=6
                                            WHERE $filtro_renglon");
        $empresas=$resultado[0]->empresas; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as otro
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as X
                                            INNER JOIN economia ON productor_id=X.id AND credito_id=7
                                            WHERE $filtro_renglon");
        $otroCredito=$resultado[0]->otro; 

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as agropecuaria
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=1
                                                WHERE $filtro_renglon");
        $agropecuaria=$resultado[0]->agropecuaria;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as asistencial
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=2
                                                WHERE $filtro_renglon");
        $asistencial=$resultado[0]->asistencial;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as comercial
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=3
                                                WHERE $filtro_renglon");
        $comercial=$resultado[0]->comercial;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as cultural
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=4
                                                WHERE $filtro_renglon");
        $cultural=$resultado[0]->cultural;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as deportiva
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=5
                                                WHERE $filtro_renglon");
        $deportiva=$resultado[0]->deportiva;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as educativa
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=6
                                                WHERE $filtro_renglon");
        $educativa=$resultado[0]->educativa;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as etnica
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=7
                                                WHERE $filtro_renglon");
        $etnica=$resultado[0]->etnica;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as politica
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=8
                                                WHERE $filtro_renglon");
        $politica=$resultado[0]->politica;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as salud
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=9
                                                WHERE $filtro_renglon");
        $salud=$resultado[0]->salud;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as social
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=10
                                                WHERE $filtro_renglon");
        $social=$resultado[0]->social;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as tecnologico
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=11
                                                WHERE $filtro_renglon");
        $tecnologico=$resultado[0]->tecnologico;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as capacitacion
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=1
                                                WHERE $filtro_renglon");
        $capacitacion=$resultado[0]->capacitacion;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as economico
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=2
                                                WHERE $filtro_renglon");
        $economico=$resultado[0]->economico;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as especie
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=3
                                                WHERE $filtro_renglon");
        $especie=$resultado[0]->especie;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as participacion
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=4
                                                WHERE $filtro_renglon");
        $participacion=$resultado[0]->participacion;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as reconocimiento
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=5
                                                WHERE $filtro_renglon");
        $reconocimiento=$resultado[0]->reconocimiento;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as recreacion
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=6
                                                WHERE $filtro_renglon");
        $recreacion=$resultado[0]->recreacion;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as otro
                                                FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=7
                                                WHERE $filtro_renglon");
        $otroBeneficio=$resultado[0]->otro;


        /*$resultado=Economia::find_by_sql("SELECT COUNT(credito) as credito_si FROM (SELECT CASE WHEN economia.credito_id IS NULL THEN 'No' ELSE 'Sí' END AS credito
                                            FROM economia) as c
                                            WHERE credito='Sí'");
        $credito_si =$resultado[0]->credito_si;*/
        
        $resultado=Producto::find_by_sql("SELECT COUNT(asistencia)as asist_no
                                            FROM(
                                            SELECT CASE WHEN asistencia_programa IS NULL THEN 'No' ELSE 'Si' END as asistencia
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                            INNER JOIN ruat ON productor_id=productor.id
                                            INNER JOIN producto ON ruat_id=ruat.id
                                            WHERE $filtro_renglon) as C
                                            WHERE asistencia='No'");
        $asist_no =$resultado[0]->asist_no;

        $resultado=Producto::find_by_sql("SELECT COUNT(asistencia)as asist_si
                                            FROM(
                                            SELECT CASE WHEN asistencia_programa IS NULL THEN 'No' ELSE 'Si' END as asistencia
                                            FROM (SELECT * FROM productor
                                                WHERE id IN(
                                                        SELECT productor_id FROM ruat
                                                        WHERE id IN(
                                                            SELECT ruat_id FROM finca
                                                            WHERE $filtro_municipio)
                                                    )
                                                ) as productor
                                            INNER JOIN ruat ON productor_id=productor.id
                                            INNER JOIN producto ON ruat_id=ruat.id
                                            WHERE $filtro_renglon) as C
                                            WHERE asistencia='Si'");
        $asist_si =$resultado[0]->asist_si;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as animal
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=1
                                                WHERE $filtro_renglon");
        $animal=$resultado[0]->animal;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as camion
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=2
                                                WHERE $filtro_renglon");
        $camion=$resultado[0]->camion;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as bicicleta
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=3
                                                WHERE $filtro_renglon");
        $bicicleta=$resultado[0]->bicicleta;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as caminata
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=4
                                                WHERE $filtro_renglon");
        $caminata=$resultado[0]->caminata;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as tractor
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=5
                                                WHERE $filtro_renglon");
        $tractor=$resultado[0]->tractor;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as barco
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=6
                                                WHERE $filtro_renglon");
        $barco=$resultado[0]->barco;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as canoa
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=7
                                                WHERE $filtro_renglon");
        $canoa=$resultado[0]->canoa;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as kayak
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=8
                                                WHERE $filtro_renglon");
        $kayak=$resultado[0]->kayak;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as planchon
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=9
                                                WHERE $filtro_renglon");
        $planchon=$resultado[0]->planchon;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as otro
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=10
                                                WHERE $filtro_renglon");
        $otroTransporte=$resultado[0]->otro;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as herramientas
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=1
                                                WHERE $filtro_renglon");
        $herramientas=$resultado[0]->herramientas;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as utensilios
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=2
                                                WHERE $filtro_renglon");
        $utensilios=$resultado[0]->utensilios;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as equipos
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=3
                                                WHERE $filtro_renglon");
        $equipos=$resultado[0]->equipos;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as liviana
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=4
                                                WHERE $filtro_renglon");
        $liviana=$resultado[0]->liviana;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as pesada
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=5
                                                WHERE $filtro_renglon");
        $pesada=$resultado[0]->pesada;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as otros
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id AND $filtro_municipio
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=6
                                                WHERE $filtro_renglon");
        $otros=$resultado[0]->otros;

        $renglones = assoc(RenglonProductivo::sorted(), 'id', 'descripcion');
        $renglones = array('' => '(Todos)') + $renglones;

        $municipios = assoc(Municipio::find('all', array('conditions' => array('departamento_id = ?', 30), 'order' => 'nombre')), 'id', 'nombre');  
        $municipios = array('' => '(Todos)') + $municipios;

        $opciones = array(
            0 => '1. Género',
            1 => '2. Nivel Educativo',
            2 => '3. Servicios Públicos',
            3 => '4. Crédito',
            4 => '5. Procedencia Crédito',
            5 => '6. Clase de Asociatividad<',
            6 => '7. Beneficio Asociatividad',
            7 => '8. Asistencia Técnica',
            8 => '9. Medios de Transporte',
            9 => '10. Maquinarias y Equipos',
            10 => '11. Tenencia',
            11 => '12. Estado de vias de acceso',
        );

        $this->twiggy->set('cantidad_tenencia_propiedad', $cantidad_tenencia_propiedad);
        $this->twiggy->set('cantidad_tenencia_sinpropiedad', $cantidad_tenencia_sinpropiedad);
        $this->twiggy->set('cantidad_tenencia_arriendo', $cantidad_tenencia_arriendo);
        $this->twiggy->set('cantidad_tenencia_comodato', $cantidad_tenencia_comodato);
        $this->twiggy->set('cantidad_tenencia_usufructo', $cantidad_tenencia_usufructo);
        $this->twiggy->set('cantidad_tenencia_aparceria', $cantidad_tenencia_aparceria);
        $this->twiggy->set('cantidad_tenencia_colectiva', $cantidad_tenencia_colectiva);
        $this->twiggy->set('cantidad_tenencia_otro', $cantidad_tenencia_otro);

        $this->twiggy->set('cantidad_vias_pavB', $cantidad_vias_pavB);
        $this->twiggy->set('cantidad_vias_pavR', $cantidad_vias_pavR);
        $this->twiggy->set('cantidad_vias_pavM', $cantidad_vias_pavM);
        $this->twiggy->set('cantidad_vias_nopavB', $cantidad_vias_nopavB);
        $this->twiggy->set('cantidad_vias_nopavR', $cantidad_vias_nopavR);
        $this->twiggy->set('cantidad_vias_nopavM', $cantidad_vias_nopavM);

        $this->twiggy->set('opciones', $opciones);
        $this->twiggy->set('municipios', $municipios);
        $this->twiggy->set('renglones', $renglones);
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
        $this->twiggy->set('otroBeneficio', $otroBeneficio);
        $this->twiggy->set('otroTransporte', $otroTransporte);
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
        $this->twiggy->set('otroCredito', $otroCredito);
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


/*
$resultado=Productor::find_by_sql("SELECT COUNT(sexo) as cantidad_hombres FROM productor WHERE sexo='M' AND $filtro_renglon");
        $cantidad_hombres=$resultado[0]->cantidad_hombres;

        $resultado=Productor::find_by_sql("SELECT COUNT(sexo) as cantidad_mujeres FROM productor WHERE sexo='F' AND $filtro_renglon");
        $cantidad_mujeres=$resultado[0]->cantidad_mujeres;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as primaria FROM productor WHERE nivel_educativo_id='1' AND $filtro_renglon");
        $primaria =$resultado[0]->primaria;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as secundaria FROM productor WHERE nivel_educativo_id='2' AND $filtro_renglon");
        $secundaria =$resultado[0]->secundaria;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as tecnica FROM productor WHERE nivel_educativo_id='3' AND $filtro_renglon");
        $tecnica = $resultado[0]->tecnica;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as tecnologica FROM productor WHERE nivel_educativo_id='4' AND $filtro_renglon");
        $tecnologica = $resultado[0]->tecnologica;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as universitaria FROM productor WHERE nivel_educativo_id='5' AND $filtro_renglon");
        $universitaria = $resultado[0]->universitaria;

        $resultado=Productor::find_by_sql("SELECT COUNT(nivel_educativo_id)as ninguna FROM productor WHERE nivel_educativo_id='6' AND $filtro_renglon");
        $ninguna =$resultado[0]->ninguna;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id)as agua
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_servicio ON finca_id=finca.id AND servicio_id=1
                                                WHERE $filtro_renglon");
        $agua =$resultado[0]->agua;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id)as acueducto
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_servicio ON finca_id=finca.id AND servicio_id=2
                                                WHERE $filtro_renglon");
        $acueducto =$resultado[0]->acueducto;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id)as internet
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_servicio ON finca_id=finca.id AND servicio_id=3
                                                WHERE $filtro_renglon");
        $internet =$resultado[0]->internet;

        $resultado=FincaServicio::find_by_sql("SELECT COUNT(servicio_id)as electricidad
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_servicio ON finca_id=finca.id AND servicio_id=4
                                                WHERE $filtro_renglon");
        $electricidad =$resultado[0]->electricidad;


        $resultado=Economia::find_by_sql("SELECT COUNT(credito) as credito_si
                                            FROM
                                            (SELECT CASE WHEN economia.credito_id IS NULL THEN 'No' ELSE 'Sí' END AS credito
                                            FROM productor
                                            INNER JOIN economia ON productor_id=productor.id
                                            WHERE $filtro_renglon)as D
                                            WHERE credito='Sí'");
        $credito_si =$resultado[0]->credito_si;
        
        $resultado=Economia::find_by_sql("SELECT COUNT(credito) as credito_no
                                            FROM
                                            (SELECT CASE WHEN economia.credito_id IS NULL THEN 'No' ELSE 'Sí' END AS credito
                                            FROM productor
                                            INNER JOIN economia ON productor_id=productor.id
                                            WHERE $filtro_renglon)as D
                                            WHERE credito='No'");
        $credito_no =$resultado[0]->credito_no;

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as bancos
                                            FROM productor
                                            INNER JOIN economia ON productor_id=productor.id AND credito_id=1
                                            WHERE $filtro_renglon");
        $bancos=$resultado[0]->bancos; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as agremiaciones
                                            FROM productor
                                            INNER JOIN economia ON productor_id=productor.id AND credito_id=2
                                            WHERE $filtro_renglon");
        $agremiaciones=$resultado[0]->agremiaciones; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as entidades
                                            FROM productor
                                            INNER JOIN economia ON productor_id=productor.id AND credito_id=3
                                            WHERE $filtro_renglon");
        $entidades=$resultado[0]->entidades; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as prestamistas
                                            FROM productor
                                            INNER JOIN economia ON productor_id=productor.id AND credito_id=4
                                            WHERE $filtro_renglon");
        $prestamistas=$resultado[0]->prestamistas; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as familiares
                                            FROM productor
                                            INNER JOIN economia ON productor_id=productor.id AND credito_id=5
                                            WHERE $filtro_renglon");
        $familiares=$resultado[0]->familiares; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as empresas
                                            FROM productor
                                            INNER JOIN economia ON productor_id=productor.id AND credito_id=6
                                            WHERE $filtro_renglon");
        $empresas=$resultado[0]->empresas; 

        $resultado=Economia::find_by_sql("SELECT COUNT(credito_id)as otro
                                            FROM productor
                                            INNER JOIN economia ON productor_id=productor.id AND credito_id=7
                                            WHERE $filtro_renglon");
        $otro=$resultado[0]->otro; 

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as agropecuaria
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=1
                                                WHERE $filtro_renglon");
        $agropecuaria=$resultado[0]->agropecuaria;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as asistencial
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=2
                                                WHERE $filtro_renglon");
        $asistencial=$resultado[0]->asistencial;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as comercial
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=3
                                                WHERE $filtro_renglon");
        $comercial=$resultado[0]->comercial;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as cultural
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=4
                                                WHERE $filtro_renglon");
        $cultural=$resultado[0]->cultural;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as deportiva
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=5
                                                WHERE $filtro_renglon");
        $deportiva=$resultado[0]->deportiva;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as educativa
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=6
                                                WHERE $filtro_renglon");
        $educativa=$resultado[0]->educativa;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as etnica
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=7
                                                WHERE $filtro_renglon");
        $etnica=$resultado[0]->etnica;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as politica
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=8
                                                WHERE $filtro_renglon");
        $politica=$resultado[0]->politica;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as salud
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=9
                                                WHERE $filtro_renglon");
        $salud=$resultado[0]->salud;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as social
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=10
                                                WHERE $filtro_renglon");
        $social=$resultado[0]->social;

        $resultado=OrgasociadaClase::find_by_sql("SELECT COUNT(clase_id) as tecnologico
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_clase ON orgasociada_id=orgasociada.id AND clase_id=11
                                                WHERE $filtro_renglon");
        $tecnologico=$resultado[0]->tecnologico;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as capacitacion
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=1
                                                WHERE $filtro_renglon");
        $capacitacion=$resultado[0]->capacitacion;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as economico
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=2
                                                WHERE $filtro_renglon");
        $economico=$resultado[0]->economico;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as especie
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=3
                                                WHERE $filtro_renglon");
        $especie=$resultado[0]->especie;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as participacion
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=4
                                                WHERE $filtro_renglon");
        $participacion=$resultado[0]->participacion;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as reconocimiento
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=5
                                                WHERE $filtro_renglon");
        $reconocimiento=$resultado[0]->reconocimiento;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as recreacion
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=6
                                                WHERE $filtro_renglon");
        $recreacion=$resultado[0]->recreacion;

        $resultado=OrgasociadaBeneficio::find_by_sql("SELECT COUNT(beneficio_id) as otro
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN orgasociada ON ruat_id=ruat.id
                                                INNER JOIN orgasociada_beneficio ON orgasociada_id=orgasociada.id AND beneficio_id=7
                                                WHERE $filtro_renglon");
        $otro=$resultado[0]->otro;


        $resultado=Economia::find_by_sql("SELECT COUNT(credito) as credito_si FROM (SELECT CASE WHEN economia.credito_id IS NULL THEN 'No' ELSE 'Sí' END AS credito
                                            FROM economia) as c
                                            WHERE credito='Sí'");
        $credito_si =$resultado[0]->credito_si;
        
        $resultado=Producto::find_by_sql("SELECT COUNT(asistencia)as asist_no
                                            FROM(
                                            SELECT CASE WHEN asistencia_programa IS NULL THEN 'No' ELSE 'Si' END as asistencia
                                            FROM productor
                                            INNER JOIN ruat ON productor_id=productor.id
                                            INNER JOIN producto ON ruat_id=ruat.id
                                            WHERE $filtro_renglon) as C
                                            WHERE asistencia='No'");
        $asist_no =$resultado[0]->asist_no;

        $resultado=Producto::find_by_sql("SELECT COUNT(asistencia)as asist_si
                                            FROM(
                                            SELECT CASE WHEN asistencia_programa IS NULL THEN 'No' ELSE 'Si' END as asistencia
                                            FROM productor
                                            INNER JOIN ruat ON productor_id=productor.id
                                            INNER JOIN producto ON ruat_id=ruat.id
                                            WHERE $filtro_renglon) as C
                                            WHERE asistencia='Si'");
        $asist_si =$resultado[0]->asist_si;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as animal
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=1
                                                WHERE $filtro_renglon");
        $animal=$resultado[0]->animal;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as camion
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=2
                                                WHERE $filtro_renglon");
        $camion=$resultado[0]->camion;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as bicicleta
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=3
                                                WHERE $filtro_renglon");
        $bicicleta=$resultado[0]->bicicleta;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as caminata
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=4
                                                WHERE $filtro_renglon");
        $caminata=$resultado[0]->caminata;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as tractor
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=5
                                                WHERE $filtro_renglon");
        $tractor=$resultado[0]->tractor;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as barco
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=6
                                                WHERE $filtro_renglon");
        $barco=$resultado[0]->barco;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as canoa
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=7
                                                WHERE $filtro_renglon");
        $canoa=$resultado[0]->canoa;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as kayak
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=8
                                                WHERE $filtro_renglon");
        $kayak=$resultado[0]->kayak;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as planchon
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=9
                                                WHERE $filtro_renglon");
        $planchon=$resultado[0]->planchon;

        $resultado=FincaTransporte::find_by_sql("SELECT COUNT(transporte_id) as otro
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_transporte ON finca_id=finca.id AND transporte_id=10
                                                WHERE $filtro_renglon");
        $otro=$resultado[0]->otro;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as herramientas
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=1
                                                WHERE $filtro_renglon");
        $herramientas=$resultado[0]->herramientas;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as utensilios
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=2
                                                WHERE $filtro_renglon");
        $utensilios=$resultado[0]->utensilios;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as equipos
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=3
                                                WHERE $filtro_renglon");
        $equipos=$resultado[0]->equipos;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as liviana
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=4
                                                WHERE $filtro_renglon");
        $liviana=$resultado[0]->liviana;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as pesada
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=5
                                                WHERE $filtro_renglon");
        $pesada=$resultado[0]->pesada;

        $resultado=FincaMaquinaria::find_by_sql("SELECT COUNT(maquinaria_id) as otros
                                                FROM productor
                                                INNER JOIN ruat ON productor_id=productor.id
                                                INNER JOIN finca ON ruat_id=ruat.id
                                                INNER JOIN finca_maquinaria ON finca_id=finca.id AND maquinaria_id=6
                                                WHERE $filtro_renglon");
        $otros=$resultado[0]->otros;
*/