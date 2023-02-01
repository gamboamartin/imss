<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\im_registro_patronal\controllers;

use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use html\im_detalle_conf_prestaciones_html;
use gamboamartin\im_registro_patronal\models\im_detalle_conf_prestaciones;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_im_detalle_conf_prestaciones extends system {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new im_detalle_conf_prestaciones(link: $link);
        $html_ = new im_detalle_conf_prestaciones_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Detalle Configuracion Prestaciones';
    }


}
