// Función para la lista de clientes
function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar();

    // VALIDACIÓN DE DATOS
    // -- Validación de cédula --
    $("#cedulaCli").on("keypress", function (e) {
        validarkeypress(/^[0-9\-\.]*$/, e);
    });
    $("#cedulaCli").on("keyup", function () {
        validarkeyup(/^[0-9\.]{7,12}$/, $(this), $("#scedulaCli"), "Cédula Inválida - El formato debe ser: 999999999");
    });

    // -- Validación de nombre --
    $("#nombreCli").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombreCli").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombreCli"), "Nombre Inválido - Solo letras (entre 3 y 100 caracteres)");
    });

    // -- Validación de apellido --
    $("#apellidoCli").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#apellidoCli").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#sapellidoCli"), "Apellido Inválido - Solo letras (entre 3 y 100 caracteres)");
    });

    // -- Validación de teléfono --
    $("#tlfCli").on("keypress", function (e) {
        validarkeypress(/^[0-9\-+ ]*$/, e);
    });
    $("#tlfCli").on("keyup", function () {
        validarkeyup(/^[0-9\-+ ]{11,12}$/, $(this), $("#stlfCli"), "Teléfono Inválido - Formato: 0000-0000000");
    });
    // Fin de Validaciones de datos

    // Evitar que el formulario se envíe con Enter
    $("#f").on("submit", function (e) {
        e.preventDefault();
    });

    // Control de botones
    $("#btnGuardar").on("click", function () {
        // BOTÓN INCLUIR
        if ($(this).text() === 'incluir') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('cedulaCli', $("#cedulaCli").val());
                datos.append('nombreCli', $("#nombreCli").val());
                datos.append('apellidoCli', $("#apellidoCli").val());
                datos.append('tlfCli', $("#tlfCli").val());
                enviaAjax(datos);
            }
        }
        // BOTÓN MODIFICAR
        else if ($(this).text() === 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('cedulaCli', $("#cedulaCli").val());
                datos.append('nombreCli', $("#nombreCli").val());
                datos.append('apellidoCli', $("#apellidoCli").val());
                datos.append('tlfCli', $("#tlfCli").val());
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

    $("#btnBuscar").on("click", function () {
        ejecutarBusqueda();
    });

    // BOTÓN CONFIRMAR ELIMINACIÓN DE CLIENTE
    $("#btnEliminar").on("click", function () {
        var cedulaEliminada = $("#eliminar").val();
        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('cedulaCli', cedulaEliminada);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#cedulaCli").prop('readonly', false);
        $("#btnGuardar").text("incluir");
        $("#modal_cliente").modal("show");
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    // -- Validación de envío de cédula --
    if (validarkeyup(/^[0-9\.]{7,11}$/, $("#cedulaCli"), $("#scedulaCli"), "Cédula Inválida - El formato debe ser: 999999999") === 0) {
        mostrarMensaje("Cédula Inválida <br/>(El formato debe ser: 99999999)");
        return false;
    }
    // -- Validación de envío de nombre --
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombreCli"), $("#snombreCli"), "Nombre Inválido - Solo letras (entre 3 y 100 caracteres)") === 0) {
        mostrarMensaje("Nombre Inválido <br>(Solo letras entre 3 y 100 caracteres)");
        return false;
    }
    // -- Validación de envío de apellido --
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#apellidoCli"), $("#sapellidoCli"), "Apellido Inválido - Solo letras (entre 3 y 100 caracteres)") === 0) {
        mostrarMensaje("Apellido Inválido <br>(Solo letras entre 3 y 100 caracteres)");
        return false;
    }
    // -- Validación de envío de teléfono --
    else if (validarkeyup(/^[0-9\-+ ]{11,12}$/, $("#tlfCli"), $("#stlfCli"), "Teléfono Inválido - Formato: 0000-0000000") === 0) {
        mostrarMensaje("Teléfono Inválido <br>(Formato: 0000-0000000)");
        return false;
    }

    return true;
}

// Función para mostrar el modal de mensajes
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

// Función para llenar el formulario con los datos del cliente
function pone(pos) {
    var linea = $(pos).closest('tr');
    var btn = $(pos);

    $("#cedulaCli").val($(linea).find("td:eq(0)").text().trim());
    $("#nombreCli").val(btn.data('nombre'));
    $("#apellidoCli").val(btn.data('apellido'));
    $("#tlfCli").val($(linea).find("td:eq(2)").text().trim());

    $("#btnGuardar").text('modificar');
    $("#modal_cliente").modal("show");
    $("#cedulaCli").prop('readonly', true);
}

function eliminar(pos) {
    var linea = $(pos).closest('tr');
    var cedula = $(linea).find("td:eq(0)").text().trim();

    $("#eliminar").val(cedula);
    $("#modal_eliminar").modal("show");
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=clientes",
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

                if (lee.resultado === 'consultar') {
                    $("#listaClientes").html(lee.mensaje);
                }
                else if (lee.resultado === 'incluir') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Cliente registrado con éxito.') {
                        $("#modal_cliente").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado === 'modificar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Cliente modificado con éxito.') {
                        $("#modal_cliente").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado === 'eliminar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Cliente eliminado con éxito.') {
                        $("#modal_eliminar").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado === 'buscar') {
                    $('#listaClientes').html(lee.mensaje);
                }
                else if (lee.resultado === "error") {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                alert("Error en JSON: " + e.name);
            }
        },
        error: function (request, status, err) {
            if (status === "timeout") {
                mostrarMensaje("Servidor ocupado, intente de nuevo.");
            } else {
                mostrarMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () { },
    });
}

// Función para limpiar
function limpia() {
    // Vaciar los inputs
    $("#cedulaCli").val("");
    $("#nombreCli").val("");
    $("#apellidoCli").val("");
    $("#tlfCli").val("");

    // Limpiar los mensajes de validación
    $("#scedulaCli").text("");
    $("#snombreCli").text("");
    $("#sapellidoCli").text("");
    $("#stlfCli").text("");
}