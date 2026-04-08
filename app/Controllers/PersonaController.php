<?php

namespace App\Controllers;

use App\Models\PersonaModel;
use App\Models\EvaluacionModel;
use App\Models\SeguimientoModel;
use App\Models\DepartamentoModel;

class PersonaController extends BaseController
{
    protected $personaModel;
    protected $evaluacionModel;
    protected $seguimientoModel;
    protected $departamentoModel;

    public function __construct()
    {
        $this->personaModel = new PersonaModel();
        $this->evaluacionModel = new EvaluacionModel();
        $this->seguimientoModel = new SeguimientoModel();
        $this->departamentoModel = new DepartamentoModel();
    }

    /**
     * Lista todas las personas
     */
    public function index()
    {
        $busqueda = $this->request->getGet('q');
        $rol = session()->get('rol');
        $departamentoId = session()->get('departamento_id');
        
        // Si es ADMIN, ve todas las personas. Si es DIRECTOR, solo ve las de su departamento
        if ($rol === 'ADMIN' || $rol === 'EVALUADOR') {
            if ($busqueda) {
                $personas = $this->personaModel
                    ->groupStart()
                    ->like('primer_nombre', $busqueda)
                    ->orLike('primer_apellido', $busqueda)
                    ->orLike('cedula', $busqueda)
                    ->orLike('correo_electronico', $busqueda)
                    ->groupEnd()
                    ->where('estado_registro', 'ACTIVO')
                    ->orderBy('primer_apellido', 'ASC')
                    ->paginate(15);
            } else {
                $personas = $this->personaModel
                    ->where('estado_registro', 'ACTIVO')
                    ->orderBy('primer_apellido', 'ASC')
                    ->paginate(15);
            }
        } else {
            if ($busqueda) {
                $personas = $this->personaModel
                    ->groupStart()
                    ->like('primer_nombre', $busqueda)
                    ->orLike('primer_apellido', $busqueda)
                    ->orLike('cedula', $busqueda)
                    ->orLike('correo_electronico', $busqueda)
                    ->groupEnd()
                    ->where('departamento_id', $departamentoId)
                    ->where('estado_registro', 'ACTIVO')
                    ->orderBy('primer_apellido', 'ASC')
                    ->paginate(15);
            } else {
                $personas = $this->personaModel
                    ->where('departamento_id', $departamentoId)
                    ->where('estado_registro', 'ACTIVO')
                    ->orderBy('primer_apellido', 'ASC')
                    ->paginate(15);
            }
        }

        $data = [
            'title'     => 'Lista de Personas',
            'personas'  => $personas,
            'pager'     => $this->personaModel->pager,
            'busqueda'  => $busqueda,
            'departamentos' => $this->departamentoModel->findAll(),
        ];

        return view('personas/index', $data);
    }

    /**
     * Muestra el formulario para crear una nueva persona
     */
    public function create()
    {
        $departamentos = $this->departamentoModel->getDepartamentosActivos();
        
        $data = [
            'title'        => 'Nueva Persona',
            'persona'      => null,
            'departamentos' => $departamentos,
        ];

        return view('personas/create', $data);
    }

