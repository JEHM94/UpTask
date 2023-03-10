// Estructura de Función IIFE
// Todo lo que se encuentre dentro de esta función
// solo puede ser accedido desde este mismo archivo
// Y no desde otros archivos .js
// Esto para evitar que las variables y funciones se
// Mezclen con otras
(function () {
    // Arreglo de tareas
    let tareas = [];
    // Tareas Filtradas
    let filtradas = [];
    // URL del Sitio
    const url = `${location.origin}`;

    // Obtiene la url del Proyecto Actual
    const proyectoParams = new URLSearchParams(window.location.search);
    const proyecto = Object.fromEntries(proyectoParams.entries());

    // Busca las tareas del Proyecto seleccionado
    obetenerTareas();

    // Botón para mostrar el Modal de Agregar Tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    // Asignamos el evento click
    nuevaTareaBtn.addEventListener('click', function () {
        mostrarFormulario();
    });

    // Filtros de búsqueda
    const filtros = document.querySelectorAll("#filtros input[type='radio']");
    filtros.forEach(radio => {
        radio.addEventListener('input', filtrarTareas);
    });

    function filtrarTareas(e) {
        const filtro = e.target.value;

        if (filtro !== '') {
            filtradas = tareas.filter(tarea => tarea.estado === filtro);
        } else {
            filtradas = [];
        }

        mostrarTareas();
    }

    async function obetenerTareas() {

        // EndPoint Para obtener las Tareas del proyecto
        const endPoint = url + `/api/tareas?url=${proyecto.url}`;
        try {

            const respuesta = await fetch(endPoint);
            const resultado = await respuesta.json();

            tareas = resultado.tareas;

            mostrarTareas();

        } catch (error) {

        }
    }

    function mostrarTareas() {
        // Limpia las tareas previas si existen
        limpiarTareas();
        // Desactiva los filtros si no hay tareas completadas o pendientes
        desactivarRadio();

        // Asigna el array de tareas (filtradas o todas)
        const arrayTareas = filtradas.length ? filtradas : tareas;

        const contenedorTareas = document.querySelector('#listado-tareas');

        // Verifica si existen tareas para mostrar
        if (arrayTareas.length === 0) {
            // Scripting para mostrar el texto de No hay tareas 
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No Hay Tareas Disponibles';
            textoNoTareas.classList.add('sin-tareas');
            // Agrega el LI creado al contenedor de Tareas
            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estadosTarea = {
            0: 'Pendiente',
            1: 'Completa'
        }
        // Muestra las Tareas
        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');

            // Párrafo nombre de la Tarea
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            nombreTarea.ondblclick = function () {
                mostrarFormulario(true, { ...tarea });
            }

            // DIV para las opciones
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            // Botón Estado de la Tarea
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estadosTarea[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estadosTarea[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function () {
                // {...tarea} Crea una copia del objeto
                // En memoria para ser modificada, evitando
                // que el objeto original se modifique antes
                // de haber hecho la petición al servidor
                actualizarTarea({ ...tarea }, true);
            }

            // Botón para Eliminar Tarea
            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.ondblclick = function () {
                confirmacionEliminarTarea({ ...tarea });
            }

            // Agrega los Elementos creados
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            contenedorTareas.appendChild(contenedorTarea);
        });
    }

    function desactivarRadio() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0");
        const pendientesRadio = document.querySelector('#pendientes');

        pendientesRadio.disabled = (totalPendientes.length === 0) ? true : false;

        const totalCompletadas = tareas.filter(tarea => tarea.estado === "1");
        const completadasRadio = document.querySelector('#completadas');

        completadasRadio.disabled = (totalCompletadas.length === 0) ? true : false;
    }

    function mostrarFormulario(editar = false, tarea = {}) {
        // Creamos el modal
        const modal = document.createElement('DIV');
        // Asigna la clase modal
        modal.classList.add('modal');
        // Inserta el HTML al modal
        modal.innerHTML = `
        <form class="formulario nueva-tarea">
            <legend>${editar ? 'Editar Tarea' : 'Añade una nueva Tarea'}</legend>
            <div class="campo">
                <label for="tarea">Tarea</label>
                <input 
                    type="text"
                    name="tarea"
                    id="tarea"
                    placeholder="${tarea.nombre ? tarea.nombre : 'Añade una tarea al Proyecto Actual'}"
                    value="${tarea.nombre ? tarea.nombre : ''}"
                />
            </div>
            <div class="opciones">
                <button type="button" class="cerrar-modal">Cancelar</button>
                <input 
                    type="submit"
                    class="submit-nueva-tarea"
                    value="${editar ? 'Guardar Cambios' : 'Añadir Tarea'}"
                 />
            </div>
        </form>
        `;

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 10);

        // Evento para cerrar modal
        modal.addEventListener('click', function (e) {
            // Quitam el comportamiento por defecto del botón submit
            e.preventDefault();
            // valida si se dio click al boton de cancelar o fuera del formulario para cerrar el modal
            if (e.target.classList.contains('cerrar-modal') || e.target.classList.contains('modal')) {

                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');

                setTimeout(() => {
                    modal.remove();
                }, 400);
            }

            if (e.target.classList.contains('submit-nueva-tarea')) {
                // Selecciona el valor del input de tarea y elimina los espacios al inicio y al final con trim
                const nombreTarea = document.querySelector('#tarea').value.trim();

                // Valida el formulario
                if (nombreTarea === '') {
                    const referencia = document.querySelector('.formulario legend');
                    // Si no ingresó ninguna tarea muestra alerta de error
                    mostrarAlerta('El nombre de la Tarea es Obligatorio', 'error', referencia);
                    return;
                }

                // Edita la tarea actual
                if (editar) {
                    tarea.nombre = nombreTarea;
                    actualizarTarea(tarea);
                    return;
                }

                // Crea Una nueva Tarea
                agregarTarea(nombreTarea);
            }
        });

        // Agrega la ventana modal 
        document.querySelector('.dashboard').appendChild(modal);
    }

    function mostrarAlerta(mensaje, tipo, referencia) {
        // Previene la creación de múltiples alertas
        const alertaPrevia = document.querySelector('.alerta');
        if (alertaPrevia) alertaPrevia.remove();

        // Crea el elememento DiV para la alerta
        const alerta = document.createElement('DIV');
        // Assigna la classes de alerta + error/exito
        alerta.classList.add('alerta', tipo);
        // Asigna el mensaje de la alerta
        alerta.textContent = mensaje;
        // Agrega la alerta al elemento de referencia
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

        // Remueve la alerta luego de 5 segundos
        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }

    async function agregarTarea(tarea) {
        // Contruye la petición
        const datos = new FormData();
        datos.append('nombre', tarea);
        datos.append('proyectoUrl', proyecto.url);

        try {
            // Endpoint para agregar tarea
            const endPoint = url + '/api/tarea';
            // Crea la petición
            const respuesta = await fetch(endPoint, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

            const referencia = document.querySelector('.formulario legend');
            // Muestra alerta de la Respuesta proviniente de la API
            mostrarAlerta(resultado.mensaje, resultado.tipo, referencia);

            if (resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 2000);

                // Agregamos el Objeto de Tarea al Global de tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }

                tareas = [...tareas, tareaObj];

                // Aplica Virtual DOM para refrescar la vista
                // y mostrar la nueva tarea sin consultar el servidor
                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }
    }

    async function actualizarTarea(tarea, cambiarEstado = false) {

        if (cambiarEstado) {
            // Cambia el Estado de la tarea
            const nuevoEstado = tarea.estado === '1' ? '0' : '1';
            tarea.estado = nuevoEstado;
        }

        // Extrae los Datos del objeto para hacer la petición
        const { estado, id, nombre } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoUrl', proyecto.url);

        try {
            // Endpoint para agregar tarea
            const endPoint = url + '/api/tarea/actualizar';

            const respuesta = await fetch(endPoint, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

            if (resultado.tipo === 'exito') {

                const modal = document.querySelector('.modal');
                if (modal) modal.remove();

                Swal.fire(resultado.mensaje, '', 'success');

                tareas = tareas.map(tareaMemoria => {
                    if (tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria;
                });

                mostrarTareas();
            }

        } catch (error) {
            console.log(error);
        }

    }

    function confirmacionEliminarTarea(tarea) {
        Swal.fire({
            title: `¿Está seguro que desea eliminar esta tarea?<br><br>${tarea.nombre}`,
            showCancelButton: true,
            confirmButtonText: 'Si, eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        })
    }

    async function eliminarTarea(tarea) {
        // Extrae los Datos del objeto para hacer la petición
        const { estado, id, nombre } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoUrl', proyecto.url);

        try {
            // Endpoint para eliminar tareas
            const endPoint = url + '/api/tarea/eliminar';

            const respuesta = await fetch(endPoint, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();

            if (resultado.tipo === 'exito') {
                // Trae las tareas que sean distintas a la que se acaba de eliminar
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();

                Swal.fire(resultado.mensaje, '', 'success')
            }

        } catch (error) {
            console.log(error);
        }
    }

    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');
        while (listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        }
    }

})();