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
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'cedula'            => 'required',
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

    /**
     * Obtiene estadísticas de estado civil
     */
    public function getEstadisticasEstadoCivil($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $estados = [];
        
        foreach ($personas as $p) {
            $estado = $p['estado_civil'] ?? 'No definido';
            if (!isset($estados[$estado])) {
                $estados[$estado] = 0;
            }
            $estados[$estado]++;
        }
        
        arsort($estados);
        return $estados;
    }

    /**
     * Obtiene estadísticas de edad (por rangos)
     */
    public function getEstadisticasEdad($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $rangos = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56+' => 0,
            'No definido' => 0
        ];
        
        foreach ($personas as $p) {
            if (empty($p['fecha_nacimiento'])) {
                $rangos['No definido']++;
                continue;
            }
            
            $edad = (new \DateTime($p['fecha_nacimiento']))->diff(new \DateTime())->y;
            
            if ($edad <= 25) $rangos['18-25']++;
            elseif ($edad <= 35) $rangos['26-35']++;
            elseif ($edad <= 45) $rangos['36-45']++;
            elseif ($edad <= 55) $rangos['46-55']++;
            else $rangos['56+']++;
        }
        
        return $rangos;
    }

    /**
     * Obtiene estadísticas de nacionalidad
     */
    public function getEstadisticasNacionalidad($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $nacionalidades = [];
        
        foreach ($personas as $p) {
            $nac = $p['nacionalidad'] ?? 'No definido';
            if (!isset($nacionalidades[$nac])) {
                $nacionalidades[$nac] = 0;
            }
            $nacionalidades[$nac]++;
        }
        
        arsort($nacionalidades);
        return $nacionalidades;
    }

    /**
     * Obtiene estadísticas de estado (geográfico)
     */
    public function getEstadisticasEstado($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $estados = [];
        
        foreach ($personas as $p) {
            $estado = $p['estado'] ?? 'No definido';
            if (!isset($estados[$estado])) {
                $estados[$estado] = 0;
            }
            $estados[$estado]++;
        }
        
        arsort($estados);
        return $estados;
    }

    /**
     * Obtiene estadísticas de municipio
     */
    public function getEstadisticasMunicipio($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $municipios = [];
        
        foreach ($personas as $p) {
            $muni = $p['municipio'] ?? 'No definido';
            if (!isset($municipios[$muni])) {
                $municipios[$muni] = 0;
            }
            $municipios[$muni]++;
        }
        
        arsort($municipios);
        return array_slice($municipios, 0, 10, true); // Top 10
    }

    /**
     * Obtiene estadísticas de comuna
     */
    public function getEstadisticasComuna($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $comunas = [];
        
        foreach ($personas as $p) {
            $comuna = $p['comuna'] ?? 'No definido';
            if (!isset($comunas[$comuna])) {
                $comunas[$comuna] = 0;
            }
            $comunas[$comuna]++;
        }
        
        arsort($comunas);
        return array_slice($comunas, 0, 10, true); // Top 10
    }

    /**
     * Obtiene estadísticas de parroquia
     */
    public function getEstadisticasParroquia($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $parroquias = [];
        
        foreach ($personas as $p) {
            $parr = $p['parroquia'] ?? 'No definido';
            if (!isset($parroquias[$parr])) {
                $parroquias[$parr] = 0;
            }
            $parroquias[$parr]++;
        }
        
        arsort($parroquias);
        return array_slice($parroquias, 0, 10, true); // Top 10
    }

    /**
     * Obtiene estadísticas de nivel académico
     */
    public function getEstadisticasNivelAcademico($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $niveles = [];
        
        foreach ($personas as $p) {
            $nivel = $p['nivel_academico'] ?? 'No definido';
            if (!isset($niveles[$nivel])) {
                $niveles[$nivel] = 0;
            }
            $niveles[$nivel]++;
        }
        
        arsort($niveles);
        return $niveles;
    }

    /**
     * Obtiene estadísticas de becas
     */
    public function getEstadisticasBeca($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $becas = [
            'Recibe beca' => 0,
            'No recibe beca' => 0,
            'No definido' => 0
        ];
        
        foreach ($personas as $p) {
            if (!isset($p['posee_beca'])) {
                $becas['No definido']++;
            } elseif ($p['posee_beca'] === 'S') {
                $becas['Recibe beca']++;
            } elseif ($p['posee_beca'] === 'N') {
                $becas['No recibe beca']++;
            }
        }
        
        return $becas;
    }

    /**
     * Obtiene estadísticas de tipo de universidad (pública/privada)
     */
    public function getEstadisticasTipoUniversidad($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $tipos = [
            'Pública' => 0,
            'Privada' => 0,
            'No estudia' => 0
        ];
        
        foreach ($personas as $p) {
            if (empty($p['tipo_ieu'])) {
                $tipos['No estudia']++;
            } elseif ($p['tipo_ieu'] === 'PUBLICA') {
                $tipos['Pública']++;
            } elseif ($p['tipo_ieu'] === 'PRIVADA') {
                $tipos['Privada']++;
            }
        }
        
        return $tipos;
    }

    /**
     * Obtiene estadísticas de carreras
     */
    public function getEstadisticasCarrera($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $carreras = [];
        
        foreach ($personas as $p) {
            if (empty($p['carrera'])) {
                continue;
            }
            $carrera = $p['carrera'];
            if (!isset($carreras[$carrera])) {
                $carreras[$carrera] = 0;
            }
            $carreras[$carrera]++;
        }
        
        arsort($carreras);
        return array_slice($carreras, 0, 10, true); // Top 10
    }

    /**
     * Obtiene estadísticas de universidades
     */
    public function getEstadisticasUniversidad($departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }
        
        $personas = $builder->findAll();
        $universidades = [];
        
        foreach ($personas as $p) {
            if (empty($p['siglas_universidad'])) {
                continue;
            }
            $uni = $p['siglas_universidad'];
            if (!isset($universidades[$uni])) {
                $universidades[$uni] = 0;
            }
            $universidades[$uni]++;
        }
        
        arsort($universidades);
        return array_slice($universidades, 0, 10, true); // Top 10
    }

    /**
     * Obtiene personas filtradas por diferentes criterios
     * 
     * @param string $filterType Tipo de filtro: estado_civil, edad, nacionalidad, estado, municipio, comuna, parroquia, nivel_academico, beca, tipo_universidad, carrera, universidad
     * @param string $filterValue Valor a filtrar
     * @param int|null $departamentoId ID del departamento (opcional)
     * @return array Lista de personas que cumplen el criterio
     */
    public function getPersonasByFilter($filterType, $filterValue, $departamentoId = null)
    {
        $builder = $this->where('estado_registro', 'ACTIVO');
        
        if ($departamentoId) {
            $builder->where('departamento_id', $departamentoId);
        }

        switch ($filterType) {
            case 'estado_civil':
                $builder->where('estado_civil', $filterValue);
                break;
            
            case 'edad':
                // $filterValue es un rango como "18-25", "26-35", etc.
                if ($filterValue === '56+') {
                    $builder->where('fecha_nacimiento <=', date('Y-m-d', strtotime('-56 years')));
                } elseif (preg_match('/^(\d+)-(\d+)$/', $filterValue, $matches)) {
                    $edadMin = intval($matches[1]);
                    $edadMax = intval($matches[2]);
                    $fechaMax = date('Y-m-d', strtotime("-$edadMin years"));
                    $fechaMin = date('Y-m-d', strtotime("-$edadMax years -364 days"));
                    $builder->where('fecha_nacimiento >=', $fechaMin)
                            ->where('fecha_nacimiento <=', $fechaMax);
                }
                break;
            
            case 'nacionalidad':
                $builder->where('nacionalidad', $filterValue);
                break;
            
            case 'estado':
                $builder->where('estado', $filterValue);
                break;
            
            case 'municipio':
                $builder->where('municipio', $filterValue);
                break;
            
            case 'comuna':
                $builder->where('comuna', $filterValue);
                break;
            
            case 'parroquia':
                $builder->where('parroquia', $filterValue);
                break;
            
            case 'nivel_academico':
                $builder->where('nivel_academico', $filterValue);
                break;
            
            case 'beca':
                if ($filterValue === 'Recibe beca') {
                    $builder->where('posee_beca', 'S');
                } elseif ($filterValue === 'No recibe beca') {
                    $builder->where('posee_beca', 'N');
                }
                break;
            
            case 'tipo_universidad':
                if ($filterValue === 'Pública') {
                    $builder->where('tipo_ieu', 'PUBLICA');
                } elseif ($filterValue === 'Privada') {
                    $builder->where('tipo_ieu', 'PRIVADA');
                } elseif ($filterValue === 'No estudia') {
                    $builder->where('tipo_ieu IS NULL');
                }
                break;
            
            case 'carrera':
                $builder->where('carrera', $filterValue);
                break;
            
            case 'universidad':
                $builder->where('siglas_universidad', $filterValue);
                break;
            
            case 'departamento':
                // $filterValue es el nombre del departamento, necesitamos el ID
                $deptModel = new \App\Models\DepartamentoModel();
                $dept = $deptModel->where('nombre', $filterValue)->first();
                if ($dept) {
                    $builder->where('departamento_id', $dept['id']);
                } else {
                    return []; // No hay coincidencias
                }
                break;
            
            case 'sexo':
                // $filterValue esperado: 'M' o 'F'
                $builder->where('sexo', $filterValue);
                break;
            
            case 'discapacidad':
                // $filterValue esperado: 'S' o 'N'
                $builder->where('posee_discapacidad', $filterValue);
                break;
            
            default:
                return [];
        }
        
        return $builder->orderBy('primer_apellido', 'ASC')
                     ->orderBy('primer_nombre', 'ASC')
                     ->findAll();
    }
}
