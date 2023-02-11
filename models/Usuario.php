<?php

namespace Model;

use Classes\Email;

class Usuario extends ActiveRecord
{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    public function __construct($arr = [])
    {
        $this->id = $arr['id'] ?? null;
        $this->nombre = $arr['nombre'] ?? '';
        $this->email = $arr['email'] ?? '';
        $this->password = $arr['password'] ?? '';
        $this->password2 = $arr['password2'] ?? null;
        $this->password_actual = $arr['password_actual'] ?? '';
        $this->password_nuevo = $arr['password_nuevo'] ?? '';
        $this->token = $arr['token'] ?? '';
        $this->confirmado = $arr['confirmado'] ?? 0;
    }

    public function validar($tipoValidacion = null) : array
    {
        switch ($tipoValidacion) {
                // Casos de Validaciones
            case CUENTA_NUEVA:
                if (!$this->nombre) {
                    self::$alertas[ERROR][] = 'El nombre es obligatorio';
                }

                // Validación de E-mail de Usuario
                if (!$this->email) {
                    self::$alertas[ERROR][] = 'Debe Ingresar su E-mail';
                } else {
                    // Verifica que sea un email válido
                    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                        self::$alertas[ERROR][] = 'E-mail no válido';
                    }
                }

                if (!$this->password) {
                    self::$alertas[ERROR][] = 'La contraseña es obligatoria';
                } else if (strlen($this->password) < 6) {
                    self::$alertas[ERROR][] = 'La contraseña debe contener almenos 6 caracteres';
                } else if ($this->password !== $this->password2) {
                    self::$alertas[ERROR][] = 'Las contraseñas no coinciden';
                }

                return self::$alertas;

            case CUENTA_EXISTENTE:
                // Validación de E-mail de Usuario
                if (!$this->email) {
                    self::$alertas[ERROR][] = 'Debe Ingresar su E-mail';
                } else {
                    // Verifica que sea un email válido
                    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                        self::$alertas[ERROR][] = 'E-mail no válido';
                    }
                }
                // Validación de Contraseña de Usuario
                if (!$this->password) {
                    self::$alertas[ERROR][] = 'Debe Ingresar una Contraseña';
                }
                return self::$alertas;

            case RECUPERAR_CUENTA:
                // Validación de E-mail de Usuario
                if (!$this->email) {
                    self::$alertas[ERROR][] = 'Debe Ingresar su E-mail';
                } else {
                    // Verifica que sea un email válido
                    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                        self::$alertas[ERROR][] = 'E-mail no válido';
                    }
                }

                return self::$alertas;

            case CAMBIAR_PASSWORD:
                // Validación de Contraseña de Usuario
                if (!$this->password) {
                    self::$alertas[ERROR][] = 'Debe Ingresar una Contraseña';
                } else if (strlen($this->password) < 6) {
                    self::$alertas[ERROR][] = 'La Contraseña debe tener un mínimo de 6 caracteres';
                } else if ($this->password !== $this->password2) {
                    self::$alertas[ERROR][] = 'Las contraseñas no coinciden';
                }

                return self::$alertas;

            case NUEVO_PASSWORD:
                // Validación de cambio Contraseña
                if (!$this->password_actual) {
                    self::$alertas[ERROR][] = 'Debe Ingresar su Contraseña actual';
                }
                if (!$this->password_nuevo) {
                    self::$alertas[ERROR][] = 'Debe Ingresar una Nueva Contraseña';
                } else if (strlen($this->password_nuevo) < 6) {
                    self::$alertas[ERROR][] = 'La nueva Contraseña debe tener un mínimo de 6 caracteres';
                } else if ($this->password_nuevo !== $this->password2) {
                    self::$alertas[ERROR][] = 'La contraseña nueva no coincide';
                }

                return self::$alertas;

            case ACTUALIZAR_PERFIL:
                if (!$this->nombre) {
                    self::$alertas[ERROR][] = 'Su nombre no puede estar vacío';
                }

                // Validación de E-mail de Usuario
                if (!$this->email) {
                    self::$alertas[ERROR][] = 'Su E-mail no puede estar vacío';
                } else {
                    // Verifica que sea un email válido
                    if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                        self::$alertas[ERROR][] = 'E-mail no válido';
                    }
                }

                return self::$alertas;

            default:
                return null;
        }
    }

    // Comprueba el password ingresado con el actual
    public function comprobarPassword() : bool
    {
        return password_verify($this->password_actual, $this->password);
    }

    // Hashea el Password
    public function hashPassword() : void
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Genera token para confirmación de cuenta
    public function crearToken() : void
    {
        $this->token = uniqid();
        // Alternativa 32 caracteres
        //$this->token = md5(uniqid());
    }
}
