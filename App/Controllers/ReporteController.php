<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ReporteModel;
use App\Utilities\FirmaDigital;

class ReporteController
{
    public function mostrarReporte(): void
    {
        $reporteModel = new ReporteModel();

        $registros = $reporteModel->obtenerReporteColaboradores();

        foreach ($registros as &$registro) {
            $cadenaAuditoria = FirmaDigital::construirCadenaAuditoria(
                (int) $registro['codigo_empleado'],
                (float) $registro['salario'],
                (string) $registro['tipo_empleado'],
                (string) $registro['planilla'],
                (string) $registro['ocupacion'],
                (string) $registro['fecha_inicio']
            );

            $firmaDigital = (string) ($registro['firma_digital'] ?? '');

            $registro['cadena_auditoria'] = $cadenaAuditoria;
            $registro['integridad_valida'] = $firmaDigital !== '' && FirmaDigital::verificar($cadenaAuditoria, $firmaDigital);
        }

        unset($registro);

        require_once APP_PATH . '/Views/reporte.php';
    }
}