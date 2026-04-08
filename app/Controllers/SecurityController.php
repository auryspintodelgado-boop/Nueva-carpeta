<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Session\Session;

class SecurityController extends BaseController
{
    protected $usuarioModel;
    protected $maxAttempts = 5;
    protected $lockoutMinutes = 15;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    /**
     * Muestra el formulario de recuperación de contraseña
     */
    public function forgotPassword()
    {
        $data = [
            'title' => 'Recuperar Contraseña',
        ];

        return view('auth/forgot_password', $data);
    }

    /**
     * Envía el correo de recuperación
     */
    public function sendResetLink()
    {
        $email = $this->request->getPost('email');

        $usuario = $this->usuarioModel->where('email', $email)->first();

        if (!$usuario) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'El correo electrónico no está registrado');
        }

        // Generar token de recuperación
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->usuarioModel->update($usuario['id'], [
            'reset_token'   => $token,
            'reset_expires' => $expires,
        ]);

        // En un entorno real, aquí se enviaría el email
        // Por ahora, mostraremos el token para pruebas
        session()->setFlashdata('reset_token', $token);
        session()->setFlashdata('success', 'Se ha enviado un enlace de recuperación a tu correo');

        return redirect()->to('/security/reset-password/' . $token);
    }

    /**
     * Muestra el formulario de reset de contraseña
     */
    public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to('/security/forgot-password')
                ->with('error', 'Token inválido');
        }

        $usuario = $this->usuarioModel->where('reset_token', $token)->first();

        if (!$usuario) {
            return redirect()->to('/login')
                ->with('error', 'Token de recuperación inválido');
        }

        if (strtotime($usuario['reset_expires']) < time()) {
            return redirect()->to('/login')
                ->with('error', 'El token de recuperación ha expirado');
        }

        $data = [
            'title' => 'Nueva Contraseña',
            'token' => $token,
        ];

        return view('auth/reset_password', $data);
    }

    /**
     * Procesa el cambio de contraseña
     */
    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $passwordConfirm = $this->request->getPost('password_confirmation');

        if ($password !== $passwordConfirm) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Las contraseñas no coinciden');
        }

        if (strlen($password) < 6) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La contraseña debe tener al menos 6 caracteres');
        }

        $usuario = $this->usuarioModel->where('reset_token', $token)->first();

        if (!$usuario) {
            return redirect()->to('/login')
                ->with('error', 'Token inválido');
        }

        // Actualizar contraseña
        $this->usuarioModel->update($usuario['id'], [
            'password'           => password_hash($password, PASSWORD_DEFAULT),
            'reset_token'        => null,
            'reset_expires'      => null,
            'password_changed_at' => date('Y-m-d H:i:s'),
            'failed_login_attempts' => 0,
        ]);

        return redirect()->to('/login')
            ->with('success', 'Contraseña actualizada correctamente. Puedes iniciar sesión.');
    }

    /**
     * Habilitar 2FA
     */
    public function enableTwoFactor()
    {
        $userId = session()->get('id');
        
        // Generar código de 6 dígitos
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expires = date('Y-m H:i:s', strtotime('+5 minutes'));

        $this->usuarioModel->update($userId, [
            'two_factor_code'    => $code,
            'two_factor_expires'=> $expires,
        ]);

        // En producción, enviar SMS o email
        session()->setFlashdata('two_factor_code', $code);
        
        return redirect()->to('/security/verify-2fa');
    }

    /**
     * Verificar código 2FA
     */
    public function verifyTwoFactor()
    {
        $userId = session()->get('id');
        $usuario = $this->usuarioModel->find($userId);

        $code = $this->request->getPost('code');

        if ($usuario['two_factor_code'] !== $code) {
            return redirect()->back()
                ->with('error', 'Código inválido');
        }

        if (strtotime($usuario['two_factor_expires']) < time()) {
            return redirect()->back()
                ->with('error', 'El código ha expirado');
        }

        // Habilitar 2FA
        $this->usuarioModel->update($userId, [
            'two_factor_enabled' => 'S',
            'two_factor_code'    => null,
            'two_factor_expires' => null,
        ]);

        session()->set('two_factor_verified', true);

        return redirect()->to('/dashboard')
            ->with('success', 'Verificación en dos pasos habilitada');
    }

    /**
     * Verificar 2FA en login
     */
    public function verifyLogin2FA()
    {
        $userId = session()->get('pending_user_id');
        $usuario = $this->usuarioModel->find($userId);

        $code = $this->request->getPost('code');

        if ($usuario['two_factor_code'] !== $code) {
            return redirect()->back()
                ->with('error', 'Código inválido');
        }

        if (strtotime($usuario['two_factor_expires']) < time()) {
            return redirect()->to('/login')
                ->with('error', 'El código ha expirado. Inicia sesión nuevamente');
        }

        // Limpiar código y crear sesión
        $this->usuarioModel->update($userId, [
            'two_factor_code'    => null,
            'two_factor_expires' => null,
        ]);

        session()->remove('pending_user_id');
        session()->set([
            'id'                => $usuario['id'],
            'username'          => $usuario['username'],
            'nombre_completo'   => $usuario['nombre_completo'],
            'rol'               => $usuario['rol'],
            'departamento_id'   => $usuario['departamento_id'],
            'isLoggedIn'        => true,
        ]);

        return redirect()->to('/dashboard');
    }

    /**
     * Deshabilitar 2FA
     */
    public function disableTwoFactor()
    {
        $userId = session()->get('id');

        $this->usuarioModel->update($userId, [
            'two_factor_enabled' => 'N',
        ]);

        return redirect()->to('/perfil')
            ->with('success', 'Verificación en dos pasos deshabilitada');
    }

    /**
     * Verificar y bloquear cuenta por intentos fallidos
     */
    public function checkFailedAttempts($username)
    {
        $usuario = $this->usuarioModel
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$usuario) {
            return true;
        }

        // Verificar si está bloqueado
        if ($usuario['locked_until'] && strtotime($usuario['locked_until']) > time()) {
            return false;
        }

        return true;
    }

    /**
     * Registrar intento fallido
     */
    public function recordFailedAttempt($username)
    {
        $usuario = $this->usuarioModel
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$usuario) {
            return;
        }

        $attempts = $usuario['failed_login_attempts'] + 1;

        if ($attempts >= $this->maxAttempts) {
            // Bloquear cuenta
            $lockedUntil = date('Y-m-d H:i:s', strtotime('+' . $this->lockoutMinutes . ' minutes'));
            $this->usuarioModel->update($usuario['id'], [
                'failed_login_attempts' => $attempts,
                'locked_until'         => $lockedUntil,
            ]);
        } else {
            $this->usuarioModel->update($usuario['id'], [
                'failed_login_attempts' => $attempts,
            ]);
        }
    }

    /**
     * Resetear intentos fallidos
     */
    public function resetFailedAttempts($userId)
    {
        $this->usuarioModel->update($userId, [
            'failed_login_attempts' => 0,
            'locked_until'         => null,
        ]);
    }

    /**
     * Formulario de cambio de contraseña (perfil)
     */
    public function changePassword()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Cambiar Contraseña',
        ];

        return view('auth/change_password', $data);
    }

    /**
     * Procesar cambio de contraseña desde perfil
     */
    public function updatePasswordFromProfile()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('id');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        $usuario = $this->usuarioModel->find($userId);

        // Verificar contraseña actual
        if (!password_verify($currentPassword, $usuario['password'])) {
            return redirect()->back()
                ->with('error', 'La contraseña actual es incorrecta');
        }

        // Verificar nuevas contraseñas
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()
                ->with('error', 'Las nuevas contraseñas no coinciden');
        }

        if (strlen($newPassword) < 6) {
            return redirect()->back()
                ->with('error', 'La nueva contraseña debe tener al menos 6 caracteres');
        }

        // Actualizar contraseña
        $this->usuarioModel->update($userId, [
            'password'           => password_hash($newPassword, PASSWORD_DEFAULT),
            'password_changed_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/perfil')
            ->with('success', 'Contraseña actualizada correctamente');
    }
}
