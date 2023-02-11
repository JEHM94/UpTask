<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php' ?>

    <form class="formulario" method="POST">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo $usuario->nombre ?>" placeholder="<?php echo $_SESSION['nombre']; ?>">
        </div><!-- .campo -->

        <div class="campo">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" value="<?php echo $usuario->email ?>" placeholder="<?php echo $_SESSION['email']; ?>">
        </div><!-- .campo -->

        <div class="btn-perfil">
            <a href="/perfil/cambiar-password" class="enlace-perfil">Cambiar Contraseña</a>

            <input type="submit" value="Guardar Cambios">
        </div>
    </form>
</div> <!-- .contenedor-sm -->

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>