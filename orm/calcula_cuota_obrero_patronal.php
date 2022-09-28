<?php

namespace models;
use gamboamartin\errores\errores;
use gamboamartin\validacion\validacion;
use stdClass;

class calcula_cuota_obrero_patronal{

    private errores $error;
    private validacion $validacion;
    public float $porc_riesgo_trabajo = 0;
    public float $porc_enf_mat_cuota_fija = 20.4;
    public float $porc_enf_mat_cuota_adicional = 1.1;
    public float $porc_enf_mat_gastos_medicos = 1.05;
    public float $porc_enf_mat_pres_dinero = 0.7;
    public float $porc_invalidez_vida = 1.75;
    public float $porc_guarderia_prestaciones_sociales = 1;
    public float $porc_retiro = 2;
    public float $porc_ceav = 3.15;
    public float $porc_credito_vivienda = 5;

    public array $salario_minimo = array(2020=>123.22,2021=>141.70,2022=>172.87);
    public array $uma = array(2020=>86.88,2021=>89.62,2022=>96.22);

    public string $fecha = '';
    public string $year = '';

    public float $monto_uma = 0.0;
    public float $n_dias = 0.0;
    public float $sbc = 0.0;
    public float $sd= 0.0;
    public float $uma_3v = 0.0;
    public float $dif_uma_sbc = 0.0;

    public float $cuota_riesgo_trabajo = 0.0;
    public float $cuota_enf_mat_cuota_fija = 0.0;
    public float $cuota_enf_mat_cuota_adicional = 0.0;
    public float $cuota_enf_mat_gastos_medicos = 0.0;
    public float $cuota_enf_mat_pres_dinero = 0.0;
    public float $cuota_invalidez_vida = 0.0;
    public float $cuota_guarderia_prestaciones_sociales = 0.0;
    public float $cuota_retiro = 0.0;
    public float $cuota_ceav = 0.0;
    public float $cuota_credito_vivienda = 0.0;

    public float $total= 0.0;


    public function __construct(){
        $this->error = new errores();
        $this->validacion = new validacion();

    }


    private function calcula(): bool|array
    {
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }
        $this->year = date('Y', strtotime($this->fecha));
        $this->monto_uma = $this->uma[$this->year];

        $riesgo_de_trabajo = $this->riesgo_de_trabajo();
        if(errores::$error){
            return $this->error->error('Error al obtener riesgo_de_trabajo', $riesgo_de_trabajo);
        }

        $enf_mat_cuota_fija = $this->enf_mat_cuota_fija();
        if(errores::$error){
            return $this->error->error('Error al obtener enf_mat_cuota_fija', $enf_mat_cuota_fija);
        }

        $enf_mat_cuota_adicional = $this->enf_mat_cuota_adicional();
        if(errores::$error){
            return $this->error->error('Error al obtener enf_mat_cuota_adicional', $enf_mat_cuota_adicional);
        }

        $enf_mat_gastos_medicos = $this->enf_mat_gastos_medicos();
        if(errores::$error){
            return $this->error->error('Error al obtener enf_mat_gastos_medicos', $enf_mat_gastos_medicos);
        }

        return true;
    }

    private function enf_mat_cuota_fija(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria = round($this->porc_enf_mat_cuota_fija * $this->monto_uma,2);
        $total_cuota = round($cuota_diaria * $this->n_dias,2);
        $this->cuota_enf_mat_cuota_fija = round($total_cuota/100,2);

        return $this->cuota_enf_mat_cuota_fija;
    }

    private function enf_mat_cuota_adicional(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $excedente = 0;
        $tres_umas = round($this->monto_uma * 3,2);
        if($this->sbc > $tres_umas){
            $excedente = round($this->sbc - $tres_umas,2);
        }

        $cuota_diaria = round($this->porc_enf_mat_cuota_adicional * $excedente,2);
        $cuota_diaria = round($cuota_diaria/100,2);
        $this->cuota_enf_mat_cuota_adicional = round($cuota_diaria * $this->n_dias,2);

        return $this->cuota_enf_mat_cuota_adicional;
    }

    private function enf_mat_gastos_medicos(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria = round($this->porc_enf_mat_gastos_medicos * $this->sbc,2);
        $cuota_diaria = round($cuota_diaria/100,2);
        $this->cuota_enf_mat_gastos_medicos = round($cuota_diaria * $this->n_dias,2);

        return $this->cuota_enf_mat_gastos_medicos;
    }

    private function riesgo_de_trabajo(){
        $valida = $this->valida_parametros();
        if(errores::$error){
            return $this->error->error('Error al validar exedente', $valida);
        }

        $cuota_diaria =  round($this->sbc * $this->n_dias ,2);
        $res = round($cuota_diaria * $this->porc_riesgo_trabajo,2);
        $this->cuota_riesgo_trabajo = round($res/100,2);

        return $this->cuota_riesgo_trabajo;
    }

    private function valida_parametros(){
        if($this->porc_riesgo_trabajo<=0){
            return $this->error->error('Error riesgo de trabajo debe ser mayor a 0', $this->porc_riesgo_trabajo);
        }
        if($this->sbc<=0){
            return $this->error->error('Error sbc debe ser mayor a 0', $this->sbc);
        }
        if($this->n_dias<=0){
            return $this->error->error('Error n_dias debe ser mayor a 0', $this->n_dias);
        }

        return true;
    }
}
