<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
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
        $usuario = Usuario::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sincroniza la instancia del usuario con los datos enviados en POST
            $usuario->sincronizar($_POST);
            // Valida los Datos del formulario
            $alertas = $usuario->validar(ACTUALIZAR_PERFIL);

            if (empty($alertas)) {

                $existeUsuario = Usuario::where('email', $usuario->email);
                // Genera alerta si el usuario ya se encuentra en uso
                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    Usuario::setAlerta('error', 'El Email ya se encuentra en uso');
                } else {
                    // Si pasa la validación, actualiza el usuario
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Cambios guardados exitosamente');
                    // Actualiza los datos de la Sesión
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;
                }
            }
        }

        $alertas = $usuario->getAlertas();

        $router->render('dashboard/perfil', [
            'titulo' => 'Datos de la Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiar_password(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = Usuario::find($_SESSION['id']);
            // Sincroniza la instancia del usuario con los datos enviados en POST
            $usuario->sincronizar($_POST);
            // Valida los Datos del formulario
            $alertas = $usuario->validar(NUEVO_PASSWORD);

            if (empty($alertas)) {
                if (!$usuario->comprobarPassword()) {
                    Usuario::setAlerta('error', 'La contraseña actual es incorrecta');
                } else {
                    // Asigna el nuevo password al usuario en memoria
                    $usuario->password = $usuario->password_nuevo;
                    // Hashea le nuevo password
                    $usuario->hashPassword();
                    // Actualiza el password del usuario en BD
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Contraseña cambiada exitosamente');
                }
            }
            $alertas = $usuario->getAlertas();
        }

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Contraseña',
            'alertas' => $alertas
        ]);
    }
}
