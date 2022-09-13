<?php
namespace tests\links\secciones;

use gamboamartin\errores\errores;
use gamboamartin\template_1\html;
use gamboamartin\test\liberator;
use gamboamartin\test\test;

use html\im_registro_patronal_html;
use html\org_empresa_html;
use models\im_movimiento;
use stdClass;


class im_movimientoTest extends test {

    public errores $errores;
    private stdClass $paths_conf;
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->errores = new errores();
    }

    /**
     */
    public function test_filtro_movimiento_fecha(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $html = new im_movimiento($this->link);
        //$html = new liberator($html);

        $em_empleado_id = -1;
        $fecha = "";
        $resultado = $html->filtro_movimiento_fecha(em_empleado_id: $em_empleado_id,fecha: $fecha);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error id del empleado no puede ser menor a uno', $resultado['mensaje']);
        errores::$error = false;

        $em_empleado_id = 1;
        $fecha = "";
        $resultado = $html->filtro_movimiento_fecha(em_empleado_id: $em_empleado_id,fecha: $fecha);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error: ingrese una fecha valida', $resultado['mensaje']);
        errores::$error = false;

        $em_empleado_id = 1;
        $fecha = "2022";
        $resultado = $html->filtro_movimiento_fecha(em_empleado_id: $em_empleado_id,fecha: $fecha);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error: ingrese una fecha valida', $resultado['mensaje']);
        errores::$error = false;


        $em_empleado_id = 1;
        $fecha = "2022-09-13-";
        $resultado = $html->filtro_movimiento_fecha(em_empleado_id: $em_empleado_id,fecha: $fecha);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error: ingrese una fecha valida', $resultado['mensaje']);
        errores::$error = false;

        $em_empleado_id = 1;
        $fecha = "2022-09-13";
        $resultado = $html->filtro_movimiento_fecha(em_empleado_id: $em_empleado_id,fecha: $fecha);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);
        errores::$error = false;
    }

    public function test_select_im_registro_patronal_id(): void
    {
        errores::$error = false;

        $_GET['seccion'] = 'cat_sat_tipo_persona';
        $_GET['accion'] = 'lista';
        $_SESSION['grupo_id'] = 1;
        $_SESSION['usuario_id'] = 2;
        $_GET['session_id'] = '1';
        $html = new im_movimiento($this->link);
        //$html = new liberator($html);

        $em_empleado_id = -1;
        $resultado = $html->get_ultimo_movimiento_empleado(em_empleado_id: $em_empleado_id);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error id del empleado no puede ser menor a uno', $resultado['mensaje']);
        errores::$error = false;

        $em_empleado_id = 999;
        $resultado = $html->get_ultimo_movimiento_empleado(em_empleado_id: $em_empleado_id);
        $this->assertIsArray($resultado);
        $this->assertTrue(errores::$error);
        $this->assertStringContainsStringIgnoringCase('Error no hay registros para el empleado', $resultado['mensaje']);
        errores::$error = false;

        $em_empleado_id = 1;
        $resultado = $html->get_ultimo_movimiento_empleado(em_empleado_id: $em_empleado_id);
        $this->assertIsArray($resultado);
        $this->assertNotTrue(errores::$error);

        errores::$error = false;
    }

}

