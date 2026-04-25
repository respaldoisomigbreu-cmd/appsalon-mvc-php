<h1 class="nombre-pagina">Nuevo Servicio</h1>
<p class="descripcion-pagina">completa los campos para crear un nuevo servicio</p>

<?php   
    include_once __DIR__ . '/../templates/barra.php'; 
    include_once __DIR__ . '/../templates/alertas.php'; 
?>

<form action="/servicios/crear" method="POST" class="formulario">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <input type="submit" value="Guardar Servicio" class="boton">

</form>