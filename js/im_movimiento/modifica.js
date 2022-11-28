let url = getAbsolutePath();

let session_id = getParameterByName('session_id');

let sl_em_empleado = $("#em_empleado_id");
let txt_salario_diario = $('#salario_diario');
let txt_salario_diario_integrado = $('#salario_diario_integrado');
let txt_fecha_inicio_rel_laboral = $('#fecha');

txt_salario_diario.change(function (){
    let salario_diario = $(this).val();
    let factor = txt_factor_integracion.val();

    let res = salario_diario * factor;
    txt_salario_diario_integrado.val(res);
});

let getData = async (url, acciones) => {
    fetch(url)
        .then(response => response.json())
        .then(data => acciones(data))
        .catch(err => {
            alert(err.message);
            console.error("ERROR: ", err.message)
        });
}

