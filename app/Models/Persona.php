<?php
/**
 * Modelo Persona
 * Sistema de Evaluación, Seguimiento y Caracterización
 * Base de datos normalizada
 */

require_once __DIR__ . '/Model.php';

class Persona extends Model {
    protected $table = 'personas';
    protected $primaryKey = 'id';
    protected $fillable = [
        // Identificación
        'numero',
        'nacionalidad_id',
        'cedula',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'sexo_id',
        'fecha_nacimiento',
        'edad',
        'correo_electronico',
        'telefono_1',
        'foto',
        
        // Académicos
        'carrera_id',
        'anio_semestre',
        'posee_beca',
        'tipo_beca',
        'sede_id',
        'universidad_id',
        'nivel_academico',
        
        // Ubicación
        'pais_id',
        'estado_id',
        'municipio_id',
        'parroquia_id',
        'urbanizacion',
        'direccion_exacta',
        'comuna',
        
        // Familiares
        'estado_civil_id',
        'tiene_hijos',
        'cantidad_hijos',
        'carga_familiar',
        
        // Salud
        'posee_discapacidad',
        'tipo_discapacidad_id',
        'presenta_enfermedad',
        'condicion_medica_id',
        'medicamentos',
        'tipo_sangre_id',
        'altura',
        'peso',
        
        // Laboral
        'trabaja',
        'tipo_empleo_id',
        'nombre_empresa',
        'cargo',
        'ingreso_mensual',
        'medio_transporte_id',
        
        // Electoral
        'inscrito_cne',
        'centro_electoral',
        'municipio_electoral',
        'puesto_votacion',
        
        // Antropométrico
        'talla_camisa',
        'talla_zapato',
        'talla_pantalon',
        
        // Metadatos
        'estado_registro_id'
    ];
    
    protected $hidden = ['foto'];
    
    /**
     * Relación con Nacionalidad
     */
    public function nacionalidad() {
        return $this->belongsTo('Nacionalidad', 'nacionalidad_id');
    }
    
    /**
     * Relación con Sexo
     */
    public function sexo() {
        return $this->belongsTo('Sexo', 'sexo_id');
    }
    
    /**
     * Relación con Carrera
     */
    public function carrera() {
        return $this->belongsTo('Carrera', 'carrera_id');
    }
    
    /**
     * Relación con Universidad
     */
    public function universidad() {
        return $this->belongsTo('Universidad', 'universidad_id');
    }
    
    /**
     * Relación con Sede
     */
    public function sede() {
        return $this->belongsTo('Sede', 'sede_id');
    }
    
    /**
     * Relación con Estado
     */
    public function estado() {
        return $this->belongsTo('Estado', 'estado_id');
    }
    
    /**
     * Relación con Municipio
     */
    public function municipio() {
        return $this->belongsTo('Municipio', 'municipio_id');
    }
    
    /**
     * Relación con Parroquia
     */
    public function parroquia() {
        return $this->belongsTo('Parroquia', 'parroquia_id');
    }
    
    /**
     * Relación con Estado Civil
     */
    public function estadoCivil() {
        return $this->belongsTo('EstadoCivil', 'estado_civil_id');
    }
    
    /**
     * Relación con Tipo de Sangre
     */
    public function tipoSangre() {
        return $this->belongsTo('TipoSangre', 'tipo_sangre_id');
    }
    
    /**
     * Relación con Tipo de Discapacidad
     */
    public function tipoDiscapacidad() {
        return $this->belongsTo('TipoDiscapacidad', 'tipo_discapacidad_id');
    }
    
    /**
     * Relación con Condición Médica
     */
    public function condicionMedica() {
        return $this->belongsTo('CondicionMedica', 'condicion_medica_id');
    }
    
    /**
     * Relación con Tipo de Empleo
     */
    public function tipoEmpleo() {
        return $this->belongsTo('TipoEmpleo', 'tipo_empleo_id');
    }
    
