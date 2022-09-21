<h1 class="nombre-pagina">He olvidado mi contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña escribiendo tu Email a continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<form action="/olvide-password" method="POST" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" id="email" placeholder="Tu email" name="email">
    </div>
    <input type="submit" value="Enviar instrucciones al Email" class="boton">
</form>


<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿No tienes una cuenta? Crea una</a>
</div>