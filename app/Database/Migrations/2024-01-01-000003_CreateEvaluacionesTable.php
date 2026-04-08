<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEvaluacionesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'persona_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tipo_evaluacion' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'fecha_evaluacion' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'resultado' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'observaciones' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'calificacion' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
            ],
            'archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'evaluador_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('persona_id');
        $this->forge->addKey('evaluador_id');
        
        $this->forge->addForeignKey('persona_id', 'personas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('evaluaciones');
    }

    public function down()
    {
        $this->forge->dropTable('evaluaciones');
    }
}
