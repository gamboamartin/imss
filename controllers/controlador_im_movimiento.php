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
use html\im_movimiento_html;
use html\org_empresa_html;
use links\secciones\link_org_empresa;
use models\im_movimiento;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_im_movimiento extends system {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new im_movimiento(link: $link);
        $html_ = new im_movimiento_html(html: $html);
        $obj_link = new links_menu($this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Movimiento';
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $keys_selects = array();

        $keys_selects['im_tipo_movimiento'] = new stdClass();
        $keys_selects['im_tipo_movimiento']->label = 'Tipo de Movimiento IMSS';

        $keys_selects['im_registro_patronal'] = new stdClass();
        $keys_selects['im_registro_patronal']->label = 'Registro Patronal';

        $keys_selects['em_empleado'] = new stdClass();
        $keys_selects['em_empleado']->label = 'Empleado';
        $keys_selects['em_empleado']->name_model = 'gamboamartin\\empleado\\models\\em_empleado';

        $inputs = (new im_movimiento_html(html: $this->html_base))->genera_inputs_alta(controler: $this,
            keys_selects: $keys_selects, link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }

        return $r_alta;
    }

    public function modifica(bool $header, bool $ws = false, string $breadcrumbs = '', bool $aplica_form = true, bool $muestra_btn = true): array|string
    {
        $r_modifica = parent::modifica($header, $ws, $breadcrumbs, $aplica_form, $muestra_btn);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $inputs = (new im_movimiento_html(html: $this->html_base))->genera_inputs_modifica(controler: $this, link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }

        return $r_modifica;
    }


}