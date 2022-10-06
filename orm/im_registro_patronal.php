<?php
namespace models;
use base\orm\modelo;
use gamboamartin\direccion_postal\models\dp_colonia_postal;
use gamboamartin\errores\errores;
use gamboamartin\facturacion\models\fc_csd;
use PDO;
use stdClass;

class im_registro_patronal extends modelo{
    public function __construct(PDO $link){
        $tabla = __CLASS__;
        $columnas = array($tabla=>false, 'fc_csd' => $tabla, 'org_sucursal' => 'fc_csd',
            'org_empresa' => 'org_sucursal','im_clase_riesgo' => $tabla,'dp_calle_pertenece'=>'org_sucursal',
            'dp_colonia_postal'=>'dp_calle_pertenece','dp_cp'=>'dp_colonia_postal',
            'cat_sat_regimen_fiscal'=>'org_empresa');
        $campos_obligatorios = array('im_clase_riesgo_id','fc_csd_id','descripcion_select');

        $campos_view = array();
        $campos_view['fc_csd_id']['type'] = 'selects';
        $campos_view['fc_csd_id']['model'] = (new fc_csd($link));

        $campos_view['im_clase_riesgo_id']['type'] = 'selects';
        $campos_view['im_clase_riesgo_id']['model'] = (new im_clase_riesgo($link));

        //$campos_view = array('fc_csd_id'=>array("type" => "selects","model"=>(new fc_csd($link))));

        parent::__construct(link: $link,tabla:  $tabla, campos_obligatorios: $campos_obligatorios,
            columnas: $columnas,campos_view:  $campos_view );
    }

    public function alta_bd(): array|stdClass
    {


        $fc_csd = new fc_csd(link: $this->link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al genera modelo',data:  $fc_csd);
        }
        $r_fc_csd = $fc_csd->registro(registro_id: $this->registro['fc_csd_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el registro',data:  $r_fc_csd);
        }


        $dp_colonia_postal = new dp_colonia_postal($this->link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al genera modelo',data:  $dp_colonia_postal);
        }
        $r_dp_colonia_postal = $dp_colonia_postal->registro(registro_id: $r_fc_csd['dp_calle_pertenece_dp_colonia_postal_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el registro',data:  $r_dp_colonia_postal);
        }

        $im_clase_riesgo = new im_clase_riesgo($this->link);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al genera modelo',data:  $im_clase_riesgo);
        }
        $r_im_clase_riesgo = $im_clase_riesgo->registro(registro_id: $this->registro['im_clase_riesgo_id']);
        if(errores::$error){
            return $this->error->error(mensaje: 'Error al obtener el registro',data:  $r_im_clase_riesgo);
        }

        if(!isset($this->registro['codigo']))
            $this->registro['codigo'] = $this->registro['descripcion'];

        if(!isset($this->registro['codigo_bis']))
            $this->registro['codigo_bis'] = $this->registro['codigo']. ' ' .$r_fc_csd['org_empresa_rfc'];

        if(!isset($this->registro['alias']))
            $this->registro['alias'] =  $this->registro['codigo_bis'];

        if(!isset($this->registro['descripcion_select']))
            $this->registro['descripcion_select'] = $this->registro['descripcion']. ' ' .
                $r_im_clase_riesgo['im_clase_riesgo_factor']. ' ' . $r_fc_csd['org_empresa_rfc']. ' '.
                $r_dp_colonia_postal['dp_estado_descripcion'];

        $r_alta_bd = parent::alta_bd();
        if(errores::$error){
            return $this->error->error('Error al dar de alta registro',$r_alta_bd);
        }

        return $r_alta_bd;
    }
}