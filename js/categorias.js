function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function () {

    consultar();

    $("#idCategoria").on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });
    $("#idCategoria").on("keyup", function () {
        validarkeyup(/^[0-9]{1,11}$/, $(this), $("#sidCategoria"), "ID Invalido - Solo números permitidos");
    });

    $("#nombre").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombre").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombre"), "Nombre Invalido - Entre 3 y 100 caracteres");
    });

    $("#descripcion").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]*$/, e);
    });
    $("#descripcion").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{3,200}$/, $(this), $("#sdescripcion"), "Descripción Invalida - Entre 3 y 200 caracteres");
    });

    $("#btnGuardar").on("click", function () {
        if ($(this).text() == 'incluir') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('idCategoria', $("#idCategoria").val());
                datos.append('nombre', $("#nombre").val());
                datos.append('descripcion', $("#descripcion").val());
                datos.append('foto', $("#foto").val());
                enviaAjax(datos);
            }
        }
        else if ($(this).text() == 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('idCategoria', $("#idCategoria").val());
                datos.append('nombre', $("#nombre").val());
                datos.append('descripcion', $("#descripcion").val());
                datos.append('foto', $("#foto").val());
                enviaAjax(datos);
            }
        }
    });

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

    $("#btnEliminar").on("click", function () {
        var idEliminado = $("#idEliminar").val();
        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('idCategoria', idEliminado);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#btnGuardar").text("incluir");
        $("#modal_categoria").modal("show");
    });
});

function validarEnvio() {
    if ($("#btnGuardar").text() == 'modificar') {
        if (validarkeyup(/^[0-9]{1,11}$/, $("#idCategoria"), $("#sidCategoria"), "ID Invalido - (Solo números)") == 0) {
            mostrarMensaje("ID Invalido - (Solo números)");
            return false;
        }
    }
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombre"), $("#snombre"), "Nombre Invalido - (Entre 3 y 100 caracteres)") == 0) {
        mostrarMensaje("Nombre Invalido - (Entre 3 y 100 caracteres)");
        return false;
    }
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{3,200}$/, $("#descripcion"), $("#sdescripcion"), "Descripción Invalida - (Entre 3 y 200 caracteres)") == 0) {
        mostrarMensaje("Descripción Invalida - (Entre 3 y 200 caracteres)");
        return false;
    }
    return true;
}

function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () { $("#mostrarModal").modal("hide") }, 3000);
}

function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
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

function pone(id, nombre, descripcion, foto) {
    $("#idCategoria").val(id);
    $("#nombre").val(nombre);
    $("#descripcion").val(descripcion);
    $("#foto").val(foto);

    $("#btnGuardar").text('modificar');
    $("#modal_categoria").modal("show");
}

function eliminar(id) {
    $("#idEliminar").val(id);
    $("#modal_eliminar").modal("show");
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=categorias",
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
                    $("#cuadricula_categorias").html(lee.mensaje);
                }
                else if (lee.resultado == 'incluir') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Categoría Registrada') {
                        $("#modal_categoria").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'modificar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Categoría Modificada') {
                        $("#modal_categoria").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'eliminar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Categoría Eliminada') {
                        $("#modal_eliminar").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'buscar') {
                    $('#cuadricula_categorias').html(lee.mensaje);
                }
                else if (lee.resultado == "error") {
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
    $("#idCategoria").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#foto").val("");
    $("#snombre").text("");
    $("#sdescripcion").text("");
}