// Función para la lista de cuentas
function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar();

    // VALIDACIÓN DE DATOS
    // -- Validación de nombre de banco --
    $("#nombreBanco").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombreBanco").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombreBanco"), "Nombre Inválido - Solo letras (entre 3 y 100 caracteres)");
    });

    // -- Validación de cédula de titular --
    $("#cedulaTitular").on("keypress", function (e) {
        validarkeypress(/^[0-9\-\.]*$/, e);
    });
    $("#cedulaTitular").on("keyup", function () {
        validarkeyup(/^[0-9\.]{7,12}$/, $(this), $("#scedulaTitular"), "Cédula Inválida - El formato debe ser: 999999999");
    });

    // -- Validación de teléfono --
    $("#tlfCuenta").on("keypress", function (e) {
        validarkeypress(/^[0-9\-+ ]*$/, e);
    });
    $("#tlfCuenta").on("keyup", function () {
        validarkeyup(/^[0-9\-+ ]{11,12}$/, $(this), $("#stlfCuenta"), "Teléfono Inválido - Formato: 0000-0000000");
    });

    // -- Validación de número de cuenta --
    $("#numCuenta").on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });
    $("#numCuenta").on("keyup", function () {
        validarkeyup(/^[0-9]{20}$/, $(this), $("#snumCuenta"), "Cuenta Inválida - Debe tener exactamente 20 números.");
    });

    // -- Validación de tipo de cuenta (Select) --
    $("#tipoCuenta").on("change", function () {
        validarSelect($(this), $("#stipoCuenta"), "Tipo de Cuenta Inválido - Debe seleccionar una opción");
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
                datos.append('nombreBanco', $("#nombreBanco").val());
                datos.append('cedulaTitular', $("#cedulaTitular").val());
                datos.append('tlfCuenta', $("#tlfCuenta").val());
                datos.append('tipoCuenta', $("#tipoCuenta").val());
                datos.append('numCuenta', $("#numCuenta").val());
                enviaAjax(datos);
            }
        }
        // BOTÓN MODIFICAR
        else if ($(this).text() === 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('nombreBanco', $("#nombreBanco").val());
                datos.append('cedulaTitular', $("#cedulaTitular").val());
                datos.append('tlfCuenta', $("#tlfCuenta").val());
                datos.append('tipoCuenta', $("#tipoCuenta").val());
                datos.append('numCuenta', $("#numCuenta").val());
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

    // BOTÓN CONFIRMAR ELIMINACIÓN DE CUENTA
    $("#btnEliminar").on("click", function () {
        var cuentaEliminada = $("#eliminar").val();
        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('numCuenta', cuentaEliminada);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#numCuenta").prop('readonly', false);
        $("#btnGuardar").text("incluir");
        $("#modal_cuentas").modal("show");
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombreBanco"), $("#snombreBanco"), "Nombre Inválido - Solo letras") === 0) {
        mostrarMensaje("Nombre de Banco Inválido <br/>(Solo letras entre 3 y 100 caracteres)");
        return false;
    }
    else if (validarkeyup(/^[0-9\.]{7,11}$/, $("#cedulaTitular"), $("#scedulaTitular"), "Cédula Inválida") === 0) {
        mostrarMensaje("Cédula Titular Inválida <br/>(El formato debe ser: 99999999)");
        return false;
    }
    else if (validarkeyup(/^[0-9\-+ ]{11,12}$/, $("#tlfCuenta"), $("#stlfCuenta"), "Teléfono Inválido") === 0) {
        mostrarMensaje("Teléfono Inválido <br>(Formato: 0000-0000000)");
        return false;
    }
    else if (validarSelect($("#tipoCuenta"), $("#stipoCuenta"), "Seleccione una opción") === 0) {
        mostrarMensaje("Tipo de Cuenta Inválido <br>(Debe seleccionar Ahorro o Corriente)");
        return false;
    }
    else if (validarkeyup(/^[0-9]{20}$/, $("#numCuenta"), $("#snumCuenta"), "Cuenta Inválida") === 0) {
        mostrarMensaje("Número de Cuenta Inválido <br>(Debe tener exactamente 20 números)");
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

// Función para validar select
function validarSelect(etiqueta, etiquetamensaje, mensaje) {
    let valor = etiqueta.val();
    if (valor === "" || valor === null) {
        etiquetamensaje.text(mensaje);
        return 0;
    } else {
        etiquetamensaje.text("");
        return 1;
    }
}

// Función para llenar el formulario con los datos de la cuenta
function pone(pos) {
    var linea = $(pos).closest('tr');
    var btn = $(pos);

    $("#nombreBanco").val(btn.data('banco'));
    $("#cedulaTitular").val(btn.data('cedula'));
    $("#tlfCuenta").val(btn.data('telefono'));
    $("#tipoCuenta").val(btn.data('tipo'));
    $("#numCuenta").val($(linea).find("td:eq(4)").text().trim());

    $("#btnGuardar").text('modificar');
    $("#modal_cuentas").modal("show");
    $("#numCuenta").prop('readonly', true);
}

function eliminar(pos) {
    var linea = $(pos).closest('tr');
    var numCuenta = $(linea).find("td:eq(4)").text().trim();

    $("#eliminar").val(numCuenta);
    $("#modal_eliminar").modal("show");
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=cuentas",
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
                    $("#listaCuentas").html(lee.mensaje);
                }
                else if (lee.resultado === 'incluir') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Cuenta registrada con éxito.') {
                        $("#modal_cuentas").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado === 'modificar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Cuenta modificada con éxito.') {
                        $("#modal_cuentas").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado === 'eliminar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Cuenta eliminada con éxito.') {
                        $("#modal_eliminar").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado === 'buscar') {
                    $('#listaCuentas').html(lee.mensaje);
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
    $("#nombreBanco").val("");
    $("#cedulaTitular").val("");
    $("#tlfCuenta").val("");
    $("#tipoCuenta").val("");
    $("#numCuenta").val("");

    // Limpiar los mensajes de validación
    $("#snombreBanco").text("");
    $("#scedulaTitular").text("");
    $("#stlfCuenta").text("");
    $("#stipoCuenta").text("");
    $("#snumCuenta").text("");
}