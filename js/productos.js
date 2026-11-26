var categoriaActual = null;
var nombreCategoriaActual = '';

// Función para la lista de categorías (pantalla inicial)
function consultar() {
    categoriaActual = null;
    nombreCategoriaActual = '';
    $("#titulo_seccion").html('<i class="bi bi-box-seam"></i> Productos');
    $("#subtitulo_seccion").text("¡Gestiona los productos de tu inventario fácilmente!");
    $("#contenedor_botones_header").html('');

    var datos = new FormData();
    datos.append('accion', 'consultarCategorias');
    enviaAjax(datos);
}

// Función para ver productos de una categoría específica
function verCategoria(elemento, nombreDirecto) {
    var id, nombre;

    if (typeof elemento === 'object') {
        var card = $(elemento);
        id = card.data('id');
        nombre = card.data('nombre');
    } else {
        id = elemento;
        nombre = nombreDirecto;
    }

    categoriaActual = id;
    nombreCategoriaActual = nombre;
    $("#titulo_seccion").html('<i class="bi bi-box-seam"></i> ' + nombre);
    $("#subtitulo_seccion").text("Productos pertenecientes a esta categoría.");

    var datos = new FormData();
    datos.append('accion', 'consultarProductos');
    datos.append('idCategoria', id);
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar();

    // VALIDACIÓN DE DATOS
    // -- Validación de código --
    $("#codigoProd").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#codigoProd").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $(this), $("#scodigoProd"), "Código Inválido - Entre 5 y 50 caracteres.");
    });

    // -- Validación de nombre --
    $("#nombreProd").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC\s.-]*$/, e);
    });
    $("#nombreProd").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC\s.-]{3,50}$/, $(this), $("#snombreProd"), "Nombre Inválido - Entre 3 y 50 caracteres.");
    });

    // -- Validación de precio --
    $("#precioProd").on("keypress", function (e) {
        validarkeypress(/^[0-9.\b]*$/, e);
    });
    $("#precioProd").on("keyup", function () {
        var valor = parseFloat($(this).val());
        var res = validarkeyup(/^[0-9]{1,8}(\.[0-9]{1,2})?$/, $(this), $("#sprecioProd"), "Precio Inválido - Formato numérico correcto.");

        if (res === 1 && (isNaN(valor) || valor <= 0)) {
            $("#sprecioProd").text("El precio debe ser mayor a 0.");
        }
    });

    // -- Validación de descripción --
    $("#descProd").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]*$/, e);
    });
    $("#descProd").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{5,150}$/, $(this), $("#sdescProd"), "Descripción Inválida - Entre 5 y 150 caracteres.");
    });
    // Fin de Validaciones de datos

    // Evitar que el formulario se envíe con Enter
    $("#f").on("submit", function (e) {
        e.preventDefault();
    });

    // Control de Botones
    $("#btnGuardar").on("click", function () {
        // Botón de Incluir
        if ($(this).text().trim() === 'Guardar Producto' || $(this).text().trim() === 'incluir') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('codigoProd', $("#codigoProd").val());
                datos.append('nombreProd', $("#nombreProd").val());
                datos.append('precioProd', $("#precioProd").val());
                datos.append('descProd', $("#descProd").val());
                datos.append('fotoProd', $("#fotoProd").val());
                datos.append('idCategoria', $("#idCategoria").val());
                enviaAjax(datos);
            }
        }
        // Botón de Modificar
        else if ($(this).text().trim() === 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('codigoOriginal', $("#codigoOriginal").val());
                datos.append('codigoProd', $("#codigoProd").val());
                datos.append('nombreProd', $("#nombreProd").val());
                datos.append('precioProd', $("#precioProd").val());
                datos.append('descProd', $("#descProd").val());
                datos.append('fotoProd', $("#fotoProd").val());
                datos.append('idCategoria', $("#idCategoria").val());
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
            if (categoriaActual !== null) {
                verCategoria(categoriaActual, nombreCategoriaActual);
            } else {
                consultar();
            }
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
        $("#codigoProd").prop('readonly', false);
        if (categoriaActual !== null) {
            $("#idCategoria").val(categoriaActual);
        }
        $("#btnGuardar").text("Guardar Producto");
        $("#modal_producto").modal("show");
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    if ($("#idCategoria").val() === "") {
        mostrarAlerta("Atención", "Debe seleccionar una categoría", "warning");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{5,50}$/, $("#codigoProd"), $("#scodigoProd"), "Código Inválido.") === 0) {
        mostrarAlerta("Atención", "Código Inválido <br/>(Entre 5 y 50 caracteres).", "warning");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC\s.-]{3,50}$/, $("#nombreProd"), $("#snombreProd"), "Nombre Inválido.") === 0) {
        mostrarAlerta("Atención", "Nombre Inválido <br/>(Entre 3 y 50 caracteres).", "warning");
        return false;
    }
    else if (validarkeyup(/^[0-9]{1,8}(\.[0-9]{1,2})?$/, $("#precioProd"), $("#sprecioProd"), "Precio Inválido.") === 0) {
        mostrarAlerta("Atención", "Precio Inválido <br/>(Ingrese un monto válido).", "warning");
        return false;
    }
    else if (parseFloat($("#precioProd").val()) <= 0 || isNaN(parseFloat($("#precioProd").val()))) {
        mostrarAlerta("Atención", "El precio debe ser mayor a 0.", "warning");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC.,-]{5,150}$/, $("#descProd"), $("#sdescProd"), "Descripción Inválida.") === 0) {
        mostrarAlerta("Atención", "Descripción Inválida <br/>(Entre 5 y 150 caracteres).", "warning");
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

// Función para llenar el formulario con los datos del producto
function pone(pos) {
    var btn = $(pos);

    $("#codigoOriginal").val(btn.data('codigo'));
    $("#codigoProd").val(btn.data('codigo'));
    $("#nombreProd").val(btn.data('nombre'));
    $("#precioProd").val(btn.data('precio'));
    $("#descProd").val(btn.data('descripcion'));
    $("#fotoProd").val(btn.data('foto'));
    $("#idCategoria").val(btn.data('idcategoria'));

    $("#btnGuardar").text('modificar');
    $("#modal_producto").modal("show");
}

// Función Eliminar 
function eliminar(pos) {
    var btn = $(pos);
    var card = btn.closest('.card');
    var codigo = card.find('button.btn-outline-primary').data('codigo');

    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Deseas eliminar este producto del sistema!",
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
            datos.append('codigoProd', codigo);
            enviaAjax(datos);
        }
    });
}

