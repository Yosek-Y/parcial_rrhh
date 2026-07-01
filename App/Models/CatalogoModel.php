<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Conexion;
use PDO;

class CatalogoModel
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = Conexion::conectar();
    }

    public function obtenerTiposSangre(): array
    {
        $sql = "SELECT id, TRIM(REPLACE(Nombre, ' ', '')) AS Nombre
                FROM cat_tiposangre
                ORDER BY id ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    public function obtenerSexos(): array
    {
        $sql = "SELECT id, nombre
                FROM cat_sexo
                ORDER BY id ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    public function obtenerEstadosCiviles(): array
    {
        $sql = "SELECT id, nombre
                FROM cat_estadocivil
                WHERE id <> 1
                ORDER BY id ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    public function obtenerRutas(): array
    {
        $sql = "SELECT id, Nombre AS nombre
                FROM cat_rutas
                ORDER BY id ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    public function obtenerTiposEmpleado(): array
    {
        $sql = "SELECT id, Nombre AS nombre, Abreviatura
                FROM cat_tipoempleado
                WHERE Activo = 1
                ORDER BY id ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    public function obtenerOcupaciones(): array
    {
        $sql = "SELECT C_OCUP AS id, OCUPACION AS nombre
                FROM cat_ocupaciones
                WHERE Activo = 1
                ORDER BY OCUPACION ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }

    public function obtenerMotivosTerminacion(): array
    {
        $sql = "SELECT C_TERMINACION AS id, MOTIVO AS nombre
                FROM cat_motivos_terminacion
                ORDER BY MOTIVO ASC";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();

        return $consulta->fetchAll();
    }
}
