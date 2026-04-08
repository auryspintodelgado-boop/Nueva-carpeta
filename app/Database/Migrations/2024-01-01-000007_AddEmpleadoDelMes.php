<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmpleadoDelMes extends Migration
{
    public function up()
    {
        // Agregar campos para evaluación mensual y empleado del mes
        $this->forge->addColumn('evaluaciones', [
            'es_empleado_mes' => [
                'type'       => 'ENUM',
                'constraint' => ['S', 'N'],
                'default'    => 'N',
                'after'      => 'calificacion',
            ],
            'mes_evaluado' => [
                'type'       => 'VARCHAR',
                'constraint' => 7, // formato YYYY-MM
                'null'       => true,
                'after'      => 'es_empleado_mes',
            ],
            'puntuacion' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'mes_evaluado',
            ],
            'asistencia' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'puntuacion',
            ],
            'puntualidad' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'asistencia',
            ],
            'trabajo_equipo' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'puntualidad',
            ],
            'iniciativa' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'trabajo_equipo',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('evaluaciones', 'es_empleado_mes');
        $this->forge->dropColumn('evaluaciones', 'mes_evaluado');
        $this->forge->dropColumn('evaluaciones', 'puntuacion');
        $this->forge->dropColumn('evaluaciones', 'asistencia');
        $this->forge->dropColumn('evaluaciones', 'puntualidad');
        $this->forge->dropColumn('evaluaciones', 'trabajo_equipo');
        $this->forge->dropColumn('evaluaciones', 'iniciativa');
    }
}
