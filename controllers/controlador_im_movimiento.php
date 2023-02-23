<?php
/**
 * @author Martin Gamboa Vazquez
 * @version 1.0.0
 * @created 2022-05-14
 * @final En proceso
 *
 */
namespace gamboamartin\im_registro_patronal\controllers;

use gamboamartin\documento\models\doc_documento;
use gamboamartin\empleado\models\em_empleado;
use gamboamartin\empleado\models\em_registro_patronal;
use gamboamartin\errores\errores;
use gamboamartin\system\links_menu;
use gamboamartin\system\system;
use html\im_movimiento_html;
use gamboamartin\im_registro_patronal\models\im_movimiento;
use gamboamartin\template\html;
use gamboamartin\im_registro_patronal\models\im_tipo_movimiento;
use PDO;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use stdClass;

class controlador_im_movimiento extends system {

    public stdClass|array $keys_selects = array();

    public function __construct(PDO $link, html $html = new \gamboamartin\template_1\html(),
                                stdClass $paths_conf = new stdClass()){
        $modelo = new im_movimiento(link: $link);
        $html_ = new im_movimiento_html(html: $html);
        $obj_link = new links_menu(link: $link, registro_id:$this->registro_id);

        $columns["im_movimiento_id"]["titulo"] = "Id";
        $columns["im_movimiento_codigo"]["titulo"] = "Código";
        $columns["im_tipo_movimiento_descripcion"]["titulo"] = "Tipo Movimiento";
        $columns["em_registro_patronal_descripcion"]["titulo"] = "Registro Patronal";
        $columns["em_empleado_nss"]["titulo"] = "NSS";
        $columns["em_empleado_nombre"]["titulo"] = "Nombre";
        $columns["em_empleado_ap"]["titulo"] = "Ap. Paterno";
        $columns["em_empleado_am"]["titulo"] = "Ap. Materno";
        $columns["im_movimiento_fecha"]["titulo"] = "Fecha";

        $datatables = new stdClass();
        $datatables->columns = $columns;

        parent::__construct(html:$html_, link: $link,modelo:  $modelo, obj_link: $obj_link, datatables: $datatables,
            paths_conf: $paths_conf);

        $this->asignar_propiedad(identificador:'im_tipo_movimiento_id', propiedades: ["label" => "Tipo de Movimiento IMSS", 'cols'=>12]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador:'im_registro_patronal_id', propiedades: ["label" => "Registro Patronal", 'cols'=>12]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador:'em_empleado_id', propiedades: ["label" => "Empleados", 'cols'=>12]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'fecha', propiedades: ['place_holder'=> 'Fecha', 'cols'=>6]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'salario_diario', propiedades: ['place_holder'=> 'Salario Diario',
            'cols'=>6,'required'=>false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'salario_diario_integrado', propiedades: [
            'place_holder'=> 'Salario Diario Integrado', 'cols'=>6,'required'=>false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'salario_mixto', propiedades: [
            'place_holder'=> 'Salario Mixto', 'cols'=>6,'required'=>false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'salario_variable', propiedades: [
            'place_holder'=> 'Salario Variable', 'cols'=>6,'required'=>false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'observaciones', propiedades: ['place_holder'=> 'Observaciones',
            'cols'=>12,'required'=>false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador: 'factor_integracion', propiedades: [
            'place_holder'=> 'Factor de Integracion', 'cols'=>6,'required'=>false]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->titulo_lista = 'Movimiento';
    }

    public function sube_archivo(bool $header, bool $ws = false){
        $r_alta =  parent::alta(header: false,ws:  false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar template',data:  $r_alta);
        }

        return $r_alta;
    }

    public function lee_archivo(bool $header, bool $ws = false)
    {
        $doc_documento_modelo = new doc_documento($this->link);
        $doc_documento_modelo->registro['descripcion'] = rand();
        $doc_documento_modelo->registro['descripcion_select'] = rand();
        $doc_documento_modelo->registro['doc_tipo_documento_id'] = 1;
        $doc_documento = $doc_documento_modelo->alta_bd(file: $_FILES['archivo']);
        if (errores::$error) {
            $error =  $this->errores->error(mensaje: 'Error al dar de alta el documento', data: $doc_documento);
            if(!$header){
                return $error;
            }
            print_r($error);
            die('Error');
        }

        $movimientos_excel = $this->obten_movimientos_excel(ruta_absoluta: $doc_documento->registro['doc_documento_ruta_absoluta']);
        if (errores::$error) {
            $error =  $this->errores->error(mensaje: 'Error obtener movimientos',data:  $movimientos_excel);
            if(!$header){
                return $error;
            }
            print_r($error);
            die('Error');
        }

        foreach ($movimientos_excel as $movimiento){
            $filtro_rp['em_registro_patronal.descripcion'] = $movimiento->registro_patronal;
            $em_registro_patronal = (new em_registro_patronal($this->link))->filtro_and(filtro: $filtro_rp);
            if (errores::$error) {
                $error =  $this->errores->error(mensaje: 'Error obtener registros patronales',data:  $em_registro_patronal);
                if(!$header){
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            $filtro_rp['em_registro_patronal.id'] = $movimiento->registro_patronal;
            $em_registro_patronal = (new em_registro_patronal($this->link))->filtro_and(filtro: $filtro_rp);
            if (errores::$error) {
                $error =  $this->errores->error(mensaje: 'Error obtener registros patronales',data:  $em_registro_patronal);
                if(!$header){
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            $filtro_tipo_movimiento['im_tipo_movimiento.descripcion'] = $movimiento->tipo_movimiento;
            $im_tipo_movimiento = (new im_tipo_movimiento($this->link))->filtro_and(filtro: $filtro_tipo_movimiento);
            if (errores::$error) {
                $error =  $this->errores->error(mensaje: 'Error obtener tipo movimiento',data:  $im_tipo_movimiento);
                if(!$header){
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            $filtro_emp['em_empleado.nombre'] = $movimiento->nombre;
            $filtro_emp['em_empleado.ap'] = $movimiento->ap;
            $filtro_emp['em_empleado.am'] = $movimiento->am;
            if(isset($movimiento->nss)) {
                $filtro_emp['em_empleado.nss'] = $movimiento->nss;
            }
            $em_empleado = (new em_empleado($this->link))->filtro_and(filtro: $filtro_emp);
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error obtener empleado', data: $em_empleado);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }

            $registro['im_tipo_movimiento_id'] = $im_tipo_movimiento->registros[0]['im_tipo_movimiento_id'];
            $registro['em_registro_patronal_id'] = $em_registro_patronal->registros[0]['em_registro_patronal_id'];
            $registro['em_empleado_id'] = $em_empleado->registros[0]['em_empleado_id'];
            $registro['salario_diario'] = $movimiento->sd;
            $registro['salario_diario_integrado'] = $movimiento->sdi;
            $registro['factor_integracion'] = $movimiento->fi;
            $registro['fecha'] = $movimiento->fecha;

            $im_movimiento = new im_movimiento($this->link);
            $im_movimiento->registro = $registro;
            $r_alta = $im_movimiento->alta_bd();
            if (errores::$error) {
                $error = $this->errores->error(mensaje: 'Error al dar de alta registro', data: $r_alta);
                if (!$header) {
                    return $error;
                }
                print_r($error);
                die('Error');
            }
        }

        $link = "./index.php?seccion=im_movimiento&accion=lista&registro_id=".$this->registro_id;
        $link.="&session_id=$this->session_id";
        header('Location:' . $link);
        exit;
    }

    public function obten_movimientos_excel(string $ruta_absoluta){
        $documento = IOFactory::load($ruta_absoluta);
        $movimientos = array();
        $hojaActual = $documento->getSheet(0);
        $registros = array();
        foreach ($hojaActual->getRowIterator() as $fila) {
            foreach ($fila->getCellIterator() as $celda) {
                $fila = $celda->getRow();
                $valorRaw = $celda->getValue();
                $columna = $celda->getColumn();

                if($fila >= 2){
                    if($columna === "A"){
                        $reg = new stdClass();
                        $reg->fila = $fila;
                        $registros[] = $reg;
                    }
                }
            }
        }

        foreach ($registros as $registro) {
            $reg = new stdClass();
            $reg->empresa = $hojaActual->getCell('A' . $registro->fila)->getValue();
            $reg->registro_patronal = $hojaActual->getCell('B' . $registro->fila)->getValue();
            $reg->tipo_movimiento = $hojaActual->getCell('C' . $registro->fila)->getValue();
            $reg->nss = $hojaActual->getCell('D' . $registro->fila)->getValue();
            $reg->nombre = $hojaActual->getCell('E' . $registro->fila)->getValue();
            $reg->ap = $hojaActual->getCell('F' . $registro->fila)->getValue();
            $reg->am = $hojaActual->getCell('G' . $registro->fila)->getValue();
            $reg->sd = $hojaActual->getCell('H' . $registro->fila)->getValue();
            $reg->fi = $hojaActual->getCell('I' . $registro->fila)->getValue();
            $reg->sdi = $hojaActual->getCell('J' . $registro->fila)->getValue();
            $fecha = $hojaActual->getCell('K' . $registro->fila)->getCalculatedValue();
            $reg->fecha  = Date::excelToDateTimeObject($fecha)->format('Y-m-d');
            $movimientos[] = $reg;
        }

        return $movimientos;
    }
    public function alta(bool $header, bool $ws = false): array|string
    {
        $r_alta =  parent::alta(header: false); // TODO: Change the autogenerated stub
        if(errores::$error){
            return $this->retorno_error(mensaje: 'Error al generar template',data:  $r_alta, header: $header,ws:$ws);
        }

        $inputs = $this->genera_inputs(keys_selects:  $this->keys_selects);
        if(errores::$error){
            $error = $this->errores->error(mensaje: 'Error al generar inputs',data:  $inputs);
            print_r($error);
            die('Error');
        }


        return $r_alta;
    }

    public function asignar_propiedad(string $identificador, array $propiedades): array|stdClass
    {
        $identificador = trim($identificador);
        if($identificador === ''){
            return $this->errores->error(mensaje: 'Error identificador esta vacio',data:  $identificador);
        }

        if (!array_key_exists($identificador,$this->keys_selects)){
            $this->keys_selects[$identificador] = new stdClass();
        }

        foreach ($propiedades as $key => $value){
            $this->keys_selects[$identificador]->$key = $value;
        }
        return $this->keys_selects;
    }

    private function base(): array|stdClass
    {
        $r_modifica =  parent::modifica(header: false);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al generar template',data:  $r_modifica);
        }

        $this->asignar_propiedad(identificador:'im_tipo_movimiento_id',
            propiedades: ["id_selected"=>$this->row_upd->im_tipo_movimiento_id]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador:'em_registro_patronal_id',
            propiedades: ["id_selected"=>$this->row_upd->em_registro_patronal_id]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }

        $this->asignar_propiedad(identificador:'em_empleado_id',
            propiedades: ["id_selected"=>$this->row_upd->em_empleado_id]);
        if (errores::$error) {
            $error = $this->errores->error(mensaje: 'Error al asignar propiedad', data: $this);
            print_r($error);
            die('Error');
        }


        $inputs = $this->genera_inputs(keys_selects:  $this->keys_selects);
        if(errores::$error){
            return $this->errores->error(mensaje: 'Error al inicializar inputs',data:  $inputs);
        }


        $data = new stdClass();
        $data->template = $r_modifica;
        $data->inputs = $inputs;

        return $data;
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
}
