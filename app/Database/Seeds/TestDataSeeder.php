<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        echo "=== CREANDO DATOS DE PRUEBA ===\n\n";

        // Limpiar tablas primero
        echo "Limpiando datos existentes...\n";
        $this->limpiarTablas();

        // Ejecutar seeder de departamentos
        echo "Creando departamentos...\n";
        $this->call('DepartamentoSeeder');

        // Ejecutar seeder de personas
        echo "Creando personas...\n";
        $this->call('PersonaSeeder');

        // Crear usuarios directores para cada departamento
        echo "Creando usuarios directores...\n";
        $this->crearDirectores();

        // Crear algunos usuarios evaluadores
        echo "Creando usuarios evaluadores...\n";
        $this->crearEvaluadores();

        echo "\n=== DATOS DE PRUEBA CREADOS EXITOSAMENTE ===\n";
        echo "\nCredenciales de acceso:\n";
        echo "- Admin: admin@aurys.com / admin123\n";
        echo "- Director RRHH: director@aurys.com / director123\n";
        echo "- Director TI: director_ti@aurys.com / director123\n";
        echo "- Director Finanzas: director_fin@aurys.com / director123\n";
        echo "- Director Marketing: director_mkt@aurys.com / director123\n";
        echo "- Director Operaciones: director_ops@aurys.com / director123\n";
        echo "- Evaluador: evaluador@aurys.com / evaluador123\n";
        echo "- Consulta: consulta@aurys.com / consulta123\n";
    }

    private function limpiarTablas()
    {
        // Desactivar foreign key checks temporalmente
        $this->db->query('SET FOREIGN_KEY_CHECKS = 0');

        // Limpiar tablas en orden correcto (dependencias)
        $tablas = ['evaluaciones', 'personas', 'usuarios', 'departamentos'];
        foreach ($tablas as $tabla) {
            $this->db->table($tabla)->truncate();
        }

        // Reactivar foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS = 1');

        echo "Tablas limpiadas.\n";
    }

    private function crearDirectores()
    {
        // Obtener IDs de departamentos
        $departamentos = $this->db->table('departamentos')
            ->select('id, codigo, nombre')
            ->where('estado', 'ACTIVO')
            ->get()
            ->getResultArray();

        $directores = [
            [
                'username' => 'director',
                'email' => 'director@aurys.com',
                'password' => password_hash('director123', PASSWORD_DEFAULT),
                'nombre_completo' => 'Director de RRHH',
                'rol' => 'DIRECTOR',
                'codigo_dept' => 'RRHH',
                'estado' => 'ACTIVO',
            ],
            [
                'username' => 'director_ti',
                'email' => 'director_ti@aurys.com',
                'password' => password_hash('director123', PASSWORD_DEFAULT),
                'nombre_completo' => 'Director de TI',
                'rol' => 'DIRECTOR',
                'codigo_dept' => 'TI',
                'estado' => 'ACTIVO',
            ],
            [
                'username' => 'director_fin',
                'email' => 'director_fin@aurys.com',
                'password' => password_hash('director123', PASSWORD_DEFAULT),
                'nombre_completo' => 'Director de Finanzas',
                'rol' => 'DIRECTOR',
                'codigo_dept' => 'FIN',
                'estado' => 'ACTIVO',
            ],
            [
                'username' => 'director_mkt',
                'email' => 'director_mkt@aurys.com',
                'password' => password_hash('director123', PASSWORD_DEFAULT),
                'nombre_completo' => 'Director de Marketing',
                'rol' => 'DIRECTOR',
                'codigo_dept' => 'MKT',
                'estado' => 'ACTIVO',
            ],
            [
                'username' => 'director_ops',
                'email' => 'director_ops@aurys.com',
                'password' => password_hash('director123', PASSWORD_DEFAULT),
                'nombre_completo' => 'Director de Operaciones',
                'rol' => 'DIRECTOR',
                'codigo_dept' => 'OPS',
                'estado' => 'ACTIVO',
            ],
        ];

        foreach ($directores as $director) {
            $deptId = null;
            foreach ($departamentos as $dept) {
                if ($dept['codigo'] === $director['codigo_dept']) {
                    $deptId = $dept['id'];
                    break;
                }
            }

            $directorData = [
                'username' => $director['username'],
                'email' => $director['email'],
                'password' => $director['password'],
                'nombre_completo' => $director['nombre_completo'],
                'rol' => $director['rol'],
                'departamento_id' => $deptId,
                'estado' => $director['estado'],
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->table('usuarios')->insert($directorData);
            echo "Usuario {$director['username']} creado para departamento {$director['codigo_dept']}\n";
        }
    }

    private function crearEvaluadores()
    {
        $evaluadores = [
            [
                'username' => 'evaluador',
                'email' => 'evaluador@aurys.com',
                'password' => password_hash('evaluador123', PASSWORD_DEFAULT),
                'nombre_completo' => 'Evaluador General',
                'rol' => 'EVALUADOR',
                'departamento_id' => null,
                'estado' => 'ACTIVO',
            ],
            [
                'username' => 'consulta',
                'email' => 'consulta@aurys.com',
                'password' => password_hash('consulta123', PASSWORD_DEFAULT),
                'nombre_completo' => 'Usuario de Consulta',
                'rol' => 'CONSULTA',
                'departamento_id' => null,
                'estado' => 'ACTIVO',
            ],
        ];

        foreach ($evaluadores as $evaluador) {
            $evaluadorData = [
                'username' => $evaluador['username'],
                'email' => $evaluador['email'],
                'password' => $evaluador['password'],
                'nombre_completo' => $evaluador['nombre_completo'],
                'rol' => $evaluador['rol'],
                'departamento_id' => $evaluador['departamento_id'],
                'estado' => $evaluador['estado'],
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->table('usuarios')->insert($evaluadorData);
            echo "Usuario {$evaluador['username']} creado\n";
        }
    }
}