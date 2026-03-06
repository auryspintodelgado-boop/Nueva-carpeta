<?php
/**
 * Modelo Genérico para Tablas de Referencia (Lookup)
 * Sistema de Evaluación, Evaluación, Seguimiento y Caracterización
 * 
 * Este modelo se puede usar para: nacionalidades, sexos, estados_civiles,
 * tipos_sangre, paises, estados, municipios, parroquias, universidades,
 * carreras, tipos_discapacidad, condiciones_medicas, tipos_empleo,
 * medios_transporte, tipos_evaluacion, tipos_seguimiento, roles, etc.
 */

require_once __DIR__ . '/Model.php';

class Lookup extends Model {
    protected $timestamps = false;
    
    /**
     * Obtener solo registros activos
     */
    public function getActive() {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE activo = 1 OR activa = 1 OR active = 1
            ORDER BY nombre ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Buscar por nombre
     */
    public function findByName($nombre) {
        return $this->findBy('nombre', $nombre);
    }
    
    /**
     * Obtener lista para combos (id, nombre)
     */
    public function forSelect($orderBy = 'nombre') {
        $stmt = $this->db->query("
            SELECT id, nombre 
            FROM {$this->table} 
            WHERE activo = 1 OR activa = 1
            ORDER BY {$orderBy} ASC
        ");
        return $stmt->fetchAll();
    }
    
    /**
     * Obtener por ID con validación
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? AND (activo = 1 OR activa = 1)");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}

// =====================================================
// MODELOS ESPECÍFICOS PARA TABLAS DE REFERENCIA
// =====================================================

class Nacionalidad extends Lookup {
    protected $table = 'nacionalidades';
    protected $fillable = ['nombre', 'codigo_iso', 'activo'];
}

class Sexo extends Lookup {
    protected $table = 'sexos';
    protected $fillable = ['nombre', 'abreviatura', 'activo'];
}

class EstadoCivil extends Lookup {
    protected $table = 'estados_civiles';
    protected $fillable = ['nombre', 'activo'];
}

class TipoSangre extends Lookup {
    protected $table = 'tipos_sangre';
    protected $fillable = ['nombre', 'factor_rh', 'activo'];
}

class Pais extends Lookup {
    protected $table = 'paises';
    protected $fillable = ['nombre', 'codigo_iso', 'activo'];
}

class Estado extends Lookup {
    protected $table = 'estados';
    protected $fillable = ['pais_id', 'nombre', 'codigo', 'activo'];
    
    public function pais() {
        return $this->belongsTo('Pais', 'pais_id');
    }
    
    public function getByPais($paisId) {
        return $this->where('pais_id', $paisId);
    }
}

class Municipio extends Lookup {
    protected $table = 'municipios';
    protected $fillable = ['estado_id', 'nombre', 'codigo', 'activo'];
    
    public function estado() {
        return $this->belongsTo('Estado', 'estado_id');
    }
    
    public function getByEstado($estadoId) {
        return $this->where('estado_id', $estadoId);
    }
}

class Parroquia extends Lookup {
    protected $table = 'parroquias';
    protected $fillable = ['municipio_id', 'nombre', 'activo'];
    
    public function municipio() {
        return $this->belongsTo('Municipio', 'municipio_id');
    }
    
    public function getByMunicipio($municipioId) {
        return $this->where('municipio_id', $municipioId);
    }
}

class TipoIeu extends Lookup {
    protected $table = 'tipos_ieu';
    protected $fillable = ['nombre', 'descripcion', 'activo'];
}

class Universidad extends Lookup {
    protected $table = 'universidades';
    protected $fillable = ['tipo_ieu_id', 'nombre', 'siglas', 'activo'];
    
    public function tipoIeu() {
        return $this->belongsTo('TipoIeu', 'tipo_ieu_id');
    }
    
    public function getByTipo($tipoIeuId) {
        return $this->where('tipo_ieu_id', $tipoIeuId);
    }
}

class Sede extends Lookup {
    protected $table = 'sedes';
    protected $fillable = ['universidad_id', 'nombre', 'direccion', 'activa'];
    
    public function universidad() {
        return $this->belongsTo('Universidad', 'universidad_id');
    }
    
    public function getByUniversidad($universidadId) {
        return $this->where('universidad_id', $universidadId);
    }
}

class Carrera extends Lookup {
    protected $table = 'carreras';
    protected $fillable = ['nombre', 'descripcion', 'area_conocimiento', 'activo'];
}

class TipoDiscapacidad extends Lookup {
    protected $table = 'tipos_discapacidad';
    protected $fillable = ['nombre', 'descripcion', 'activo'];
}

class CondicionMedica extends Lookup {
    protected $table = 'condiciones_medicas';
    protected $fillable = ['nombre', 'categoria', 'activo'];
}

class TipoEmpleo extends Lookup {
    protected $table = 'tipos_empleo';
    protected $fillable = ['nombre', 'activo'];
}

class MedioTransporte extends Lookup {
    protected $table = 'medios_transporte';
    protected $fillable = ['nombre', 'activo'];
}

class TipoEvaluacion extends Lookup {
    protected $table = 'tipos_evaluacion';
    protected $fillable = ['nombre', 'descripcion', 'activo'];
}

class TipoSeguimiento extends Lookup {
    protected $table = 'tipos_seguimiento';
    protected $fillable = ['nombre', 'descripcion', 'activo'];
}

class Rol extends Lookup {
    protected $table = 'roles';
    protected $fillable = ['nombre', 'descripcion', 'permisos', 'activo'];
}

class EstadoRegistro extends Lookup {
    protected $table = 'estados_registro';
    protected $fillable = ['nombre', 'color', 'activo'];
}
