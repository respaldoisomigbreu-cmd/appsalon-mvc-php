<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-paginia">Restablece tu password escribiendo tu email a continuacion</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/olvide" method="POST" class="formulario">

    <div class="campo">
        <label for="email">E-mail</label>
        <input type="email" 
                id="email"
                placeholder="Tu E-mail"
                name="email"
                />
    </div>


        <input type="submit" value="Enviar Email" class="boton">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Secion</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear Cuenta</a>

</div>