// Función AJAX
function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=productos",
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

                if (lee.resultado === 'consultarCategorias') {
                    $("#cuadricula_items").html(lee.cards);
                    $("#idCategoria").html(lee.select);
                    $("#contenedor_botones_header").html(lee.boton);
                }
                else if (lee.resultado === 'consultarProductos') {
                    $("#cuadricula_items").html(lee.mensaje);
                    $("#idCategoria").html(lee.select);
                    $("#contenedor_botones_header").html(lee.boton);
                }
                else if (lee.resultado === 'incluir') {
                    if (lee.mensaje === 'Producto registrado con éxito.' || lee.mensaje === 'Producto incluido.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_producto").modal("hide");
                        if (categoriaActual !== null) {
                            verCategoria(categoriaActual, nombreCategoriaActual);
                        } else {
                            consultar();
                        }
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'modificar') {
                    if (lee.mensaje === 'Producto modificado con éxito.' || lee.mensaje === 'Producto modificado.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        $("#modal_producto").modal("hide");
                        if (categoriaActual !== null) {
                            verCategoria(categoriaActual, nombreCategoriaActual);
                        } else {
                            consultar();
                        }
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                }
                else if (lee.resultado === 'eliminar') {
                    if (lee.mensaje === 'Producto eliminado con éxito.' || lee.mensaje === 'Producto eliminado.') {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                        if (categoriaActual !== null) {
                            verCategoria(categoriaActual, nombreCategoriaActual);
                        } else {
                            consultar();
                        }
                    } else {
                        mostrarAlerta("Atención", lee.mensaje, "warning");
                    }
                }
                else if (lee.resultado === 'buscar') {
                    $('#cuadricula_items').html(lee.mensaje);
                    $("#contenedor_botones_header").html('');
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
    $("#codigoProd").val("");
    $("#nombreProd").val("");
    $("#precioProd").val("");
    $("#descProd").val("");
    $("#fotoProd").val("");

    // Limpiar los mensajes de validación
    $("#scodigoProd").text("");
    $("#snombreProd").text("");
    $("#sprecioProd").text("");
    $("#sdescProd").text("");
    $("#sidCategoria").text("");
}