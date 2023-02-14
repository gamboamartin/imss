<?php
namespace gamboamartin\im_registro_patronal\models;
use base\orm\modelo;
use gamboamartin\cat_sat\models\cat_sat_isn;
use gamboamartin\empleado\models\em_clase_riesgo;
use gamboamartin\empleado\models\em_registro_patronal;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_csd;
use PDO;
use stdClass;

class im_registro_patronal extends modelo{
    public function __construct(PDO $link){
        $tabla = "im_registro_patronal";
        $columnas = array($tabla=>false, 'fc_csd' => $tabla, 'cat_sat_isn'=>$tabla,'org_sucursal' => 'fc_csd',
            'org_empresa' => 'org_sucursal','em_clase_riesgo' => $tabla,'dp_calle_pertenece'=>'org_sucursal',
            'dp_colonia_postal'=>'dp_calle_pertenece','dp_cp'=>'dp_colonia_postal',
            'cat_sat_regimen_fiscal'=>'org_empresa');
        $campos_obligatorios = array('em_clase_riesgo_id','fc_csd_id','descripcion_select');

        $campos_view = array();
        $campos_view['fc_csd_id']['type'] = 'selects';
        $campos_view['fc_csd_id']['model'] = (new fc_csd($link));
        $campos_view['em_clase_riesgo_id']['type'] = 'selects';
        $campos_view['em_clase_riesgo_id']['model'] = (new em_clase_riesgo($link));
        $campos_view['descripcion']['type'] = "inputs";
        $campos_view['cat_sat_isn_id']['type'] = 'selects';
        $campos_view['cat_sat_isn_id']['model'] = (new cat_sat_isn($link));

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,campos_view:  $campos_view );
        $this->NAMESPACE = __NAMESPACE__;
    }

    public function alta_bd(): array|stdClass
    {

        $keys = array('fc_csd_id','em_clase_riesgo_id','cat_sat_isn_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }

        $keys = array('descripcion');
        $valida = $this->validacion->valida_existencia_keys(keys: $keys,registro:  $this->registro);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al validar registro',data:  $valida);
        }


        $fc_csd = (new fc_csd(link: $this->link))->registro(registro_id: $this->registro['fc_csd_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el registro',data:  $fc_csd);
        }


        $em_clase_riesgo = (new em_clase_riesgo(link: $this->link))->registro(registro_id: $this->registro['em_clase_riesgo_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el registro',data:  $em_clase_riesgo);
        }


        if(!isset($this->registro['codigo'])){
            $this->registro['codigo'] = $this->registro['descripcion'];
        }

        if(!isset($this->registro['codigo_bis'])) {
            $this->registro['codigo_bis'] = $this->registro['codigo'] . ' ' . $fc_csd['org_empresa_rfc'];
        }

        if(!isset($this->registro['alias'])) {
            $this->registro['alias'] = $this->registro['codigo_bis'];
        }

        if(!isset($this->registro['descripcion_select'])) {
            $this->registro['descripcion_select'] = $fc_csd['org_empresa_razon_social'] . ' ' . $this->registro['descripcion'];
        }


        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error('Error al dar de alta registro',$r_alta_bd);
        }


        $em_registro_patronal_ins = $this->registro(registro_id: $r_alta_bd->registro_id,columnas_en_bruto: true);
        if(errores::$error){
            return $this->error->error('Error al obtener registro',$r_alta_bd);
        }


        if(array_key_exists('im_clase_riesgo_id',$em_registro_patronal_ins)){
            unset($em_registro_patronal_ins['im_clase_riesgo_id']);
        }
        if(array_key_exists('usuario_alta_id',$em_registro_patronal_ins)){
            unset($em_registro_patronal_ins['usuario_alta_id']);
        }
        if(array_key_exists('usuario_update_id',$em_registro_patronal_ins)){
            unset($em_registro_patronal_ins['usuario_update_id']);
        }

        $r_alta_em_registro_patronal = (new em_registro_patronal(link: $this->link))->alta_registro(
            registro: $em_registro_patronal_ins);
        if(errores::$error){
            return $this->error->error('Error al dar de alta registro desde emp',$r_alta_em_registro_patronal);
        }

        return $r_alta_bd;
    }

    public function modifica_bd(array $registro, int $id, bool $reactiva = false): array|stdClass
    {


        $fc_csd = (new fc_csd(link: $this->link))->registro(registro_id: $registro['fc_csd_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el registro',data:  $fc_csd);
        }

        $em_clase_riesgo = (new em_clase_riesgo(link: $this->link))->registro(registro_id: $registro['em_clase_riesgo_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el registro',data:  $em_clase_riesgo);
        }

        if(!isset($registro['codigo'])) {
            $registro['codigo'] = $registro['descripcion'];
        }

        if(!isset($registro['codigo_bis'])) {
            $registro['codigo_bis'] = $registro['codigo'] . ' ' . $fc_csd['org_empresa_rfc'];
        }

        if(!isset($registro['alias'])) {
            $registro['alias'] = $registro['codigo_bis'];
        }

        if(!isset($registro['descripcion_select'])) {
            $registro['descripcion_select'] = $fc_csd['org_empresa_razon_social'] . ' ' . $registro['descripcion'];
        }





        $r_modifica_bd = parent::modifica_bd($registro, $id, $reactiva); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->error->error('Error al modificar registro',$r_modifica_bd);
        }

        return $r_modifica_bd;
    }
}