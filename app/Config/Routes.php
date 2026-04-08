<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas públicas
$routes->get('/', 'HomeController::index');
$routes->get('/welcome', 'HomeController::welcome');

// Rutas de autenticación
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::attemptRegister');

// Dashboard
$routes->get('/dashboard', 'HomeController::dashboard');

// Rutas de Personas
$routes->get('/personas', 'PersonaController::index');
$routes->get('/personas/create', 'PersonaController::create');
$routes->post('/personas', 'PersonaController::store');
$routes->get('/personas/show/(:num)', 'PersonaController::show/$1');
$routes->get('/personas/edit/(:num)', 'PersonaController::edit/$1');
$routes->post('/personas/update/(:num)', 'PersonaController::update/$1');
$routes->post('/personas/delete/(:num)', 'PersonaController::delete/$1');
$routes->get('/personas/buscar', 'PersonaController::buscar');
$routes->get('/personas/estadisticas', 'PersonaController::estadisticas');
$routes->post('/personas/upload-foto/(:num)', 'PersonaController::uploadFoto/$1');
$routes->post('/personas/delete-foto/(:num)', 'PersonaController::deleteFoto/$1');

// Rutas de Evaluaciones
$routes->get('/evaluaciones', 'EvaluacionController::index');
$routes->get('/evaluaciones/create', 'EvaluacionController::create');
$routes->post('/evaluaciones', 'EvaluacionController::store');
$routes->get('/evaluaciones/show/(:num)', 'EvaluacionController::show/$1');
$routes->get('/evaluaciones/edit/(:num)', 'EvaluacionController::edit/$1');
$routes->post('/evaluaciones/update/(:num)', 'EvaluacionController::update/$1');
$routes->post('/evaluaciones/delete/(:num)', 'EvaluacionController::delete/$1');
$routes->get('/evaluaciones/empleado-del-mes', 'EvaluacionController::empleadoDelMes');
$routes->post('/evaluaciones/seleccionar-empleado/(:num)', 'EvaluacionController::seleccionarEmpleadoDelMes/$1');

// Rutas de Evaluaciones - Director (Esquema de 20 puntos, 4 secciones)
$routes->get('/evaluaciones/director', 'EvaluacionDirectorController::index');
$routes->get('/evaluaciones/director/create', 'EvaluacionDirectorController::create');
$routes->post('/evaluaciones/director/store', 'EvaluacionDirectorController::store');
$routes->get('/evaluaciones/director/historial', 'EvaluacionDirectorController::historial');
$routes->get('/evaluaciones/director/show/(:num)', 'EvaluacionDirectorController::show/$1');

// Rutas de Seguimientos
$routes->get('/seguimientos', 'SeguimientoController::index');
$routes->get('/seguimientos/create', 'SeguimientoController::create');
$routes->post('/seguimientos', 'SeguimientoController::store');
$routes->get('/seguimientos/show/(:num)', 'SeguimientoController::show/$1');
$routes->get('/seguimientos/edit/(:num)', 'SeguimientoController::edit/$1');
$routes->post('/seguimientos/update/(:num)', 'SeguimientoController::update/$1');
$routes->post('/seguimientos/delete/(:num)', 'SeguimientoController::delete/$1');
$routes->get('/seguimientos/pendientes', 'SeguimientoController::pendientes');
$routes->get('/seguimientos/proximos', 'SeguimientoController::proximos');
$routes->post('/seguimientos/cambiar-estado/(:num)', 'SeguimientoController::cambiarEstado/$1');

// Rutas de Seguridad
$routes->get('/security/forgot-password', 'SecurityController::forgotPassword');
$routes->post('/security/send-reset-link', 'SecurityController::sendResetLink');
$routes->get('/security/reset-password/(:any)', 'SecurityController::resetPassword/$1');
$routes->post('/security/update-password', 'SecurityController::updatePassword');
$routes->get('/security/change-password', 'SecurityController::changePassword');
$routes->post('/security/change-password', 'SecurityController::updatePasswordFromProfile');
$routes->get('/security/enable-2fa', 'SecurityController::enableTwoFactor');
$routes->post('/security/verify-2fa', 'SecurityController::verifyTwoFactor');
$routes->post('/security/verify-login-2fa', 'SecurityController::verifyLogin2FA');
$routes->get('/security/disable-2fa', 'SecurityController::disableTwoFactor');

// Rutas de Reportes
$routes->get('/reportes', 'ReporteController::index');
$routes->get('/reportes/personas', 'ReporteController::personas');
$routes->get('/reportes/personas/(:any)', 'ReporteController::personas/$1');
$routes->get('/reportes/evaluaciones', 'ReporteController::evaluaciones');
$routes->get('/reportes/evaluaciones/(:any)', 'ReporteController::evaluaciones/$1');
$routes->get('/reportes/seguimientos', 'ReporteController::seguimientos');
$routes->get('/reportes/seguimientos/(:any)', 'ReporteController::seguimientos/$1');
$routes->get('/reportes/estadisticas', 'ReporteController::estadisticas');

// Rutas de Administración
$routes->get('/admin', 'AdminController::index');
$routes->get('/admin/usuarios', 'AdminController::usuarios');
$routes->get('/admin/usuarios/crear', 'AdminController::crearUsuario');
$routes->post('/admin/usuarios/crear', 'AdminController::crearUsuario');
$routes->get('/admin/usuarios/editar/(:num)', 'AdminController::editarUsuario/$1');
$routes->post('/admin/usuarios/editar/(:num)', 'AdminController::editarUsuario/$1');
$routes->get('/admin/usuarios/eliminar/(:num)', 'AdminController::eliminarUsuario/$1');
$routes->get('/admin/departamentos', 'AdminController::departamentos');
$routes->get('/admin/departamentos/crear', 'AdminController::crearDepartamento');
$routes->post('/admin/departamentos/crear', 'AdminController::crearDepartamento');
$routes->get('/admin/departamentos/editar/(:num)', 'AdminController::editarDepartamento/$1');
$routes->post('/admin/departamentos/editar/(:num)', 'AdminController::editarDepartamento/$1');
$routes->get('/admin/departamentos/eliminar/(:num)', 'AdminController::eliminarDepartamento/$1');
