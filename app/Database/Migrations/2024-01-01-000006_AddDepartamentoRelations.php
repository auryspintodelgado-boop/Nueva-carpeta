<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDepartamentoRelations extends Migration
{
    public function up()
    {
        // Agregar departamento_id a personas
        $this->forge->addColumn('personas', [
            'departamento_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'observaciones',
            ],
        ]);
        
        $this->forge->addForeignKey('departamento_id', 'departamentos', 'id', 'CASCADE', 'SET NULL');

        // Agregar departamento_id a usuarios
        $this->forge->addColumn('usuarios', [
            'departamento_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'estado',
            ],
        ]);
        
        $this->forge->addForeignKey('departamento_id', 'departamentos', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        $this->forge->dropForeignKey('personas', 'personas_departamento_id_foreign');
        $this->forge->dropColumn('personas', 'departamento_id');
        
        $this->forge->dropForeignKey('usuarios', 'usuarios_departamento_id_foreign');
        $this->forge->dropColumn('usuarios', 'departamento_id');
    }
}
