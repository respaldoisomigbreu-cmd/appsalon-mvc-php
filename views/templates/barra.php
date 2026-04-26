
<div class="barra">
    <p>Bienvenido, <b> <?php echo $nombre ?? ''; ?> </b></p>

    <a class="boton" href="/logout"> Cerrar Sesión </a>

</div>

<?php
SESSION_START();

if(isset($_SESSION['admin'])){ ?>
    <div class="barra-servicios">
        <a class="boton" href="/admin"> Ver Citas</a>
        <a class="boton" href="/servicios"> Ver Servicios</a>
        <a class="boton" href="/servicios/crear"> Nuevo Servicio</a>

    </div>

<?php } ?>