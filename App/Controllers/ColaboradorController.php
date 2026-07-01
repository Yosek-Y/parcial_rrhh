<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\CatalogoModel;
use App\Utilities\Sanitizador;
use App\Utilities\Validador;
use App\Models\ColaboradorModel;

class ColaboradorController
{
    public function mostrarFormulario(array $errores = [], array $datosAnteriores = []): void
    {
        $catalogos = $this->cargarCatalogos();

        $tiposSangre = $catalogos['tiposSangre'];
        $sexos = $catalogos['sexos'];
        $estadosCiviles = $catalogos['estadosCiviles'];
        $rutas = $catalogos['rutas'];
        $tiposEmpleado = $catalogos['tiposEmpleado'];
        $ocupaciones = $catalogos['ocupaciones'];
        $motivosTerminacion = $catalogos['motivosTerminacion'];

        require_once APP_PATH . '/Views/formulario_colaborador.php';
    }

    public function guardarColaborador(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?accion=formulario');
            exit;
        }

        $catalogos = $this->cargarCatalogos();

        $tiposSangre = $catalogos['tiposSangre'];
        $sexos = $catalogos['sexos'];
        $estadosCiviles = $catalogos['estadosCiviles'];
        $rutas = $catalogos['rutas'];
        $tiposEmpleado = $catalogos['tiposEmpleado'];
        $ocupaciones = $catalogos['ocupaciones'];
        $motivosTerminacion = $catalogos['motivosTerminacion'];

        $datos = [
            'identidad' => Sanitizador::limpiarTexto($_POST['identidad'] ?? ''),
            'nombre' => Sanitizador::formatoTitulo($_POST['nombre'] ?? ''),
            'apellido' => Sanitizador::formatoTitulo($_POST['apellido'] ?? ''),
            'edad' => Sanitizador::limpiarEntero($_POST['edad'] ?? ''),
            'tipo_sangre_id' => Sanitizador::limpiarEntero($_POST['tipo_sangre_id'] ?? ''),
            'sexo_id' => Sanitizador::limpiarEntero($_POST['sexo_id'] ?? ''),
            'estado_civil_id' => Sanitizador::limpiarEntero($_POST['estado_civil_id'] ?? ''),
            'nacionalidad' => Sanitizador::formatoTitulo($_POST['nacionalidad'] ?? ''),
            'ruta_id' => Sanitizador::limpiarEntero($_POST['ruta_id'] ?? ''),
            'correo' => Sanitizador::limpiarCorreo($_POST['correo'] ?? ''),
            'celular' => Sanitizador::limpiarTexto($_POST['celular'] ?? ''),

            'ocupacion_id' => Sanitizador::limpiarEntero($_POST['ocupacion_id'] ?? ''),
            'tipo_empleado_id' => Sanitizador::limpiarEntero($_POST['tipo_empleado_id'] ?? ''),
            'salario' => Sanitizador::limpiarDecimal($_POST['salario'] ?? ''),
            'fecha_inicio' => Sanitizador::limpiarFecha($_POST['fecha_inicio'] ?? ''),
            'fecha_fin' => Sanitizador::limpiarFecha($_POST['fecha_fin'] ?? ''),
            'cargo_activo' => Sanitizador::limpiarEntero($_POST['cargo_activo'] ?? '1'),
            'empleado_activo' => Sanitizador::limpiarEntero($_POST['empleado_activo'] ?? '1'),
            'motivo_terminacion_id' => Sanitizador::limpiarEntero($_POST['motivo_terminacion_id'] ?? '0'),
            'motivo_baja' => Sanitizador::limpiarMotivo($_POST['motivo_baja'] ?? ''),
        ];

        if ($datos['fecha_fin'] !== '') {
            $datos['empleado_activo'] = 0;
            $datos['cargo_activo'] = 0;
        } else {
            $datos['empleado_activo'] = 1;
            $datos['motivo_terminacion_id'] = 0;
        }

        $errores = [];

