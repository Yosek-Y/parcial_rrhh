<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<section class="tarjeta">
    <div class="encabezado-formulario">
        <div>
            <h2>Datos validados correctamente</h2>
            <p>Estos datos ya fueron limpiados y están listos para guardarse en la siguiente fase.</p>
        </div>

        <a class="boton-secundario" href="index.php?accion=formulario">Registrar otro</a>
    </div>

    <div class="alerta-exito">
        <strong>Validación exitosa.</strong>
        <span>
            En esta fase se corrigió el proyecto para usar la base oficial parcial_3 y sus catálogos.
            La siguiente fase será guardar en las tablas colaborador/perfil laboral.
        </span>
    </div>

    <table class="tabla-preview">
        <tbody>
            <tr>
                <th>Identidad</th>
                <td><?php echo htmlspecialchars($datos['identidad'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Nombre completo</th>
                <td><?php echo htmlspecialchars($datos['nombre'] . ' ' . $datos['apellido'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Edad</th>
                <td><?php echo htmlspecialchars((string) $datos['edad'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Tipo de sangre</th>
                <td><?php echo htmlspecialchars($tipoSangre, ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Sexo</th>
                <td><?php echo htmlspecialchars($sexo, ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Nacionalidad</th>
                <td><?php echo htmlspecialchars($datos['nacionalidad'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Ruta</th>
                <td><?php echo htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Correo</th>
                <td><?php echo htmlspecialchars($datos['correo'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Celular</th>
                <td><?php echo htmlspecialchars($datos['celular'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Ocupación</th>
                <td><?php echo htmlspecialchars($ocupacion, ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Tipo de empleado / planilla</th>
                <td><?php echo htmlspecialchars($tipoEmpleado, ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Salario</th>
                <td>B/. <?php echo htmlspecialchars(number_format($datos['salario'], 2), ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Fecha de inicio</th>
                <td><?php echo htmlspecialchars($datos['fecha_inicio'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Fecha fin</th>
                <td><?php echo htmlspecialchars($datos['fecha_fin'] !== '' ? $datos['fecha_fin'] : 'No aplica', ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Cargo activo</th>
                <td><?php echo ((int) $datos['cargo_activo'] === 1) ? 'Sí' : 'No'; ?></td>
            </tr>

            <tr>
                <th>Empleado activo</th>
                <td><?php echo ((int) $datos['empleado_activo'] === 1) ? 'Sí' : 'No'; ?></td>
            </tr>

            <tr>
                <th>Motivo de baja</th>
                <td><?php echo htmlspecialchars($motivoTerminacion, ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Cadena previa para OpenSSL</th>
                <td><code><?php echo htmlspecialchars($cadenaAuditoriaPrevia, ENT_QUOTES, 'UTF-8'); ?></code></td>
            </tr>
        </tbody>
    </table>

    <div class="acciones-formulario">
        <a class="boton-secundario" href="index.php?accion=formulario">Volver al formulario</a>
    </div>
</section>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
