<?php
namespace models;
use base\orm\modelo;
use DateTime;
use gamboamartin\errores\errores;
use gamboamartin\xml_cfdi_4\validacion;
use PDO;
use stdClass;

class im_movimiento extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false,'em_empleado' => $tabla, 'im_registro_patronal'=>$tabla,
            'im_tipo_movimiento'=>$tabla);
        $campos_obligatorios = array('im_registro_patronal_id','im_tipo_movimiento_id','em_empleado_id','fecha');

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);
    }

    public function alta_bd(): array|stdClass
    {
        if(!isset($this->registro['salario_diario'])){
            $this->registro['salario_diario'] = 0.0;
        }

        $alta_bd = parent::alta_bd();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar movimiento', data: $alta_bd);
        }
        
        return $alta_bd;
    }

    private function data_filtro_movto(int $em_empleado_id, string $fecha): array|stdClass
    {
        $filtro['em_empleado.id'] = $em_empleado_id;
        $order['im_movimiento.fecha'] = 'DESC';

        $filtro_extra = $this->filtro_extra_fecha(fecha: $fecha);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener filtro', data: $filtro_extra);
        }

        $data = new stdClass();
        $data->filtro = $filtro;
        $data->order = $order;
        $data->filtro_extra = $filtro_extra;
        return $data;
    }

    private function filtro_extra_fecha(string $fecha): array
    {
        $filtro_extra[0]['im_movimiento.fecha']['valor'] = $fecha;
        $filtro_extra[0]['im_movimiento.fecha']['operador'] = '>=';
        $filtro_extra[0]['im_movimiento.fecha']['comparacion'] = 'AND';

        return $filtro_extra;
    }

    public function filtro_movimiento_fecha(int $em_empleado_id,string $fecha): stdClass|array
    {
        if ($em_empleado_id <= -1) {
            return $this->error->error(mensaje: 'Error id del empleado no puede ser menor a uno', data: $em_empleado_id);
        }


        $valida = (new validacion())->valida_fecha(fecha: $fecha,tipo_val: 'fecha');
        if(errores::$error){
            return $this->error->error(mensaje: 'Error: ingrese una fecha valida', data: $valida);
        }


        $data = $this->data_filtro_movto(em_empleado_id:  $em_empleado_id,fecha: $fecha);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener datos para filtro', data: $data);
        }

        $im_movimiento = $this->obten_datos_ultimo_registro(filtro: $data->filtro, filtro_extra: $data->filtro_extra,
            order: $data->order);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener el movimiento del empleado', data: $im_movimiento);
        }

        if (count($im_movimiento) === 0) {
            return $this->error->error(mensaje: 'Error no hay registros para el empleado', data: $em_empleado_id);
        }

        return $im_movimiento;
    }

    public function get_ultimo_movimiento_empleado(int $em_empleado_id): stdClass|array
    {
        if ($em_empleado_id <= -1) {
            return $this->error->error(mensaje: 'Error id del empleado no puede ser menor a uno', data: $em_empleado_id);
        }

        $filtro['em_empleado.id'] = $em_empleado_id;
        $order['im_movimiento.fecha'] = 'DESC';
        $im_movimiento = $this->obten_datos_ultimo_registro(filtro: $filtro, order: $order);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener el movimiento del empleado', data: $im_movimiento);
        }

        if (count($im_movimiento) === 0) {
            return $this->error->error(mensaje: 'Error no hay registros para el empleado', data: $im_movimiento);
        }

        return $im_movimiento;
    }



}