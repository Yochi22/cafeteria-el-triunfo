var preciosDeliveryGlobal = {};

$(document).ready(function () {
    try {
        cargarPreciosDelivery();
    } catch (e) {
        console.error(e);
    }

    $("#btnAbrirModalHorarios").on("click", function () {
        cargarHorariosTabla();
        $("#modal_horarios").modal("show");
    });

    $("#btnAbrirModalPrecios").on("click", function () {
        actualizarInputPrecioZona();
        limpiaPrecios();
        $("#modal_precios_delivery").modal("show");
    });

    $("#selectZona").on("change", function () {
        actualizarInputPrecioZona();
    });

    $("#inputPrecioZona").on("keypress", function (e) {
        validarkeypress(/^[0-9.]*$/, e);
    });

    $("#inputPrecioZona").on("keyup", function () {
        var valor = parseFloat($(this).val());
        var esValidoRegEx = validarkeyup(
            /^[0-9]{1,8}(\.[0-9]{1,2})?$/,
            $(this),
            $("#sinputPrecioZona"),
            "Precio inválido.",
        );

        if (esValidoRegEx == 1) {
            if (isNaN(valor) || valor <= 0) {
                $("#sinputPrecioZona").text("El precio debe ser mayor a 0.");
            } else {
                $("#sinputPrecioZona").text("");
            }
        }
    });

    $("#editHoraApertura").on("keypress", function (e) {
        validarkeypress(/^[0-9:]*$/, e);
    });

    $("#editHoraApertura").on("keyup", function () {
        validarkeyup(/^([01]\d|2[0-3]):([0-5]\d)$/, $(this), $("#seditHoraApertura"), "Hora inválida (HH:MM).");
    });

    $("#editHoraCierre").on("keypress", function (e) {
        validarkeypress(/^[0-9:]*$/, e);
    });

    $("#editHoraCierre").on("keyup", function () {
        validarkeyup(/^([01]\d|2[0-3]):([0-5]\d)$/, $(this), $("#seditHoraCierre"), "Hora inválida (HH:MM).");
    });

    $("#formPreciosDelivery, #formEditarHorario").on("submit", function (e) {
        e.preventDefault();
    });

    $("#btnGuardarPrecio").on("click", function (e) {
        e.preventDefault();
        if (validarEnvioPrecio()) {
            var datos = new FormData();
            datos.append("accion", "modificar_precio");
            datos.append("zona", $("#selectZona").val());
            datos.append("precio", $("#inputPrecioZona").val());
            enviaAjax(datos);
        }
    });

    $("#btnGuardarHorarioIndividual").on("click", function (e) {
        e.preventDefault();
        if (validarEnvioHorario()) {
            var datos = new FormData();
            datos.append("accion", "modificar_horario");
            datos.append("idHorario", $("#editIdHorario").val());
            datos.append("horaApertura", $("#editHoraApertura").val());
            datos.append("horaCierre", $("#editHoraCierre").val());
            enviaAjax(datos);
        }
    });
});

function cargarHorariosTabla() {
    var datos = new FormData();
    datos.append("accion", "consultar_horarios");
    enviaAjax(datos);
}

function cargarPreciosDelivery() {
    var datos = new FormData();
    datos.append("accion", "consultar_precios");
    enviaAjax(datos);
}

function actualizarInputPrecioZona() {
    var zona = $("#selectZona").val();
    var precio = 0;

    if (zona === "Centro") precio = preciosDeliveryGlobal.precioCentro || 0;
    if (zona === "Este") precio = preciosDeliveryGlobal.precioEste || 0;
    if (zona === "Oeste") precio = preciosDeliveryGlobal.precioOeste || 0;
    if (zona === "Norte") precio = preciosDeliveryGlobal.precioNorte || 0;
    if (zona === "Sur") precio = preciosDeliveryGlobal.precioSur || 0;
    $("#inputPrecioZona").val(parseFloat(precio).toFixed(2));
    $("#sinputPrecioZona").text("");
}