    /**
     * Relación con Medio de Transporte
     */
    public function medioTransporte() {
        return $this->belongsTo('MedioTransporte', 'medio_transporte_id');
    }
    
    /**
     * Relación con Estado de Registro
     */
    public function estadoRegistro() {
        return $this->belongsTo('EstadoRegistro', 'estado_registro_id');
    }
    
    /**
     * Relación con Evaluaciones
     */
    public function evaluaciones() {
        return $this->hasMany('Evaluacion', 'persona_id');
    }
    
    /**
     * Relación con Seguimientos
     */
    public function seguimientos() {
        return $this->hasMany('Seguimiento', 'persona_id');
    }
    
    /**
     * Obtener persona por número de cédula
     */
    public function findByCedula($cedula) {
        return $this->findBy('cedula', $cedula);
    }
    
    /**
     * Obtener personas activas
     */
    public function getActivos() {
        return $this->where('estado_registro_id', 1);
    }
    
    /**
     * Obtener personas con becas
     */
    public function getConBecas() {
        return $this->where('posee_beca', 1);
    }
    
    /**
     * Buscar personas por nombre o apellido
     */
    public function search($query) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE primer_nombre LIKE ? 
            OR segundo_nombre LIKE ? 
            OR primer_apellido LIKE ? 
            OR segundo_apellido LIKE ?
            OR cedula LIKE ?
        ");
        $searchTerm = "%{$query}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener persona completa con relaciones
     */
    public function findWithRelations($id) {
        $stmt = $this->db->prepare("
            SELECT p.*,
                   n.nombre as nacionalidad_nombre,
                   s.nombre as sexo_nombre,
                   c.nombre as carrera_nombre,
                   u.nombre as universidad_nombre,
                   u.siglas as universidad_siglas,
                   sed.nombre as sede_nombre,
                   est.nombre as estado_nombre,
                   m.nombre as municipio_nombre,
                   pq.nombre as parroquia_nombre,
                   ec.nombre as estado_civil_nombre,
                   ts.nombre as tipo_sangre_nombre,
                   td.nombre as tipo_discapacidad_nombre,
                   cm.nombre as condicion_medica_nombre,
                   te.nombre as tipo_empleo_nombre,
                   mt.nombre as medio_transporte_nombre,
                   er.nombre as estado_registro_nombre
            FROM personas p
            LEFT JOIN nacionalidades n ON p.nacionalidad_id = n.id
            LEFT JOIN sexos s ON p.sexo_id = s.id
            LEFT JOIN carreras c ON p.carrera_id = c.id
            LEFT JOIN universidades u ON p.universidad_id = u.id
            LEFT JOIN sedes sed ON p.sede_id = sed.id
            LEFT JOIN estados est ON p.estado_id = est.id
            LEFT JOIN municipios m ON p.municipio_id = m.id
            LEFT JOIN parroquias pq ON p.parroquia_id = pq.id
            LEFT JOIN estados_civiles ec ON p.estado_civil_id = ec.id
            LEFT JOIN tipos_sangre ts ON p.tipo_sangre_id = ts.id
            LEFT JOIN tipos_discapacidad td ON p.tipo_discapacidad_id = td.id
            LEFT JOIN condiciones_medicas cm ON p.condicion_medica_id = cm.id
            LEFT JOIN tipos_empleo te ON p.tipo_empleo_id = te.id
            LEFT JOIN medios_transporte mt ON p.medio_transporte_id = mt.id
            LEFT JOIN estados_registro er ON p.estado_registro_id = er.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Obtener estadísticas de caracterización
     */
    public function getEstadisticas() {
        $stats = [];
        
        // Total de personas
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table}");
        $stats['total'] = $stmt->fetch()['total'];
        
        // Por género
        $stmt = $this->db->query("
            SELECT s.nombre as sexo, COUNT(*) as total 
            FROM {$this->table} p
            JOIN sexos s ON p.sexo_id = s.id
            GROUP BY p.sexo_id
        ");
        $stats['por_sexo'] = $stmt->fetchAll();
        
        // Por estado civil
        $stmt = $this->db->query("
            SELECT ec.nombre as estado_civil, COUNT(*) as total 
            FROM {$this->table} p
            JOIN estados_civiles ec ON p.estado_civil_id = ec.id
            GROUP BY p.estado_civil_id
        ");
        $stats['por_estado_civil'] = $stmt->fetchAll();
        
        // Con becas
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE posee_beca = 1");
        $stats['con_becas'] = $stmt->fetch()['total'];
        
        // Con discapacidad
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE posee_discapacidad = 1");
        $stats['con_discapacidad'] = $stmt->fetch()['total'];
        
        // Trabajan
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE trabaja = 1");
        $stats['trabajadores'] = $stmt->fetch()['total'];
        
        // Inscritos CNE
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$this->table} WHERE inscrito_cne = 1");
        $stats['inscritos_cne'] = $stmt->fetch()['total'];
        
        // Por carrera
        $stmt = $this->db->query("
            SELECT c.nombre as carrera, COUNT(*) as total 
            FROM {$this->table} p
            JOIN carreras c ON p.carrera_id = c.id
            GROUP BY p.carrera_id
            ORDER BY total DESC
        ");
        $stats['por_carrera'] = $stmt->fetchAll();
        
        // Por universidad
        $stmt = $this->db->query("
            SELECT u.nombre as universidad, u.siglas, COUNT(*) as total 
            FROM {$this->table} p
            JOIN universidades u ON p.universidad_id = u.id
            GROUP BY p.universidad_id
            ORDER BY total DESC
        ");
        $stats['por_universidad'] = $stmt->fetchAll();
        
        // Por estado de residencia
        $stmt = $this->db->query("
            SELECT e.nombre as estado, COUNT(*) as total 
            FROM {$this->table} p
            JOIN estados e ON p.estado_id = e.id
            GROUP BY p.estado_id
            ORDER BY total DESC
        ");
        $stats['por_estado'] = $stmt->fetchAll();
        
        // Por nivel académico
        $stmt = $this->db->query("
            SELECT nivel_academico, COUNT(*) as total 
            FROM {$this->table} 
            WHERE nivel_academico IS NOT NULL
            GROUP BY nivel_academico
        ");
        $stats['por_nivel'] = $stmt->fetchAll();
        
        // Promedio de edad
        $stmt = $this->db->query("SELECT AVG(edad) as promedio FROM {$this->table} WHERE edad > 0");
        $stats['promedio_edad'] = round($stmt->fetch()['promedio'], 1);
        
        // Por estado de registro
        $stmt = $this->db->query("
            SELECT er.nombre as estado, COUNT(*) as total 
            FROM {$this->table} p
            JOIN estados_registro er ON p.estado_registro_id = er.id
            GROUP BY p.estado_registro_id
        ");
        $stats['por_estado_registro'] = $stmt->fetchAll();
        
        return $stats;
    }
    
    /**
     * Obtener promedio de edad
     */
    public function getPromedioEdad() {
        $stmt = $this->db->query("SELECT AVG(edad) as promedio FROM {$this->table} WHERE edad > 0");
        $result = $stmt->fetch();
        return round($result['promedio'], 1) ?? 0;
    }
    
    /**
     * Calcular edad automáticamente desde fecha de nacimiento
     */
    public static function calcularEdad($fechaNacimiento) {
        $nacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();
        return $nacimiento->diff($hoy)->y;
    }
    
    /**
     * Obtener personas con paginación y filtros
     */
    public function getFiltered($filters = [], $page = 1, $perPage = 20) {
        $where = [];
        $params = [];
        
        if (!empty($filters['sexo_id'])) {
            $where[] = 'p.sexo_id = ?';
            $params[] = $filters['sexo_id'];
        }
        
        if (!empty($filters['carrera_id'])) {
            $where[] = 'p.carrera_id = ?';
            $params[] = $filters['carrera_id'];
        }
        
        if (!empty($filters['universidad_id'])) {
            $where[] = 'p.universidad_id = ?';
            $params[] = $filters['universidad_id'];
        }
        
        if (!empty($filters['estado_id'])) {
            $where[] = 'p.estado_id = ?';
            $params[] = $filters['estado_id'];
        }
        
        if (!empty($filters['posee_beca'])) {
            $where[] = 'p.posee_beca = ?';
            $params[] = $filters['posee_beca'];
        }
        
        if (!empty($filters['trabaja'])) {
            $where[] = 'p.trabaja = ?';
            $params[] = $filters['trabaja'];
        }
        
        if (!empty($filters['estado_registro_id'])) {
            $where[] = 'p.estado_registro_id = ?';
            $params[] = $filters['estado_registro_id'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = '(p.primer_nombre LIKE ? OR p.segundo_nombre LIKE ? OR p.primer_apellido LIKE ? OR p.segundo_apellido LIKE ? OR p.cedula LIKE ?)';
            $search = "%{$filters['search']}%";
            $params = array_merge($params, [$search, $search, $search, $search, $search]);
        }
        
        $sql = "
            SELECT p.*,
                   n.nombre as nacionalidad_nombre,
                   s.nombre as sexo_nombre,
                   c.nombre as carrera_nombre,
                   u.nombre as universidad_nombre,
                   u.siglas as universidad_siglas,
                   est.nombre as estado_nombre,
                   er.nombre as estado_registro_nombre
            FROM personas p
            LEFT JOIN nacionalidades n ON p.nacionalidad_id = n.id
            LEFT JOIN sexos s ON p.sexo_id = s.id
            LEFT JOIN carreras c ON p.carrera_id = c.id
            LEFT JOIN universidades u ON p.universidad_id = u.id
            LEFT JOIN estados est ON p.estado_id = est.id
            LEFT JOIN estados_registro er ON p.estado_registro_id = er.id
        ";
        
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        
        $offset = ($page - 1) * $perPage;
        $sql .= " ORDER BY p.id DESC LIMIT {$perPage} OFFSET {$offset}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    /**
     * Contar registros con filtros
     */
    public function countFiltered($filters = []) {
        $where = [];
        $params = [];
        
        if (!empty($filters['sexo_id'])) {
            $where[] = 'p.sexo_id = ?';
            $params[] = $filters['sexo_id'];
        }
        
        if (!empty($filters['carrera_id'])) {
            $where[] = 'p.carrera_id = ?';
            $params[] = $filters['carrera_id'];
        }
        
        if (!empty($filters['universidad_id'])) {
            $where[] = 'p.universidad_id = ?';
            $params[] = $filters['universidad_id'];
        }
        
        if (!empty($filters['estado_id'])) {
            $where[] = 'p.estado_id = ?';
            $params[] = $filters['estado_id'];
        }
        
        if (!empty($filters['posee_beca'])) {
            $where[] = 'p.posee_beca = ?';
            $params[] = $filters['posee_beca'];
        }
        
        if (!empty($filters['trabaja'])) {
            $where[] = 'p.trabaja = ?';
            $params[] = $filters['trabaja'];
        }
        
        if (!empty($filters['search'])) {
            $where[] = '(p.primer_nombre LIKE ? OR p.segundo_nombre LIKE ? OR p.primer_apellido LIKE ? OR p.segundo_apellido LIKE ? OR p.cedula LIKE ?)';
            $search = "%{$filters['search']}%";
            $params = array_merge($params, [$search, $search, $search, $search, $search]);
        }
        
        $sql = "SELECT COUNT(*) as total FROM personas p";
        
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch()['total'];
    }
}
