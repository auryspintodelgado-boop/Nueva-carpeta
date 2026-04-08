<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoToPersonas extends Migration
{
    public function up()
    {
        // Agregar campo foto sin especificar 'after' para evitar errores
        $this->forge->addColumn('personas', [
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('personas', 'foto');
    }
}
