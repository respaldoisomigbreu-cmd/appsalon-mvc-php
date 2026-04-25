<h1 class="nombre-pagina">Recuperar Password</h1>

<p class="descripcion-pagina">Coloca Tu Nuevo Password a Continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; 


if($error) return;
?>

<form class="formulario" method="POST">
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tu Nuevo Password"
        />
    </div>
    <input type="submit" class="boton" value="Guardar Nuevo Password">
</form>

<div class="acciones">
    <a href="/"> ¿ Ya tienes Cuenta ? Iniciar Secion</a>
    <a href="/crear-cuenta"> ¿ Aún No Tines Cuenta ? Obtener Una</a>
</div>