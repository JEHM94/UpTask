<div class="contenedor reestablecer">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Ingresa tu nueva Contraseña</p>

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
        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una</a>
            <a href="/olvide">Olvidaste tu Contraseña?</a>
        </div><!-- .acciones -->
    </div><!-- .contenedor-sm -->
</div><!-- .contenedor -->