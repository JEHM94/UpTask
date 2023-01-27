<div class="contenedor reestablecer">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Ingresa tu nueva Contraseña</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <?php if (!empty($usuario) && $usuario->confirmado) : ?>

            <form class="formulario" method="POST">
                <div class="campo">
                    <label for="password">Nueva Contraseña</label>
                    <input type="password" id="password" placeholder="******" name="password" />
                </div><!-- .campo -->

                <div class="campo">
                    <label for="password2">Repetir Contraseña</label>
                    <input type="password" id="password2" placeholder="******" name="password2" />
                </div><!-- .campo -->

                <input type="submit" class="boton" value="Cambiar Contraseña">

            </form>

        <?php endif;  ?>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una</a>
            <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
        </div><!-- .acciones -->
    </div><!-- .contenedor-sm -->
</div><!-- .contenedor -->