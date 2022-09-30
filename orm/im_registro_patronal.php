<?php
namespace models;
use base\orm\modelo;
use gamboamartin\facturacion\models\fc_csd;
use PDO;

class im_registro_patronal extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false, 'fc_csd' => $tabla, 'org_sucursal' => 'fc_csd',
            'org_empresa' => 'org_sucursal','im_clase_riesgo' => $tabla,'dp_calle_pertenece'=>'org_sucursal',
            'dp_colonia_postal'=>'dp_calle_pertenece','dp_cp'=>'dp_colonia_postal',
            'cat_sat_regimen_fiscal'=>'org_empresa');
        $campos_obligatorios = array('im_clase_riesgo_id','fc_csd_id','descripcion_select');

        $campos_view = array();
        $campos_view['fc_csd_id']['type'] = 'selects';
        $campos_view['fc_csd_id']['model'] = (new fc_csd($link));

        $campos_view['im_clase_riesgo_id']['type'] = 'selects';
        $campos_view['im_clase_riesgo_id']['model'] = (new im_clase_riesgo($link));

        //$campos_view = array('fc_csd_id'=>array("type" => "selects","model"=>(new fc_csd($link))));

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,campos_view:  $campos_view );
    }
}