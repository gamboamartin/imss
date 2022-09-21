<?php
namespace models;
use base\orm\modelo;
use PDO;

class im_conf_pres_empresa extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false, 'org_empresa'=>$tabla, 'im_conf_prestaciones'=>$tabla);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }
}