function validarEnvioPrecio() {
    var precioVal = parseFloat($("#inputPrecioZona").val());

    if (
        validarkeyup(
            /^[0-9]{1,8}(\.[0-9]{1,2})?$/,
            $("#inputPrecioZona"),
            $("#sinputPrecioZona"),
            "Precio inválido.",
        ) == 0
    ) {
        mostrarMensaje("Precio inválido.");
        return false;
    } else if (isNaN(precioVal) || precioVal <= 0) {
        $("#sinputPrecioZona").text("El precio debe ser mayor a 0.");
        mostrarMensaje("El precio debe ser mayor a 0.");
        return false;
    }
    $("#sinputPrecioZona").text("");

    return true;
}

function validarEnvioHorario() {
    var ap = $("#editHoraApertura").val();
    var ci = $("#editHoraCierre").val();

    if (ap.trim() === "" || ci.trim() === "") {
        mostrarMensaje("Debe completar ambas horas.");
        return false;
    }

    return true;
}

function abrirModalEditarHorario(id, dia, ap, ci) {
    $("#editIdHorario").val(id);
    $("#spanDiaEditar").text(dia.charAt(0).toUpperCase() + dia.slice(1));
    $("#editHoraApertura").val(ap);
    $("#editHoraCierre").val(ci);
    $("#seditHoraApertura").text("");
    $("#seditHoraCierre").text("");
    $("#modal_horarios").modal("hide");
    $("#modal_editar_horario").modal("show");
}

function formatearHora12(hora24) {
    if (!hora24) return "";
    var partes = hora24.split(":");
    var h = parseInt(partes[0]);
    var m = partes[1];
    var ampm = h >= 12 ? "PM" : "AM";
    h = h % 12;
    h = h ? h : 12;
    return (h < 10 ? "0" + h : h) + ":" + m + " " + ampm;
}

function limpiaPrecios() {
    $("#sinputPrecioZona").text("");
}

function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () {
        $("#mostrarModal").modal("hide");
    }, 5000);
}

function validarkeypress(er, e) {
    key = e.keyCode || e.which;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);

    if (!a && key != 8 && key != 13) {
        e.preventDefault();
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er.test(etiqueta.val());

    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
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
        beforeSend: function () { },
        timeout: 10000,
        success: function (respuesta) {
            console.log(respuesta);
            try {
                var lee = JSON.parse(respuesta);

                if (Array.isArray(lee)) {
                    var html = "";
                    lee.forEach(function (h) {
                        html += "<tr>";
                        html += "<td class='fw-bold text-capitalize'>" + h.dia + "</td>";
                        html += "<td>" + formatearHora12(h.horaApertura) + "</td>";
                        html += "<td>" + formatearHora12(h.horaCierre) + "</td>";
                        html += "<td><button type='button' class='btn btn-outline-secondary btn-sm' onclick='abrirModalEditarHorario(" + h.idHorario + ", \"" + h.dia + "\", \"" + h.horaApertura + "\", \"" + h.horaCierre + "\")'><i class='bi bi-pencil-square'></i> Editar</button></td>";
                        html += "</tr>";
                    });
                    $("#tablaHorariosBody").html(html);
                }
                else if (lee.precioCentro !== undefined) {
                    preciosDeliveryGlobal = lee;
                }
                else if (lee.resultado === 'modificar_horario') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Horario actualizado con éxito.') {
                        $("#modal_editar_horario").modal("hide");
                        $("#modal_horarios").modal("hide");
                    }
                }
                else if (lee.resultado === 'modificar_precio') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Precio de delivery actualizado con éxito.') {
                        $("#modal_precios_delivery").modal("hide");
                        cargarPreciosDelivery();
                    }
                }
                else if (lee.resultado === "error") {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                alert("Error en JSON " + e.name);
            }
        },
        error: function (request, status, err) {
            if (status === "timeout") {
                mostrarMensaje("Servidor ocupado, intente de nuevo.");
            } else {
                mostrarMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () { }
    });
}