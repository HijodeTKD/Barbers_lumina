<?php include_once __DIR__ . '/../templates/barra.php' ?>
<h1 class="nombre-pagina">Crear una nueva Cita</h1>
<p class="descripcion-pagina">Elige los servicios y establece una fecha</p>

<div id="app" class="app">
    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Fecha y hora</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>
    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación (Max. 6)</p>
        <div class="alertas-servicios"></div>
        <div id="servicios" class="listado-servicios"></div> <!-- JS API -->
    </div>
    <div id="paso-2" class="seccion">
        <h2>Información de la cita</h2>
        <p class="text-center">Coloca tus datos y fecha de tu cita</p>
        <div class="alertas-contenedor"></div>
        <form class="formulario">

            <div class="campo">
                <label >Nombre</label>
                <input type="text" id="nombre" placeholder="<?php echo $nombre ?>" disabled >
            </div>

            <div class="campo">
                <label for="fecha">Fecha</label>
                <input type="date" min="<?php echo date('Y-m-d', strtotime('+1 day'))?>" id="fecha">
            </div>

            <div class="campo">
                <label for="hora">Fecha</label>
                <input type="time" id="hora">
            </div>
            <input type="hidden" id="id" value="<?php echo $id ?>">
        </form>
    </div>
    <div id="paso-3" class="seccion">
        <h2>Resumen</h2>
        <p class="text-center">Comprueba que los datos sean correctos.</p>
        <div class="alertas-resumen"></div>
        <div class="contenedor-servicios"></div>
        <div class="contenedor-datos"></div>
    </div>
    <div class="paginacion">
        <button class="boton" id="anterior" >&laquo; Anterior</button>
        <button class="boton" id="siguiente" >Siguiente &raquo;</button>
    </div>
</div>

<?php 

$script = "
<script src='//cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script src='build/js/app.js'></script>
";

?>