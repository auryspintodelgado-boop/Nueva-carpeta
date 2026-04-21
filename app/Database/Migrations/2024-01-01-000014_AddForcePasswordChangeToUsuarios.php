<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForcePasswordChangeToUsuarios extends Migration
{
    public function up()
    {
        $fields = [
            'force_password_change' => [
                'type'       => 'ENUM',
                'constraint' => ['S', 'N'],
                'default'    => 'N',
                'after'      => 'password_changed_at',
            ],
        ];
        
        $this->forge->addColumn('usuarios', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', 'force_password_change');
    }
}