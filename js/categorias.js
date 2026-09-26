// Función para la lista de categorías
function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar();

    // VALIDACIÓN DE DATOS
    // -- Validación de código --
    $("#codigoCat").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#codigoCat").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $(this), $("#scodigoCat"), "Código Inválido - Entre 5 y 50 caracteres");
    });

    // -- Validación de nombre --
    $("#nombreCat").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombreCat").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $(this), $("#snombreCat"), "Nombre Inválido - Solo letras (entre 5 y 50 caracteres)");
    });

    // -- Validación de descripción --
    $("#descCat").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]*$/, e);
    });
    $("#descCat").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{5,150}$/, $(this), $("#sdescCat"), "Descripción Inválida - Entre 5 y 150 caracteres");
    });
    // Fin de Validaciones de datos

    // Evitar que el formulario se envíe con Enter
    $("#f").on("submit", function (e) {
        e.preventDefault();
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

    // BOTÓN CONFIRMAR ELIMINACIÓN DE CATEGORÍA
    $("#btnEliminar").on("click", function () {
        var codigoEliminado = $("#eliminar").val();
        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('codigoCat', codigoEliminado);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#codigoOriginal").val("");
        $("#codigoCat").prop('readonly', false);
        $("#btnGuardar").text("incluir");
        $("#modal_categoria").modal("show");
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $("#codigoCat"), $("#scodigoCat"), "Código Inválido") === 0) {
        mostrarMensaje("Código Inválido <br/>(Entre 5 y 50 caracteres)");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $("#nombreCat"), $("#snombreCat"), "Nombre Inválido") === 0) {
        mostrarMensaje("Nombre Inválido <br/>(Solo letras entre 5 y 50 caracteres)");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{5,150}$/, $("#descCat"), $("#sdescCat"), "Descripción Inválida") === 0) {
        mostrarMensaje("Descripción Inválida <br/>(Entre 5 y 150 caracteres)");
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

// Función para llenar el formulario con los datos de la categoría (usando atributos data-* en el botón)
function pone(pos) {
    var btn = $(pos);

    $("#codigoOriginal").val(btn.data('codigo'));
    $("#codigoCat").val(btn.data('codigo'));
    $("#nombreCat").val(btn.data('nombre'));
    $("#descCat").val(btn.data('descripcion'));
    $("#fotoCat").val(btn.data('foto'));

    $("#btnGuardar").text('modificar');
    $("#modal_categoria").modal("show");
}

function eliminar(pos) {
    var btn = $(pos);
    // Buscamos el botón de editar hermano o extraemos el código directamente del botón de edición de la tarjeta
    var card = btn.closest('.card');
    var codigo = card.find('button.btn-outline-primary').data('codigo');

    $("#eliminar").val(codigo);
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

                if (lee.resultado === 'consultar') {
                    $("#cuadricula_categorias").html(lee.mensaje);
                }
                else if (lee.resultado === 'incluir') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Categoría registrada con éxito.') {
                        $("#modal_categoria").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado === 'modificar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Categoría modificada con éxito.') {
                        $("#modal_categoria").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado === 'eliminar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje === 'Categoría eliminada con éxito.') {
                        $("#modal_eliminar").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado === 'buscar') {
                    $('#cuadricula_categorias').html(lee.mensaje);
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
    $("#codigoOriginal").val("");
    $("#codigoCat").val("");
    $("#nombreCat").val("");
    $("#descCat").val("");
    $("#fotoCat").val("");

    // Limpiar los mensajes de validación
    $("#scodigoCat").text("");
    $("#snombreCat").text("");
    $("#sdescCat").text("");
}