<?php
define('CUENTA_NUEVA', 'cuenta_nueva');
define('CUENTA_EXISTENTE', 'cuenta_existente');
define('RECUPERAR_CUENTA', 'recuperar_cuenta');
define('CAMBIAR_PASSWORD', 'cambiar_password');
define('NUEVO_PASSWORD', 'nuevo_password');
define('ACTUALIZAR_PERFIL', 'actualizar_perfil');

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
