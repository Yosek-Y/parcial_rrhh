<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<section class="tarjeta">
    <div class="encabezado-formulario">
        <div>
            <h2>Colaborador registrado correctamente</h2>
            <p>El colaborador y su perfil laboral fueron guardados con firma digital.</p>
        </div>

        <a class="boton-secundario" href="index.php?accion=formulario">
            Registrar otro
        </a>
    </div>

    <div class="alerta-exito">
        <strong>Registro exitoso.</strong>
        <span>
            Los datos fueron guardados usando una transacción y la firma digital fue generada con OpenSSL.
        </span>
    </div>

    <table class="tabla-preview">
        <tbody>
            <tr>
                <th>Código de empleado</th>
                <td><?php echo htmlspecialchars((string) $codigoEmpleado, ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>ID del perfil laboral</th>
                <td><?php echo htmlspecialchars((string) $perfilId, ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Nombre completo</th>
                <td>
                    <?php echo htmlspecialchars($datos['nombre'] . ' ' . $datos['apellido'], ENT_QUOTES, 'UTF-8'); ?>
                </td>
            </tr>

            <tr>
                <th>Identidad</th>
                <td><?php echo htmlspecialchars($datos['identidad'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>

            <tr>
                <th>Correo</th>
                <td><?php echo htmlspecialchars($datos['correo'], ENT_QUOTES, 'UTF-8'); ?></td>
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
                <th>Cadena firmada</th>
                <td>
                    <code><?php echo htmlspecialchars($cadenaAuditoriaFinal, ENT_QUOTES, 'UTF-8'); ?></code>
                </td>
            </tr>

            <tr>
                <th>Firma digital</th>
                <td>
                    <code><?php echo htmlspecialchars($firmaDigital, ENT_QUOTES, 'UTF-8'); ?></code>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="acciones-formulario">
        <a class="boton-principal" href="index.php?accion=formulario">
            Registrar nuevo colaborador
        </a>
    </div>
</section>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>