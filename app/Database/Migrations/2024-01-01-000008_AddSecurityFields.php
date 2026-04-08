<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSecurityFields extends Migration
{
    public function up()
    {
        // Agregar campos de seguridad a usuarios
        $this->forge->addColumn('usuarios', [
            'two_factor_enabled' => [
                'type'       => 'ENUM',
                'constraint' => ['S', 'N'],
                'default'    => 'N',
                'after'      => 'departamento_id',
            ],
            'two_factor_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'null'       => true,
                'after'      => 'two_factor_enabled',
            ],
            'two_factor_expires' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'two_factor_code',
            ],
            'reset_token' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'after'      => 'two_factor_expires',
            ],
            'reset_expires' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'reset_token',
            ],
            'password_changed_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'reset_expires',
            ],
            'failed_login_attempts' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'password_changed_at',
            ],
            'locked_until' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'failed_login_attempts',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('usuarios', 'two_factor_enabled');
        $this->forge->dropColumn('usuarios', 'two_factor_code');
        $this->forge->dropColumn('usuarios', 'two_factor_expires');
        $this->forge->dropColumn('usuarios', 'reset_token');
        $this->forge->dropColumn('usuarios', 'reset_expires');
        $this->forge->dropColumn('usuarios', 'password_changed_at');
        $this->forge->dropColumn('usuarios', 'failed_login_attempts');
        $this->forge->dropColumn('usuarios', 'locked_until');
    }
}
