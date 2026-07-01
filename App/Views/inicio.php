<?php require_once APP_PATH . '/Views/layouts/header.php'; ?>

<section class="tarjeta">
    <h2>Prueba inicial del sistema RRHH</h2>

    <div class="estado-grid">
        <div class="estado">
            <h3>Base de datos</h3>

            <?php if ($conexionActiva): ?>
                <p class="ok">Conexión exitosa con la base de datos parcial_3.</p>
            <?php else: ?>
                <p class="error">No se pudo conectar con la base de datos.</p>
            <?php endif; ?>
        </div>

        <div class="estado">
            <h3>OpenSSL</h3>

            <?php if ($opensslDisponible): ?>
                <p class="ok">OpenSSL está disponible para la firma digital.</p>
            <?php else: ?>
                <p class="error">OpenSSL no está habilitado en PHP.</p>
            <?php endif; ?>
        </div>

        <div class="estado">
            <h3>Storage de llaves</h3>

            <?php if ($carpetaLlavesExiste): ?>
                <p class="ok">La carpeta storage/keys existe correctamente.</p>
            <?php else: ?>
                <p class="error">No existe la carpeta storage/keys.</p>
            <?php endif; ?>
        </div>

        <div class="estado">
            <h3>Versión de PHP</h3>
            <p><?php echo htmlspecialchars($versionPhp, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </div>
</section>

<section class="tarjeta">
    <h2>Catálogos oficiales cargados desde parcial_3</h2>

    <div class="catalogos-grid">
        <div class="catalogo">
            <h3>Tipos de sangre</h3>
            <ul class="lista">
                <?php foreach ($tiposSangre as $tipo): ?>
                    <li><?php echo htmlspecialchars($tipo['Nombre'], ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="catalogo">
            <h3>Sexo</h3>
            <ul class="lista">
                <?php foreach ($sexos as $sexo): ?>
                    <li><?php echo htmlspecialchars($sexo['nombre'], ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="catalogo">
            <h3>Rutas</h3>
            <ul class="lista">
                <?php foreach ($rutas as $ruta): ?>
                    <li><?php echo htmlspecialchars($ruta['nombre'], ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="catalogo">
            <h3>Tipos de empleado / planilla</h3>
            <ul class="lista">
                <?php foreach ($tiposEmpleado as $tipoEmpleado): ?>
                    <li><?php echo htmlspecialchars($tipoEmpleado['nombre'], ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="catalogo catalogo-ancho">
            <h3>Ocupaciones</h3>
            <p class="ayuda-campo">
                Se cargaron <?php echo count($ocupaciones); ?> ocupaciones activas desde el catálogo oficial.
            </p>
        </div>

        <div class="catalogo catalogo-ancho">
            <h3>Motivos de terminación</h3>
            <p class="ayuda-campo">
                Se cargaron <?php echo count($motivosTerminacion); ?> motivos oficiales para bajas laborales.
            </p>
        </div>
    </div>
</section>

<a class="boton-principal" href="index.php?accion=formulario">
    Ir al formulario de colaborador
</a>


<?php require_once APP_PATH . '/Views/layouts/footer.php'; ?>
