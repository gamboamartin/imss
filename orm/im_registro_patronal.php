<?php
namespace models;
use base\orm\modelo;
use PDO;

class im_registro_patronal extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false, 'fc_csd' => $tabla, 'org_sucursal' => 'fc_csd',
            'org_empresa' => 'org_sucursal','im_clase_riesgo' => $tabla);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }
}