<?php
define('CUENTA_NUEVA', 'cuenta_nueva');
define('CUENTA_EXISTENTE', 'cuenta_existente');
define('RECUPERAR_CUENTA', 'recuperar_cuenta');
define('CAMBIAR_PASSWORD', 'cambiar_password');

// Nombre de clase css para Alertas
define('ERROR', 'error');
define('EXITO', 'exito');
define('NEUTRAL', 'neutral');

function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

// Función que revisa que el usuario este autenticado
function isAuth(): void
{
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    }
}
