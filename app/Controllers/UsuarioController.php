<?php
/**
 * Controlador de Usuarios
 * Sistema de Evaluación, Seguimiento y Caracterización
 */

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/Usuario.php';
require_once __DIR__ . '/../Models/Persona.php';

class UsuarioController extends Controller {
    private $usuarioModel;
    private $personaModel;
    
    public function __construct() {
        parent::__construct();
        $this->usuarioModel = new Usuario();
        $this->personaModel = new Persona();
    }
    
    /**
     * Obtener roles desde la base de datos
     */
    private function getRoles() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT * FROM roles WHERE activo = 1 ORDER BY nombre ASC");
        return $stmt->fetchAll();
    }
    
    /**
     * Index - Listar todos los usuarios
     */
    public function index() {
        $page = $this->getInputValue('page', 1);
        $search = $this->getInputValue('search', '');
        $rol = $this->getInputValue('rol', '');
        
        $usuarios = $this->usuarioModel->getWithRoles();
        
        // Aplicar paginación manual
        $total = count($usuarios);
        $totalPages = ceil($total / Config::ITEMS_PER_PAGE);
        $offset = ($page - 1) * Config::ITEMS_PER_PAGE;
        $usuarios = array_slice($usuarios, $offset, Config::ITEMS_PER_PAGE);
        
        $roles = $this->getRoles();
        
        $this->view('usuarios/index', [
            'usuarios' => $usuarios,
            'page' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'rol' => $rol,
            'roles' => $roles,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Mostrar formulario de creación
     */
    public function create() {
        $personas = $this->personaModel->all();
        $roles = $this->getRoles();
        
        $this->view('usuarios/create', [
            'personas' => $personas,
            'roles' => $roles,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Guardar nuevo usuario
     */
    public function store() {
        if (!$this->isPost()) {
            $this->redirect('/usuarios/create');
        }
        
        $data = $this->getInput();
        
        // Validar datos requeridos
        $rules = [
            'username' => 'required|min:3|max:50',
            'password' => 'required|min:6',
            'nombre_completo' => 'required',
            'correo' => 'required|email',
            'rol_id' => 'required|numeric'
        ];
        
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            $this->redirect('/usuarios/create');
        }
        
        // Verificar si username ya existe
        if ($this->usuarioModel->usernameExists($data['username'])) {
            $_SESSION['errors'] = ['username' => 'El nombre de usuario ya existe'];
            $_SESSION['old_input'] = $data;
            $this->redirect('/usuarios/create');
        }
        
        // Verificar si correo ya existe
        if ($this->usuarioModel->emailExists($data['correo'])) {
            $_SESSION['errors'] = ['correo' => 'El correo electrónico ya está registrado'];
            $_SESSION['old_input'] = $data;
            $this->redirect('/usuarios/create');
        }
        
        try {
            // Encriptar password
            $data['password'] = Usuario::encryptPassword($data['password']);
            
            $this->usuarioModel->create($data);
            $this->redirectWith('/usuarios', 'Usuario creado correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/usuarios/create', 'Error al crear usuario: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Mostrar formulario de edición
     */
    public function edit($id = null) {
        if (!$id) {
            $this->redirect('/usuarios');
        }
        
        $usuario = $this->usuarioModel->find($id);
        
        if (!$usuario) {
            $this->redirectWith('/usuarios', 'Usuario no encontrado', 'error');
        }
        
        // Ocultar password
        $usuario['password'] = '';
        
        $personas = $this->personaModel->all();
        $roles = $this->getRoles();
        
        $this->view('usuarios/edit', [
            'usuario' => $usuario,
            'personas' => $personas,
            'roles' => $roles,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Actualizar usuario
     */
    public function update($id = null) {
        if (!$this->isPost() || !$id) {
            $this->redirect('/usuarios');
        }
        
        $data = $this->getInput();
        
        // Validar datos requeridos
        $rules = [
            'username' => 'required|min:3|max:50',
            'nombre_completo' => 'required',
            'correo' => 'required|email',
            'rol_id' => 'required|numeric'
        ];
        
        $errors = $this->validate($data, $rules);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            $this->redirect('/usuarios/edit/' . $id);
        }
        
        // Verificar si username ya existe (excluyendo el actual)
        if ($this->usuarioModel->usernameExists($data['username'], $id)) {
            $_SESSION['errors'] = ['username' => 'El nombre de usuario ya existe'];
            $_SESSION['old_input'] = $data;
            $this->redirect('/usuarios/edit/' . $id);
        }
        
        // Verificar si correo ya existe (excluyendo el actual)
        if ($this->usuarioModel->emailExists($data['correo'], $id)) {
            $_SESSION['errors'] = ['correo' => 'El correo electrónico ya está registrado'];
            $_SESSION['old_input'] = $data;
            $this->redirect('/usuarios/edit/' . $id);
        }
        
        try {
            // Si se proporcionó una nueva password, encriptarla
            if (!empty($data['password'])) {
                $data['password'] = Usuario::encryptPassword($data['password']);
            } else {
                unset($data['password']);
            }
            
            $this->usuarioModel->update($id, $data);
            $this->redirectWith('/usuarios', 'Usuario actualizado correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/usuarios/edit/' . $id, 'Error al actualizar usuario: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Eliminar usuario
     */
    public function delete($id = null) {
        if (!$id) {
            $this->redirect('/usuarios');
        }
        
        // No permitir eliminar el propio usuario
        if ($id == ($_SESSION['usuario_id'] ?? 0)) {
            $this->redirectWith('/usuarios', 'No puede eliminar su propio usuario', 'error');
        }
        
        try {
            $this->usuarioModel->delete($id);
            $this->redirectWith('/usuarios', 'Usuario eliminado correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/usuarios', 'Error al eliminar usuario: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Ver detalles de un usuario
     */
    public function show($id = null) {
        if (!$id) {
            $this->redirect('/usuarios');
        }
        
        $usuario = $this->usuarioModel->find($id);
        
        if (!$usuario) {
            $this->redirectWith('/usuarios', 'Usuario no encontrado', 'error');
        }
        
        $this->view('usuarios/view', [
            'usuario' => $usuario,
            'message' => $this->getMessage()
        ]);
    }
    
    /**
     * Cambiar estado de un usuario
     */
    public function cambiarEstado($id = null) {
        if (!$id) {
            $this->redirect('/usuarios');
        }
        
        $usuario = $this->usuarioModel->find($id);
        
        if (!$usuario) {
            $this->redirectWith('/usuarios', 'Usuario no encontrado', 'error');
        }
        
        $nuevoEstado = $usuario['estado'] === 'Activo' ? 'Inactivo' : 'Activo';
        
        try {
            $this->usuarioModel->cambiarEstado($id, $nuevoEstado);
            $this->redirectWith('/usuarios', 'Estado actualizado correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/usuarios', 'Error al actualizar estado: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Cambiar password del usuario actual
     */
    public function cambiarPassword() {
        if (!$this->isPost()) {
            $this->redirect('/usuarios');
        }
        
        $data = $this->getInput();
        $userId = $_SESSION['usuario_id'] ?? 0;
        
        if (!$userId) {
            $this->redirect('/login');
        }
        
        // Validar que las passwords coincidan
        if ($data['password'] !== $data['password_confirm']) {
            $this->redirectWith('/usuarios/perfil', 'Las contraseñas no coinciden', 'error');
        }
        
        try {
            $this->usuarioModel->cambiarPassword($userId, $data['password']);
            $this->redirectWith('/usuarios/perfil', 'Contraseña actualizada correctamente', 'success');
        } catch (Exception $e) {
            $this->redirectWith('/usuarios/perfil', 'Error al cambiar contraseña: ' . $e->getMessage(), 'error');
        }
    }
    
    /**
     * Mostrar perfil del usuario actual
     */
    public function perfil() {
        $userId = $_SESSION['usuario_id'] ?? 0;
        
        if (!$userId) {
            $this->redirect('/login');
        }
        
        $usuario = $this->usuarioModel->find($userId);
        
        $this->view('usuarios/perfil', [
            'usuario' => $usuario,
            'message' => $this->getMessage()
        ]);
    }
}
