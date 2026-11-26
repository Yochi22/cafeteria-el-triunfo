var horariosGlobal = [];

$(document).ready(function () {
    cargarHorarios();

    setInterval(function () {
        cargarHorarios();
    }, 15000);
});

function cargarHorarios() {
    var datos = new FormData();
    datos.append("accion", "consultarHorarios");
    enviaAjax(datos);
}

function normalizarDia(texto) {
    return (texto || "")
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim();
}

function verificarEstadoLocal() {
    if (horariosGlobal.length === 0) return;

    var dias = ["domingo", "lunes", "martes", "miercoles", "jueves", "viernes", "sabado"];
    var ahora = new Date();
    var diaActual = dias[ahora.getDay()];
    var horaActual = ahora.getHours() * 60 + ahora.getMinutes();

    var horarioHoy = horariosGlobal.find(h => normalizarDia(h.dia) === normalizarDia(diaActual));
    if (!horarioHoy) {
        $("#lblHorarioHoy").text("No disponible");
        return;
    }

    $("#lblHorarioHoy").text(
        formatearHora12(horarioHoy.horaApertura) + " - " + formatearHora12(horarioHoy.horaCierre)
    );

    var partesApertura = horarioHoy.horaApertura.split(":");
    var minutosApertura = parseInt(partesApertura[0], 10) * 60 + parseInt(partesApertura[1], 10);

    var partesCierre = horarioHoy.horaCierre.split(":");
    var minutosCierre = parseInt(partesCierre[0], 10) * 60 + parseInt(partesCierre[1], 10);

    var abierto = false;
    if (minutosApertura <= minutosCierre) {
        abierto = (horaActual >= minutosApertura && horaActual <= minutosCierre);
    } else {
        abierto = (horaActual >= minutosApertura || horaActual <= minutosCierre);
    }

    if (abierto) {
        $("#iconoEstado").removeClass("text-danger").addClass("text-success");
        $("#textoEstado").text("Abierto");
    } else {
        $("#iconoEstado").removeClass("text-success").addClass("text-danger");
        $("#textoEstado").text("Cerrado");
    }
}

function formatearHora12(hora24) {
    if (!hora24) return "";
    var partes = hora24.split(":");
    var h = parseInt(partes[0], 10);
    var m = partes[1];
    var ampm = h >= 12 ? "PM" : "AM";
    h = h % 12;
    h = h ? h : 12;
    return (h < 10 ? "0" + h : h) + ":" + m + " " + ampm;
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function (respuesta) {
            try {
                var lee = typeof respuesta === "string" ? JSON.parse(respuesta) : respuesta;

                if (Array.isArray(lee)) {
                    horariosGlobal = lee;
                    verificarEstadoLocal();
                }
            } catch (e) {
                console.error("Error al procesar JSON:", e, respuesta);
            }
        },
        error: function (request, status, error) {
            console.error("Error de conexión:", status, error);
        }
    });
}