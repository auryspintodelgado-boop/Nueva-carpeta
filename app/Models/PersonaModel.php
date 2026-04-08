<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonaModel extends Model
{
    protected $table            = 'personas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'numero',
        'nacionalidad',
        'cedula',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'sexo',
        'fecha_nacimiento',
        'edad',
        'telefono1',
        'telefono2',
        'correo_electronico',
        'carrera',
        'ano_semestre',
        'posee_beca',
        'sede',
        'estado',
        'siglas_universidad',
        'tipo_ieu',
        'nivel_academico',
        'urbanismo',
        'municipio',
        'parroquia',
        'tiene_hijos',
        'cantidad_hijos',
        'posee_discapacidad',
        'descripcion_discapacidad',
        'presenta_enfermedad',
        'condicion_medica',
        'medicamentos',
        'trabaja',
        'tipo_empleo',
        'medio_transporte',
        'inscrito_cne',
        'centro_electoral',
        'comuna',
        'estado_civil',
        'talla_camisa',
        'talla_zapatos',
        'talla_pantalon',
        'altura',
        'peso',
        'tipo_sangre',
        'carga_familiar',
        'fotos',
        'foto',
        'observaciones',
        'departamento_id',
        'fecha_registro',
        'estado_registro',
        'usuario_registro',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'cedula'            => 'required|is_unique[personas.cedula]',
        'primer_nombre'     => 'required|min_length[2]|max_length[50]',
        'primer_apellido'   => 'required|min_length[2]|max_length[50]',
        'correo_electronico' => 'valid_email',
    ];

    protected $validationMessages = [
        'cedula' => [
            'required'   => 'La cédula es requerida',
            'is_unique'  => 'Ya existe una persona registrada con esta cédula',
        ],
        'primer_nombre' => [
            'required'    => 'El primer nombre es requerido',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
        ],
        'primer_apellido' => [
            'required'    => 'El primer apellido es requerido',
            'min_length'  => 'El apellido debe tener al menos 2 caracteres',
        ],
    ];

    /**
     * Obtiene el nombre completo de la persona
     */
    public function getNombreCompleto($id)
    {
        $persona = $this->find($id);
        if ($persona) {
            return trim($persona['primer_nombre'] . ' ' . 
                $persona['segundo_nombre'] . ' ' . 
                $persona['primer_apellido'] . ' ' . 
                $persona['segundo_apellido']);
        }
        return '';
    }

    /**
     * Busca personas por número de cédula
     */
    public function buscarPorCedula($cedula)
    {
        return $this->where('cedula', $cedula)->first();
    }

    /**
     * Obtiene personas con estado activo
     */
    public function getPersonasActivas()
    {
        return $this->where('estado_registro', 'ACTIVO')->findAll();
    }

    /**
     * Calcula la edad a partir de la fecha de nacimiento
     */
    public function calcularEdad($fechaNacimiento)
    {
        $nacimiento = new \DateTime($fechaNacimiento);
        $hoy = new \DateTime();
        return $hoy->diff($nacimiento)->y;
    }

    /**
     * Obtiene estadísticas del sistema
     */
    public function getEstadisticas()
    {
        $total = $this->countAll();
        $activos = $this->where('estado_registro', 'ACTIVO')->countAllResults();
        
        return [
            'total'     => $total,
            'activos'   => $activos,
            'inactivos' => $total - $activos,
        ];
    }

    /**
     * Obtiene personas por departamento
     */
    public function getPersonasPorDepartamento($departamentoId)
    {
        return $this->where('departamento_id', $departamentoId)
                    ->where('estado_registro', 'ACTIVO')
                    ->findAll();
    }
    
    /**
     * Obtiene todas las personas activas (para ADMIN)
     */
    public function getPersonasActivasTodas()
    {
        return $this->where('estado_registro', 'ACTIVO')
                    ->orderBy('primer_apellido', 'ASC')
                    ->findAll();
    }

    /**
     * Obtiene personas por departamento con paginación
     */
    public function getPersonasPorDepartamentoPaginadas($departamentoId, $pagina = 15)
    {
        return $this->where('departamento_id', $departamentoId)
                    ->where('estado_registro', 'ACTIVO')
                    ->orderBy('primer_apellido', 'ASC')
                    ->paginate($pagina);
    }

    /**
     * Sube una foto de perfil
     */
    public function uploadFoto($personaId, $file)
    {
        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        // Validar que es una imagen
        if (!$file->isValid()) {
            throw new \RuntimeException('Archivo inválido');
        }

        // Validar tipo de imagen
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new \RuntimeException('Solo se permiten imágenes JPEG, PNG, GIF o WebP');
        }

        // Validar tamaño (máx 2MB)
        if ($file->getSize() > 2 * 1024 * 1024) {
            throw new \RuntimeException('La imagen no puede superar los 2MB');
        }

        // Usar carpeta public para que sea accesible via URL
        $uploadPath = ROOTPATH . 'public/uploads/fotos/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generar nombre único
        $extension = $file->getExtension();
        $newName = 'persona_' . $personaId . '_' . time() . '.' . $extension;

        // Mover archivo
        $file->move($uploadPath, $newName);

        // Actualizar base de datos
        $this->update($personaId, ['foto' => $newName]);

        return $newName;
    }

    /**
     * Obtiene la ruta de la foto
     */
    public function getFotoPath($persona)
    {
        if (empty($persona['foto'])) {
            return null;
        }

        // Buscar en public/uploads/fotos/
        $path = ROOTPATH . 'public/uploads/fotos/' . $persona['foto'];
        if (file_exists($path)) {
            return base_url('uploads/fotos/' . $persona['foto']);
        }

        return null;
    }

    /**
     * Elimina la foto de una persona
     */
    public function deleteFoto($personaId)
    {
        $persona = $this->find($personaId);

        if ($persona && $persona['foto']) {
            $path = ROOTPATH . 'public/uploads/fotos/' . $persona['foto'];
            if (file_exists($path)) {
                unlink($path);
            }

            $this->update($personaId, ['foto' => null]);
        }
    }

    /**
     * Obtiene estadísticas de tipo de sangre
     */
    public function getEstadisticasTipoSangre($departamentoId = null)
    {
        if ($departamentoId) {
            $personas = $this->where('departamento_id', $departamentoId)
                ->where('estado_registro', 'ACTIVO')
                ->findAll();
        } else {
            $personas = $this->where('estado_registro', 'ACTIVO')->findAll();
        }

        $tiposSangre = [];
        foreach ($personas as $p) {
            $tipo = $p['tipo_sangre'] ?? 'No definido';
            if (!isset($tiposSangre[$tipo])) {
                $tiposSangre[$tipo] = 0;
            }
            $tiposSangre[$tipo]++;
        }

        // Ordenar por cantidad
        arsort($tiposSangre);

        return $tiposSangre;
    }
}
