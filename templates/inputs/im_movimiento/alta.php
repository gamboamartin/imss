<?php /** @var gamboamartin\im_registro_patronal\controllers\controlador_im_registro_patronal $controlador  controlador en ejecucion */ ?>
<?php use config\views; ?>
<?php echo $controlador->forms_inputs_alta; ?>
<?php echo $controlador->inputs->select->im_tipo_movimiento_id; ?>
<?php echo $controlador->inputs->select->im_registro_patronal_id; ?>
<?php echo $controlador->inputs->select->em_empleado_id; ?>
<?php echo $controlador->inputs->fecha; ?>
<?php include (new views())->ruta_templates.'botons/submit/alta_bd_otro.php';?>
<div class="control-group btn-alta">
</div>