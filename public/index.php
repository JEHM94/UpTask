<?php

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\TareaController;
use MVC\Router;

$router = new Router();

// ----- Login & Autenticación-----
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
// Desconectar
$router->get('/logout', [LoginController::class, 'logout']);
// ----- Login & Autenticación FIN-----

// ----- Cuentas -----
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);

// Recuperación de Contraseña
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);

$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);

// Confirmación de Cuenta
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);

// ----- Cuentas END-----

// ----- Proyectos-----
$router->get('/dashboard', [DashboardController::class, 'index']);
// Ver Proyecto
$router->get('/proyecto', [DashboardController::class, 'proyecto']);
// Crear Proyectos
$router->get('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
$router->post('/crear-proyecto', [DashboardController::class, 'crear_proyecto']);
// Perfil de Usuario
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);
// Cambiar password
$router->get('/perfil/cambiar-password', [DashboardController::class, 'cambiar_password']);
$router->post('/perfil/cambiar-password', [DashboardController::class, 'cambiar_password']);
// ----- Proyectos END-----

// ----- API Tareas-----
// Ver Tareas
$router->get('/api/tareas', [TareaController::class, 'index']);
// Crear Tareas
$router->post('/api/tarea', [TareaController::class, 'crear']);
// Actualizar Tareas
$router->post('/api/tarea/actualizar', [TareaController::class, 'actualizar']);
// Eliminar Tareas
$router->post('/api/tarea/eliminar', [TareaController::class, 'eliminar']);

// ----- API Tareas END-----



// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