        if (!Validador::requerido($datos['identidad'])) {
            $errores['identidad'] = 'La identidad es obligatoria.';
        } elseif (!Validador::identidadValida($datos['identidad'])) {
            $errores['identidad'] = 'La identidad solo debe contener letras, números, espacios o guiones.';
        }

        if (!Validador::requerido($datos['nombre'])) {
            $errores['nombre'] = 'El nombre es obligatorio.';
        } elseif (!Validador::longitudEntre($datos['nombre'], 2, 100)) {
            $errores['nombre'] = 'El nombre debe tener entre 2 y 100 caracteres.';
        }

        if (!Validador::requerido($datos['apellido'])) {
            $errores['apellido'] = 'El apellido es obligatorio.';
        } elseif (!Validador::longitudEntre($datos['apellido'], 2, 100)) {
            $errores['apellido'] = 'El apellido debe tener entre 2 y 100 caracteres.';
        }

        if (!Validador::edadValida($datos['edad'])) {
            $errores['edad'] = 'La edad debe estar entre 18 y 100 años.';
        }

        if (!Validador::idEnCatalogo($datos['tipo_sangre_id'], $this->obtenerIds($tiposSangre))) {
            $errores['tipo_sangre_id'] = 'Debe seleccionar un tipo de sangre válido.';
        }

        if (!Validador::idEnCatalogo($datos['sexo_id'], $this->obtenerIds($sexos))) {
            $errores['sexo_id'] = 'Debe seleccionar un sexo válido.';
        }

        if (!Validador::idEnCatalogo($datos['estado_civil_id'], $this->obtenerIds($estadosCiviles))) {
            $errores['estado_civil_id'] = 'Debe seleccionar un estado civil válido.';
        }

        if (!Validador::requerido($datos['nacionalidad'])) {
            $errores['nacionalidad'] = 'La nacionalidad es obligatoria.';
        } elseif (!Validador::longitudEntre($datos['nacionalidad'], 2, 100)) {
            $errores['nacionalidad'] = 'La nacionalidad debe tener entre 2 y 100 caracteres.';
        }

        if (!Validador::idEnCatalogo($datos['ruta_id'], $this->obtenerIds($rutas))) {
            $errores['ruta_id'] = 'Debe seleccionar una ruta válida.';
        }

        if (!Validador::requerido($datos['correo'])) {
            $errores['correo'] = 'El correo es obligatorio.';
        } elseif (!Validador::correoValido($datos['correo'])) {
            $errores['correo'] = 'Debe ingresar un correo electrónico válido.';
        }

        if (!Validador::requerido($datos['celular'])) {
            $errores['celular'] = 'El celular es obligatorio.';
        } elseif (!Validador::celularValido($datos['celular'])) {
            $errores['celular'] = 'El celular solo debe contener números, espacios, paréntesis, + o guiones.';
        }

        if (!Validador::idEnCatalogo($datos['ocupacion_id'], $this->obtenerIds($ocupaciones))) {
            $errores['ocupacion_id'] = 'Debe seleccionar una ocupación válida.';
        }

        if (!Validador::idEnCatalogo($datos['tipo_empleado_id'], $this->obtenerIds($tiposEmpleado))) {
            $errores['tipo_empleado_id'] = 'Debe seleccionar un tipo de empleado o planilla válido.';
        }

        if (!Validador::salarioValido($datos['salario'])) {
            $errores['salario'] = 'El salario debe ser mayor que 0.';
        }

        if (!Validador::requerido($datos['fecha_inicio'])) {
            $errores['fecha_inicio'] = 'La fecha de inicio es obligatoria.';
        } elseif (!Validador::fechaValida($datos['fecha_inicio'])) {
            $errores['fecha_inicio'] = 'La fecha de inicio no tiene un formato válido.';
        }

