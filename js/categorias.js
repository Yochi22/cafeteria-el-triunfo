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
        if ($(this).text().trim() === 'Guardar Categoría' || $(this).text().trim() === 'incluir') {
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
        else if ($(this).text().trim() === 'modificar') {
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

    $("#incluir").on("click", function () {
        limpia();
        $("#codigoOriginal").val("");
        $("#codigoCat").prop('readonly', false);
        $("#btnGuardar").text("Guardar Categoría");
        $("#modal_categoria").modal("show");
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $("#codigoCat"), $("#scodigoCat"), "Código Inválido") === 0) {
        mostrarAlerta("Atención", "Código Inválido <br/>(Entre 5 y 50 caracteres)", "warning");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $("#nombreCat"), $("#snombreCat"), "Nombre Inválido") === 0) {
        mostrarAlerta("Atención", "Nombre Inválido <br/>(Solo letras entre 5 y 50 caracteres)", "warning");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{5,150}$/, $("#descCat"), $("#sdescCat"), "Descripción Inválida") === 0) {
        mostrarAlerta("Atención", "Descripción Inválida <br/>(Entre 5 y 150 caracteres)", "warning");
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

// Función para llenar el formulario con los datos de la categoría
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

// Función Eliminar 
function eliminar(pos) {
    var btn = $(pos);
    var card = btn.closest('.card');
    var codigo = card.find('button.btn-outline-primary').data('codigo');

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Deseas eliminar esta categoría del sistema!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'red',
        cancelButtonColor: 'darkgray',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-4 shadow'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            var datos = new FormData();
            datos.append('accion', 'eliminar');
            datos.append('codigoCat', codigo);
            enviaAjax(datos);
        }
    });
}

// Función AJAX
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
                    if (lee.mensaje === 'Categoría registrada con éxito.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_categoria").modal("hide");
                        consultar();
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'modificar') {
                    if (lee.mensaje === 'Categoría modificada con éxito.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_categoria").modal("hide");
                        consultar();
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'eliminar') {
                    if (lee.mensaje === 'Categoría eliminada con éxito.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        consultar();
                    } else {
                        mostrarAlerta("Atención", lee.mensaje, "warning");
                    }
                }
                else if (lee.resultado === 'buscar') {
                    $('#cuadricula_categorias').html(lee.mensaje);
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