// Estructura de Función IIFE
// Todo lo que se encuentre dentro de esta función
// solo puede ser accedido desde este mismo archivo
// Y no desde otros archivos .js
// Esto para evitar que las variables y funciones se
// Mezclen con otras
(function () {
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
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);


    async function obetenerTareas() {

        // EndPoint Para obtener las Tareas del proyecto
        const endPoint = url + `/api/tareas?url=${proyecto.url}`;
        try {

            const respuesta = await fetch(endPoint);
            const resultado = await respuesta.json();
            
            const { tareas } = resultado;

            mostrarTareas(tareas);

        } catch (error) {

        }
    }

    function mostrarTareas(tareas){
        
    }

    function mostrarFormulario() {
        // Creamos el modal
        const modal = document.createElement('DIV');
        // Asigna la clase modal
        modal.classList.add('modal');
        // Inserta el HTML al modal
        modal.innerHTML = `
        <form class="formulario nueva-tarea">
            <legend>Añade una nueva Tarea</legend>
            <div class="campo">
                <label for="tarea">Tarea</label>
                <input type="text" name="tarea" id="tarea" placeholder="Añade una tarea al Proyecto Actual">
            </div>
            <div class="opciones">
                <button type="button" class="cerrar-modal">Cancelar</button>
                <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea" />
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
                submitNuevaTarea();
            }
        });

        // Agrega la ventana modal 
        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitNuevaTarea() {
        // Selecciona el valor del input de tarea y elimina los espacios al inicio y al final con trim
        const tarea = document.querySelector('#tarea').value.trim();

        if (tarea === '') {
            const referencia = document.querySelector('.formulario legend');
            // Si no ingresó ninguna tarea muestra alerta de error
            mostrarAlerta('El nombre de la Tarea es Obligatorio', 'error', referencia);
            return;
        }

        agregarTarea(tarea);
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
                }, 3000);
            }

        } catch (error) {
            console.log(error);
        }
    }

})();