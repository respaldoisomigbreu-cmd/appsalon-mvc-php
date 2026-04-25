<h1 class="nombre-pagina">Crear Nuevas Citas</h1>
<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>

    <?php
        include_once __DIR__ . '/../templates/barra.php';
    // 1. Configurar la zona horaria de Venezuela
    date_default_timezone_set('America/Caracas'); 
    ?>



<div id="app">

    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Informacion Citas</button>
        <button type="button" data-paso="3">Resumen</button>

    </nav>

    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tu servicios a continuacion</p>
        <div id="servicios" class=" listado-servicios"></div>
    </div>

    <div id="paso-2" class="seccion">
        <h2>Tus Datos Y Citas</h2>
        <p class="text-center">coloca tus datos y fecha de tu cita</p>

        <form class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"    
                    name="nombre" 
                    id="nombre"
                    placeholder="Tu Nombre"
                    value="<?php echo $nombre ; ?>"
                    disabled
                    />
            </div>
        
            <div class="campo">
                <label for="fecha">Fecha</label>
                <input 
                    type="date"    
                    name="fecha" 
                    id="fecha"
                    min="<?php echo date('Y-m-d'); ?>"
                    value="<?php echo date('Y-m-d'); ?>"                    
                    />
            </div>

            <div class="campo">
                <label for="hora">Hora</label>
                <input 
                    type="time"    
                    name="hora" 
                    id="hora"
                    value="<?php echo date('H').':00'; ?>"
                    min="10:00"
                    max="18:00"
                    step="3600"          
                    onclick="this.showPicker()"       
                    />
            </div>
            <input type="hidden" id="id" value="<?php echo $id; ?>">

        </form>
    </div>
    
    <div id="paso-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la Informacion sea correcta</p>
    </div>
    <div class="paginacion">
        <button
            id="anterior"
            class="boton"
        >&laquo; Anterior</button>
        <button
            id="siguiente"
            class="boton"
        > Siguente &raquo; </button>
    </div>
</div>

<?php 
    $script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script src='build/js/app.js'></script>
    ";

?>