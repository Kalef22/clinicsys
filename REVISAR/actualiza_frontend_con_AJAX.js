{/* <script> */}
    const appointments = {}; // Objeto para almacenar citas cargadas desde la base de datos

    // Función para cargar citas desde la base de datos
    function loadAppointments() {
        fetch('obtener_citas.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(cita => {
                    const dateTimeKey = `${cita.fecha}-${cita.hora}`;
                    appointments[dateTimeKey] = { name: cita.nombre, time: cita.hora };
                });
                generateCalendar(currentMonth, currentYear); // Regenerar el calendario con las citas cargadas
            })
            .catch(error => console.error('Error al cargar citas:', error));
    }

    // Función para guardar citas en la base de datos
    document.getElementById('appointmentForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const name = document.getElementById('appointmentName').value;
        const time = document.getElementById('appointmentTime').value;
        const date = document.getElementById('selectedDate').value;

        // Validación: Verificar si ya existe una cita para la misma fecha y hora
        const dateTimeKey = `${date}-${time}`;
        if (appointments[dateTimeKey]) {
            alert('Ya existe una cita programada para esta fecha y hora.');
            return;
        }

        // Enviar la cita al backend para guardarla en la base de datos
        const formData = new FormData();
        formData.append('nombre', name);
        formData.append('fecha', date);
        formData.append('hora', time);

        fetch('guardar_cita.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                alert(data.success);
                appointments[dateTimeKey] = { name, time }; // Guardar en el objeto local
                generateCalendar(currentMonth, currentYear); // Regenerar calendario
            }
        })
        .catch(error => console.error('Error al guardar la cita:', error));

        // Cerrar el modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('appointmentModal'));
        modal.hide();
    });

    // Generar calendario para el mes actual al cargar la página
    const today = new Date();
    let currentMonth = today.getMonth() + 1;
    let currentYear = today.getFullYear();

    loadAppointments(); // Cargar citas desde la base de datos al cargar la página

    // Funciones de navegación para cambiar de mes (las mismas de antes)
    // ... 
{/* </script> */}
// Actualización del Frontend con AJAX
// Ahora debes conectar el frontend con el backend usando AJAX para que la aplicación se comunique con el servidor sin recargar la página.

// Actualiza el archivo JavaScript para cargar las citas desde la base de datos y guardar nuevas citas:

// Ajuste del calendario para mostrar las citas almacenadas
// En la función generateCalendar, las citas almacenadas en appointments ya se están mostrando de acuerdo a la clave (fecha y hora). Cada vez que cargues las citas desde la base de datos, el calendario se regenerará para mostrarlas.

// 7. Resumen del flujo
// El frontend utiliza AJAX para:
// Cargar las citas existentes desde la base de datos con loadAppointments().
// Guardar nuevas citas en la base de datos mediante el formulario y el archivo guardar_cita.php.
// El backend en PHP maneja las solicitudes:
// obtener_citas.php: Devuelve las citas existentes en formato JSON.
// guardar_cita.php: Recibe y guarda las citas nuevas, verificando primero si ya existe una cita a la misma hora.
// Conclusión
// Este sistema permite que las citas sean persistentes mediante una base de datos MySQL, y el frontend interactúa con el backend usando AJAX para una experiencia de usuario más fluida.

