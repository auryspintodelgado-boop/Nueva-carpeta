<?php

namespace App\Controllers;

use App\Models\PersonaModel;

class TestController extends BaseController
{
    public function testUpdate()
    {
        $personaModel = new PersonaModel();

        // Obtener una persona de prueba
        $persona = $personaModel->where('estado_registro', 'ACTIVO')->first();
        if (!$persona) {
            echo "No hay personas activas para probar.";
            return;
        }

        echo "Persona original - ID: {$persona['id']}, Departamento: " . ($persona['departamento_id'] ?? 'NULL') . "<br>";

        // Cambiar departamento_id
        $nuevoDepartamentoId = 1; // Asumiendo que existe el departamento con ID 1
        $updateData = ['departamento_id' => $nuevoDepartamentoId];

        echo "Intentando actualizar departamento_id a: {$nuevoDepartamentoId}<br>";

        $result = $personaModel->update($persona['id'], $updateData);

        echo "Resultado del update: " . ($result ? 'SUCCESS' : 'FAILED') . "<br>";

        // Verificar el resultado
        $personaActualizada = $personaModel->find($persona['id']);
        echo "Persona después del update - ID: {$personaActualizada['id']}, Departamento: " . ($personaActualizada['departamento_id'] ?? 'NULL') . "<br>";

        // Revertir el cambio para no afectar los datos de prueba
        $personaModel->update($persona['id'], ['departamento_id' => $persona['departamento_id']]);
        echo "Cambio revertido.";
    }
}