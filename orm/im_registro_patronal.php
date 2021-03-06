<?php
namespace models;
use base\orm\modelo;
use PDO;

class im_registro_patronal extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false,'org_empresa'=>$tabla,'cat_sat_regimen_fiscal'=>'org_empresa');
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }
}