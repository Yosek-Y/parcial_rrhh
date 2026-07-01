<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<section class="tarjeta">
    <div class="encabezado-formulario">
        <div>
            <h2>Reporte de Colaboradores</h2>
            <p>Listado de colaboradores registrados con verificación de integridad OpenSSL.</p>
        </div>

        <div class="acciones-header">
            <a class="boton-secundario" href="index.php?accion=formulario">
                Registrar colaborador
            </a>

            <a class="boton-principal" href="index.php?accion=exportar_excel">
                Exportar Excel
            </a>
        </div>
    </div>

    <?php if (empty($registros)): ?>
        <div class="resumen-errores">
            <strong>No hay registros.</strong>
            <span>Aún no se han registrado colaboradores en el sistema.</span>
        </div>
    <?php else: ?>

        <div class="tabla-contenedor">
            <table class="tabla-reporte">
                <thead>
                    <tr>
                        <th>Integridad</th>
                        <th>Código</th>
                        <th>Colaborador</th>
                        <th>Identidad</th>
                        <th>Contacto</th>
                        <th>Datos personales</th>
                        <th>Perfil laboral</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($registros as $registro): ?>
                        <tr>
                            <td>
                                <?php if ($registro['integridad_valida']): ?>
                                    <span class="badge-integridad valido">Válido</span>
                                <?php else: ?>
                                    <span class="badge-integridad corrupto">Alterado</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <strong>
                                    <?php echo htmlspecialchars((string) $registro['codigo_empleado'], ENT_QUOTES, 'UTF-8'); ?>
                                </strong>
                                <br>
                                <small>Perfil #<?php echo htmlspecialchars((string) $registro['perfil_id'], ENT_QUOTES, 'UTF-8'); ?></small>
                            </td>

                            <td>
                                <strong>
                                    <?php echo htmlspecialchars($registro['nombre'] . ' ' . $registro['apellido'], ENT_QUOTES, 'UTF-8'); ?>
                                </strong>
                                <br>
                                <small>
                                    Registrado: <?php echo htmlspecialchars((string) $registro['fecha_registro'], ENT_QUOTES, 'UTF-8'); ?>
                                </small>
                            </td>

                            <td>
                                <?php echo htmlspecialchars((string) $registro['identidad'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <strong>Correo:</strong>
                                <?php echo htmlspecialchars((string) $registro['correo'], ENT_QUOTES, 'UTF-8'); ?>
                                <br>

                                <strong>Celular:</strong>
                                <?php echo htmlspecialchars((string) $registro['celular'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <strong>Edad:</strong>
                                <?php echo htmlspecialchars((string) $registro['edad'], ENT_QUOTES, 'UTF-8'); ?>
                                <br>

                                <strong>Sexo:</strong>
                                <?php echo htmlspecialchars((string) $registro['sexo'], ENT_QUOTES, 'UTF-8'); ?>
                                <br>

                                <strong>Estado civil:</strong>
                                <?php echo htmlspecialchars((string) $registro['estado_civil'], ENT_QUOTES, 'UTF-8'); ?>
                                <br>

                                <strong>Sangre:</strong>
                                <?php echo htmlspecialchars((string) $registro['tipo_sangre'], ENT_QUOTES, 'UTF-8'); ?>
                                <br>

                                <strong>Nacionalidad:</strong>
                                <?php echo htmlspecialchars((string) $registro['nacionalidad'], ENT_QUOTES, 'UTF-8'); ?>
                                <br>

                                <strong>Ruta:</strong>
                                <?php echo htmlspecialchars((string) $registro['ruta'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <strong>Ocupación:</strong>
                                <?php echo htmlspecialchars((string) $registro['ocupacion'], ENT_QUOTES, 'UTF-8'); ?>
                                <br>

                                <strong>Tipo empleado:</strong>
                                <?php echo htmlspecialchars((string) $registro['tipo_empleado'], ENT_QUOTES, 'UTF-8'); ?>
                                <br>

                                <strong>Salario:</strong>
                                B/. <?php echo htmlspecialchars(number_format((float) $registro['salario'], 2), ENT_QUOTES, 'UTF-8'); ?>
                                <br>

                                <strong>Inicio:</strong>
                                <?php echo htmlspecialchars((string) $registro['fecha_inicio'], ENT_QUOTES, 'UTF-8'); ?>
                                <br>

                                <strong>Fin:</strong>
                                <?php echo htmlspecialchars($registro['fecha_fin'] ?: 'No aplica', ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <strong>Cargo activo:</strong>
                                <?php echo ((int) $registro['cargo_activo'] === 1) ? 'Sí' : 'No'; ?>
                                <br>

                                <strong>Empleado activo:</strong>
                                <?php echo ((int) $registro['empleado_activo'] === 1) ? 'Sí' : 'No'; ?>
                                <br>

                                <strong>Motivo:</strong>
                                <?php
                                    $motivo = $registro['motivo_terminacion'] ?: $registro['motivo_baja'] ?: 'No aplica';
                                    echo htmlspecialchars((string) $motivo, ENT_QUOTES, 'UTF-8');
                                ?>
                            </td>
                        </tr>

                        <tr class="fila-auditoria">
                            <td colspan="8">
                                <strong>Cadena auditada:</strong>
                                <code>
                                    <?php echo htmlspecialchars((string) $registro['cadena_auditoria'], ENT_QUOTES, 'UTF-8'); ?>
                                </code>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</section>

<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>