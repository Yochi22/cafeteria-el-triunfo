$(document).ready(function () {
    // Limpiar campos y spans al cargar la página
    limpia();

    // VALIDACIÓN DE DATOS
    $("#username").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9_.-]*$/, e);
    });

    $("#username").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9_.-]{3,30}$/, $(this), $("#susername"), "Usuario inválido - Debe tener entre 3 y 30 caracteres.");
    });

    $("#password").on("keyup", function () {
        if ($(this).val().trim() != "") {
            $("#spassword").text("");
        }
    });
    // Fin de Validaciones de datos

    // Envío por AJAX
    $("#f").on("submit", function (e) {
        e.preventDefault();

        if (validarEnvio()) {
            var datos = new FormData();
            datos.append('accion', 'login');
            datos.append('username', $("#username").val());
            datos.append('password', $("#password").val());

            enviaAjax(datos);
        }
    });
});

// Validación previa al envío
function validarEnvio() {
    if (validarkeyup(/^[A-Za-z0-9_.-]{3,30}$/, $("#username"), $("#susername"), "Usuario inválido - Debe tener entre 3 y 30 caracteres.") === 0) {
        mostrarAlerta("Atención", "Por favor, ingresa un usuario válido.", "warning");
        return false;
    }
    else if ($("#password").val().trim() === "") {
        $("#spassword").text("Por favor, ingresa tu contraseña.");
        mostrarAlerta("Atención", "Por favor, ingresa tu contraseña.", "warning");
        return false;
    }

    return true;
}

// Función para mostrar alertas
function mostrarAlerta(titulo, mensaje, icono) {
    Swal.fire({
        title: titulo,
        html: mensaje,
        icon: icono,
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#FF8C00',
        customClass: {
            popup: 'rounded-4 shadow'
        }
    });
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

// Función AJAX para procesar el acceso
function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=login",
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

                if (lee.resultado === 'login') {
                    if (lee.estatus === 'exito') {
                        mostrarAlerta("¡Bienvenido!", lee.mensaje, "success");
                        setTimeout(function () {
                            window.location.href = lee.url || "index.php?pagina=principal";
                        }, 1500);
                    } else {
                        // Aquí se muestran los errores de credenciales (contraseña incorrecta o usuario no existe) en el SweetAlert
                        mostrarAlerta("Atención", lee.mensaje, "warning");
                    }
                }
                else if (lee.resultado === "error") {
                    mostrarAlerta("Error", lee.mensaje, "error");
                }
            } catch (e) {
                mostrarAlerta("Error de sistema", "Error en formato JSON: " + e.name, "error");
            }
        },
        error: function (request, status, err) {
            if (status === "timeout") {
                mostrarAlerta("Servidor Ocupado", "El servidor tardó demasiado, intente de nuevo.", "warning");
            } else {
                mostrarAlerta("Error crítico", "Ocurrió un error de comunicación.", "error");
            }
        },
        complete: function () { },
    });
}

// Función para limpiar campos
function limpia() {
    $("#username").val("");
    $("#password").val("");

    $("#susername").text("");
    $("#spassword").text("");
}