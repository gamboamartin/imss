<?php
namespace gamboamartin\im_registro_patronal\models;
use base\orm\modelo;

use PDO;

class im_codigo_clase extends modelo{
    public function __construct(PDO $link){
        $tabla = "im_codigo_clase";
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();


        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;
    }



}