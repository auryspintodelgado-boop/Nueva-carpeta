<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class AuthController extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        helper(['form', 'session']);
    }

    /**
     * Muestra el formulario de login
     */
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Iniciar Sesión',
        ];

        return view('auth/login', $data);
    }

    /**
     * Procesa el login
     */
    public function attemptLogin()
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Verificar si la cuenta está bloqueada
        if ($this->usuarioModel->isLocked($username)) {
            $minutes = $this->usuarioModel->getLockoutTimeRemaining($username);
            return redirect()->back()
                ->withInput()
                ->with('error', "Cuenta bloqueada. Intente en {$minutes} minutos.");
        }

        $usuario = $this->usuarioModel->verifyCredentials($username, $password);

        if ($usuario) {
            // Verificar estado del usuario
            if ($usuario['estado'] !== 'ACTIVO') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Usuario inactivo. Contacte al administrador.');
            }

            // Resetear intentos fallidos
            $this->usuarioModel->resetFailedAttempts($usuario['id']);

            // Verificar si tiene 2FA habilitado
            if (isset($usuario['two_factor_enabled']) && $usuario['two_factor_enabled'] === 'S') {
                // Generar código 2FA
                $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $expires = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                $this->usuarioModel->update($usuario['id'], [
                    'two_factor_code'   => $code,
                    'two_factor_expires' => $expires,
                ]);

                // Guardar ID temporal
                session()->set('pending_user_id', $usuario['id']);
                session()->setFlashdata('two_factor_code', $code);

                return redirect()->to('/security/verify-login-2fa');
            }

            // Crear sesión
            session()->set([
                'id'                => $usuario['id'],
                'username'          => $usuario['username'],
                'nombre_completo'   => $usuario['nombre_completo'],
                'rol'               => $usuario['rol'],
                'departamento_id'   => $usuario['departamento_id'] ?? null,
                'isLoggedIn'        => true,
            ]);

            return redirect()->to('/dashboard')
                ->with('success', 'Bienvenido, ' . $usuario['username']);
        }

        // Registrar intento fallido
        $this->usuarioModel->recordFailedAttempt($username);

        // Verificar si se bloqueó
        if ($this->usuarioModel->isLocked($username)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Demasiados intentos. Cuenta bloqueada por 15 minutos.');
        }

        $attemptsLeft = 5 - ($this->usuarioModel->getIntentsLeft($username) ?? 0);
        return redirect()->back()
            ->withInput()
            ->with('error', "Credenciales incorrectas. Intentos restantes: {$attemptsLeft}");
    }

    /**
     * Cierra la sesión
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')
            ->with('success', 'Sesión cerrada exitosamente');
    }

    /**
     * Muestra el formulario de registro
     */
    public function register()
    {
        $data = [
            'title' => 'Registrar Usuario',
        ];

        return view('auth/register', $data);
    }

    /**
     * Procesa el registro
     */
    public function attemptRegister()
    {
        $rules = [
            'username'              => 'required|min_length[3]|max_length[50]|is_unique[usuarios.username]',
            'email'                 => 'required|valid_email|is_unique[usuarios.email]',
            'password'              => 'required|min_length[6]',
            'password_confirmation' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username'       => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nombre_completo' => $this->request->getPost('nombre_completo') ?? '',
            'rol'           => 'CONSULTA',
            'estado'        => 'ACTIVO',
        ];

        $this->usuarioModel->insert($data);

        return redirect()->to('/login')
            ->with('success', 'Usuario registrado exitosamente. Puede iniciar sesión.');
    }
}
