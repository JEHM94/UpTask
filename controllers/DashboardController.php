<?php

namespace Controllers;

use Model\Proyecto;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {
        $proyectos = Proyecto::belongsTo('usuarios_id', $_SESSION['id']) ?? '';

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function proyecto(Router $router)
    {
        // Obtiene el token y verifica que sea válido
        $token = $_GET['url'] ?? '';

        // Si no existe un token redirecciona al Dashboard
        if (!$token) header('Location: /dashboard');

        // Verifica que exista un proyecto con la url ingresada
        $proyecto = Proyecto::where('url', $token);

        // Si no existe el proyecto redirecciona al Dashboard
        if (!$proyecto) header('Location: /dashboard');

        // Verifica que la persona que visita el proyecto es quien lo creó
        if ($proyecto->usuarios_id !== $_SESSION['id']) header('Location: /dashboard');


        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function crear_proyecto(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            $alertas = $proyecto->validar();

            if (empty($alertas)) {
                // Genera URL del Proyecto
                $proyecto->url = md5(uniqid());
                // Asigna el Id de creador del Proyecto
                $proyecto->usuarios_id = $_SESSION['id'];
                // Guarda el Proyecto en la DB
                if ($proyecto->guardar()) {
                    header('Location: /proyecto?url=' . $proyecto->url);
                }
            }
        }


        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router)
    {
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil'
        ]);
    }
}
