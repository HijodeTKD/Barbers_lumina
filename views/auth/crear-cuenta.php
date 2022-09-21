<h1 class="nombre-pagina">Crear Cuenta</h1>
<p class="descripcion-pagina">Completa el siguiente formulario para crear tu cuenta</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<form action="/crear-cuenta" method="POST" class="formulario">

    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" placeholder="Tu nombre" value="<?php echo s($usuario->nombre); //Rellenado automatico de lo que ya hemos escrito en caso de error ?>">
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input type="text" name="apellido" id="apellido" placeholder="Tu apellido" value="<?php echo s($usuario->apellido); ?>">
    </div>
    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input type="tel" name="telefono" id="telefono" placeholder="Tu teléfono" value="<?php echo s($usuario->telefono); ?>">
    </div>
    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" placeholder="Tu email" value="<?php echo s($usuario->email); ?>">
    </div>
    <div class="campo">
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Tu contraseña" value="">
    </div>
    <div class="campo">
        <label for="confirmar-password">Confirmar contraseña</label>
        <input autocomplete="off" type="password" name="confirmar-password" id="confirmar-password" placeholder="Confirma tu contraseña" value="">
    </div>
    <input type="submit" value="Crear Cuenta" class="boton">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/olvide-password">No recuerdo mi contraseña</a>
</div>