        if ($datos['fecha_fin'] !== '') {
            if (!Validador::fechaValida($datos['fecha_fin'])) {
                $errores['fecha_fin'] = 'La fecha fin no tiene un formato válido.';
            } elseif (
                Validador::fechaValida($datos['fecha_inicio']) &&
                !Validador::fechaFinValida($datos['fecha_inicio'], $datos['fecha_fin'])
            ) {
                $errores['fecha_fin'] = 'La fecha fin no puede ser menor que la fecha de inicio.';
            }

            if (!Validador::idEnCatalogo($datos['motivo_terminacion_id'], $this->obtenerIds($motivosTerminacion))) {
                $errores['motivo_terminacion_id'] = 'Debe seleccionar un motivo de baja válido.';
            }
        }

        if (!Validador::opcionBinaria($datos['cargo_activo'])) {
            $errores['cargo_activo'] = 'Debe seleccionar si el cargo está activo.';
        }

        if (!Validador::opcionBinaria($datos['empleado_activo'])) {
            $errores['empleado_activo'] = 'Debe seleccionar si el empleado está activo.';
        }

        if (!empty($errores)) {
            $this->mostrarFormulario($errores, $datos);
            return;
        }

        $tipoSangre = $this->buscarNombrePorId($tiposSangre, $datos['tipo_sangre_id'], 'Nombre');
        $sexo = $this->buscarNombrePorId($sexos, $datos['sexo_id']);
        $ruta = $this->buscarNombrePorId($rutas, $datos['ruta_id']);
        $ocupacion = $this->buscarNombrePorId($ocupaciones, $datos['ocupacion_id']);
        $tipoEmpleado = $this->buscarNombrePorId($tiposEmpleado, $datos['tipo_empleado_id']);
        $motivoTerminacion = $datos['motivo_terminacion_id'] > 0
            ? $this->buscarNombrePorId($motivosTerminacion, $datos['motivo_terminacion_id'])
            : 'No aplica';

        $cadenaAuditoriaPrevia = implode('|', [
            'CODIGO_GENERADO_EN_BD',
            number_format($datos['salario'], 2, '.', ''),
            $tipoEmpleado,
            $tipoEmpleado,
            $ocupacion,
            $datos['fecha_inicio'],
        ]);

        $planilla = $tipoEmpleado;

        $colaboradorModel = new ColaboradorModel();

        $resultadoGuardado = $colaboradorModel->guardarColaboradorConPerfil(
            $datos,
            $tipoEmpleado,
            $planilla,
            $ocupacion
        );

        if (!$resultadoGuardado['exito']) {
            $errores['general'] = $resultadoGuardado['mensaje'];
            $this->mostrarFormulario($errores, $datos);
            return;
        }

        $codigoEmpleado = $resultadoGuardado['codigo_empleado'];
        $perfilId = $resultadoGuardado['perfil_id'];
        $cadenaAuditoriaFinal = $resultadoGuardado['cadena_auditoria'];
        $firmaDigital = $resultadoGuardado['firma_digital'];

        require_once APP_PATH . '/Views/registro_exitoso.php';
    }

    private function cargarCatalogos(): array
    {
        $catalogoModel = new CatalogoModel();

        return [
            'tiposSangre' => $catalogoModel->obtenerTiposSangre(),
            'sexos' => $catalogoModel->obtenerSexos(),
            'estadosCiviles' => $catalogoModel->obtenerEstadosCiviles(),
            'rutas' => $catalogoModel->obtenerRutas(),
            'tiposEmpleado' => $catalogoModel->obtenerTiposEmpleado(),
            'ocupaciones' => $catalogoModel->obtenerOcupaciones(),
            'motivosTerminacion' => $catalogoModel->obtenerMotivosTerminacion(),
        ];
    }

    private function obtenerIds(array $catalogo): array
    {
        return array_map('intval', array_column($catalogo, 'id'));
    }

    private function buscarNombrePorId(array $catalogo, int $id, string $campoNombre = 'nombre'): string
    {
        foreach ($catalogo as $item) {
            if ((int) $item['id'] === $id) {
                return (string) $item[$campoNombre];
            }
        }

        return '';
    }
}
