<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartamentoModel extends Model
{
    protected $table            = 'departamentos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'nombre',
        'descripcion',
        'codigo',
        'estado',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtiene departamentos activos
     */
    public function getDepartamentosActivos()
    {
        return $this->where('estado', 'ACTIVO')->findAll();
    }

    /**
     * Obtiene un departamento por ID
     */
    public function getDepartamento($id)
    {
        return $this->find($id);
    }
    
    /**
     * Elimina todos los departamentos
     */
    public function deleteDepartamentos()
    {
        return $this->truncate();
    }
}
