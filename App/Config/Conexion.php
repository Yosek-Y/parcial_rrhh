<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

class Conexion
{
    private const SERVIDOR = 'localhost';
    private const BASE_DATOS = 'parcial_3';
    private const USUARIO = 'root';
    private const PASSWORD = '';

    private static ?PDO $conexion = null;

    public static function conectar(): PDO
    {
        if (self::$conexion === null) {
            try {
                $dsn = 'mysql:host=' . self::SERVIDOR . ';dbname=' . self::BASE_DATOS . ';charset=utf8mb4';

                self::$conexion = new PDO(
                    $dsn,
                    self::USUARIO,
                    self::PASSWORD,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $error) {
                throw new PDOException('Error al conectar con la base de datos: ' . $error->getMessage());
            }
        }

        return self::$conexion;
    }

    public static function probarConexion(): bool
    {
        $conexion = self::conectar();

        return $conexion instanceof PDO;
    }
}
