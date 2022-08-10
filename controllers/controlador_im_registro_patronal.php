<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace controllers;

use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use html\im_registro_patronal_html;
use html\org_empresa_html;
use links\secciones\link_org_empresa;
use models\im_registro_patronal;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_im_registro_patronal extends system {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new im_registro_patronal(link: $link);
        $html_ = new im_registro_patronal_html(html: $html);
        $obj_link = new links_menu($this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Registro Patronal';
    }

}
