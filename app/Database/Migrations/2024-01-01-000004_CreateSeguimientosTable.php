<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSeguimientosTable extends Migration
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
            'tipo_seguimiento' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'fecha_seguimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'descripcion' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'acciones' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'resultado' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'proxima_fecha' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'prioridad' => [
                'type'       => 'ENUM',
                'constraint' => ['BAJA', 'MEDIA', 'ALTA', 'URGENTE'],
                'default'    => 'MEDIA',
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['PENDIENTE', 'EN_PROCESO', 'COMPLETADO', 'CANCELADO'],
                'default'    => 'PENDIENTE',
            ],
            'usuario_seguimiento' => [
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
        $this->forge->addKey('usuario_seguimiento');
        
        $this->forge->addForeignKey('persona_id', 'personas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('seguimientos');
    }

    public function down()
    {
        $this->forge->dropTable('seguimientos');
    }
}
