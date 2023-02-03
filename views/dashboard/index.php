<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<?php if (count($proyectos) === 0) : ?>
    <div class="no-proyectos">
        <p>No se han encontrado Proyectos...</p>
        <a href="/crear-proyecto">Crear Proyecto</a>
    </div>

<?php else : ?>
    <ul class="listado-proyectos">
        <?php foreach ($proyectos as $proyecto) : ?>
            <li class="proyecto">
                <a href="/proyecto?url=<?php echo $proyecto->url; ?>">
                    <?php echo $proyecto->proyecto; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>