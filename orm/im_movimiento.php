<?php
namespace models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class im_movimiento extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false,'em_empleado' => $tabla);
        $campos_obligatorios = array();

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas);

        $data = $this->get_ultimo_movimiento_empleado(1);
          $filtro  = $this->filtro_movimiento_fecha($data, "2022-09-12");

    }


    private function get_ultimo_movimiento_empleado(int $em_empleado_id): stdClass|array
    {
        if ($em_empleado_id <= -1) {
            return $this->error->error(mensaje: 'Error id del empleado no puede ser menor a uno', data: $em_empleado_id);
        }

        $Sql = "SELECT * FROM im_movimiento WHERE em_empleado_id = {$em_empleado_id} ORDER BY fecha DESC LIMIT 1";
        $r_im_movimiento = $this->ejecuta_consulta(consulta: $Sql);
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al obtener el movimiento del empleado', data: $r_im_movimiento);
        }

        if ($r_im_movimiento->n_registros === 0) {
            return $this->error->error(mensaje: 'Error no hay registros para el empleado', data: $em_empleado_id);
        }

        return $r_im_movimiento->registros[0];
    }


    private function filtro_movimiento_fecha(mixed $data,string $fecha): stdClass|array
    {
        if ($data['fecha'] === $fecha) {
            return $data;
        }

        return array();
    }



}