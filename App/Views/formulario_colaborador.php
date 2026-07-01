<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<?php
$errores = $errores ?? [];
$datosAnteriores = $datosAnteriores ?? [];

$valorCampo = function (string $campo) use ($datosAnteriores): string {
    return htmlspecialchars((string) ($datosAnteriores[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
};

$seleccionar = function (string $campo, int $valor) use ($datosAnteriores): string {
    return ((int) ($datosAnteriores[$campo] ?? 0) === $valor) ? 'selected' : '';
};

$marcarRadio = function (string $campo, int $valor, bool $porDefecto = false) use ($datosAnteriores): string {
    if (array_key_exists($campo, $datosAnteriores)) {
        return ((int) $datosAnteriores[$campo] === $valor) ? 'checked' : '';
    }

    return $porDefecto ? 'checked' : '';
};

$mostrarError = function (string $campo) use ($errores): void {
    if (isset($errores[$campo])) {
        echo '<p class="mensaje-error">' . htmlspecialchars($errores[$campo], ENT_QUOTES, 'UTF-8') . '</p>';
    }
};
?>

<section class="tarjeta">
    <div class="encabezado-formulario">
        <div>
            <h2>Registro de Colaborador</h2>
            <p>Complete los datos personales y laborales del colaborador.</p>
        </div>

        <a class="boton-secundario" href="index.php?accion=inicio">Ver prueba del sistema</a>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="resumen-errores">
            <strong>Revise el formulario.</strong>
            <span>Hay campos que necesitan corrección antes de continuar.</span>

            <?php if (isset($errores['general'])): ?>
                <p class="mensaje-error">
                    <?php echo htmlspecialchars($errores['general'], ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form class="formulario" action="index.php?accion=guardar_colaborador" method="POST">

        <div class="seccion-formulario">
            <h3>Datos personales del colaborador</h3>

            <div class="grupo-formulario">
                <label for="identidad">Identidad</label>
                <input 
                    type="text" 
                    id="identidad" 
                    name="identidad" 
                    value="<?php echo $valorCampo('identidad'); ?>"
                    placeholder="Ejemplo: 8-999-1234"
                    required
                >
                <?php $mostrarError('identidad'); ?>
            </div>

            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label for="nombre">Nombre</label>
                    <input 
                        type="text" 
                        id="nombre" 
                        name="nombre" 
                        value="<?php echo $valorCampo('nombre'); ?>"
                        placeholder="Ingrese el nombre"
                        required
                    >
                    <?php $mostrarError('nombre'); ?>
                </div>

                <div class="grupo-formulario">
                    <label for="apellido">Apellido</label>
                    <input 
                        type="text" 
                        id="apellido" 
                        name="apellido" 
                        value="<?php echo $valorCampo('apellido'); ?>"
                        placeholder="Ingrese el apellido"
                        required
                    >
                    <?php $mostrarError('apellido'); ?>
                </div>
            </div>

            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label for="edad">Edad</label>
                    <input 
                        type="number" 
                        id="edad" 
                        name="edad" 
                        min="18" 
                        max="100"
                        value="<?php echo $valorCampo('edad'); ?>"
                        placeholder="Ejemplo: 25"
                        required
                    >
                    <?php $mostrarError('edad'); ?>
                </div>

                <div class="grupo-formulario">
                    <label for="tipo_sangre_id">Tipo de sangre</label>

                    <select id="tipo_sangre_id" name="tipo_sangre_id" required>
                        <option value="">Seleccione el tipo de sangre</option>

                        <?php foreach ($tiposSangre as $tipo): ?>
                            <option 
                                value="<?php echo htmlspecialchars((string) $tipo['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php echo $seleccionar('tipo_sangre_id', (int) $tipo['id']); ?>
                            >
                                <?php echo htmlspecialchars($tipo['Nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php $mostrarError('tipo_sangre_id'); ?>
                </div>
            </div>

            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label for="sexo_id">Sexo</label>

                    <select id="sexo_id" name="sexo_id" required>
                        <option value="">Seleccione el sexo</option>

                        <?php foreach ($sexos as $sexoItem): ?>
                            <option 
                                value="<?php echo htmlspecialchars((string) $sexoItem['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php echo $seleccionar('sexo_id', (int) $sexoItem['id']); ?>
                            >
                                <?php echo htmlspecialchars($sexoItem['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php $mostrarError('sexo_id'); ?>
                </div>

                <div class="grupo-formulario">
                    <label for="estado_civil_id">Estado civil</label>

                    <select id="estado_civil_id" name="estado_civil_id" required>
                        <option value="">Seleccione el estado civil</option>

                        <?php foreach ($estadosCiviles as $estadoCivil): ?>
                            <option 
                                value="<?php echo htmlspecialchars((string) $estadoCivil['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php echo $seleccionar('estado_civil_id', (int) $estadoCivil['id']); ?>
                            >
                                <?php echo htmlspecialchars($estadoCivil['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php $mostrarError('estado_civil_id'); ?>
                </div>
            </div>

            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label for="nacionalidad">Nacionalidad</label>
                    <input 
                        type="text" 
                        id="nacionalidad" 
                        name="nacionalidad" 
                        value="<?php echo $valorCampo('nacionalidad'); ?>"
                        placeholder="Ejemplo: Panameña"
                        required
                    >
                    <?php $mostrarError('nacionalidad'); ?>
                </div>

                <div class="grupo-formulario">
                    <label for="ruta_id">Ruta del colaborador</label>

                    <select id="ruta_id" name="ruta_id" required>
                        <option value="">Seleccione una ruta</option>

                        <?php foreach ($rutas as $ruta): ?>
                            <option 
                                value="<?php echo htmlspecialchars((string) $ruta['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php echo $seleccionar('ruta_id', (int) $ruta['id']); ?>
                            >
                                <?php echo htmlspecialchars($ruta['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php $mostrarError('ruta_id'); ?>
                </div>
            </div>

            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label for="correo">Correo electrónico</label>
                    <input 
                        type="email" 
                        id="correo" 
                        name="correo" 
                        value="<?php echo $valorCampo('correo'); ?>"
                        placeholder="correo@ejemplo.com"
                        required
                    >
                    <?php $mostrarError('correo'); ?>
                </div>

                <div class="grupo-formulario">
                    <label for="celular">Celular</label>
                    <input 
                        type="text" 
                        id="celular" 
                        name="celular" 
                        value="<?php echo $valorCampo('celular'); ?>"
                        placeholder="Ejemplo: 6123-4567"
                        required
                    >
                    <?php $mostrarError('celular'); ?>
                </div>
            </div>

        <div class="seccion-formulario">
            <h3>Perfil laboral</h3>

            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label for="ocupacion_id">Puesto / Ocupación</label>

                    <select id="ocupacion_id" name="ocupacion_id" required>
                        <option value="">Seleccione una ocupación</option>

                        <?php foreach ($ocupaciones as $ocupacion): ?>
                            <option 
                                value="<?php echo htmlspecialchars((string) $ocupacion['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php echo $seleccionar('ocupacion_id', (int) $ocupacion['id']); ?>
                            >
                                <?php echo htmlspecialchars($ocupacion['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php $mostrarError('ocupacion_id'); ?>
                </div>

                <div class="grupo-formulario">
                    <label for="tipo_empleado_id">Tipo de empleado / planilla</label>

                    <select id="tipo_empleado_id" name="tipo_empleado_id" required>
                        <option value="">Seleccione el tipo</option>

                        <?php foreach ($tiposEmpleado as $tipoEmpleado): ?>
                            <option 
                                value="<?php echo htmlspecialchars((string) $tipoEmpleado['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php echo $seleccionar('tipo_empleado_id', (int) $tipoEmpleado['id']); ?>
                            >
                                <?php echo htmlspecialchars($tipoEmpleado['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <?php $mostrarError('tipo_empleado_id'); ?>
                </div>
            </div>

            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label for="salario">Salario</label>
                    <input 
                        type="number" 
                        id="salario" 
                        name="salario" 
                        min="1" 
                        step="0.01"
                        value="<?php echo $valorCampo('salario'); ?>"
                        placeholder="Ejemplo: 850.00"
                        required
                    >
                    <?php $mostrarError('salario'); ?>
                </div>

                <div class="grupo-formulario">
                    <label for="fecha_inicio">Fecha de inicio</label>
                    <input 
                        type="date" 
                        id="fecha_inicio" 
                        name="fecha_inicio" 
                        value="<?php echo $valorCampo('fecha_inicio'); ?>"
                        required
                    >
                    <?php $mostrarError('fecha_inicio'); ?>
                </div>
            </div>

            <div class="grupo-formulario">
                <label for="fecha_fin">Fecha fin</label>
                <input 
                    type="date" 
                    id="fecha_fin" 
                    name="fecha_fin"
                    value="<?php echo $valorCampo('fecha_fin'); ?>"
                >
                <p class="ayuda-campo">Si coloca fecha fin, el empleado y el cargo quedarán marcados como inactivos.</p>
                <?php $mostrarError('fecha_fin'); ?>
            </div>

            <div class="fila-formulario">
                <div class="grupo-formulario">
                    <label>Cargo activo</label>

                    <div class="opciones-linea opciones-dos">
                        <label class="opcion-radio">
                            <input type="radio" id="cargo_activo_si" name="cargo_activo" value="1" <?php echo $marcarRadio('cargo_activo', 1, true); ?> required>
                            Sí
                        </label>

                        <label class="opcion-radio">
                            <input type="radio" id="cargo_activo_no" name="cargo_activo" value="0" <?php echo $marcarRadio('cargo_activo', 0); ?>>
                            No
                        </label>
                    </div>

                    <?php $mostrarError('cargo_activo'); ?>
                </div>

                <div class="grupo-formulario">
                    <label>Empleado activo</label>

                    <div class="opciones-linea opciones-dos">
                        <label class="opcion-radio">
                            <input type="radio" id="empleado_activo_si" name="empleado_activo" value="1" <?php echo $marcarRadio('empleado_activo', 1, true); ?> required>
                            Sí
                        </label>

                        <label class="opcion-radio">
                            <input type="radio" id="empleado_activo_no" name="empleado_activo" value="0" <?php echo $marcarRadio('empleado_activo', 0); ?>>
                            No
                        </label>
                    </div>

                    <?php $mostrarError('empleado_activo'); ?>
                </div>
            </div>

            <div class="grupo-formulario">
                <label for="motivo_terminacion_id">Motivo de baja</label>

                <select id="motivo_terminacion_id" name="motivo_terminacion_id" disabled>
                    <option value="">Seleccione un motivo si aplica</option>

                    <?php foreach ($motivosTerminacion as $motivo): ?>
                        <option 
                            value="<?php echo htmlspecialchars((string) $motivo['id'], ENT_QUOTES, 'UTF-8'); ?>"
                            <?php echo $seleccionar('motivo_terminacion_id', (int) $motivo['id']); ?>
                        >
                            <?php echo htmlspecialchars($motivo['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php $mostrarError('motivo_terminacion_id'); ?>
            </div>

            <div class="aviso-openssl">
                <strong>Auditoría OpenSSL:</strong>
                <span>
                    Más adelante se firmarán salario, código de empleado, tipo de empleado/planilla,
                    ocupación y fecha de inicio.
                </span>
            </div>
        </div>

        <div class="acciones-formulario">
            <button type="reset" class="boton-secundario">Limpiar formulario</button>
            <button type="submit" class="boton-principal">Validar colaborador</button>
        </div>

    </form>
</section>

<script src="public/js/formulario.js"></script>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
