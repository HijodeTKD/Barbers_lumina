<?php include_once __DIR__ . '/../templates/barra.php';?>

<h1 class="nombre-pagina">Mis citas</h1>
<p class="descripcion-pagina">Estas son tus siguientes citas</p>

<?php include_once __DIR__ . '/../templates/alertas.php';?>

<div id="citas-admin">
    <div class="citas">

        <?php 

        $citaid = '';  
        foreach($citas as $key => $cita):;
        if($cita->id !== $citaid):;
        $total = 0;

        ?>

        <div class="mis-citas flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front card-small">
                    <p hidden>ID: <span><?php echo $cita->id ?></span></p>
                    <p class="hora">Hora: <span><?php echo substr("$cita->hora", 0, 5) ?></span></p>
                    <p class="fecha">Fecha: <span><?php echo $cita->fecha ?></span></p>
                    <p class="nota">Pulsa aqui para ver los servicios solicitados*</span></p>
                </div> <!-- flip-card-front -->
                    <div id="<?php echo $cita->id ?>" class="flip-card-back">
                    </div> <!-- /flip-card-back -->
            </div> <!-- /flip-card-inner -->
        </div><!--/flip-card -->
    <?php 

        $citaid = $cita->id;
        endif; 
        $total += $cita->precio;

    ?>
    <!-- Generates html for "servicios", javascripts adds to back-card -->
    <p class="servicio" id="<?php echo $cita->id ?>"><?php echo $cita->servicio . ": " . "<span>" . $cita->precio . "€" . "</span>" ?></p>

    <?php 
        $actual = $cita->id;
        $siguiente = $citas[$key + 1]->id ?? 0; //Check if next id is diferent than previous id
        
        if(esUltimo($actual, $siguiente)): ?>
            <p class="servicio precio-total" id="<?php echo $cita->id ?>">Total: <span><?php echo $total ?>€</span></p>
            <form action="/mis-citas" method="POST">
                <input type="hidden" name="id" value="<?php echo $cita->id;?>">
                <input type="submit" class="boton-eliminar" value="Anular cita">
            </form>

        <?php

        endif;
        endforeach; 

        ?>        
</div>