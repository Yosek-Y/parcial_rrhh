<?php

declare(strict_types=1);

session_start();

define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'App');

$vendorAutoload = ROOT_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
}

spl_autoload_register(function (string $clase): void {
    $prefijo = 'App\\';
    $baseDirectorio = APP_PATH . DIRECTORY_SEPARATOR;

    $longitudPrefijo = strlen($prefijo);

    if (strncmp($prefijo, $clase, $longitudPrefijo) !== 0) {
        return;
    }

    $claseRelativa = substr($clase, $longitudPrefijo);

    $archivo = $baseDirectorio . str_replace('\\', DIRECTORY_SEPARATOR, $claseRelativa) . '.php';

    if (file_exists($archivo)) {
        require_once $archivo;
    }
});

use App\Controllers\HomeController;
use App\Controllers\ColaboradorController;
use App\Controllers\ReporteController;
use App\Controllers\ExportarController;

$accion = $_GET['accion'] ?? 'formulario';

try {
    switch ($accion) {
        case 'inicio':
            $controlador = new HomeController();
            $controlador->inicio();
            break;

        case 'formulario':
            $controlador = new ColaboradorController();
            $controlador->mostrarFormulario();
            break;

        case 'guardar_colaborador':
            $controlador = new ColaboradorController();
            $controlador->guardarColaborador();
            break;

        case 'reporte':
            $controlador = new ReporteController();
            $controlador->mostrarReporte();
            break;

        case 'exportar_excel':
            $controlador = new ExportarController();
            $controlador->exportarExcel();
            break;

        default:
            http_response_code(404);
            echo 'Página no encontrada.';
            break;
    }
} catch (Throwable $error) {
    http_response_code(500);

    echo '<h2>Error interno del sistema</h2>';
    echo '<p>No se pudo procesar la solicitud.</p>';
    echo '<p><strong>Detalle:</strong> ' . htmlspecialchars($error->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
}