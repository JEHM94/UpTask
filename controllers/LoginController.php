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
            // Instanciamos un nuevo usuario con los datos del formulario
            $usuario = new Usuario($_POST);
            // Validamos los datos del formulario
            $alertas = $usuario->validar(CUENTA_EXISTENTE);

            if (empty($alertas)) {
                // Comprueba que el Email exista
                $usuario = Usuario::where('email', $usuario->email);

                if (!$usuario || !$usuario->confirmado) {
                    // Alerta de Usuario no encontrado
                    Usuario::setAlerta(ERROR, 'El E-mail no se encuentra registrado o no ha sido confirmado');
                } else {
                    // El usuario existe, procede a comprobar su contraseña
                    if (!password_verify($_POST['password'], $usuario->password)) {
                        // Contraseña incorrecta
                        Usuario::setAlerta(ERROR, 'Contraseña incorrecta');
                    } else {
                        // Autenticamos al usuario
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamos al Módilo de Proyectos
                        header('Location: /dashboard');
                    }
                }
            }
        }

        // Obtenemos las alertas para pasarlas a la vista
        $alertas = Usuario::getAlertas();
        // Render a la Vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        $_SESSION = [];
        header('Location: /');
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
                        if ($email->enviarConfirmacion(CUENTA_NUEVA)) {
                            // Redireccionamos
                            header('Location: /mensaje');
                        }
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
                // Busca el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if ($usuario && $usuario->confirmado === '1') {
                    // Elimina password2 para evitar errores al guardar en DB
                    unset($usuario->password2);
                    // Genera un nuevo token 
                    $usuario->crearToken();
                    // Actualiza el Usuario
                    if ($usuario->guardar()) {
                        // Envia E-mail de Recuperación de contraseña
                        $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                        $email->enviarConfirmacion(RECUPERAR_CUENTA);
                        // Genera Alerta
                        Usuario::setAlerta(EXITO, 'Hemos enviado un e-mail con las intrucciones para reestablecer tu contraseña');
                    }
                } else {
                    Usuario::setAlerta(ERROR, 'El usuario no existe o no está confirmado');
                }
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
        // Obtiene el token ingresado por la URL
        $token = s($_GET['token'] ?? '');
        // Si no hay ningún token, redirecciona a /
        if (!$token) header('Location: /');

        // Busca al usuario con el token ingresado
        $usuario = Usuario::where('token', $token);

        // Genera alerta Si no hay ningún usuario o si no está confirmado
        if (empty($usuario) || $usuario->confirmado === '0') {
            // No se encontró un usuario con ese token
            Usuario::setAlerta(ERROR, 'Token no válido');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($usuario) {
                // Añadimos el Nuevo Password
                $usuario->sincronizar($_POST);
                // Validamos el Password
                $alertas = $usuario->validar(CAMBIAR_PASSWORD);

                if (empty($alertas)) {
                    // Hashea el nuevo Password
                    $usuario->hashPassword();
                    // Elimina password2 para evitar errores al guardar en DB
                    unset($usuario->password2);
                    // Elimina el token
                    $usuario->token = null;
                    // Guardamos cambios
                    if ($usuario->guardar()) {
                        // Creamos Alerta
                        Usuario::setAlerta(EXITO, 'Contraseña cambiada Exitosamente');
                        // Eliminamos los datos en memoria
                        unset($usuario);
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        // Render a la Vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Contraseña',
            'usuario' => $usuario ?? '',
            'alertas' => $alertas
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
        $token = s($_GET['token'] ?? '');
        // Si no hay ningún token, redirecciona a /
        if (!$token) header('Location: /');

        // Busca al usuario con el token ingresado
        $usuario = Usuario::where('token', $token);

        // Genera alerta Si no hay ningún usuario o si ya está confirmado
        if (empty($usuario) || $usuario->confirmado === '1') {
            // No se encontró un usuario con ese token
            Usuario::setAlerta(ERROR, 'Token no válido');
        } else {
            // Usuario Encontrado y no ha Sido confirmado aún
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
