$(document).ready(function () {
    // VALIDACIÓN DE DATOS
    $("#username").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9_.-]*$/, e);
    });
    $("#username").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9_.-]{3,30}$/, $(this), $("#susername"), "Usuario inválido - Debe tener entre 3 y 30 caracteres.");
    });
    $("#password").on("keyup", function () {
        if ($(this).val() != "") {
            $("#spassword").text("");
        }
    });
    // Fin de Validaciones de datos

    // Envío del formulario
    $("#f").on("submit", function (e) {
        if (!validarEnvio()) {
            e.preventDefault();
        }
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    if (validarkeyup(/^[A-Za-z0-9_.-]{3,30}$/, $("#username"), $("#susername"), "Usuario inválido - Debe tener entre 3 y 30 caracteres.") === 0) {
        mostrarMensaje("Por favor, ingresa un usuario válido.");
        return false;
    }
    else if ($("#password").val().trim() == "") {
        $("#spassword").text("Por favor, ingresa tu contraseña.");
        mostrarMensaje("Por favor, ingresa tu contraseña.");
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