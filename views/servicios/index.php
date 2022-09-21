<?php include_once __DIR__ . '/../templates/barra.php' ?>

<h1 class="nombre-pagina">Servicios disponibles</h1>
<p class="descripcion-pagina">Administración de servicios</p>

<?php include_once __DIR__ . '/../templates/alertas.php';?>

<div class="servicios">
    <?php foreach($servicios as $servicio): ?>

    <div class="servicio card">
        <div class="card-inner">
            <div class="card-front">
                <p class="nombre-servicio">Servicio: <span><?php echo $servicio->nombre ?></span></p>
                <p class="precio-servicio">Precio: <span><?php echo $servicio->precio ?></span></p>

                <div class="acciones">
                    <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id; ?>">Actualizar</a>
                </div>

                <div class="acciones">
                    <form class="formulario" action="/servicios/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $servicio->id ?>">
                        <input type="submit" value="Eliminar" class="boton-eliminar">
                    </form>
                </div>

            </div> <!--card-front -->
        </div> <!--card-inner -->
    </div><!--servicio card -->
    <?php endforeach; ?>
</div>