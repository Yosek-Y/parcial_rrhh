<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Conexion;
use App\Models\CatalogoModel;
use App\Utilities\VerificadorSistema;

class HomeController
{
    public function inicio(): void
    {
        $conexionActiva = Conexion::probarConexion();

        $catalogoModel = new CatalogoModel();

        $tiposSangre = $catalogoModel->obtenerTiposSangre();
        $sexos = $catalogoModel->obtenerSexos();
        $rutas = $catalogoModel->obtenerRutas();
        $tiposEmpleado = $catalogoModel->obtenerTiposEmpleado();
        $ocupaciones = $catalogoModel->obtenerOcupaciones();
        $motivosTerminacion = $catalogoModel->obtenerMotivosTerminacion();

        $opensslDisponible = VerificadorSistema::opensslDisponible();
        $versionPhp = VerificadorSistema::versionPhp();
        $carpetaLlavesExiste = VerificadorSistema::carpetaLlavesExiste();

        require_once APP_PATH . '/Views/inicio.php';
    }
}
