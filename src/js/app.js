let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita ={
    id: '',
    nombre: '',
    fecha: '',
    hora:'',
    servicios: []
}


document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});


function iniciarApp(){
    mostrarSeccion();                // muesta y oculta las secciones 
    tabs();                          //cambia la seccion cuando se presenten los tabs
    botonesPaginador();              //agrega o quita los botones del paginador
    paginaAnterior();               
    paginaSiguiente();
    consultarAPI();                 //consulta la api de php para mostrar los servicios
    idCliente();                   //agrega el id del cliente a la cita
    nombreCliente();                //agrega el nombre del cliente a la cita
    seleccionarFecha();              //agrega la fecha de la cita
    seleccionarHora();               //agrega la hora de la cita    
    muestraResumen();                //muestra el resumen de la cita
}

function mostrarSeccion(){
    //ocultar la seccion que tenga la clase Mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }
    //seleccionar la seccion con el paso 
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    //quita la clase actual al tabs anterios
    const tabsAnterior = document.querySelector('.actual');
    if(tabsAnterior){
        tabsAnterior.classList.remove('actual');
    }

    //resalta el tabs actual
    const claseTabs = `[data-paso="${paso}"]`
    const tab = document.querySelector(claseTabs);
    tab.classList.add('actual');
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach( boton => {
        boton.addEventListener('click', function(e){
            paso = parseInt (e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
        });
    })
}

function botonesPaginador(){
    const paginaAnterior = document.querySelector('#anterior');
    const paginaSiguiente = document.querySelector('#siguiente');

    mostrarSeccion();

    if(paso === 1){
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if(paso === 3){
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.add('ocultar');
        muestraResumen();
    } else{
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
}
function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function(){
        if(paso <= pasoInicial) return;
        paso--;
        botonesPaginador();
    })
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function(){
        if(paso >= pasoFinal) return;
        paso++;
        botonesPaginador();
    })
}

async function consultarAPI(){
    try {
        const url = '/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }

}

function mostrarServicios(servicios){
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio;  
        //DOM Scripting
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;

        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function(){
            seleccionarServicio(servicio);
        };

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);
        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

function seleccionarServicio(servicio){
    const{ id } = servicio;
    const{ servicios} = cita;
    //identificar el servicio que se le dio click
    const servicioDiv = document.querySelector(`[data-id-servicio="${id}"]`);


    //identificar si el servicio ya esta agregado
    if(servicios.some( agregado => agregado.id === id)){
        //eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id);
        servicioDiv.classList.remove('seleccionado');

    } else{
        cita.servicios = [...servicios, servicio];
        servicioDiv.classList.add('seleccionado');
    }
    console.log(cita);
}
function idCliente(){
    cita.id = document.querySelector('#id').value;

   // console.log(id);
}

function nombreCliente(){
    cita.nombre = document.querySelector('#nombre').value;

   // console.log(nombre);
}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');

    inputFecha.addEventListener('input', function(e){
        const seleccionada = e.target.value;
        const dia = new Date(seleccionada).getUTCDay();
        const hoy = new Date().toLocaleDateString('en-CA', {
            timeZone: 'America/Caracas'
        });
        // Validar que no se puedan seleccionar los dias domingos
        if([0].includes(dia)){                  
            e.target.value = '';
            mostrarAlertas('Fines de semana no permitidos', 'error', '.formulario');
            return;
        }
        // Validar que no se puedan seleccionar el dia actual
        if(seleccionada <= hoy){                    
            e.target.value = '';
            mostrarAlertas('No puede seleccionar el dia actual', 'error', '.formulario');
            return;
        }
            cita.fecha = e.target.value;
            //console.log(cita);
    });
}

function seleccionarHora(){
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e){
        const horaCita = e.target.value;
        const hora = horaCita.split(':')[0];

        if(hora < 10 || hora > 18){
            e.target.value = '';
            mostrarAlertas('Hora no valida', 'error' , '.formulario');
        } else{
            cita.hora = e.target.value;
            //console.log(cita);
        }   
    });
}

function mostrarAlertas(mensaje, tipo, elemento, desaparecer = true){
    // si ya hay una alerta, no crear otra
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }
    //crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    
    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    //eliminar la alerta despues de 3 segundos
    if (desaparecer) {
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}

function muestraResumen(){
    const resumen = document.querySelector('.contenido-resumen');

    //limpiar el resumen previo
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlertas('Faltan datos de servicios, Fecha u hora', 'error', '.contenido-resumen', false);
        return;
    }
    
   //crear el resumen
    const {nombre, fecha, hora, servicios} = cita;


    //header para los servicios
    const headerServicios = document.createElement('H3');
    headerServicios.textContent = 'Servicios Solicitados';
    resumen.appendChild(headerServicios);

    //crear un contenedor para los servicios
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('resumen-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);

    });

        //header para las citass
    const headerCitas = document.createElement('H3');
    headerCitas.textContent = 'Cita Agendada';
    resumen.appendChild(headerCitas);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`; 
    
    //formatear la fecha a un formato mas legible
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth() ;                // Los meses en JavaScript son base 0
    const dia = fechaObj.getDate() +2 ;                  // Obtener el día del mes
    const year = fechaObj.getFullYear();                // Obtener el año

    const fechaUTC = new Date(Date.UTC(year, mes, dia));            // Crear un objeto Date en UTC
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };  // Opciones para formatear la fecha
    const fechaFormateada = fechaUTC.toLocaleDateString('es-ES', opciones);   // Formatear la fecha en español (España)
    console.log(fechaFormateada);

    const fechaCliente = document.createElement('P');
    fechaCliente.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`; 

    const horaCliente = document.createElement('P');
    horaCliente.innerHTML = `<span>Hora:</span> ${hora} Horas`; 

    //boton para confirmar la cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Resevar Cita';
    botonReservar.onclick = reservarCita;


    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCliente);
    resumen.appendChild(horaCliente);

    resumen.appendChild(botonReservar);

}

    async function reservarCita(){
        const {id, fecha, hora, servicios} = cita;
   //     const idServicios = servicios.map(servicio => servicio.id).join(', '); //obtener los ids de los servicios, separados por comas
        const idServicios = servicios.map(servicio => servicio.id); //obtener los ids de los servicios, separados por comas

        const datos = new FormData();           //FormData es una clase que nos permite crear un objeto con los datos de la cita, que luego podemos enviar al servidor
        datos.append('fecha', fecha);
        datos.append('hora', hora);
        datos.append('usuarioId', id);
        datos.append('servicios', idServicios); //enviar solo los ids de los servicios, separados por comas


        try {
             //peticion hacia la api de php para guardar la cita
        const url = '/api/citas';
        const repspuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        
        const resultado = await repspuesta.json();
        console.log(resultado.resultado);

        if(resultado.resultado){
            Swal.fire({
                icon: "success",
                title: "Cita Reservada",
                text: "Tu cita ha sido reservada exitosamente!",
                button: 'ok'
            }).then(() => {
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            })
        }
        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Hubo un error al reservar tu cita, por favor intenta de nuevo",
                button: 'ok'
            }).then(() => {
                    window.location.reload();               
            })
        }       

        //console.log([...datos]);             //formatear el FormData a un array para poder verlo en la consola
}