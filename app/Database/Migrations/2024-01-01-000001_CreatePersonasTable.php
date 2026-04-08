<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePersonasTable extends Migration
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
            'numero' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'nacionalidad' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'cedula' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'primer_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'segundo_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'primer_apellido' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'segundo_apellido' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'sexo' => [
                'type'       => 'ENUM',
                'constraint' => ['M', 'F'],
                'null'       => true,
            ],
            'fecha_nacimiento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'edad' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => true,
            ],
            'telefono1' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'telefono2' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'correo_electronico' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'carrera' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'ano_semestre' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'posee_beca' => [
                'type'       => 'ENUM',
                'constraint' => ['S', 'N'],
                'null'       => true,
            ],
            'sede' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'siglas_universidad' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'tipo_ieu' => [
                'type'       => 'ENUM',
                'constraint' => ['PUBLICA', 'PRIVADA'],
                'null'       => true,
            ],
            'nivel_academico' => [
                'type'       => 'ENUM',
                'constraint' => ['PREGRADO', 'POSTGRADO'],
                'null'       => true,
            ],
            'urbanismo' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'municipio' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'parroquia' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'tiene_hijos' => [
                'type'       => 'ENUM',
                'constraint' => ['S', 'N'],
                'null'       => true,
            ],
            'cantidad_hijos' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => true,
            ],
            'posee_discapacidad' => [
                'type'       => 'ENUM',
                'constraint' => ['S', 'N'],
                'null'       => true,
            ],
            'descripcion_discapacidad' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'presenta_enfermedad' => [
                'type'       => 'ENUM',
                'constraint' => ['S', 'N'],
                'null'       => true,
            ],
            'condicion_medica' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'medicamentos' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'trabaja' => [
                'type'       => 'ENUM',
                'constraint' => ['S', 'N'],
                'null'       => true,
            ],
            'tipo_empleo' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'medio_transporte' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'inscrito_cne' => [
                'type'       => 'ENUM',
                'constraint' => ['S', 'N'],
                'null'       => true,
            ],
            'centro_electoral' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'comuna' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'estado_civil' => [
                'type'       => 'ENUM',
                'constraint' => ['CASADO', 'SOLTERO', 'DIVORCIADO', 'VIUDO', 'UNION_LIBRE'],
                'null'       => true,
            ],
            'talla_camisa' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'talla_zapatos' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'talla_pantalon' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'altura' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'peso' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
                'null'       => true,
            ],
            'tipo_sangre' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'null'       => true,
            ],
            'carga_familiar' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => true,
            ],
            'fotos' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'observaciones' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'fecha_registro' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'estado_registro' => [
                'type'       => 'ENUM',
                'constraint' => ['ACTIVO', 'INACTIVO'],
                'default'    => 'ACTIVO',
            ],
            'usuario_registro' => [
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
        $this->forge->addKey('cedula');
        $this->forge->addKey('correo_electronico');
        
        $this->forge->createTable('personas');
    }

    public function down()
    {
        $this->forge->dropTable('personas');
    }
}
