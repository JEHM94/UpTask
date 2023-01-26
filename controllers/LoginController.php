<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController
{
    public static function login(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        // Render a la Vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión'
        ]);
    }

    public static function logout()
    {
        echo 'Desde Logout';
    }

    public static function crear(Router $router)
    {
        // Instanciamos un nuevo Usuario
        $usuario = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sincronizamos el usuario en memoria con los datos de POST
            $usuario->sincronizar($_POST);
            // Valida los cmapos del formulario
            $alertas = $usuario->validar(CUENTA_NUEVA);
            // Si pasa la validación, comprueba que el email no exista
            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                // Crea alerta de error si ya existe el usuario
                if ($existeUsuario) {
                    Usuario::setAlerta(ERROR, 'El e-mail ya se encuenta en uso.');
                } else {
                    // Hashea el password del usuario
                    $usuario->hashPassword();
                    // Elimina password2 para evitar errores al guardar en DB
                    unset($usuario->password2);
                    // Genera Token para confirmación de cuenta
                    $usuario->crearToken();
                    // Guardamos el nuevo usuario en la BD y redireccionamos
                    if ($usuario->guardar()) {
                        // Enviamos el E-mail de Confirmación de cuenta
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarConfirmacion(CUENTA_NUEVA);
                        // Redireccionamos
                        header('Location: /mensaje');
                    }
                }
            }
        }
        // Obtenemos las alertas para pasarlas a la vista
        $alertas = Usuario::getAlertas();
        // Render a la Vista
        $router->render('auth/crear', [
            'titulo' => 'Crea tu cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Instancia nuevo Usuario con los datos de POST
            $usuario = new Usuario($_POST);
            // Valida el e-mail
            $alertas = $usuario->validar(RECUPERAR_CUENTA);

            if (empty($alertas)) {
            }
        }

        $alertas = Usuario::getAlertas();

        // Render a la Vista
        $router->render('auth/olvide', [
            'titulo' => 'Recuperar Contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        }

        // Render a la Vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Contraseña'
        ]);
    }

    public static function mensaje(Router $router)
    {
        // Render a la Vista
        $router->render('auth/mensaje', []);
    }

    public static function confirmar(Router $router)
    {
        // Obtiene el token ingresado por la URL
        $token = s($_GET['token']);
        // Si no hay ningún token, redirecciona a /
        if (!$token) header('Location: /');

        // Busca al usuario con el token ingresado
        $usuario = Usuario::where('token', $token);
        // Si no hay ningún usuario, redirecciona a /
        if (empty($usuario)) {
            // No se encontró un usuario con ese token
            Usuario::setAlerta(ERROR, 'Token no válido');
        } else {
            // Confirmar la cuenta
            $usuario->confirmado = 1;
            // Elimina el token
            $usuario->token = null;
            // Elimina el password2
            unset($usuario->password2);
            // Guardamos cambios
            if ($usuario->guardar()) Usuario::setAlerta(EXITO, 'E-mail verificado exitosamente');
        }

        $alertas = Usuario::getAlertas();

        // Render a la Vista
        $router->render('auth/confirmar', [
            'titulo' => 'Confirmar cuenta',
            'alertas' => $alertas
        ]);
    }
}
