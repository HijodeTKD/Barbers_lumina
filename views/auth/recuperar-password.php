<h1 class="nombre-pagina">Recuperar Password</h1>
<p class="descripcion-pagina" >Coloca tu nueva contraseña a continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<?php if($error){return;} ?>

<?php
    if(!$reestablecido): ?>
<form class="formulario" method="POST" > <!-- Remove "action", because will remove the url token when form will send-->
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu nueva contraseña" value="">
    </div>
    <div class="campo">
        <label for="confirmar-password">Confirmar contraseña</label>
        <input autocomplete="off" type="password" name="confirmar-password" id="confirmar-password" placeholder="Confirma tu nueva contraseña" value="">
    </div>
    <input type="submit" class="boton" value="Reestablecer mi contraseña">
</form>
<?php endif; ?>

<?php
    if($reestablecido): ?>
        <div class="acciones accion-unica">
            <a href="/">¡Es hora de iniciar sesion!</a>
        </div>
<?php endif; ?>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión.</a>
    <a href="/crear-cuenta">¿No tienes una cuenta? Crea una</a>
</div>