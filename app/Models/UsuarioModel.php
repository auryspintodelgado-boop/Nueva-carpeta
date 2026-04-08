<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'nombre_completo',
        'rol',
        'departamento_id',
        'estado',
        'ultimo_login',
        'two_factor_enabled',
        'two_factor_code',
        'two_factor_expires',
        'reset_token',
        'reset_expires',
        'password_changed_at',
        'failed_login_attempts',
        'locked_until',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $hidden = ['password'];

    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[usuarios.username]',
        'email'    => 'required|valid_email|is_unique[usuarios.email]',
        'password' => 'required|min_length[6]',
        'rol'      => 'required|in_list[ADMIN,EVALUADOR,DIRECTOR,CONSULTA]',
    ];

    /**
     * Verifica las credenciales del usuario
     */
    public function verifyCredentials($username, $password)
    {
        $usuario = $this->where('username', $username)
                       ->orWhere('email', $username)
                       ->first();

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Actualizar último login
            $this->update($usuario['id'], ['ultimo_login' => date('Y-m-d H:i:s')]);
            return $usuario;
        }

        return null;
    }

    /**
     * Obtiene usuarios activos
     */
    public function getUsuariosActivos()
    {
        return $this->where('estado', 'ACTIVO')->findAll();
    }

    /**
     * Obtiene usuarios por rol
     */
    public function getUsuariosPorRol($rol)
    {
        return $this->where('rol', $rol)->findAll();
    }

    /**
     * Verifica si el usuario tiene un rol específico
     */
    public function hasRole($userId, $rol)
    {
        $usuario = $this->find($userId);
        return $usuario && $usuario['rol'] === $rol;
    }

    /**
     * Verifica si la cuenta está bloqueada
     */
    public function isLocked(string $username): bool
    {
        $usuario = $this->where('username', $username)
                       ->orWhere('email', $username)
                       ->first();

        if (!$usuario) {
            return false;
        }

        if ($usuario['locked_until'] && strtotime($usuario['locked_until']) > time()) {
            return true;
        }

        return false;
    }

    /**
     * Registra un intento fallido de login
     */
    public function recordFailedAttempt(string $username): void
    {
        $usuario = $this->where('username', $username)
                       ->orWhere('email', $username)
                       ->first();

        if (!$usuario) {
            return;
        }

        $attempts = ($usuario['failed_login_attempts'] ?? 0) + 1;
        $maxAttempts = 5;

        if ($attempts >= $maxAttempts) {
            $lockedUntil = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            $this->update($usuario['id'], [
                'failed_login_attempts' => $attempts,
                'locked_until'         => $lockedUntil,
            ]);
        } else {
            $this->update($usuario['id'], [
                'failed_login_attempts' => $attempts,
            ]);
        }
    }

    /**
     * Resetea los intentos fallidos
     */
    public function resetFailedAttempts(int $userId): void
    {
        $this->update($userId, [
            'failed_login_attempts' => 0,
            'locked_until'         => null,
        ]);
    }

    /**
     * Obtiene el tiempo restante de bloqueo
     */
    public function getLockoutTimeRemaining(string $username): ?int
    {
        $usuario = $this->where('username', $username)
                       ->orWhere('email', $username)
                       ->first();

        if (!$usuario || !$usuario['locked_until']) {
            return null;
        }

        $lockedUntil = strtotime($usuario['locked_until']);
        $now = time();

        if ($lockedUntil <= $now) {
            return null;
        }

        return (int) (($lockedUntil - $now) / 60);
    }

    /**
     * Obtiene los intentos restantes
     */
    public function getIntentsLeft(string $username): ?int
    {
        $usuario = $this->where('username', $username)
                       ->orWhere('email', $username)
                       ->first();

        if (!$usuario) {
            return null;
        }

        $attempts = $usuario['failed_login_attempts'] ?? 0;
        return 5 - $attempts;
    }
}
