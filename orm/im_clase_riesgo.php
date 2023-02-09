<?php
namespace gamboamartin\im_registro_patronal\models;
use base\orm\modelo;
use gamboamartin\empleado\models\em_clase_riesgo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class im_clase_riesgo extends modelo{
    public function __construct(PDO $link){
        $tabla = "im_clase_riesgo";
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $this->NAMESPACE = __NAMESPACE__;

    }

    public function alta_bd(): array|stdClass
    {
        $keys = array('descripcion','factor');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar registro', data: $valida);
        }

        $keys = array('factor');
        $valida = $this->validacion->valida_double_mayores_0(keys: $keys,registro:  $this->registro);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al validar registro', data: $valida);
        }

        if(!isset($this->registro['codigo'])){
            $this->registro['codigo'] = $this->registro['descripcion'].$this->registro['factor'];
        }

        if(!isset($this->registro['codigo_bis'])){
            $this->registro['codigo_bis'] = $this->registro['codigo'];
        }

        $alta_bd = parent::alta_bd();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar clase riesgo', data: $alta_bd);
        }


        $em_clase_riesgo_ins['id'] = $alta_bd->registro_id;
        $em_clase_riesgo_ins['descripcion'] = $this->registro['descripcion'];
        $em_clase_riesgo_ins['factor'] = $this->registro['factor'];

        $r_em_clase_riesgo = (new em_clase_riesgo(link: $this->link))->alta_registro(registro: $em_clase_riesgo_ins);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar em clase riesgo', data: $r_em_clase_riesgo);
        }


        return $alta_bd;
    }
}