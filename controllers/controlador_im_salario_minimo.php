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
use gamboamartin\template\html;
use html\im_salario_minimo_html;
use html\nom_conf_deduccion_html;
use html\nom_conf_nomina_html;
use html\nom_conf_percepcion_html;
use html\nom_deduccion_html;
use gamboamartin\im_registro_patronal\models\im_salario_minimo;
use gamboamartin\im_registro_patronal\models\nom_conf_deduccion;
use gamboamartin\im_registro_patronal\models\nom_conf_nomina;
use gamboamartin\im_registro_patronal\models\nom_conf_percepcion;
use PDO;
use stdClass;

class controlador_im_salario_minimo extends system {

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new im_salario_minimo(link: $link);
        $html_ = new im_salario_minimo_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);
        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);

        $this->titulo_lista = 'Salario Minimo';
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false, ws: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $keys_selects = array();
        $keys_selects['im_tipo_salario_minimo_id'] = new stdClass();
        $keys_selects['im_tipo_salario_minimo_id']->label = 'Salario Minimo';

        $keys_selects['dp_cp_id'] = new stdClass();
        $keys_selects['dp_cp_id']->label = 'CP';

        $keys_selects['fecha_inicio'] = new stdClass();
        $keys_selects['fecha_inicio']->place_holder = 'Fecha Inicio';

        $keys_selects['fecha_fin'] = new stdClass();
        $keys_selects['fecha_fin']->place_holder = 'Fecha Fin';

        $keys_selects['monto'] = new stdClass();
        $keys_selects['monto']->place_holder = 'Monto';


        $inputs = (new im_salario_minimo_html(html: $this->html_base))->genera_inputs_alta(controler: $this,
            modelo: $this->modelo, link: $this->link,keys_selects: $keys_selects);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }
        return $r_alta;
    }

    public function modifica(bool $header, bool $ws = false): array|stdClass
    {
        $base = $this->base();
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $base,
                header: $header,ws:$ws);
        }

        return $base->template;
    }

    private function base(stdClass $params = new stdClass()): array|stdClass
    {
        $r_modifica =  parent::modifica(header: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar template',data:  $r_modifica);
        }

        $inputs = (new im_salario_minimo_html(html: $this->html_base))->inputs_im_salario_minimo(
            controlador:$this, params: $params);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar inputs',data:  $inputs);
        }

        $data = new stdClass();
        $data->template = $r_modifica;
        $data->inputs = $inputs;

        return $data;
    }

}
