<?php
namespace html;

use gamboamartin\errores\errores;
use gamboamartin\im_tipo_movimiento\controllers\controlador_im_tipo_movimiento;
use gamboamartin\system\html_controler;
use gamboamartin\system\system;
use gamboamartin\template\directivas;
use models\im_tipo_movimiento;
use PDO;
use stdClass;


class im_tipo_movimiento_html extends html_controler {

    /**
     * Genera un select de tipo im registro patronal
     * @param int $cols No de columnas css
     * @param bool $con_registros si con registros muestra todos los registros
     * @param int|null $id_selected id para selected
     * @param PDO $link conexion a la base de datos
     * @return array|string
     * @version 0.9.2
     */
    public function select_im_tipo_movimiento_id(int $cols,bool $con_registros,int $id_selected, PDO $link): array|string
    {
        $modelo = new im_tipo_movimiento($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Tipo empresa',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }



}
