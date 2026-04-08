<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DirectorUsuarioSeeder extends Seeder
{
    public function run()
    {
        // Primero crear un departamento si no existe
        $departamento = [
            'nombre' => 'Recursos Humanos',
            'descripcion' => 'Departamento de Recursos Humanos',
            'codigo' => 'RRHH',
            'estado' => 'ACTIVO',
        ];
        
        // Insertar departamento
        $this->db->table('departamentos')->insert($departamento);
        $departamentoId = $this->db->insertID();
        
        // Crear usuario director
        $data = [
            'username'       => 'director',
            'email'         => 'director@aurys.com',
            'password'      => password_hash('director123', PASSWORD_DEFAULT),
            'nombre_completo' => 'Director de RRHH',
            'rol'           => 'DIRECTOR',
            'departamento_id' => $departamentoId,
            'estado'        => 'ACTIVO',
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $this->db->table('usuarios')->insert($data);
        
        echo "Usuario director creado: username='director', password='director123', departamento_id=$departamentoId\n";
    }
}
