document.addEventListener('DOMContentLoaded', function () {
    const fechaFin = document.getElementById('fecha_fin');

    const empleadoActivoSi = document.getElementById('empleado_activo_si');
    const empleadoActivoNo = document.getElementById('empleado_activo_no');

    const cargoActivoSi = document.getElementById('cargo_activo_si');
    const cargoActivoNo = document.getElementById('cargo_activo_no');

    const motivoTerminacion = document.getElementById('motivo_terminacion_id');

    function actualizarEstadoLaboral() {
        const tieneFechaFin = fechaFin.value.trim() !== '';

        if (tieneFechaFin) {
            empleadoActivoNo.checked = true;
            cargoActivoNo.checked = true;

            motivoTerminacion.disabled = false;
            motivoTerminacion.required = true;
        } else {
            empleadoActivoSi.checked = true;
            cargoActivoSi.checked = true;

            motivoTerminacion.value = '';
            motivoTerminacion.disabled = true;
            motivoTerminacion.required = false;
        }
    }

    fechaFin.addEventListener('change', actualizarEstadoLaboral);
    fechaFin.addEventListener('input', actualizarEstadoLaboral);

    actualizarEstadoLaboral();
});
