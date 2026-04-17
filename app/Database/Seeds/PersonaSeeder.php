<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PersonaSeeder extends Seeder
{
    public function run()
    {
        // Obtener IDs de departamentos
        $departamentos = $this->db->table('departamentos')
            ->select('id, codigo')
            ->where('estado', 'ACTIVO')
            ->get()
            ->getResultArray();

        $deptMap = [];
        foreach ($departamentos as $dept) {
            $deptMap[$dept['codigo']] = $dept['id'];
        }

        $personas = [
            // Personas en RRHH
            [
                'cedula' => '12345678',
                'numero' => '001',
                'nacionalidad' => 'VENEZUELA',
                'primer_nombre' => 'María',
                'segundo_nombre' => 'José',
                'primer_apellido' => 'González',
                'segundo_apellido' => 'Pérez',
                'sexo' => 'F',
                'fecha_nacimiento' => '1990-05-15',
                'edad' => 36,
                'telefono1' => '04141234567',
                'correo_electronico' => 'maria.gonzalez@empresa.com',
                'estado_civil' => 'CASADO',
                'tiene_hijos' => 'S',
                'cantidad_hijos' => 2,
                'carga_familiar' => 3,
                'departamento_id' => $deptMap['RRHH'] ?? null,
                'estado_registro' => 'ACTIVO',
                'fecha_registro' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'cedula' => '87654321',
                'numero' => '002',
                'nacionalidad' => 'VENEZUELA',
                'primer_nombre' => 'Carlos',
                'segundo_nombre' => 'Alberto',
                'primer_apellido' => 'Rodríguez',
                'segundo_apellido' => 'Martínez',
                'sexo' => 'M',
                'fecha_nacimiento' => '1985-08-20',
                'edad' => 40,
                'telefono1' => '04149876543',
                'correo_electronico' => 'carlos.rodriguez@empresa.com',
                'estado_civil' => 'SOLTERO',
                'tiene_hijos' => 'N',
                'cantidad_hijos' => 0,
                'carga_familiar' => 1,
                'departamento_id' => $deptMap['RRHH'] ?? null,
                'estado_registro' => 'ACTIVO',
                'fecha_registro' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Personas en TI
            [
                'cedula' => '11223344',
                'numero' => '003',
                'nacionalidad' => 'VENEZUELA',
                'primer_nombre' => 'Ana',
                'segundo_nombre' => 'María',
                'primer_apellido' => 'López',
                'segundo_apellido' => 'García',
                'sexo' => 'F',
                'fecha_nacimiento' => '1992-03-10',
                'edad' => 34,
                'telefono1' => '04145556677',
                'correo_electronico' => 'ana.lopez@empresa.com',
                'estado_civil' => 'SOLTERO',
                'tiene_hijos' => 'N',
                'cantidad_hijos' => 0,
                'carga_familiar' => 1,
                'departamento_id' => $deptMap['TI'] ?? null,
                'estado_registro' => 'ACTIVO',
                'fecha_registro' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'cedula' => '44332211',
                'numero' => '004',
                'nacionalidad' => 'VENEZUELA',
                'primer_nombre' => 'Pedro',
                'segundo_nombre' => 'José',
                'primer_apellido' => 'Hernández',
                'segundo_apellido' => 'Sánchez',
                'sexo' => 'M',
                'fecha_nacimiento' => '1988-11-25',
                'edad' => 37,
                'telefono1' => '04148887766',
                'correo_electronico' => 'pedro.hernandez@empresa.com',
                'estado_civil' => 'CASADO',
                'tiene_hijos' => 'S',
                'cantidad_hijos' => 1,
                'carga_familiar' => 2,
                'departamento_id' => $deptMap['TI'] ?? null,
                'estado_registro' => 'ACTIVO',
                'fecha_registro' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Personas en Finanzas
            [
                'cedula' => '55667788',
                'numero' => '005',
                'nacionalidad' => 'VENEZUELA',
                'primer_nombre' => 'Laura',
                'segundo_nombre' => 'Beatriz',
                'primer_apellido' => 'Morales',
                'segundo_apellido' => 'Torres',
                'sexo' => 'F',
                'fecha_nacimiento' => '1987-07-12',
                'edad' => 38,
                'telefono1' => '04146677889',
                'correo_electronico' => 'laura.morales@empresa.com',
                'estado_civil' => 'DIVORCIADO',
                'tiene_hijos' => 'S',
                'cantidad_hijos' => 1,
                'carga_familiar' => 2,
                'departamento_id' => $deptMap['FIN'] ?? null,
                'estado_registro' => 'ACTIVO',
                'fecha_registro' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Personas en Marketing
            [
                'cedula' => '99887766',
                'numero' => '006',
                'nacionalidad' => 'VENEZUELA',
                'primer_nombre' => 'Roberto',
                'segundo_nombre' => 'Antonio',
                'primer_apellido' => 'Díaz',
                'segundo_apellido' => 'Vargas',
                'sexo' => 'M',
                'fecha_nacimiento' => '1991-01-30',
                'edad' => 35,
                'telefono1' => '04149988777',
                'correo_electronico' => 'roberto.diaz@empresa.com',
                'estado_civil' => 'SOLTERO',
                'tiene_hijos' => 'N',
                'cantidad_hijos' => 0,
                'carga_familiar' => 1,
                'departamento_id' => $deptMap['MKT'] ?? null,
                'estado_registro' => 'ACTIVO',
                'fecha_registro' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Personas en Operaciones
            [
                'cedula' => '22334455',
                'numero' => '007',
                'nacionalidad' => 'VENEZUELA',
                'primer_nombre' => 'Carmen',
                'segundo_nombre' => 'Elena',
                'primer_apellido' => 'Ruiz',
                'segundo_apellido' => 'Fernández',
                'sexo' => 'F',
                'fecha_nacimiento' => '1989-09-05',
                'edad' => 36,
                'telefono1' => '04142233445',
                'correo_electronico' => 'carmen.ruiz@empresa.com',
                'estado_civil' => 'CASADO',
                'tiene_hijos' => 'S',
                'cantidad_hijos' => 3,
                'carga_familiar' => 4,
                'departamento_id' => $deptMap['OPS'] ?? null,
                'estado_registro' => 'ACTIVO',
                'fecha_registro' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Persona sin departamento asignado
            [
                'cedula' => '33445566',
                'numero' => '008',
                'nacionalidad' => 'VENEZUELA',
                'primer_nombre' => 'Luis',
                'segundo_nombre' => 'Miguel',
                'primer_apellido' => 'Jiménez',
                'segundo_apellido' => 'Ramírez',
                'sexo' => 'M',
                'fecha_nacimiento' => '1993-12-08',
                'edad' => 32,
                'telefono1' => '04143344556',
                'correo_electronico' => 'luis.jimenez@empresa.com',
                'estado_civil' => 'SOLTERO',
                'tiene_hijos' => 'N',
                'cantidad_hijos' => 0,
                'carga_familiar' => 1,
                'departamento_id' => null,
                'estado_registro' => 'ACTIVO',
                'fecha_registro' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($personas as $persona) {
            $this->db->table('personas')->insert($persona);
        }

        echo "Personas de prueba creadas exitosamente.\n";
    }
}