<?php

declare(strict_types=1);

namespace App\Utilities;

class Validador
{
    public static function requerido(string $valor): bool
    {
        return trim($valor) !== '';
    }

    public static function longitudEntre(string $valor, int $minimo, int $maximo): bool
    {
        $longitud = mb_strlen(trim($valor), 'UTF-8');

        return $longitud >= $minimo && $longitud <= $maximo;
    }

    public static function identidadValida(string $identidad): bool
    {
        return preg_match('/^[A-Za-z0-9\- ]{4,30}$/', $identidad) === 1;
    }

    public static function edadValida(int $edad): bool
    {
        return $edad >= 18 && $edad <= 100;
    }

    public static function correoValido(string $correo): bool
    {
        return filter_var($correo, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function celularValido(string $celular): bool
    {
        return preg_match('/^[0-9+\-\s()]{7,20}$/', $celular) === 1;
    }

    public static function salarioValido(float $salario): bool
    {
        return $salario > 0 && $salario <= 999999.99;
    }

    public static function idEnCatalogo(int $id, array $idsPermitidos): bool
    {
        return in_array($id, $idsPermitidos, true);
    }

    public static function opcionBinaria(int $valor): bool
    {
        return in_array($valor, [0, 1], true);
    }

    public static function fechaValida(string $fecha): bool
    {
        $fechaObjeto = \DateTime::createFromFormat('Y-m-d', $fecha);

        return $fechaObjeto !== false && $fechaObjeto->format('Y-m-d') === $fecha;
    }

    public static function fechaFinValida(string $fechaInicio, string $fechaFin): bool
    {
        if ($fechaFin === '') {
            return true;
        }

        return strtotime($fechaFin) >= strtotime($fechaInicio);
    }
}
