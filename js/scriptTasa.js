function cargarTasa() {        
    $.ajax({
        url: "https://ve.dolarapi.com/v1/dolares/oficial",
        method: "GET",
        dataType: "json",
        success: function(R) {
            const precio = R.promedio;
            
            localStorage.setItem('tasaDolar', precio);

            const fechaActualizacion = new Date(R.fechaActualizacion).toLocaleDateString('es-VE', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            const precioActu = `Bs. ${precio.toLocaleString('es-VE', { minimumFractionDigits: 2 })}`;
            const fechaActu = `Actualizado: ${fechaActualizacion}`;

            $('#contenidoModal').html(`
                <div class="fs-4 text-success mb-2">${precioActu}</div>
                <div class="fs-6 text-muted fw-normal" style="font-size: 0.8rem;">${fechaActu}</div>
            `);

            $("#mostrarModal").modal("show");
        },
        error: function(request, status, err) {
            console.error("Error al consultar la API:", status, err);
            $('#contenidoModal').html(`
                <h5 class="text-danger mb-2">¡API no disponible!</h5>
                <p class="text-muted small mb-3">No pudimos encontrar la tasa automáticamente. Por favor, ingresar manualmente:</p>
                
                <form id="formTasaManual">
                    <div class="mb-3 text-start">
                        <label for="tasaManual" class="form-label small fw-bold">Tasa del Dólar (Bs):</label>
                        <input type="number" step="0.01" class="form-control rounded-3" id="tasaManual" name="tasaManual" placeholder="00.00 Bs." required>
                        <span id="stasaManual" class="text-danger small"></span>
                    </div>
                    <button type="submit" class="btn btn-crear btn-sm d-block mx-auto">Guardar Tasa</button>
                </form>
            `);

            $("#mostrarModal").modal("show");
        }
    });
}

function mostrarNotificacion(mensaje) {
    if ($("#contenidoModal").length) {
        $("#contenidoModal").html(mensaje);
        $("#mostrarModal").modal("show");
        setTimeout(function () {
            $("#mostrarModal").modal("hide");
        }, 2000);
    } else {
        alert(mensaje);
    }
}

$(document).on("submit", "#formTasaManual", function(e) {
    e.preventDefault();
    let tasaManual = parseFloat($("#tasaManual").val());
    if (tasaManual > 0) {
        localStorage.setItem('tasaDolar', tasaManual);
        mostrarNotificacion("¡Tasa guardada exitosamente!");
        }
});

$(document).ready(function() {
    $("#btnTasa").on("click", function(e) {
        e.preventDefault();
        cargarTasa();
    });

    if (!localStorage.getItem('tasaDolar')) {
        $.get("https://ve.dolarapi.com/v1/dolares/oficial", function(R) {
            if (R && R.promedio) {
                localStorage.setItem('tasaDolar', R.promedio);
            }
        });
    }
});