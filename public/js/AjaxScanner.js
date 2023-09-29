function realizarLlamadoAjax() {
    var ajaxUpdateDataUrl = "{{ path('ajax_update_data') }}";
    $.ajax({
        url: ajaxUpdateDataUrl,
        method: 'GET',
        success: function (data) {
            if (data && data.ips) {
                // Actualiza el contenido de la página con los datos recibidos
                // Por ejemplo, puedes actualizar una tabla con id "tabla-ips"
                $('#rowMain').html.twig(data.ips);
            }
        }
    });
}

// Configura el intervalo en milisegundos (por ejemplo, 5000 ms = 5 segundos)
var intervalo = 5000;

// Ejecuta el llamado AJAX repetidamente cada "intervalo" de tiempo
setInterval(realizarLlamadoAjax, intervalo);
