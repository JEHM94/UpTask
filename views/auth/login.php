<div class="contenedor">
    <h1>UpTask</h1>
    <p class="tagline">Crea y Administra tus Proyectos</p>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <form class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="nombre@dominio.com" name="email" />
            </div><!-- .campo -->

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="******" name="password" />
            </div><!-- .campo -->

            <input type="submit" class="boton" value="Iniciar Sesión">

        </form>
        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Crea una</a>
            <a href="/olvide">Olvidaste tu Contraseña?</a>
        </div><!-- .acciones -->
    </div><!-- .contenedor-sm -->
</div><!-- .contenedor -->