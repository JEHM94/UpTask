<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php' ?>

    <form class="formulario" method="POST">
        <div class="campo">
            <label for="password_actual">Contraseña Actual</label>
            <input type="password" name="password_actual" placeholder="********">
        </div><!-- .campo -->

        <div class="campo">
            <label for="password_nuevo">Nueva Contraseña</label>
            <input type="password" name="password_nuevo" placeholder="********">
        </div><!-- .campo -->

        <div class="campo">
            <label for="password2">Repetir Contraseña</label>
            <input type="password" name="password2" placeholder="********">
        </div><!-- .campo -->

        <div class="btn-perfil">
            <a href="/perfil" class="enlace-perfil">Regresar</a>

            <input type="submit" value="Guardar Cambios">
        </div>
    </form>
</div> <!-- .contenedor-sm -->

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>