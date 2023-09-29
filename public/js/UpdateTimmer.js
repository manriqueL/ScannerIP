// function actualizarTimers() {
//     $.ajax({
//         url: "{{ path('ajax_update_timmer') }}", // Reemplaza con la ruta adecuada
//         method: 'GET',
//         success: function (data) {
//             // Actualiza los temporizadores con los datos recibidos
//             for (var id in data.data) {
//                 var timerElement = document.getElementById('timer-' + id);
//                 var hours = data.data[id].hours;
//                 var minutes = data.data[id].minutes;
//                 var seconds = data.data[id].seconds;
//                 timerElement.textContent = hours + 'h ' + minutes + 'm ' + seconds + 's';
//             }
//         }
//     });
// }

// // Configura el intervalo en milisegundos (por ejemplo, 1000 ms = 1 segundo)
// var intervalo = 1000;

// // Ejecuta la actualización de temporizadores repetidamente cada "intervalo" de tiempo
// setInterval(actualizarTimers, intervalo);
