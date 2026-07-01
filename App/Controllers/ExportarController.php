<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\ReporteModel;
use App\Utilities\FirmaDigital;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportarController
{
    public function exportarExcel(): void
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

            $registro['integridad_valida'] = $firmaDigital !== '' && FirmaDigital::verificar($cadenaAuditoria, $firmaDigital);
        }

        unset($registro);

        $spreadsheet = new Spreadsheet();
        $hoja = $spreadsheet->getActiveSheet();
        $hoja->setTitle('Colaboradores');

        $encabezados = [
            'Integridad',
            'Código Empleado',
            'ID Perfil',
            'Identidad',
            'Nombre',
            'Apellido',
            'Edad',
            'Sexo',
            'Estado Civil',
            'Tipo Sangre',
            'Nacionalidad',
            'Ruta',
            'Correo',
            'Celular',
            'Ocupación',
            'Tipo Empleado / Planilla',
            'Salario',
            'Fecha Inicio',
            'Fecha Fin',
            'Cargo Activo',
            'Empleado Activo',
            'Motivo Terminación',
            'Motivo Baja',
            'Fecha Registro'
        ];

        $hoja->fromArray($encabezados, null, 'A1');

        $fila = 2;

        foreach ($registros as $registro) {
            $integridad = $registro['integridad_valida'] ? 'Válido' : 'Alterado';

            $hoja->setCellValue('A' . $fila, $integridad);
            $hoja->setCellValue('B' . $fila, (int) $registro['codigo_empleado']);
            $hoja->setCellValue('C' . $fila, (int) $registro['perfil_id']);
            $hoja->setCellValue('D' . $fila, (string) $registro['identidad']);
            $hoja->setCellValue('E' . $fila, (string) $registro['nombre']);
            $hoja->setCellValue('F' . $fila, (string) $registro['apellido']);
            $hoja->setCellValue('G' . $fila, (int) $registro['edad']);
            $hoja->setCellValue('H' . $fila, (string) $registro['sexo']);
            $hoja->setCellValue('I' . $fila, (string) $registro['estado_civil']);
            $hoja->setCellValue('J' . $fila, (string) $registro['tipo_sangre']);
            $hoja->setCellValue('K' . $fila, (string) $registro['nacionalidad']);
            $hoja->setCellValue('L' . $fila, (string) $registro['ruta']);
            $hoja->setCellValue('M' . $fila, (string) $registro['correo']);
            $hoja->setCellValue('N' . $fila, (string) $registro['celular']);
            $hoja->setCellValue('O' . $fila, (string) $registro['ocupacion']);
            $hoja->setCellValue('P' . $fila, (string) $registro['tipo_empleado']);
            $hoja->setCellValue('Q' . $fila, (float) $registro['salario']);
            $hoja->setCellValue('R' . $fila, (string) $registro['fecha_inicio']);
            $hoja->setCellValue('S' . $fila, $registro['fecha_fin'] ?: 'No aplica');
            $hoja->setCellValue('T' . $fila, ((int) $registro['cargo_activo'] === 1) ? 'Sí' : 'No');
            $hoja->setCellValue('U' . $fila, ((int) $registro['empleado_activo'] === 1) ? 'Sí' : 'No');
            $hoja->setCellValue('V' . $fila, $registro['motivo_terminacion'] ?: 'No aplica');
            $hoja->setCellValue('W' . $fila, $registro['motivo_baja'] ?: 'No aplica');
            $hoja->setCellValue('X' . $fila, (string) $registro['fecha_registro']);

            $hoja->getStyle('A' . $fila)->getFont()->setBold(true);

            if ($registro['integridad_valida']) {
                $hoja->getStyle('A' . $fila)->getFont()->getColor()->setRGB('059669');
            } else {
                $hoja->getStyle('A' . $fila)->getFont()->getColor()->setRGB('DC2626');
            }

            $fila++;
        }

        $ultimaFila = max($fila - 1, 1);
        $rangoTabla = 'A1:X' . $ultimaFila;

        $hoja->getStyle('A1:X1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $hoja->getStyle($rangoTabla)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D7DDE5'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
                'wrapText' => true,
            ],
        ]);

        $hoja->getStyle('Q2:Q' . $ultimaFila)
            ->getNumberFormat()
            ->setFormatCode('"B/. "#,##0.00');

        foreach (range('A', 'X') as $columna) {
            $hoja->getColumnDimension($columna)->setAutoSize(true);
        }

        $hoja->freezePane('A2');
        $hoja->setAutoFilter($rangoTabla);

        $nombreArchivo = 'reporte_colaboradores_' . date('Y-m-d_H-i-s') . '.xlsx';

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        exit;
    }
}