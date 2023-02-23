<?php
namespace gamboamartin\im_registro_patronal\models;
use base\orm\modelo;
use gamboamartin\errores\errores;
use PDO;
use stdClass;

class im_rcv extends modelo{
    public function __construct(PDO $link){
        $tabla = "im_rcv";
        $columnas = array($tabla=>false);
        $campos_obligatorios = array();

        $campos_view = array();
        $campos_view['factor']['type'] = "inputs";
        $campos_view['monto_inicial']['type'] = "inputs";
        $campos_view['monto_final']['type'] = "inputs";

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas, campos_view: $campos_view);

        $this->NAMESPACE = __NAMESPACE__;

    }

    public function alta_bd(): array|stdClass
    {
        if(!isset($this->registro['codigo'])){
            $this->registro['codigo'] = $this->registro['descripcion'];
        }

        if(!isset($this->registro['codigo_bis'])) {
            $this->registro['codigo_bis'] = $this->registro['codigo'];
        }

        if(!isset($this->registro['alias'])) {
            $this->registro['alias'] = $this->registro['codigo_bis'];
        }

        if(!isset($this->registro['descripcion_select'])) {
            $this->registro['descripcion_select'] = $this->registro['descripcion'];
        }

        $alta_bd = parent::alta_bd();
        if (errores::$error) {
            return $this->error->error(mensaje: 'Error al insertar movimiento', data: $alta_bd);
        }

        return $alta_bd;
    }

    public function filtro_por_montos(float $monto): array|stdClass
    {

            $monto_inicial = $monto;
            $monto_final = $monto;

            $filtro_especial[0][$monto_final]['operador'] = '>=';
            $filtro_especial[0][$monto_final]['valor'] = 'im_rcv.monto_inicial';
            $filtro_especial[0][$monto_final]['comparacion'] = 'AND';
            $filtro_especial[0][$monto_final]['valor_es_campo'] = true;

            $filtro_especial[1][$monto_inicial]['operador'] = '<=';
            $filtro_especial[1][$monto_inicial]['valor'] = 'im_rcv.monto_final';
            $filtro_especial[1][$monto_inicial]['comparacion'] = 'AND';
            $filtro_especial[1][$monto_inicial]['valor_es_campo'] = true;

        $rcvs = (new im_rcv($this->link))->filtro_and(columnas: array('im_rcv_id'),
            filtro_especial: $filtro_especial);
        if(errores::$error){
            $error = $this->error->error(mensaje: 'Error al obtener registros',data:  $rcvs);
            print_r($error);
            die('Error');
        }
        return $rcvs;
    }
}