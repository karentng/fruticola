<?php
 
class Producto extends MyModel
{
    static $table_name = "producto";

    static $belongs_to = array(
        #array('semilla', 'class_name'=>'TipoSemilla', 'foreign_key'=>'semilla_certificada'),
        array('sitio_venta', 'class_name'=>'TipoSitioVenta', 'foreign_key'=>'sitio_venta_id'),
        array('vende_tipo', 'class_name'=>'TipoVende', 'foreign_key'=>'vende_tipo_id'),
        array('forma_pago', 'class_name'=>'TipoFormaPago', 'foreign_key'=>'forma_pago_id'),
    );

}