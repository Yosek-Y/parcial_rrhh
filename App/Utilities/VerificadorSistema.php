<?php

declare(strict_types=1);

namespace App\Utilities;

class VerificadorSistema
{
    public static function opensslDisponible(): bool
    {
        return extension_loaded('openssl');
    }

    public static function versionPhp(): string
    {
        return PHP_VERSION;
    }

    public static function rutaProyecto(): string
    {
        return ROOT_PATH;
    }

    public static function carpetaLlavesExiste(): bool
    {
        return is_dir(ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'keys');
    }
}