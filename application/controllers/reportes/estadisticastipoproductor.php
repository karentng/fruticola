<?php

class Estadisticastipoproductor extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //check_profile(array("Administrador", "Coordinador", "Consultas"));
    }

    public function index() {

        $data = array();

        $query = "  SELECT avg(valor), renglonproductivo.id
                    FROM tp_c_respuesta
                    INNER JOIN visita_tipo_productor ON tp_c_respuesta.visita_id = visita_tipo_productor.id
                    INNER JOIN ruat ON ruat.id = visita_tipo_productor.ruat_id
                    INNER JOIN productor ON productor.id = ruat.productor_id
                    INNER JOIN renglonproductivo ON productor.renglon_productivo_id = renglonproductivo.id
                    WHERE pregunta_c_id = :var
                    GROUP BY renglonproductivo.id
                    ORDER BY descripcion
        ";

        $id_reporte1 = array(':var' => 13);
        $id_reporte2 = array(':var' => 19);
        $id_reporte3 = array(':var' => 25);
        $id_reporte4 = array(':var' => 26);

        $promedio_activos = TipoProductor::connection()->query($query, $id_reporte1);
        $promedio_ingresos = TipoProductor::connection()->query($query, $id_reporte2);
        $promedio_egresos = TipoProductor::connection()->query($query, $id_reporte3);
        $promedio_utilidad = TipoProductor::connection()->query($query, $id_reporte4);

        function to_array($model) {
            return $model->to_array();
        }

        $renglones_productivos = array_map('to_array', RenglonProductivo::sorted());

        $row1 = $row2 = $row3 = $row4 = null;


        $data[0][0] = "1.Promedio de activos por productor anual";
        $data[1][0] = "2.Promedio de ingresos por productor anual";
        $data[2][0] = "3.Promedio de egresos por productor anual";
        $data[3][0] = "4.Promedio de utilidad por productor anual";


        foreach ($renglones_productivos as $renglon_productivo) {
            $renglon_id = $renglon_productivo['id'];
            
            ///Inicializo en 0
            $data[0][$renglon_id] = 0;
            ///cargo desde la consulta los promedios
            foreach ($promedio_activos as $column) {
                $id = $column['id'];
                $data[0][$id] = $column['avg'];
            }

            ///Inicializo en 0
            $data[1][$renglon_id] = 0;
            ///cargo desde la consulta los promedios
            foreach ($promedio_ingresos as $column) {
                $id = $column['id'];
                $data[1][$id] = $column['avg'];
            }

            ///Inicializo en 0
            $data[2][$renglon_id] = 0;
            ///cargo desde la consulta los promedios
            foreach ($promedio_egresos as $column) {
                $id = $column['id'];
                $data[2][$id] = $column['avg'];
            }

            ///Inicializo en 0
            $data[3][$renglon_id] = 0;
            ///cargo desde la consulta los promedios
            foreach ($promedio_utilidad as $column) {
                $id = $column['id'];
                $data[3][$id] = $column['avg'];
            }
        }
        
//        echo '<pre>';
//        var_dump($data);
//        echo '</pre>';


        /*
          $data[0][1] = isset($row1['Cítricos']) ? $row1['Cítricos'] : 0;
          $data[0][2] = isset($row1['Aguacate']) ? $row1['Aguacate'] : 0;
          $data[0][3] = isset($row1['Mora']) ? $row1['Mora'] : 0;
          $data[0][4] = isset($row1['Papaya']) ? $row1['Papaya'] : 0;
          $data[0][5] = isset($row1['Melon']) ? $row1['Melon'] : 0;
          $data[0][6] = isset($row1['Maracuya']) ? $row1['Maracuya'] : 0;
          $data[0][7] = isset($row1['Piña']) ? $row1['Piña'] : 0;
          $data[0][8] = isset($row1['Lulo']) ? $row1['Lulo'] : 0;
          $data[0][9] = isset($row1['Mango']) ? $row1['Mango'] : 0;
          $data[0][10] = isset($row1['Chontaduro']) ? $row1['Chontaduro'] : 0;
          $data[0][11] = isset($row1['Bananito']) ? $row1['Bananito'] : 0;
          $data[0][12] = isset($row1['Fresa']) ? $row1['Fresa'] : 0;
          $data[0][13] = isset($row1['Uva']) ? $row1['Uva'] : 0;

          $data[1][1] = isset($row2['Cítricos']) ? $row2['Cítricos'] : 0;
          $data[1][2] = isset($row2['Aguacate']) ? $row2['Aguacate'] : 0;
          $data[1][3] = isset($row2['Mora']) ? $row2['Mora'] : 0;
          $data[1][4] = isset($row2['Papaya']) ? $row2['Papaya'] : 0;
          $data[1][5] = isset($row2['Melon']) ? $row2['Melon'] : 0;
          $data[1][6] = isset($row2['Maracuya']) ? $row2['Maracuya'] : 0;
          $data[1][7] = isset($row2['Piña']) ? $row2['Piña'] : 0;
          $data[1][8] = isset($row2['Lulo']) ? $row2['Lulo'] : 0;
          $data[1][9] = isset($row2['Mango']) ? $row2['Mango'] : 0;
          $data[1][10] = isset($row2['Chontaduro']) ? $row2['Chontaduro'] : 0;
          $data[1][11] = isset($row2['Bananito']) ? $row2['Bananito'] : 0;
          $data[1][12] = isset($row2['Fresa']) ? $row2['Fresa'] : 0;
          $data[1][13] = isset($row2['Uva']) ? $row2['Uva'] : 0;

          $data[2][1] = isset($row3['Cítricos']) ? $row3['Cítricos'] : 0;
          $data[2][2] = isset($row3['Aguacate']) ? $row3['Aguacate'] : 0;
          $data[2][3] = isset($row3['Mora']) ? $row3['Mora'] : 0;
          $data[2][4] = isset($row3['Papaya']) ? $row3['Papaya'] : 0;
          $data[2][5] = isset($row3['Melon']) ? $row3['Melon'] : 0;
          $data[2][6] = isset($row3['Maracuya']) ? $row3['Maracuya'] : 0;
          $data[2][7] = isset($row3['Piña']) ? $row3['Piña'] : 0;
          $data[2][8] = isset($row3['Lulo']) ? $row3['Lulo'] : 0;
          $data[2][9] = isset($row3['Mango']) ? $row3['Mango'] : 0;
          $data[2][10] = isset($row3['Chontaduro']) ? $row3['Chontaduro'] : 0;
          $data[2][11] = isset($row3['Bananito']) ? $row3['Bananito'] : 0;
          $data[2][12] = isset($row3['Fresa']) ? $row3['Fresa'] : 0;
          $data[2][13] = isset($row3['Uva']) ? $row3['Uva'] : 0;

          $data[3][1] = isset($row4['Cítricos']) ? $row4['Cítricos'] : 0;
          $data[3][2] = isset($row4['Aguacate']) ? $row4['Aguacate'] : 0;
          $data[3][3] = isset($row4['Mora']) ? $row4['Mora'] : 0;
          $data[3][4] = isset($row4['Papaya']) ? $row4['Papaya'] : 0;
          $data[3][5] = isset($row4['Melon']) ? $row4['Melon'] : 0;
          $data[3][6] = isset($row4['Maracuya']) ? $row4['Maracuya'] : 0;
          $data[3][7] = isset($row4['Piña']) ? $row4['Piña'] : 0;
          $data[3][8] = isset($row4['Lulo']) ? $row4['Lulo'] : 0;
          $data[3][9] = isset($row4['Mango']) ? $row4['Mango'] : 0;
          $data[3][10] = isset($row4['Chontaduro']) ? $row4['Chontaduro'] : 0;
          $data[3][11] = isset($row4['Bananito']) ? $row4['Bananito'] : 0;
          $data[3][12] = isset($row4['Fresa']) ? $row4['Fresa'] : 0;
          $data[3][13] = isset($row4['Uva']) ? $row4['Uva'] : 0; */

        $this->twiggy->set('renglones_productivos', $renglones_productivos);
        $this->twiggy->set('data', $data);

        $this->twiggy->template("reportes/estadisticastipoproductor");
        $this->twiggy->display();
    }

}
