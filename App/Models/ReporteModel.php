<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Conexion;
use PDO;

class ReporteModel
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    public function obtenerReporteColaboradores(): array
    {
        $sql = "SELECT
                    p.id AS perfil_id,
                    c.codigo_empleado,
                    c.identidad,
                    c.nombre,
                    c.apellido,
                    c.edad,
                    TRIM(ts.Nombre) AS tipo_sangre,
                    sx.nombre AS sexo,
                    ec.nombre AS estado_civil,
                    c.nacionalidad,
                    r.Nombre AS ruta,
                    c.correo,
                    c.celular,
                    o.OCUPACION AS ocupacion,
                    te.Nombre AS tipo_empleado,
                    te.Nombre AS planilla,
                    p.salario,
                    p.fecha_inicio,
                    p.fecha_fin,
                    p.cargo_activo,
                    p.empleado_activo,
                    mt.MOTIVO AS motivo_terminacion,
                    p.motivo_baja,
                    p.firma_digital,
                    c.fecha_registro
                FROM colaboradores c
                INNER JOIN cat_tiposangre ts
                    ON c.tipo_sangre_id = ts.id
                INNER JOIN cat_sexo sx
                    ON c.sexo_id = sx.id
                INNER JOIN cat_estadocivil ec
                    ON c.estado_civil_id = ec.id
                INNER JOIN cat_rutas r
                    ON c.ruta_id = r.id
                INNER JOIN perfiles_laborales p
                    ON c.codigo_empleado = p.codigo_empleado
                INNER JOIN cat_ocupaciones o
                    ON p.ocupacion_id = o.C_OCUP
                INNER JOIN cat_tipoempleado te
                    ON p.tipo_empleado_id = te.id
                LEFT JOIN cat_motivos_terminacion mt
                    ON p.motivo_terminacion_id = mt.C_TERMINACION
                ORDER BY c.codigo_empleado DESC, p.id DESC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }
}