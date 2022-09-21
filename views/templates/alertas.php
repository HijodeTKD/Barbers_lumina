<div class="alertas-contenedor">
    
<?php
foreach($alertas as $tipoalerta => $mensajes): 
    foreach($mensajes as $mensaje):
?>

<div class="alerta <?php echo $tipoalerta ?>"><?php echo $mensaje; ?></div>

<?php
    endforeach;
    endforeach;
?>

</div>