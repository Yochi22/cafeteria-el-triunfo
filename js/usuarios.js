function consultar() {
    var datos = new FormData();
    datos.append("accion", "consultar");
    enviaAjax(datos);
}

function consultarPersonal() {
    var datos = new FormData();
    datos.append("accion", "consultarPersonal");
    enviaAjax(datos);
}

function consultarRoles() {
    var datos = new FormData();
    datos.append("accion", "consultarRoles");
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar();
    consultarPersonal();
    consultarRoles();
    $("#username").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9_.-]*$/, e);
    });

    $("#username").on("keyup", function () {
        validarkeyup(
            /^[A-Za-z0-9_.-]{3,30}$/,
            $(this),
            $("#susername"),
            "Usuario inválido - Debe tener entre 3 y 30 caracteres.",
        );
    });

    $("#password").on("keyup", function () {
        if ($(this).val() != "") {
            $("#spassword").text("");
        }
    });

    $("#btnGuardar").on("click", function () {
        if ($(this).text() == "incluir") {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append("accion", "incluir");
                datos.append("idPersonal", $("#idPersonal").val());
                datos.append("idRol", $("#idRol").val());
                datos.append("username", $("#username").val());
                datos.append("password", $("#password").val());
                enviaAjax(datos);
            }
        } else if ($(this).text() == "modificar") {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append("accion", "modificar");
                datos.append("idUsuario", $("#idUsuario").val());
                datos.append("idPersonal", $("#idPersonal").val());
                datos.append("idRol", $("#idRol").val());
                datos.append("username", $("#username").val());
                datos.append("password", $("#password").val());
                enviaAjax(datos);
            }
        }
    });

    function ejecutarBusqueda() {
        var valor = $("#valorBusqueda").val();
        if (valor.trim().length > 0) {
            var datos = new FormData();
            datos.append("accion", "buscar");
            datos.append("valorBusqueda", valor);
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

    $("#btnEliminar").on("click", function () {
        var idUsuarioEliminado = $("#eliminar").val();
        var datos = new FormData();
        datos.append("accion", "eliminar");
        datos.append("idUsuario", idUsuarioEliminado);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#btnGuardar").text("incluir");

        $("#mensajePassword").text(
            "La contraseña es obligatoria al incluir un usuario. Al modificar, déjala vacía para conservar la actual.",
        );
        $("#modal_usuarios").modal("show");
    });
});

function validarEnvio() {
    if ($("#idPersonal").val() == "") {
        mostrarMensaje("Debe seleccionar el personal.");
        return false;
    } else if ($("#idRol").val() == "") {
        mostrarMensaje("Debe seleccionar un rol.");
        return false;
    } else if (
        validarkeyup(
            /^[A-Za-z0-9_.-]{3,30}$/,
            $("#username"),
            $("#susername"),
            "Usuario inválido - Debe tener entre 3 y 30 caracteres.",
        ) == 0
    ) {
        mostrarMensaje("Nombre de usuario inválido.");
        return false;
    } else if (
        $("#btnGuardar").text() == "incluir" &&
        $("#password").val().trim() == ""
    ) {
        mostrarMensaje("Debe ingresar una contraseña.");
        return false;
    }

    return true;
}

function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () {
        $("#mostrarModal").modal("hide");
    }, 5000);
}

function validarkeypress(er, e) {
    var key = e.keyCode;
    var tecla = String.fromCharCode(key);
    var a = er.test(tecla);

    if (!a) {
        e.preventDefault();
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    var a = er.test(etiqueta.val());

    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

function pone(pos) {
    var linea = $(pos).closest("tr");
    $("#idUsuario").val($(linea).find("td:eq(5)").text());
    $("#idPersonal").val($(linea).find("td:eq(6)").text());
    $("#idRol").val($(linea).find("td:eq(7)").text());
    $("#username").val($(linea).find("td:eq(2)").text());
    $("#password").val("");
    $("#btnGuardar").text("modificar");
    $("#mensajePassword").text(
        "Deja la contraseña vacía para conservar la actual.",
    );
    $("#modal_usuarios").modal("show");
}

function eliminar(pos) {
    var linea = $(pos).closest("tr");
    var idUsuario = $(linea).find("td:eq(5)").text();
    $("#eliminar").val(idUsuario);
    $("#modal_eliminar").modal("show");
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=usuarios",
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
                if (lee.resultado == "consultar") {
                    $("#listaUsuarios").html(lee.mensaje);
                } else if (lee.resultado == "consultarPersonal") {
                    $("#idPersonal").html(lee.mensaje);
                } else if (lee.resultado == "consultarRoles") {
                    $("#idRol").html(lee.mensaje);
                } else if (lee.resultado == "incluir") {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == "Usuario incluido.") {
                        $("#modal_usuarios").modal("hide");
                        consultar();
                    }
                } else if (lee.resultado == "modificar") {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == "Usuario modificado.") {
                        $("#modal_usuarios").modal("hide");
                        consultar();
                    }
                } else if (lee.resultado == "eliminar") {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == "Usuario eliminado.") {
                        $("#modal_eliminar").modal("hide");
                        consultar();
                    }
                } else if (lee.resultado == "buscar") {
                    $("#listaUsuarios").html(lee.mensaje);
                } else if (lee.resultado == "error") {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                alert("Error en JSON " + e.name);
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
    $("#idUsuario").val("");
    $("#idPersonal").val("");
    $("#idRol").val("");
    $("#username").val("");
    $("#password").val("");
    $("#susername").text("");
    $("#spassword").text("");
}