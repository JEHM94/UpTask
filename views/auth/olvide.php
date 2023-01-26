<div class="contenedor olvide">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu Contraseña</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

        <form class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="email@dominio.com" name="email" />
            </div><!-- .campo -->

            <div class="right">
                <input type="submit" class="boton" value="Recuperar Contraseña">
            </div>

        </form>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una</a>
            <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
        </div><!-- .acciones -->
    </div><!-- .contenedor-sm -->
</div><!-- .contenedor -->