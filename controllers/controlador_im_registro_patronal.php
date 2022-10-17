<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\im_registro_patronal\controllers;

use gamboamartin\direccion_postal\models\dp_colonia_postal;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_csd;
use gamboamartin\organigrama\models\org_empresa;
use gamboamartin\organigrama\models\org_sucursal;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use html\im_registro_patronal_html;
use html\org_empresa_html;
use links\secciones\link_org_empresa;
use models\im_clase_riesgo;
use models\im_registro_patronal;
use gamboamartin\template\html;
use PDO;
use stdClass;

class controlador_im_registro_patronal extends system {
    public array $keys_selects = array();
    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new im_registro_patronal(link: $link);
        $html_ = new im_registro_patronal_html(html: $html);
        $obj_link = new links_menu($this->registro_id);

        $this->rows_lista[] = 'im_clase_riesgo_id';
        $this->rows_lista[] = 'fc_csd_id';

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, paths_conf: $paths_conf);


        $this->asignar_propiedad(identificador:'fc_csd_id', propiedades: ["label" => "CSD Sucursal"]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador:'im_clase_riesgo_id', propiedades: ["label" => "Clase de Riesgo."]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->titulo_lista = 'Registro Patronal';
    }

    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $inputs = (new im_registro_patronal_html(html: $this->html_base))->genera_inputs_alta(controler: $this,
            keys_selects: $this->keys_selects, link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }

        return $r_alta;
    }

    public function asignar_propiedad(string $identificador, mixed $propiedades)
    {
        if (!array_key_exists($identificador,$this->keys_selects)){
            $this->keys_selects[$identificador] = new stdClass();
        }

        foreach ($propiedades as $key => $value){
            $this->keys_selects[$identificador]->$key = $value;
        }
    }

    private function cat_sat_regimen_fiscal_descripcion_row(stdClass $row): array|stdClass
    {
        $keys = array('im_registro_patronal_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $row);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al validar row',data:  $valida);
        }

        $fc_csd = new fc_csd($this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al genera modelo',data:  $fc_csd);
        }
        $r_fc_csd = $fc_csd->registro(registro_id: $row->im_registro_patronal_fc_csd_id);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener el registro',data:  $r_fc_csd);
        }

        $row->cat_sat_regimen_fiscal_descripcion = $r_fc_csd['cat_sat_regimen_fiscal_descripcion'];

        return $row;
    }

    private function dp_estado_descripcion_row(stdClass $row): array|stdClass
    {
        $keys = array('im_registro_patronal_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $row);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al validar row',data:  $valida);
        }

        $fc_csd = new fc_csd($this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al genera modelo',data:  $fc_csd);
        }
        $r_fc_csd = $fc_csd->registro(registro_id: $row->im_registro_patronal_fc_csd_id);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener el registro',data:  $r_fc_csd);
        }


        $dp_colonia_postal = new dp_colonia_postal($this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al genera modelo',data:  $dp_colonia_postal);
        }
        $r_dp_colonia_postal = $dp_colonia_postal->registro(registro_id: $r_fc_csd['dp_calle_pertenece_dp_colonia_postal_id']);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener el registro',data:  $r_dp_colonia_postal);
        }

        $row->dp_estado_descripcion = $r_dp_colonia_postal['dp_estado_descripcion'];

        return $row;
    }

    private function im_clase_riesgo_factor_row(stdClass $row): array|stdClass
    {
        $keys = array('im_registro_patronal_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $row);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al validar row',data:  $valida);
        }

        $im_clase_riesgo = new im_clase_riesgo($this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al genera modelo',data:  $im_clase_riesgo);
        }
        $r_im_clase_riesgo = $im_clase_riesgo->registro(registro_id: $row->im_registro_patronal_im_clase_riesgo_id);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener el registro',data:  $r_im_clase_riesgo);
        }

        $row->im_clase_riesgo_factor = $r_im_clase_riesgo['im_clase_riesgo_factor'];

        return $row;
    }

    public function lista(bool $header, bool $ws = false): array
    {
        $r_lista = parent::lista($header, $ws); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar datos',data:  $r_lista, header: $header,ws:$ws);
        }

        $registros = $this->maqueta_registros_lista(registros: $this->registros);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al maquetar registros',data:  $registros, header: $header,ws:$ws);
        }
        $this->registros = $registros;

        return $r_lista;
    }

    private function maqueta_registros_lista(array $registros): array
    {
        foreach ($registros as $indice=> $row){
            $row = $this->org_empresa_descripcion_row(row: $row);
            if(errores::$error){
                return $this->errores->error(mensaje: 'Error al maquetar row',data:  $row);
            }
            $registros[$indice] = $row;

            $row = $this->cat_sat_regimen_fiscal_descripcion_row(row: $row);
            if(errores::$error){
                return $this->errores->error(mensaje: 'Error al maquetar row',data:  $row);
            }
            $registros[$indice] = $row;

            $row = $this->org_empresa_rfc_row(row: $row);
            if(errores::$error){
                return $this->errores->error(mensaje: 'Error al maquetar row',data:  $row);
            }
            $registros[$indice] = $row;

            $row = $this->im_clase_riesgo_factor_row(row: $row);
            if(errores::$error){
                return $this->errores->error(mensaje: 'Error al maquetar row',data:  $row);
            }
            $registros[$indice] = $row;

            $row = $this->dp_estado_descripcion_row(row: $row);
            if(errores::$error){
                return $this->errores->error(mensaje: 'Error al maquetar row',data:  $row);
            }
            $registros[$indice] = $row;



        }
        return $registros;
    }

    public function modifica(bool $header, bool $ws = false, string $breadcrumbs = '', bool $aplica_form = true, bool $muestra_btn = true): array|string
    {
        $r_modifica = parent::modifica($header, $ws, $breadcrumbs, $aplica_form, $muestra_btn);
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_modifica, header: $header,ws:$ws);
        }

        $inputs = (new im_registro_patronal_html(html: $this->html_base))->genera_inputs_modifica(controler: $this, link: $this->link);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }

        return $r_modifica;
    }

    private function org_empresa_descripcion_row(stdClass $row): array|stdClass
    {
        $keys = array('im_registro_patronal_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $row);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al validar row',data:  $valida);
        }

        $fc_csd = new fc_csd($this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al genera modelo',data:  $fc_csd);
        }
        $r_fc_csd = $fc_csd->registro(registro_id: $row->im_registro_patronal_fc_csd_id);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener el registro',data:  $r_fc_csd);
        }

        $row->org_empresa_descripcion = $r_fc_csd['org_empresa_descripcion'];

        return $row;
    }

    private function org_empresa_rfc_row(stdClass $row): array|stdClass
    {
        $keys = array('im_registro_patronal_id');
        $valida = $this->validacion->valida_ids(keys: $keys,registro:  $row);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al validar row',data:  $valida);
        }

        $fc_csd = new fc_csd($this->link);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al genera modelo',data:  $fc_csd);
        }
        $r_fc_csd = $fc_csd->registro(registro_id: $row->im_registro_patronal_fc_csd_id);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al obtener el registro',data:  $r_fc_csd);
        }

        $row->org_empresa_rfc = $r_fc_csd['org_empresa_rfc'];

        return $row;
    }



}
