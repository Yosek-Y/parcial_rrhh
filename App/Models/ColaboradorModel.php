<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Conexion;
use App\Utilities\FirmaDigital;
use PDO;
use PDOException;
use Throwable;

class ColaboradorModel
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    public function guardarColaboradorConPerfil(
        array $datos,
        string $tipoEmpleadoNombre,
        string $planillaNombre,
        string $ocupacionNombre
    ): array {
        try {
            $this->conexion->beginTransaction();

            $codigoEmpleado = $this->insertarColaborador($datos);

            $cadenaAuditoria = FirmaDigital::construirCadenaAuditoria(
                $codigoEmpleado,
                (float) $datos['salario'],
                $tipoEmpleadoNombre,
                $planillaNombre,
                $ocupacionNombre,
                $datos['fecha_inicio']
            );

            $firmaDigital = FirmaDigital::firmar($cadenaAuditoria);

            $perfilId = $this->insertarPerfilLaboral(
                $codigoEmpleado,
                $datos,
                $firmaDigital
            );

            $this->conexion->commit();

            return [
                'exito' => true,
                'codigo_empleado' => $codigoEmpleado,
                'perfil_id' => $perfilId,
                'cadena_auditoria' => $cadenaAuditoria,
                'firma_digital' => $firmaDigital,
                'mensaje' => 'Colaborador registrado correctamente.'
            ];
        } catch (Throwable $error) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }

            return [
                'exito' => false,
                'codigo_empleado' => 0,
                'perfil_id' => 0,
                'cadena_auditoria' => '',
                'firma_digital' => '',
                'mensaje' => $this->traducirError($error->getMessage())
            ];
        }
    }

    private function insertarColaborador(array $datos): int
    {
        $sql = "INSERT INTO colaboradores (
                    identidad,
                    nombre,
                    apellido,
                    edad,
                    tipo_sangre_id,
                    sexo_id,
                    estado_civil_id,
                    nacionalidad,
                    ruta_id,
                    correo,
                    celular
                ) VALUES (
                    :identidad,
                    :nombre,
                    :apellido,
                    :edad,
                    :tipo_sangre_id,
                    :sexo_id,
                    :estado_civil_id,
                    :nacionalidad,
                    :ruta_id,
                    :correo,
                    :celular
                )";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':identidad' => $datos['identidad'],
            ':nombre' => $datos['nombre'],
            ':apellido' => $datos['apellido'],
            ':edad' => $datos['edad'],
            ':tipo_sangre_id' => $datos['tipo_sangre_id'],
            ':sexo_id' => $datos['sexo_id'],
            ':estado_civil_id' => $datos['estado_civil_id'],
            ':nacionalidad' => $datos['nacionalidad'],
            ':ruta_id' => $datos['ruta_id'],
            ':correo' => $datos['correo'],
            ':celular' => $datos['celular'],
        ]);

        return (int) $this->conexion->lastInsertId();
    }

    private function insertarPerfilLaboral(
        int $codigoEmpleado,
        array $datos,
        string $firmaDigital
    ): int {
        $fechaFin = $datos['fecha_fin'] !== '' ? $datos['fecha_fin'] : null;
        $motivoTerminacionId = $datos['motivo_terminacion_id'] > 0 ? $datos['motivo_terminacion_id'] : null;
        $motivoBaja = $datos['motivo_baja'] !== '' ? $datos['motivo_baja'] : null;

        $sql = "INSERT INTO perfiles_laborales (
                    codigo_empleado,
                    ocupacion_id,
                    tipo_empleado_id,
                    salario,
                    fecha_inicio,
                    fecha_fin,
                    cargo_activo,
                    empleado_activo,
                    motivo_terminacion_id,
                    motivo_baja,
                    firma_digital
                ) VALUES (
                    :codigo_empleado,
                    :ocupacion_id,
                    :tipo_empleado_id,
                    :salario,
                    :fecha_inicio,
                    :fecha_fin,
                    :cargo_activo,
                    :empleado_activo,
                    :motivo_terminacion_id,
                    :motivo_baja,
                    :firma_digital
                )";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            ':codigo_empleado' => $codigoEmpleado,
            ':ocupacion_id' => $datos['ocupacion_id'],
            ':tipo_empleado_id' => $datos['tipo_empleado_id'],
            ':salario' => $datos['salario'],
            ':fecha_inicio' => $datos['fecha_inicio'],
            ':fecha_fin' => $fechaFin,
            ':cargo_activo' => $datos['cargo_activo'],
            ':empleado_activo' => $datos['empleado_activo'],
            ':motivo_terminacion_id' => $motivoTerminacionId,
            ':motivo_baja' => $motivoBaja,
            ':firma_digital' => $firmaDigital,
        ]);

        return (int) $this->conexion->lastInsertId();
    }

    private function traducirError(string $mensajeError): string
    {
        if (str_contains($mensajeError, 'Duplicate entry')) {
            return 'Ya existe un colaborador con esa identidad, correo o celular.';
        }

        if (str_contains($mensajeError, 'foreign key constraint')) {
            return 'Uno de los catálogos seleccionados no existe en la base de datos.';
        }

        if (str_contains($mensajeError, 'No se pudieron generar las llaves OpenSSL')) {
            return 'No se pudieron generar las llaves OpenSSL. Revise que la extensión OpenSSL esté activa en XAMPP.';
        }

        return 'No se pudo guardar el colaborador. Detalle técnico: ' . $mensajeError;
    }
}