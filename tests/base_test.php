<?php
namespace gamboamartin\im_registro_patronal\test;
use base\orm\modelo_base;
use gamboamartin\errores\errores;
use models\im_movimiento;
use models\im_registro_patronal;
use models\im_tipo_movimiento;
use PDO;

class base_test{



    public function alta_im_movimiento(PDO $link): array|\stdClass
    {
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

    public function alta_im_tipo_movimiento(PDO $link): array|\stdClass
    {
        $org_puesto = array();
        $org_puesto['id'] = 1;
        $org_puesto['codigo'] = 1;
        $org_puesto['descripcion'] = 1;



        $alta = (new im_tipo_movimiento($link))->alta_registro($org_puesto);
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

    public function del_im_registro_patronal(PDO $link): array
    {
        $del = $this->del($link, 'im_registro_patronal');
        if(errores::$error){
            return (new errores())->error('Error al eliminar', $del);
        }
        return $del;
    }

}
