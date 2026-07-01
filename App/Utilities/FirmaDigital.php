<?php

declare(strict_types=1);

namespace App\Utilities;

use RuntimeException;

class FirmaDigital
{
    private const PRIVATE_KEY = 'private_key.pem';
    private const PUBLIC_KEY = 'public_key.pem';
    private const OPENSSL_CONFIG = 'openssl.cnf';

    public static function construirCadenaAuditoria(
        int $codigoEmpleado,
        float $salario,
        string $tipoEmpleado,
        string $planilla,
        string $ocupacion,
        string $fechaInicio
    ): string {
        return implode('|', [
            $codigoEmpleado,
            number_format($salario, 2, '.', ''),
            trim($tipoEmpleado),
            trim($planilla),
            trim($ocupacion),
            trim($fechaInicio),
        ]);
    }

    public static function firmar(string $cadena): string
    {
        if (!extension_loaded('openssl')) {
            throw new RuntimeException('La extensión OpenSSL no está habilitada en PHP.');
        }

        self::generarLlavesSiNoExisten();

        $rutaPrivada = self::rutaLlaves() . DIRECTORY_SEPARATOR . self::PRIVATE_KEY;
        $contenidoPrivado = file_get_contents($rutaPrivada);

        if ($contenidoPrivado === false) {
            throw new RuntimeException('No se pudo leer la llave privada.');
        }

        $llavePrivada = openssl_pkey_get_private($contenidoPrivado);

        if ($llavePrivada === false) {
            throw new RuntimeException('No se pudo cargar la llave privada. ' . self::obtenerErroresOpenSsl());
        }

        $firma = '';

        $resultado = openssl_sign(
            $cadena,
            $firma,
            $llavePrivada,
            OPENSSL_ALGO_SHA256
        );

        if (!$resultado) {
            throw new RuntimeException('No se pudo firmar la cadena con OpenSSL. ' . self::obtenerErroresOpenSsl());
        }

        return base64_encode($firma);
    }

    public static function verificar(string $cadena, string $firmaBase64): bool
    {
        if (!extension_loaded('openssl')) {
            return false;
        }

        self::generarLlavesSiNoExisten();

        $rutaPublica = self::rutaLlaves() . DIRECTORY_SEPARATOR . self::PUBLIC_KEY;
        $contenidoPublico = file_get_contents($rutaPublica);

        if ($contenidoPublico === false) {
            return false;
        }

        $llavePublica = openssl_pkey_get_public($contenidoPublico);

        if ($llavePublica === false) {
            return false;
        }

        $firma = base64_decode($firmaBase64, true);

        if ($firma === false) {
            return false;
        }

        $resultado = openssl_verify(
            $cadena,
            $firma,
            $llavePublica,
            OPENSSL_ALGO_SHA256
        );

        return $resultado === 1;
    }

    private static function generarLlavesSiNoExisten(): void
    {
        $directorio = self::rutaLlaves();

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $rutaPrivada = $directorio . DIRECTORY_SEPARATOR . self::PRIVATE_KEY;
        $rutaPublica = $directorio . DIRECTORY_SEPARATOR . self::PUBLIC_KEY;

        if (file_exists($rutaPrivada) && file_exists($rutaPublica)) {
            return;
        }

        $rutaConfig = self::obtenerRutaConfigOpenSsl();

        $configuracion = [
            'config' => $rutaConfig,
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'digest_alg' => 'sha256',
        ];

        $recursoLlave = openssl_pkey_new($configuracion);

        if ($recursoLlave === false) {
            throw new RuntimeException(
                'No se pudieron generar las llaves OpenSSL. ' . self::obtenerErroresOpenSsl()
            );
        }

        $llavePrivada = '';

        $exportada = openssl_pkey_export(
            $recursoLlave,
            $llavePrivada,
            null,
            $configuracion
        );

        if (!$exportada) {
            throw new RuntimeException(
                'No se pudo exportar la llave privada. ' . self::obtenerErroresOpenSsl()
            );
        }

        $detalles = openssl_pkey_get_details($recursoLlave);

        if ($detalles === false || !isset($detalles['key'])) {
            throw new RuntimeException(
                'No se pudo obtener la llave pública. ' . self::obtenerErroresOpenSsl()
            );
        }

        file_put_contents($rutaPrivada, $llavePrivada);
        file_put_contents($rutaPublica, $detalles['key']);
    }

    private static function rutaLlaves(): string
    {
        return ROOT_PATH . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'keys';
    }

    private static function obtenerRutaConfigOpenSsl(): string
    {
        $directorio = self::rutaLlaves();

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $rutasPosibles = [
            'C:\xampp\apache\conf\openssl.cnf',
            'C:\xampp\php\extras\ssl\openssl.cnf',
            'C:\xampp\apache\bin\openssl.cnf',
            'C:\wamp64\bin\apache\apache2.4.58\conf\openssl.cnf',
        ];

        foreach ($rutasPosibles as $ruta) {
            if (file_exists($ruta)) {
                putenv('OPENSSL_CONF=' . $ruta);
                return $ruta;
            }
        }

        /*
         * Si XAMPP no trae openssl.cnf donde PHP lo espera,
         * creamos uno mínimo dentro del proyecto.
         */
        $rutaConfigProyecto = $directorio . DIRECTORY_SEPARATOR . self::OPENSSL_CONFIG;

        if (!file_exists($rutaConfigProyecto)) {
            $contenido = <<<CONFIG
[ req ]
default_bits = 2048
default_md = sha256
distinguished_name = req_distinguished_name
prompt = no

[ req_distinguished_name ]
C = PA
ST = Panama
L = Panama
O = ITECH
OU = RecursosHumanos
CN = ParcialRRHH
CONFIG;

            file_put_contents($rutaConfigProyecto, $contenido);
        }

        putenv('OPENSSL_CONF=' . $rutaConfigProyecto);

        return $rutaConfigProyecto;
    }

    private static function obtenerErroresOpenSsl(): string
    {
        $errores = [];

        while ($mensaje = openssl_error_string()) {
            $errores[] = $mensaje;
        }

        if (empty($errores)) {
            return 'OpenSSL no devolvió un detalle específico.';
        }

        return 'Detalle: ' . implode(' | ', $errores);
    }
}