<?php

namespace App\Controllers;

use App\Models\SeguimientoModel;
use App\Models\PersonaModel;

class SeguimientoController extends BaseController
{
    protected $seguimientoModel;
    protected $personaModel;

    public function __construct()
    {
        $this->seguimientoModel = new SeguimientoModel();
        $this->personaModel = new PersonaModel();
    }

    /**
     * Lista todos los seguimientos
     */
    public function index()
    {
        $personaId = $this->request->getGet('persona_id');
        $estado = $this->request->getGet('estado');
        
        if ($personaId) {
            $seguimientos = $this->seguimientoModel->getSeguimientosPorPersona($personaId);
            $persona = $this->personaModel->find($personaId);
            $titulo = 'Seguimientos de: ' . ($persona ? $persona['primer_nombre'] . ' ' . $persona['primer_apellido'] : 'Desconocido');
        } elseif ($estado) {
            $seguimientos = $this->seguimientoModel->getSeguimientosPorEstado($estado);
            $titulo = 'Seguimientos - ' . ucfirst($estado);
            $persona = null;
        } else {
            $seguimientos = $this->seguimientoModel->orderBy('fecha_seguimiento', 'DESC')->findAll();
            $titulo = 'Lista de Seguimientos';
            $persona = null;
        }

        $data = [
            'title'       => $titulo,
            'seguimientos' => $seguimientos,
            'persona_id'  => $personaId,
            'persona'     => $persona,
            'estado'      => $estado,
        ];

        return view('seguimientos/index', $data);
    }

    /**
     * Muestra el formulario para crear un seguimiento
     */
    public function create()
    {
        $personaId = $this->request->getGet('persona_id');
        $personas = $this->personaModel->getPersonasActivas();

        $data = [
            'title'      => 'Nuevo Seguimiento',
            'personas'   => $personas,
            'seguimiento' => null,
            'persona_id'  => $personaId,
        ];

        return view('seguimientos/create', $data);
    }

    /**
     * Guarda un nuevo seguimiento
     */
    public function store()
    {
        $rules = [
            'persona_id'        => 'required|is_natural_no_zero',
            'tipo_seguimiento'  => 'required',
            'fecha_seguimiento' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Agregar usuario responsable (seudónimo - ajustar según auth)
        $data['usuario_seguimiento'] = 1;
        
        // Estado por defecto
        if (!isset($data['estado'])) {
            $data['estado'] = 'PENDIENTE';
        }

        $this->seguimientoModel->insert($data);

        $personaId = $data['persona_id'];

        return redirect()->to('/seguimientos?persona_id=' . $personaId)
            ->with('success', 'Seguimiento registrado exitosamente');
    }

    /**
     * Muestra los detalles de un seguimiento
     */
    public function show($id = null)
    {
        $seguimiento = $this->seguimientoModel->find($id);

        if (!$seguimiento) {
            return redirect()->to('/seguimientos')
                ->with('error', 'Seguimiento no encontrado');
        }

        $persona = $this->personaModel->find($seguimiento['persona_id']);

        $data = [
            'title'       => 'Ver Seguimiento',
            'seguimiento' => $seguimiento,
            'persona'     => $persona,
        ];

        return view('seguimientos/show', $data);
    }

    /**
     * Muestra el formulario para editar un seguimiento
     */
    public function edit($id = null)
    {
        $seguimiento = $this->seguimientoModel->find($id);

        if (!$seguimiento) {
            return redirect()->to('/seguimientos')
                ->with('error', 'Seguimiento no encontrado');
        }

        $personas = $this->personaModel->getPersonasActivas();

        $data = [
            'title'       => 'Editar Seguimiento',
            'seguimiento' => $seguimiento,
            'personas'    => $personas,
        ];

        return view('seguimientos/edit', $data);
    }

    /**
     * Actualiza un seguimiento
     */
    public function update($id = null)
    {
        $seguimiento = $this->seguimientoModel->find($id);

        if (!$seguimiento) {
            return redirect()->to('/seguimientos')
                ->with('error', 'Seguimiento no encontrado');
        }

        $rules = [
            'persona_id'        => 'required|is_natural_no_zero',
            'tipo_seguimiento' => 'required',
            'fecha_seguimiento' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();

        $this->seguimientoModel->update($id, $data);

        return redirect()->to('/seguimientos/show/' . $id)
            ->with('success', 'Seguimiento actualizado exitosamente');
    }

    /**
     * Elimina un seguimiento
     */
    public function delete($id = null)
    {
        $seguimiento = $this->seguimientoModel->find($id);

        if (!$seguimiento) {
            return redirect()->to('/seguimientos')
                ->with('error', 'Seguimiento no encontrado');
        }

        $personaId = $seguimiento['persona_id'];
        
        $this->seguimientoModel->delete($id);

        return redirect()->to('/seguimientos?persona_id=' . $personaId)
            ->with('success', 'Seguimiento eliminado exitosamente');
    }

    /**
     * Lista seguimientos pendientes
     */
    public function pendientes()
    {
        $seguimientos = $this->seguimientoModel->getSeguimientosPendientes();

        $data = [
            'title'       => 'Seguimientos Pendientes',
            'seguimientos' => $seguimientos,
        ];

        return view('seguimientos/pendientes', $data);
    }

    /**
     * Lista seguimientos próximos
     */
    public function proximos()
    {
        $seguimientos = $this->seguimientoModel->getSeguimientosProximos();

        $data = [
            'title'       => 'Seguimientos Próximos',
            'seguimientos' => $seguimientos,
        ];

        return view('seguimientos/proximos', $data);
    }

    /**
     * Cambia el estado de un seguimiento
     */
    public function cambiarEstado($id = null)
    {
        $seguimiento = $this->seguimientoModel->find($id);

        if (!$seguimiento) {
            return redirect()->to('/seguimientos')
                ->with('error', 'Seguimiento no encontrado');
        }

        $estado = $this->request->getPost('estado');
        
        $this->seguimientoModel->update($id, ['estado' => $estado]);

        return redirect()->to('/seguimientos/show/' . $id)
            ->with('success', 'Estado actualizado exitosamente');
    }
}
