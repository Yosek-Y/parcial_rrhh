<?php

declare(strict_types=1);

namespace App\Utilities;

class Sanitizador
{
    public static function limpiarTexto(string $valor): string
    {
        $valor = trim($valor);
        $valor = strip_tags($valor);

        return $valor;
    }

    public static function formatoTitulo(string $valor): string
    {
        $valor = self::limpiarTexto($valor);
        $valor = mb_strtolower($valor, 'UTF-8');

        return mb_convert_case($valor, MB_CASE_TITLE, 'UTF-8');
    }

    public static function limpiarCorreo(string $correo): string
    {
        $correo = trim($correo);

        return filter_var($correo, FILTER_SANITIZE_EMAIL) ?: '';
    }

    public static function limpiarEntero(string $valor): int
    {
        $valor = trim($valor);
        $valor = filter_var($valor, FILTER_SANITIZE_NUMBER_INT);

        return (int) $valor;
    }

    public static function limpiarDecimal(string $valor): float
    {
        $valor = trim($valor);
        $valor = str_replace(',', '.', $valor);
        $valor = preg_replace('/[^0-9.]/', '', $valor);

        return (float) $valor;
    }

    public static function limpiarFecha(?string $valor): string
    {
        if ($valor === null) {
            return '';
        }

        return self::limpiarTexto($valor);
    }

    public static function limpiarTextoOpcional(?string $valor): string
    {
        if ($valor === null) {
            return '';
        }

        return self::limpiarTexto($valor);
    }

    public static function limpiarMotivo(?string $valor): string
    {
        if ($valor === null) {
            return '';
        }

        return self::limpiarTexto($valor);
    }
}