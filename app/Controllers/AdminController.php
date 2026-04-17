<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Models\DepartamentoModel;
use App\Models\PersonaModel;

class AdminController extends BaseController
{
    protected $usuarioModel;
    protected $departamentoModel;
    protected $personaModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->departamentoModel = new DepartamentoModel();
        $this->personaModel = new PersonaModel();
        helper(['form', 'session']);
    }

    /**
     * Verifica que el usuario sea ADMIN
     */
    private function verificarAdmin()
    {
        $userId = session()->get('id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $usuario = $this->usuarioModel->find($userId);
        if (!$usuario || $usuario['rol'] !== 'ADMIN') {
            return redirect()->to('/dashboard')->with('error', 'Acceso denegado. Solo administradores.');
        }

        return null;
    }

    /**
     * Panel de administración
     */
    public function index()
    {
        $redirect = $this->verificarAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Panel de Administración',
            'totalUsuarios' => $this->usuarioModel->countAll(),
            'totalDepartamentos' => $this->departamentoModel->countAll(),
            'totalPersonas' => $this->personaModel->countAll(),
            'usuarios' => $this->usuarioModel->findAll(),
            'departamentos' => $this->departamentoModel->findAll(),
        ];

        return view('admin/index', $data);
    }

    /**
     * Lista de usuarios
     */
    public function usuarios()
    {
        $redirect = $this->verificarAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $this->usuarioModel->findAll(),
            'departamentos' => $this->departamentoModel->findAll(),
        ];

        return view('admin/usuarios', $data);
    }

    /**
     * Crea un nuevo usuario
     */
    public function crearUsuario()
    {
        $redirect = $this->verificarAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Crear Usuario',
            'departamentos' => $this->departamentoModel->findAll(),
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'username' => 'required|min_length[3]|max_length[50]|is_unique[usuarios.username]',
                'email' => 'required|valid_email|is_unique[usuarios.email]',
                'password' => 'required|min_length[6]',
                'nombre_completo' => 'required',
                'rol' => 'required|in_list[ADMIN,EVALUADOR,DIRECTOR,CONSULTA]',
            ];

            if (!$this->validate($rules)) {
                return view('admin/crear_usuario', $data)->with('errors', $this->validator->getErrors());
            }

            $this->usuarioModel->insert([
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                'nombre_completo' => $this->request->getPost('nombre_completo'),
                'rol' => $this->request->getPost('rol'),
                'departamento_id' => $this->request->getPost('departamento_id') ?: null,
                'estado' => 'ACTIVO',
            ]);

            return redirect()->to('/admin/usuarios')->with('success', 'Usuario creado exitosamente.');
        }

        return view('admin/crear_usuario', $data);
    }

    /**
     * Edita un usuario existente
     */
    public function editarUsuario($id)
    {
        $redirect = $this->verificarAdmin();
        if ($redirect) return $redirect;

        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuario no encontrado.');
        }

        $data = [
            'title' => 'Editar Usuario',
            'usuario' => $usuario,
            'departamentos' => $this->departamentoModel->findAll(),
        ];

        if ($this->request->getMethod() === 'POST') {
            $isUniqueUsername = $usuario['username'] === $this->request->getPost('username') ? '' : '|is_unique[usuarios.username]';
            $isUniqueEmail = $usuario['email'] === $this->request->getPost('email') ? '' : '|is_unique[usuarios.email]';

            $rules = [
                'username' => 'required|min_length[3]|max_length[50]' . $isUniqueUsername,
                'email' => 'required|valid_email' . $isUniqueEmail,
                'nombre_completo' => 'required',
                'rol' => 'required|in_list[ADMIN,EVALUADOR,DIRECTOR,CONSULTA]',
            ];

            if (!$this->validate($rules)) {
                return view('admin/editar_usuario', $data)->with('errors', $this->validator->getErrors());
            }

            $updateData = [
                'username' => $this->request->getPost('username'),
                'email' => $this->request->getPost('email'),
                'nombre_completo' => $this->request->getPost('nombre_completo'),
                'rol' => $this->request->getPost('rol'),
                'departamento_id' => $this->request->getPost('departamento_id') ?: null,
                'estado' => $this->request->getPost('estado'),
            ];

            // Solo actualizar contraseña si se proporciona
            $password = $this->request->getPost('password');
            if ($password) {
                $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $this->usuarioModel->update($id, $updateData);

            return redirect()->to('/admin/usuarios')->with('success', 'Usuario actualizado exitosamente.');
        }

        return view('admin/editar_usuario', $data);
    }

    /**
     * Elimina un usuario
     */
    public function eliminarUsuario($id)
    {
        $redirect = $this->verificarAdmin();
        if ($redirect) return $redirect;

        // No permitir eliminar al propio admin
        $userId = session()->get('id');
        if ($id == $userId) {
            return redirect()->to('/admin/usuarios')->with('error', 'No puede eliminarse a sí mismo.');
        }

        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            return redirect()->to('/admin/usuarios')->with('error', 'Usuario no encontrado.');
        }

        $this->usuarioModel->delete($id);

        return redirect()->to('/admin/usuarios')->with('success', 'Usuario eliminado exitosamente.');
    }

    /**
     * Lista de departamentos
     */
    public function departamentos()
    {
        $redirect = $this->verificarAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Gestión de Departamentos',
            'departamentos' => $this->departamentoModel->findAll(),
        ];

        return view('admin/departamentos', $data);
    }

    /**
     * Crea un nuevo departamento
     */
    public function crearDepartamento()
    {
        $redirect = $this->verificarAdmin();
        if ($redirect) return $redirect;

        $data = [
            'title' => 'Crear Departamento',
        ];

        if ($this->request->getMethod() === 'POST') {
            $codigo = strtoupper($this->request->getPost('codigo'));

            // Verificar si el código ya existe
            if ($this->departamentoModel->codigoExists($codigo)) {
                return view('admin/crear_departamento', $data)->with('errors', ['codigo' => 'El código ya existe.']);
            }

            $rules = [
                'nombre' => 'required|min_length[2]|max_length[100]',
                'codigo' => 'required|min_length[2]|max_length[20]',
            ];

            if (!$this->validate($rules)) {
                return view('admin/crear_departamento', $data)->with('errors', $this->validator->getErrors());
            }

            $this->departamentoModel->insert([
                'nombre' => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion'),
                'codigo' => $codigo,
                'estado' => 'ACTIVO',
            ]);

            return redirect()->to('/admin/departamentos')->with('success', 'Departamento creado exitosamente.');
        }

        return view('admin/crear_departamento', $data);
    }

    /**
     * Edita un departamento existente
     */
    public function editarDepartamento($id)
    {
        $redirect = $this->verificarAdmin();
        if ($redirect) return $redirect;

        $departamento = $this->departamentoModel->find($id);
        if (!$departamento) {
            return redirect()->to('/admin/departamentos')->with('error', 'Departamento no encontrado.');
        }

        $data = [
            'title' => 'Editar Departamento',
            'departamento' => $departamento,
        ];

        if ($this->request->getMethod() === 'POST') {
            $codigo = strtoupper($this->request->getPost('codigo'));

            // Verificar si el código ya existe (excluyendo el departamento actual)
            if ($this->departamentoModel->codigoExists($codigo, $id)) {
                return view('admin/editar_departamento', $data)->with('errors', ['codigo' => 'El código ya existe.']);
            }

            $rules = [
                'nombre' => 'required|min_length[2]|max_length[100]',
                'codigo' => 'required|min_length[2]|max_length[20]',
            ];

            if (!$this->validate($rules)) {
                return view('admin/editar_departamento', $data)->with('errors', $this->validator->getErrors());
            }

            $this->departamentoModel->update($id, [
                'nombre' => $this->request->getPost('nombre'),
                'descripcion' => $this->request->getPost('descripcion'),
                'codigo' => $codigo,
                'estado' => $this->request->getPost('estado'),
            ]);

            return redirect()->to('/admin/departamentos')->with('success', 'Departamento actualizado exitosamente.');
        }

        return view('admin/editar_departamento', $data);
    }

    /**
     * Elimina un departamento
     */
    public function eliminarDepartamento($id)
    {
        $redirect = $this->verificarAdmin();
        if ($redirect) return $redirect;

        // Verificar si hay personas en el departamento
        $personas = $this->personaModel->where('departamento_id', $id)->findAll();
        if (!empty($personas)) {
            return redirect()->to('/admin/departamentos')->with('error', 'No se puede eliminar el departamento porque tiene personas asignadas.');
        }

        // Verificar si hay directores asignados
        $directores = $this->usuarioModel->where('departamento_id', $id)->where('rol', 'DIRECTOR')->findAll();
        if (!empty($directores)) {
            return redirect()->to('/admin/departamentos')->with('error', 'No se puede eliminar el departamento porque tiene directores asignados.');
        }

        $departamento = $this->departamentoModel->find($id);
        if (!$departamento) {
            return redirect()->to('/admin/departamentos')->with('error', 'Departamento no encontrado.');
        }

        $this->departamentoModel->delete($id);

        return redirect()->to('/admin/departamentos')->with('success', 'Departamento eliminado exitosamente.');
    }
}
