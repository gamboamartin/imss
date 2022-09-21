<?php
namespace models;
use base\orm\modelo;
use PDO;

class im_detalle_conf_prestaciones extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false, 'im_conf_prestaciones'=>$tabla);
        $campos_obligatorios = array('im_conf_prestaciones_id','n_year','n_dias','n_dias_vacaciones','n_dias_aguinaldo');

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }
}