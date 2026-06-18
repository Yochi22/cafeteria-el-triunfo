function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar();

    $("#codigoCat").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#codigoCat").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $(this), $("#scodigoCat"), "Código inválido.");
    });

    $("#nombreCat").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombreCat").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombreCat"), "Nombre inválido.");
    });

    $("#descCat").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]*$/, e);
    });
    $("#descCat").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{3,150}$/, $(this), $("#sdescCat"), "Descripción inválida.");
    });

    $("#btnGuardar").on("click", function () {
        if ($(this).text() == 'incluir') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('codigoCat', $("#codigoCat").val());
                datos.append('nombreCat', $("#nombreCat").val());
                datos.append('descCat', $("#descCat").val());
                datos.append('fotoCat', $("#fotoCat").val());
                enviaAjax(datos);
            }
        }
        else if ($(this).text() == 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('codigoOriginal', $("#codigoOriginal").val());
                datos.append('codigoCat', $("#codigoCat").val());
                datos.append('nombreCat', $("#nombreCat").val());
                datos.append('descCat', $("#descCat").val());
                datos.append('fotoCat', $("#fotoCat").val());
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
        var codigoEliminado = $("#eliminar").val();
        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('codigoCat', codigoEliminado);
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
        if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#codigoCat"), $("#scodigoCat"), "Código inválida.") == 0) {
            mostrarMensaje("Código inválido.");

            return false;
        }
    }
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#nombreCat"), $("#snombreCat"), "Nombre inválido.") == 0) {
        mostrarMensaje("Nombre inválido.");

        return false;
    }
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{3,150}$/, $("#descCat"), $("#sdescCat"), "Descripción inválida.") == 0) {
        mostrarMensaje("Descripción inválida.");

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

function pone(codigoCat, nombreCat, descCat, fotoCat) {
    $("#codigoOriginal").val(codigoCat);
    $("#codigoCat").val(codigoCat);
    $("#nombreCat").val(nombreCat);
    $("#descCat").val(descCat);
    $("#fotoCat").val(fotoCat);

    $("#btnGuardar").text('modificar');
    $("#modal_categoria").modal("show");
}

function eliminar(codigoCat) {
    $("#eliminar").val(codigoCat);
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
                    if (lee.mensaje == 'Categoría registrada.') {
                        $("#modal_categoria").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'modificar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Categoría modificada.') {
                        $("#modal_categoria").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'eliminar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Categoría eliminada.') {
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
                mostrarMensaje("Servidor ocupado, intente de nuevo.");
            } else {
                mostrarMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () { },
    });
}

function limpia() {
    $("#codigoOriginal").val("");
    $("#codigoCat").val("");
    $("#nombreCat").val("");
    $("#descCat").val("");
    $("#fotoCat").val("");
}