<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartamentoSeeder extends Seeder
{
    public function run()
    {
        $departamentos = [
            [
                'nombre' => 'Recursos Humanos',
                'codigo' => 'RRHH',
                'descripcion' => 'Departamento encargado de la gestión del talento humano',
                'estado' => 'ACTIVO',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nombre' => 'Tecnología de la Información',
                'codigo' => 'TI',
                'descripcion' => 'Departamento de sistemas y tecnología',
                'estado' => 'ACTIVO',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nombre' => 'Finanzas',
                'codigo' => 'FIN',
                'descripcion' => 'Departamento de contabilidad y finanzas',
                'estado' => 'ACTIVO',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nombre' => 'Marketing',
                'codigo' => 'MKT',
                'descripcion' => 'Departamento de marketing y ventas',
                'estado' => 'ACTIVO',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nombre' => 'Operaciones',
                'codigo' => 'OPS',
                'descripcion' => 'Departamento de operaciones y logística',
                'estado' => 'ACTIVO',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($departamentos as $departamento) {
            $this->db->table('departamentos')->insert($departamento);
        }

        echo "Departamentos de prueba creados exitosamente.\n";
    }
}