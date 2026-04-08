<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEvaluacionEsquemaCampos extends Migration
{
    public function up()
    {
        // Obtener las columnas existentes de la tabla
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('evaluaciones');
        
        // Agregar departamento_id si no existe
        if (!in_array('departamento_id', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'departamento_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
            ]);
            $this->forge->addForeignKey('departamento_id', 'departamentos', 'id', 'CASCADE', 'SET NULL');
            $this->forge->addKey('departamento_id');
        }
        
        // Agregar estado_evaluacion si no existe
        if (!in_array('estado_evaluacion', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'estado_evaluacion' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'default'    => 'PENDIENTE',
                    'null'       => true,
                ],
            ]);
        }
        
        // Agregar orientacion_resultados si no existe
        if (!in_array('orientacion_resultados', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'orientacion_resultados' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '3,2',
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Agregar calidad_organizacion si no existe
        if (!in_array('calidad_organizacion', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'calidad_organizacion' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '3,2',
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Agregar relaciones_interpersonales si no existe
        if (!in_array('relaciones_interpersonales', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'relaciones_interpersonales' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '3,2',
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Agregar observaciones de secciones si no existen
        if (!in_array('obs_orientacion', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'obs_orientacion' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }
        
        if (!in_array('obs_calidad', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'obs_calidad' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }
        
        if (!in_array('obs_relaciones', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'obs_relaciones' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }
        
        if (!in_array('obs_iniciativa', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'obs_iniciativa' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        // Obtener las columnas existentes de la tabla
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('evaluaciones');
        
        // Eliminar columnas que podrían haber sido agregadas por esta migración
        $columnsToRemove = [
            'departamento_id',
            'estado_evaluacion',
            'orientacion_resultados',
            'calidad_organizacion',
            'relaciones_interpersonales',
            'obs_orientacion',
            'obs_calidad',
            'obs_relaciones',
            'obs_iniciativa',
        ];
        
        foreach ($columnsToRemove as $column) {
            if (in_array($column, $fields)) {
                // Solo eliminar si no fueron agregadas por otras migraciones
                // Para simplificar, solo eliminamos las que Agregamos explícitamente
                if (in_array($column, ['estado_evaluacion', 'orientacion_resultados', 'calidad_organizacion', 
                    'relaciones_interpersonales', 'obs_orientacion', 'obs_calidad', 'obs_relaciones', 'obs_iniciativa'])) {
                    $this->forge->dropColumn('evaluaciones', $column);
                }
            }
        }
        
        // Eliminar foreign key y índices de departamento_id
        $this->forge->dropForeignKey('evaluaciones', 'evaluaciones_departamento_id_foreign');
        $this->forge->dropColumn('evaluaciones', 'departamento_id');
    }
}
