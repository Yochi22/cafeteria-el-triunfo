// Función para la lista de personal
function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar();

    // VALIDACIÓN DE DATOS
    // Validación de cédula
    $("#cedulaPer").on("keypress", function (e) {
        validarkeypress(/^[0-9-\.]*$/, e);
    });
    $("#cedulaPer").on("keyup", function () {
        validarkeyup(/^[0-9\.]{7,12}$/, $(this), $("#scedulaPer"), "Cédula Inválida - El Formato debe ser: 99999999");
    });

    // Validación de nombre
    $("#nombrePer").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombrePer").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombrePer"), "Nombre Inválido - Solo letras entre 3 y 100 carácteres");
    });

    // Validación de apellido
    $("#apellidoPer").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#apellidoPer").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#sapellidoPer"), "Apellido Inválido - Solo letras entre 3 y 100 carácteres");
    });
    // Fin de validación de datos

    //Control de botones
    $("#btnGuardar").on("click", function () {
        // BOTÓN INCLUIR
        if ($(this).text() == 'incluir') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('cedulaPer', $("#cedulaPer").val());
                datos.append('nombrePer', $("#nombrePer").val());
                datos.append('apellidoPer', $("#apellidoPer").val());
                enviaAjax(datos);
            }
        }
        // BOTÓN MODIFICAR
        else if ($(this).text() == 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('cedulaPer', $("#cedulaPer").val());
                datos.append('nombrePer', $("#nombrePer").val());
                datos.append('apellidoPer', $("#apellidoPer").val());
                enviaAjax(datos);
            }
        }
    });

    // BOTÓN BUSCAR
    function ejecutarBusqueda() {
        var valor = $("#valorBusqueda").val();

        if (valor.trim().length > 0) {
            var datos = new FormData();
            datos.append('accion', 'buscar');
            datos.append('valorBusqueda', valor);
            enviaAjax(datos);
        } else {
            consultar();
        }
    }

    $("#valorBusqueda").on("keyup", function () {
        ejecutarBusqueda();
    });

    // BOTÓN CONFIRMAR ELIMINACIÓN DE PERSONAL
    $("#btnEliminar").on("click", function () {
        var cedulaEliminada = $("#cedulaEliminar").val();

        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('cedulaPer', cedulaEliminada);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#cedulaPer").prop("readonly", false);
        $("#btnGuardar").text("incluir");
        $("#modal_personal").modal("show");
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    // Validación de envio de cédula
    if (validarkeyup(/^[0-9\.]{7,12}$/, $("#cedulaPer"), $("#scedulaPer"), "Cédula Inválida - (El Formato debe ser: 99999999)") == 0) {
        mostrarMensaje("Cédula Inválida - (El Formato debe ser: 99999999)");
        return false;
    }
    // Validación de envio de nombre
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombrePer"), $("#snombrePer"), "Nombre Inválido - (Solo letras entre 3 y 100 carácteres)") == 0) {
        mostrarMensaje("Nombre Inválido - (Solo letras entre 3 y 100 carácteres)");
        return false;
    }
    // Validación de envio de apellido
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#apellidoPer"), $("#sapellidoPer"), "Apellido Inválido - (Solo letras entre 3 y 100 carácteres)") == 0) {
        mostrarMensaje("Apellido Inválido - (Solo letras entre 3 y 100 carácteres)");
        return false;
    }

    return true;
}

// Función para mostrar del modal del mensaje
function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () { $("#mostrarModal").modal("hide") }, 5000);
}

// Función para validar por keypress
function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault();
    }
}

// Función para validar por keyup
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

// Función para llenar el formulario con los datos del personal
function pone(pos) {
    linea = $(pos).closest('tr');
    $("#cedulaPer").prop("readonly", true);
    var nombrePerCompleto = $(linea).find("td:eq(1)").text();
    var nombrePerSeparado = nombrePerCompleto.split(' ');

    $("#cedulaPer").val($(linea).find("td:eq(0)").text());
    $("#nombrePer").val(nombrePerSeparado[0]);
    $("#apellidoPer").val(nombrePerSeparado[1]);
    $("#btnGuardar").text('modificar');
    $("#modal_personal").modal("show");
}

function eliminar(pos) {
    var linea = $(pos).closest('tr');
    var cedula = $(linea).find("td:eq(0)").text();

    $("#cedulaEliminar").val(cedula);
    $("#modal_eliminar").modal("show");
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=personal",
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
                if (lee.resultado == 'consultar') {
                    $("#listaPersonal").html(lee.mensaje);
                }
                else if (lee.resultado == 'incluir') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Empleado incluido.') {
                        $("#modal_personal").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'modificar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Empleado modificado.') {
                        $("#modal_personal").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'eliminar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Empleado eliminado.') {
                        $("#modal_eliminar").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'buscar') {
                    $('#listaPersonal').html(lee.mensaje);
                }
                else if (lee.resultado == "error") {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                alert("Error en JSON" + e.name);
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mostrarMensaje("Servidor Ocupado, Intente de Nuevo");
            } else {
                mostrarMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () { },
    });
}

function limpia() {
    $("#cedulaPer").val("");
    $("#nombrePer").val("");
    $("#apellidoPer").val("");
}