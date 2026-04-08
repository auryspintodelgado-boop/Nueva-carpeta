<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'       => 'admin',
            'email'         => 'admin@aurys.com',
            'password'      => password_hash('admin123', PASSWORD_DEFAULT),
            'nombre_completo' => 'Administrador',
            'rol'           => 'ADMIN',
            'estado'        => 'ACTIVO',
            'created_at'    => date('Y-m-d H:i:s'),
        ];

        $this->db->table('usuarios')->insert($data);
    }
}
