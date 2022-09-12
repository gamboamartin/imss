<?php
namespace html;

use gamboamartin\errores\errores;
use gamboamartin\im_movimiento\controllers\controlador_im_movimiento;
use gamboamartin\system\html_controler;
use gamboamartin\system\system;
use gamboamartin\template\directivas;
use models\im_movimiento;
use PDO;
use stdClass;


class im_movimiento_html extends html_controler {


    public function select_im_movimiento_id(int $cols,bool $con_registros,int $id_selected, PDO $link): array|string
    {
        $modelo = new im_movimiento($link);

        $select = $this->select_catalogo(cols:$cols,con_registros:$con_registros,id_selected:$id_selected,
            modelo: $modelo,label: 'Tipo empresa',required: true);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al generar select', data: $select);
        }
        return $select;
    }



}
