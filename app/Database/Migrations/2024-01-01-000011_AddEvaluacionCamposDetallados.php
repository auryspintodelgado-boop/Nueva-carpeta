<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEvaluacionCamposDetallados extends Migration
{
    public function up()
    {
        // Obtener las columnas existentes de la tabla
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('evaluaciones');
        
        // ==================== Orientación de Resultados (3 sub-campos) ====================
        
        // Termina su trabajo oportunamente
        if (!in_array('ori_termino_oportuno', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'ori_termino_oportuno' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Cumple con las tareas que se le encomienda
        if (!in_array('ori_cumple_tareas', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'ori_cumple_tareas' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Realiza un volumen adecuado de trabajo
        if (!in_array('ori_volumen_adecuado', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'ori_volumen_adecuado' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // ==================== Calidad y Organización (8 sub-campos) ====================
        
        // No comete errores en el trabajo
        if (!in_array('cal_no_errores', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'cal_no_errores' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Hace uso racional de los recursos
        if (!in_array('cal_recursos_racionales', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'cal_recursos_racionales' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // No Requiere de supervisión frecuente
        if (!in_array('cal_supervision', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'cal_supervision' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Se muestra profesional en el trabajo
        if (!in_array('cal_profesional', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'cal_profesional' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Se muestra respetuoso y amable en el trato
        if (!in_array('cal_respetuoso', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'cal_respetuoso' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Planifica sus actividades
        if (!in_array('cal_planifica', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'cal_planifica' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Hace uso de indicadores
        if (!in_array('cal_indicadores', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'cal_indicadores' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Se preocupa por alcanzar las metas
        if (!in_array('cal_metas', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'cal_metas' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // ==================== Relaciones Interpersonales (5 sub-campos) ====================
        
        // Se muestra cortés con el personal y con sus compañeros
        if (!in_array('rel_cortes', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'rel_cortes' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Brinda una adecuada orientación a sus compañeros
        if (!in_array('rel_orientacion', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'rel_orientacion' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Evita los conflictos dentro del trabajo
        if (!in_array('rel_conflictos', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'rel_conflictos' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Muestra aptitud para integrarse al equipo
        if (!in_array('rel_integracion', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'rel_integracion' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Se identifica fácilmente con los objetivos del equipo
        if (!in_array('rel_objetivos', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'rel_objetivos' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // ==================== Iniciativa (4 sub-campos) ====================
        
        // Muestra nuevas ideas para mejorar los procesos
        if (!in_array('ini_ideas', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'ini_ideas' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Se muestra asequible al cambio
        if (!in_array('ini_cambio', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'ini_cambio' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Se anticipa a las dificultades
        if (!in_array('ini_anticipacion', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'ini_anticipacion' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // Tiene gran capacidad para resolver problemas
        if (!in_array('ini_resolucion', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'ini_resolucion' => [
                    'type'       => 'INT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => true,
                ],
            ]);
        }
        
        // ==================== Campos adicionales ====================
        
        // Fecha de ingreso del empleado
        if (!in_array('fecha_ingreso', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'fecha_ingreso' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
            ]);
        }
        
        // Firma del evaluador
        if (!in_array('firma_evaluador', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'firma_evaluador' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
            ]);
        }
        
        // Nombre del evaluador
        if (!in_array('nombre_evaluador', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'nombre_evaluador' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                ],
            ]);
        }
        
        // Fecha de ratification
        if (!in_array('fecha_ratificacion', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'fecha_ratificacion' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
            ]);
        }
        
        // Comentarios generales adicionales
        if (!in_array('comentarios_adicionales', $fields)) {
            $this->forge->addColumn('evaluaciones', [
                'comentarios_adicionales' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
            ]);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('evaluaciones');
        
        $columnsToRemove = [
            // Orientación de Resultados
            'ori_termino_oportuno',
            'ori_cumple_tareas',
            'ori_volumen_adecuado',
            // Calidad y Organización
            'cal_no_errores',
            'cal_recursos_racionales',
            'cal_supervision',
            'cal_profesional',
            'cal_respetuoso',
            'cal_planifica',
            'cal_indicadores',
            'cal_metas',
            // Relaciones Interpersonales
            'rel_cortes',
            'rel_orientacion',
            'rel_conflictos',
            'rel_integracion',
            'rel_objetivos',
            // Iniciativa
            'ini_ideas',
            'ini_cambio',
            'ini_anticipacion',
            'ini_resolucion',
            // Adicionales
            'fecha_ingreso',
            'firma_evaluador',
            'nombre_evaluador',
            'fecha_ratificacion',
            'comentarios_adicionales',
        ];
        
        foreach ($columnsToRemove as $column) {
            if (in_array($column, $fields)) {
                $this->forge->dropColumn('evaluaciones', $column);
            }
        }
    }
}
