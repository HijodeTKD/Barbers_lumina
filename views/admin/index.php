<?php include_once __DIR__ . '/../templates/barra.php';?>

<h1 class="nombre-pagina">Citas</h1>
<p class="descripcion-pagina">Busca una cita</p>

<?php include_once __DIR__ . '/../templates/alertas.php';?>

<div class="buscador">
    <form class="formulario">
        <div class="campo margen">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
        </div>
    </form>
</div>

<?php 
    if(count($citas) === 0){ //If date selected has not "citas" show:
        echo "<h2>No existen citas en esta fecha</h2>";
    }
?>

<div id="citas-admin">
    <div class="citas">

        <?php 

        $citaid = '';  
        foreach($citas as $key => $cita):;
        if($cita->id !== $citaid):;
        $total = 0;

        ?>

        <div class="cita flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <p hidden>ID: <span><?php echo $cita->id ?></span></p>
                    <p class="hora">Hora: <span><?php echo substr("$cita->hora", 0, 5) ?></span></p>
                    <p class="fecha">Fecha: <span><?php echo $cita->fecha ?></span></p>
                    <p class="cliente">Cliente: <span><?php echo $cita->cliente ?></span></p>
                    <p class="email">Email: <span><?php echo $cita->email ?></span></p>
                    <p class="telefono">Teléfono: <span><?php echo $cita->telefono ?></span></p>
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

    <!-- Calc total price -->
    <?php 
        $actual = $cita->id;
        $siguiente = $citas[$key + 1]->id ?? 0; //Check if next id is diferent than previous id
        
        if(esUltimo($actual, $siguiente)): //Adds button and total price when last id element was generate
    ?>

            <p class="servicio precio-total" id="<?php echo $cita->id ?>">Total: <span><?php echo $total ?>€</span></p>
            <form action="/api/eliminar" method="POST">
                <input type="hidden" name="id" value="<?php echo $cita->id;?>">
                <input type="submit" class="boton-eliminar" value="Eliminar">
            </form>

        <?php

        endif;
        endforeach; 

        ?>
</div>

<?php
        $script = "<script src='build/js/buscador.js'></script>"
?>