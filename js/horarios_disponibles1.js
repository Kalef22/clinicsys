document.addEventListener("DOMContentLoaded", function () {
    const monthYear = document.getElementById("monthYear");
    const calendarBody = document.getElementById("calendar-body");
    const appointmentModal = new bootstrap.Modal(document.getElementById("appointmentModal"));
    const selectedDateInput = document.getElementById("selectedDate");

    let currentDate = new Date();

    function renderCalendar(date) {
        // Obtener año y mes actuales
        const year = date.getFullYear();
        const month = date.getMonth();

        // Establecer el encabezado del mes y año
        monthYear.innerText = date.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });

        // Limpiar el calendario antes de renderizar
        calendarBody.innerHTML = "";

        // Obtener el primer día del mes y la cantidad total de días
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);
        const daysInMonth = lastDayOfMonth.getDate();
        const firstDayOfWeek = firstDayOfMonth.getDay();

        let weekRow = document.createElement("div");
        weekRow.classList.add("row");

        // Crear celdas vacías hasta el primer día del mes
        for (let i = 0; i < firstDayOfWeek; i++) {
            const emptyCell = document.createElement("div");
            emptyCell.classList.add("col", "calendar-day");
            weekRow.appendChild(emptyCell);
        }

        // Crear celdas para cada día del mes
        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement("div");
            dayCell.classList.add("col", "calendar-day");
            dayCell.innerText = day;

            // Verificar si el día es hoy o ya ha pasado
            const cellDate = new Date(year, month, day);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (cellDate < today) {
                // Estilo para días pasados
                dayCell.classList.add("disabled-day");
            } else {
                // Día clickeable para seleccionar
                dayCell.addEventListener("click", function () {
                    // Ajuste de fecha al inicio del día en UTC para evitar problemas de zona horaria
                    const adjustedDate = new Date(Date.UTC(year, month, day));
                    selectedDateInput.value = adjustedDate.toISOString().split('T')[0];
                    appointmentModal.show();
                });
            }

            // Agregar el día a la fila de la semana
            weekRow.appendChild(dayCell);

            // Si la semana está completa (7 días), añadir la fila al calendario y crear una nueva fila
            if ((day + firstDayOfWeek) % 7 === 0) {
                calendarBody.appendChild(weekRow);
                weekRow = document.createElement("div");
                weekRow.classList.add("row");
            }
        }

        // Añadir celdas vacías para completar la última fila si no tiene 7 días
        const remainingDays = 7 - weekRow.children.length;
        for (let i = 0; i < remainingDays; i++) {
            const emptyCell = document.createElement("div");
            emptyCell.classList.add("col", "calendar-day");
            weekRow.appendChild(emptyCell);
        }

        // Añadir la última fila al calendario
        if (weekRow.hasChildNodes()) {
            calendarBody.appendChild(weekRow);
        }
    }




    // Funciones para cambiar de mes
    document.getElementById("prevMonth").addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar(currentDate);
    });

    document.getElementById("nextMonth").addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar(currentDate);
    });

    // Renderizar el calendario al cargar la página
    renderCalendar(currentDate);
});