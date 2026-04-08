<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepartamentosTable extends Migration
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
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'descripcion' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'estado' => [
                'type'       => 'ENUM',
                'constraint' => ['ACTIVO', 'INACTIVO'],
                'default'    => 'ACTIVO',
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
        $this->forge->createTable('departamentos');

        // Insertar departamentos por defecto
        $this->db->table('departamentos')->insertBatch([
            ['nombre' => 'Recursos Humanos', 'descripcion' => 'Departamento de Recursos Humanos', 'codigo' => 'RRHH'],
            ['nombre' => 'Académico', 'descripcion' => 'Departamento Académico', 'codigo' => 'ACAD'],
            ['nombre' => 'Becas', 'descripcion' => 'Departamento de Becas', 'codigo' => 'BECAS'],
            ['nombre' => 'Bienestar Estudiantil', 'descripcion' => 'Departamento de Bienestar Estudiantil', 'codigo' => 'BIEN'],
            ['nombre' => 'Secretaría', 'descripcion' => 'Secretaría General', 'codigo' => 'SEC'],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('departamentos');
    }
}