    /**
     * Guarda una nueva persona
     */
    public function store()
    {
        $rules = [
            'cedula'            => 'required|is_unique[personas.cedula]',
            'primer_nombre'     => 'required|min_length[2]|max_length[50]',
            'primer_apellido'   => 'required|min_length[2]|max_length[50]',
            'correo_electronico' => 'valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Convertir cadena vacía a null para departamento_id
        if (isset($data['departamento_id']) && $data['departamento_id'] === '') {
            $data['departamento_id'] = null;
        }
        
        // Calcular edad si se proporcionó fecha de nacimiento
        if (!empty($data['fecha_nacimiento'])) {
            $data['edad'] = $this->personaModel->calcularEdad($data['fecha_nacimiento']);
        }
        
        // Agregar fecha de registro
        $data['fecha_registro'] = date('Y-m-d H:i:s');
        $data['estado_registro'] = 'ACTIVO';

        // Si es director, asignar automáticamente su departamento
        $rol = session()->get('rol');
        $departamentoId = session()->get('departamento_id');
        if ($rol === 'DIRECTOR' && $departamentoId) {
            $data['departamento_id'] = $departamentoId;
        }

        $this->personaModel->insert($data);

        return redirect()->to('/personas')
            ->with('success', 'Persona registrada exitosamente');
    }

    /**
     * Muestra los detalles de una persona
     */
    public function show($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (!$persona) {
            return redirect()->to('/personas')
                ->with('error', 'Persona no encontrada');
        }

        // Obtener evaluaciones y seguimientos
        $evaluaciones = $this->evaluacionModel->getEvaluacionesPorPersona($id);
        $seguimientos = $this->seguimientoModel->getSeguimientosPorPersona($id);

        $data = [
            'title'       => 'Ver Persona',
            'persona'    => $persona,
            'evaluaciones'  => $evaluaciones,
            'seguimientos'  => $seguimientos,
        ];

        return view('personas/show', $data);
    }

    /**
     * Muestra el formulario para editar una persona
     */
    public function edit($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (!$persona) {
            return redirect()->to('/personas')
                ->with('error', 'Persona no encontrada');
        }

        // Verificar permisos para DIRECTOR - solo puede editar personas de su departamento
        $rol = session()->get('rol');
        $departamentoId = session()->get('departamento_id');
        if ($rol === 'DIRECTOR' && $persona['departamento_id'] != $departamentoId) {
            return redirect()->to('/personas')
                ->with('error', 'No tiene permiso para editar personas de otros departamentos');
        }

        $departamentos = $this->departamentoModel->getDepartamentosActivos();

        // Si es DIRECTOR, solo mostrar su departamento
        if ($rol === 'DIRECTOR' && $departamentoId) {
            $departamentos = array_filter($departamentos, function($dep) use ($departamentoId) {
                return $dep['id'] == $departamentoId;
            });
        }

        $data = [
            'title'        => 'Editar Persona',
            'persona'     => $persona,
            'departamentos' => $departamentos,
        ];

        return view('personas/edit', $data);
    }

    /**
     * Actualiza una persona
     */
    public function update($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (!$persona) {
            return redirect()->to('/personas')
                ->with('error', 'Persona no encontrada');
        }

        $cedulaActual = $this->request->getPost('cedula');
        $rules = [
            'primer_nombre'     => 'required|min_length[2]|max_length[50]',
            'primer_apellido'  => 'required|min_length[2]|max_length[50]',
            'correo_electronico' => 'valid_email',
        ];

        if ($cedulaActual !== $persona['cedula']) {
            $rules['cedula'] = 'required|is_unique[personas.cedula]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();

        // Convertir cadena vacía a null para departamento_id
        if (isset($data['departamento_id']) && $data['departamento_id'] === '') {
            $data['departamento_id'] = null;
        }

        // Asegurarse de que estado_registro tenga un valor válido
        if (empty($data['estado_registro'])) {
            $data['estado_registro'] = 'ACTIVO';
        }

        if (!empty($data['fecha_nacimiento'])) {
            $data['edad'] = $this->personaModel->calcularEdad($data['fecha_nacimiento']);
        }

        // Manejar eliminación de foto
        if (isset($data['eliminar_foto']) && $data['eliminar_foto'] == '1') {
            try {
                $this->personaModel->deleteFoto($id);
            } catch (\Exception $e) {
                // Ignorar errores al eliminar
            }
        }

        // Manejar nueva foto
        $file = $this->request->getFile('foto');
        if ($file && $file->isValid() && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            try {
                $newName = $this->personaModel->uploadFoto($id, $file);
                $data['foto'] = $newName;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error al subir la foto: ' . $e->getMessage());
            }
        }

        // Eliminar campos de foto del array de datos
        unset($data['eliminar_foto']);

        $this->personaModel->update($id, $data);

        return redirect()->to('/personas/show/' . $id)
            ->with('success', 'Persona actualizada exitosamente');
    }

    /**
     * Elimina (desactiva) una persona
     */
    public function delete($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (!$persona) {
            return redirect()->to('/personas')
                ->with('error', 'Persona no encontrada');
        }

        $this->personaModel->update($id, ['estado_registro' => 'INACTIVO']);

        return redirect()->to('/personas')
            ->with('success', 'Persona eliminada exitosamente');
    }

    /**
     * Busca personas por cédula (API)
     */
    public function buscar()
    {
        $cedula = $this->request->getGet('cedula');

        if (!$cedula) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cédula no proporcionada'
            ]);
        }

        $persona = $this->personaModel->buscarPorCedula($cedula);

        if ($persona) {
            return $this->response->setJSON([
                'success' => true,
                'data'    => $persona
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'No se encontró ninguna persona con esa cédula'
        ]);
    }

    /**
     * Estadísticas del módulo de personas
     */
    public function estadisticas()
    {
        $rol = session()->get('rol');
        $departamentoId = session()->get('departamento_id');
        
        if ($rol === 'ADMIN' || $rol === 'EVALUADOR') {
            $data = $this->personaModel->getEstadisticas();
        } else {
            $total = $this->personaModel->where('departamento_id', $departamentoId)->countAllResults();
            $activos = $this->personaModel->where('departamento_id', $departamentoId)->where('estado_registro', 'ACTIVO')->countAllResults();
            $data = [
                'total'     => $total,
                'activos'   => $activos,
                'inactivos' => $total - $activos,
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data'    => $data
        ]);
    }

    /**
     * Sube una foto para una persona
     */
    public function uploadFoto($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (!$persona) {
            return redirect()->to('/personas')
                ->with('error', 'Persona no encontrada');
        }

        $file = $this->request->getFile('foto');

        if (!$file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return redirect()->to('/personas/show/' . $id)
                ->with('error', 'No se ha seleccionado ningún archivo');
        }

        try {
            $newName = $this->personaModel->uploadFoto($id, $file);

            return redirect()->to('/personas/show/' . $id)
                ->with('success', 'Foto actualizada exitosamente');
        } catch (\Exception $e) {
            return redirect()->to('/personas/show/' . $id)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Elimina la foto de una persona
     */
    public function deleteFoto($id = null)
    {
        $persona = $this->personaModel->find($id);

        if (!$persona) {
            return redirect()->to('/personas')
                ->with('error', 'Persona no encontrada');
        }

        try {
            $this->personaModel->deleteFoto($id);

            return redirect()->to('/personas/show/' . $id)
                ->with('success', 'Foto eliminada exitosamente');
        } catch (\Exception $e) {
            return redirect()->to('/personas/show/' . $id)
                ->with('error', $e->getMessage());
        }
    }
}
