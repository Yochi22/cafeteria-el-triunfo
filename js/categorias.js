// Función para Consultar
function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar();

    // Validación de Código
    $("#codigoCat").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#codigoCat").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $(this), $("#scodigoCat"), "Código inválido.");
    });

    // Validación de Nombre
    $("#nombreCat").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombreCat").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this), $("#snombreCat"), "Nombre inválido.");
    });

    // Validación de Descripción
    $("#descCat").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]*$/, e);
    });
    $("#descCat").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{3,150}$/, $(this), $("#sdescCat"), "Descripción inválida.");
    });

    // Control de Botones
    $("#btnGuardar").on("click", function () {
        // Botón de Incluir
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
        // Botón de Modificar
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
        // Botón de Eliminar
        if ($(this).text() == 'eliminar') {
            if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $("#codigoCat"),
                $("#scodigoCat"), "Código inválido.") == 0) {
                mostrarMensaje("Código inválido.");
            } else {
                var datos = new FormData();
                datos.append('accion', 'eliminar');
                datos.append('codigoCat', $("#codigoCat").val());
                enviaAjax(datos);
            }
        }
    });

    // Botón de Buscar
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

    // Botón para Confirmar Eliminación
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

// Validación de los Datos Antes de Enviarlos
function validarEnvio() {
    // Validación de Envío de Código
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#codigoCat"), $("#scodigoCat"), "Código inválida.") == 0) {
        mostrarMensaje("Código inválido.");
        return false;
    }
    // Validación de Envío de Nombre
    else if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#nombreCat"), $("#snombreCat"), "Nombre inválido.") == 0) {
        mostrarMensaje("Nombre inválido.");
        return false;
    }
    // Validación de Envío de Descripción
    else if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{3,150}$/, $("#descCat"), $("#sdescCat"), "Descripción inválida.") == 0) {
        mostrarMensaje("Descripción inválida.");
        return false;
    }

    return true;
}

// Función para Mostrar el Modal del Mensaje
function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () { $("#mostrarModal").modal("hide") }, 3000);
}

// Función para Validar por Keypress
function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault();
    }
}

// Función para Validar por Keyup
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

// Función para Llenar el Formulario
function pone(codigoCat, nombreCat, descCat, fotoCat) {
    $("#codigoOriginal").val(codigoCat);
    $("#codigoCat").val(codigoCat);
    $("#nombreCat").val(nombreCat);
    $("#descCat").val(descCat);
    $("#fotoCat").val(fotoCat);

    $("#btnGuardar").text('modificar');
    $("#modal_categoria").modal("show");
}

// Función para Eliminar
function eliminar(codigoCat) {
    $("#eliminar").val(codigoCat);
    $("#modal_eliminar").modal("show");
}

// Función para Enviar los Datos por Ajax
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

// Función para Limpiar el Formulario
function limpia() {
    $("#codigoOriginal").val("");
    $("#codigoCat").val("");
    $("#nombreCat").val("");
    $("#descCat").val("");
    $("#fotoCat").val("");
}