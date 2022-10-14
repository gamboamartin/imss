<?php
namespace gamboamartin\im_registro_patronal\test;
use base\orm\modelo_base;
use gamboamartin\errores\errores;
use models\im_movimiento;
use models\im_registro_patronal;
use models\im_tipo_movimiento;
use models\im_uma;
use PDO;

class base_test{

    public function alta_em_empleado(PDO $link): array|\stdClass
    {

        $alta = (new \gamboamartin\empleado\test\base_test())->alta_em_empleado($link);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_fc_csd(PDO $link): array|\stdClass
    {

        $alta = (new \gamboamartin\facturacion\tests\base_test())->alta_fc_csd($link);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_im_movimiento(PDO $link): array|\stdClass
    {

        $alta = (new base_test())->alta_im_registro_patronal($link);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }

        $org_puesto = array();
        $org_puesto['id'] = 1;
        $org_puesto['codigo'] = 1;
        $org_puesto['descripcion'] = 1;
        $org_puesto['im_registro_patronal_id'] = 1;
        $org_puesto['im_tipo_movimiento_id'] = 1;
        $org_puesto['em_empleado_id'] = 1;
        $org_puesto['fecha'] = '2022-09-13';


        $alta = (new im_movimiento($link))->alta_registro($org_puesto);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_im_registro_patronal(PDO $link): array|\stdClass
    {

        $alta = (new base_test())->alta_fc_csd($link);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }

        $org_puesto = array();
        $org_puesto['id'] = 1;
        $org_puesto['codigo'] = 1;
        $org_puesto['descripcion'] = 1;
        $org_puesto['im_clase_riesgo_id'] = 1;
        $org_puesto['fc_csd_id'] = 1;
        $org_puesto['descripcion_select'] = 1;


        $alta = (new im_registro_patronal($link))->alta_registro($org_puesto);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_im_tipo_movimiento(PDO $link, string $codigo = '1', string $codigo_bis = '1',
                                            string $descripcion = '1', string $es_alta = 'inactivo', int $id = 1): array|\stdClass
    {
        $org_puesto = array();
        $org_puesto['id'] = $id;
        $org_puesto['codigo'] = $codigo;
        $org_puesto['codigo_bis'] = $codigo_bis;
        $org_puesto['descripcion'] = $descripcion;
        $org_puesto['es_alta'] = $es_alta;



        $alta = (new im_tipo_movimiento($link))->alta_registro($org_puesto);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }

    public function alta_im_uma(PDO $link, string $codigo = '1', string $codigo_bis = '1', string $descripcion = '1',
                                string $fecha_fin = '2020-12-31', string $fecha_inicio ='2020-01-01',
                                int $id = 1): array|\stdClass
    {
        $registro = array();
        $registro['id'] = $id;
        $registro['codigo'] = $codigo;
        $registro['codigo_bis'] = $codigo_bis;
        $registro['descripcion'] = $descripcion;
        $registro['fecha_inicio'] = $fecha_inicio;
        $registro['fecha_fin'] = $fecha_fin;

        $alta = (new im_uma($link))->alta_registro($registro);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }



    public function alta_org_puesto(PDO $link): array|\stdClass
    {

        $alta = (new \gamboamartin\organigrama\tests\base_test())->alta_org_puesto($link);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }
        return $alta;
    }




    public function del(PDO $link, string $name_model): array
    {
        $model = (new modelo_base($link))->genera_modelo(modelo: $name_model);
        $del = $model->elimina_todo();
        if(errores::$error){
            return (new errores())->error(mensaje: 'Error al eliminar '.$name_model, data: $del);
        }
        return $del;
    }

    public function del_im_conf_pres_empresa(PDO $link): array
    {
        $del = $this->del($link, 'im_conf_pres_empresa');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_im_movimiento(PDO $link): array
    {
        $del = $this->del($link, 'im_movimiento');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_im_registro_patronal(PDO $link): array
    {
        $del = $this->del($link, 'im_registro_patronal');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_im_tipo_movimiento(PDO $link): array
    {

        $alta = (new base_test())->del_im_movimiento($link);
        if(errores::$error){
            return (new errores())->error('Error al dar de alta ', $alta);

        }

        $del = $this->del($link, 'im_tipo_movimiento');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_im_uma(PDO $link): array
    {
        $del = $this->del($link, 'im_uma');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

    public function del_org_clasificacion_dep(PDO $link): array
    {
        $del = (new \gamboamartin\organigrama\tests\base_test())->del_org_clasificacion_dep($link);
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

}
