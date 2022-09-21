<div class="barra">
    <p> Hola, <?php echo $nombre ?></p>
    <a href="/logout">Cerrar Sesión</a>
</div>

<?php if(isset($_SESSION['admin'])){ ?>

        <div class="barra-nav">
            <a class="nav" href="/admin">Ver citas</a>
            <a class="nav" href="/servicios">Ver servicios</a>
            <a class="nav" href="/servicios/crear">Nuevo servicio</a>
        </div>

<?php }else{ ?>

        <div class="barra-nav">
            <a class="nav" href="/cita">Nueva cita</a>
            <a class="nav" href="/mis-citas">Mis citas</a>
        </div>

<?php } ?>