// FUNCIONES DEL CATÁLOGO
$(document).ready(function () {
    try {
        cargarCategorias();
        cargarProductos();
        cargarCarritoLocal();
        cargarCuentas();
        actualizarContadorCarrito();
    } catch (e) {
        console.error(e);
    }

    function ejecutarBusqueda() {
        var valor = $("#valorBusqueda").val();

        if (valor && valor.trim().length > 0) {
            var datos = new FormData();
            datos.append("accion", "buscar");
            datos.append("valorBusqueda", valor);
            enviaAjax(datos);
        } else {
            cargarProductos();
        }
    }

    $("#valorBusqueda").on("keyup", function () {
        ejecutarBusqueda();
    });

    $("#btnBuscar").on("click", function () {
        ejecutarBusqueda();
    });
});

function cargarCategorias() {
    var datos = new FormData();
    datos.append("accion", "obtener_datos_categorias");
    enviaAjax(datos);
}

function cargarProductos() {
    var datos = new FormData();
    datos.append("accion", "cargarProductos");
    $("#titulo_categoria_activo").text("Todos los productos");
    enviaAjax(datos);
}

function listar_productos(idCategoria, nombreCat) {
    var datos = new FormData();
    datos.append("accion", "listar_productos");
    datos.append("idCategoria", idCategoria);
    $("#titulo_categoria_activo").text(nombreCat);
    enviaAjax(datos);
}

function abrirModalDetalle(id, nombre, descripcion, precio, imagen) {
    $("#modal-id-producto").val(id);
    $("#modal-titulo").text(nombre);
    $("#modal-descripcion").text(descripcion);
    $("#modal-precio").text("Bs. " + parseFloat(precio).toFixed(2));
    // SE GUARDA EL PRECIO EN UN INPUT OCULTO!
    $("#modal-precio").attr("precioOriginal", precio);
    $("#modal-img").attr("src", imagen);
    $("#modal-cantidad").text("1");

    var modal = new bootstrap.Modal(
        document.getElementById("modalDetalleProducto"),
    );
    modal.show();
}

function cambiarCantidadModal(cambio) {
    let spanCantidad = $("#modal-cantidad");
    let cantidadActual = parseInt(spanCantidad.text()) || 1;
    let nuevaCantidad = cantidadActual + cambio;

    if (nuevaCantidad >= 1) {
        spanCantidad.text(nuevaCantidad);
    }
}

function mostrarNotificacion(mensaje) {
    if ($("#contenidoModal").length) {
        $("#contenidoModal").html(mensaje);
        $("#mostrarModal").modal("show");
        setTimeout(function () {
            $("#mostrarModal").modal("hide");
        }, 2000);
    } else {
        alert(mensaje);
    }
}

// FUNCIONES DE CARRITO
function cargarCarritoLocal() {
    let carritoLocal = localStorage.getItem("carrito") || "[]";
    actualizarContadorCarrito();
    var datos = new FormData();
    datos.append("accion", "cargarCarrito");
    datos.append("carrito", carritoLocal);
    enviaAjax(datos);
}

document.addEventListener("DOMContentLoaded", function () {
    let carrito = JSON.parse(localStorage.removeItem("carrito")) || [];
});

$("#carritoModal").on("show.bs.modal", function () {
    cargarCarritoLocal();
});

// Función para agregar productos al carrito
$("#modal-btn-agregar").on("click", function () {
    let nuevoProd = {
        nombreProd: $("#modal-titulo").text(),
        precioProd: parseFloat($("#modal-precio").attr("precioOriginal")) || 0,
        fotoProd: $("#modal-img").attr("src"),
        cantidad: parseInt($("#modal-cantidad").text()) || 1,
    };

    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    // Verificamos si el producto ya existe en el carrito
    let index = carrito.findIndex((p) => p.nombreProd === nuevoProd.nombreProd);
    if (index !== -1) {
        carrito[index].cantidad += nuevoProd.cantidad;
    } else {
        carrito.push(nuevoProd);
    }

    localStorage.setItem("carrito", JSON.stringify(carrito));

    cargarCarritoLocal();
    $("#modalDetalleProducto").modal("hide");
    mostrarNotificacion("¡Producto agregado con éxito!");
});

function agregarAlCarrito(idProducto, cantidad) {
    console.log(
        "Producto ID: " + idProducto + " | Cantidad agregada: " + cantidad,
    );
    mostrarNotificacion("¡Producto agregado al carrito con éxito!");
}

function actualizarContadorCarrito() {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    let totalUnidades = 0;

    for (let i = 0; i < carrito.length; i++) {
        totalUnidades += parseInt(carrito[i].cantidad) || 0;
    }

    $("#contador-carrito").text(totalUnidades);
}

function cambiarCantidadLocal(index, cambio) {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    if (carrito[index]) {
        carrito[index].cantidad += cambio;

        if (carrito[index].cantidad < 1) {
            carrito[index].cantidad = 1;
        }

        localStorage.setItem("carrito", JSON.stringify(carrito));
        cargarCarritoLocal();
    }
}

$(document).on("click", ".btn-eliminar", function () {
    // Capturamos el índice del atributo data-index que guardamos en el botón
    let index = $(this).attr("data-index");

    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    carrito.splice(index, 1);
    localStorage.setItem("carrito", JSON.stringify(carrito));
    cargarCarritoLocal();
});

// FUNCIONES DE LA CUENTA
$("#cuentaDestino").on("change", function () {
    let idCuenta = $(this).val();
    if (!idCuenta) return;

    let montoTotal = $("#subtotalCompra").text() || "0.00";

    var datos = new FormData();
    datos.append("accion", "detalleCuenta");
    datos.append("idCuenta", idCuenta);
    datos.append("montoTotal", montoTotal);
    enviaAjax(datos);

    $(this).val("");
});

function cargarCuentas() {
    var datos = new FormData();
    datos.append("accion", "cargarCuentas");
    enviaAjax(datos);
}

$(document).on("click", "#btnCopiarDatos", function () {
    let banco = $("#copiarBanco").text();
    let cedula = $("#copiarCedula").text();
    let telefono = $("#copiarTlfn").text();
    let cuenta = $("#copiarNum").text();

    navigator.clipboard
        .writeText(
            banco + "\n" + cedula + "\n" + telefono + "\n" + "\n" + cuenta,
        )
        .then(() => {
            alert("¡Texto copiado al portapapeles!");
        })
        .catch((err) => {
            console.error("Error al copiar:", err);
        });
});

// VALIDACIONES DE CLIENTE
$("#cedulaCli").on("keypress", function (e) {
    validarkeypress(/^[0-9\-\.]*$/, e);
});
$("#cedulaCli").on("keyup", function () {
    validarkeyup(
        /^[0-9\.]{7,12}$/,
        $(this),
        $("#scedulaCli"),
        "Cédula Inválida - El formato debe ser: 999999999",
    );
});

// Validación de nombre
$("#nombreCli").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
});
$("#nombreCli").on("keyup", function () {
    validarkeyup(
        /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/,
        $(this),
        $("#snombreCli"),
        "Nombre Inválido - Solo letras (entre 3 y 100 caracteres)",
    );
});

// Validación de apellido
$("#apellidoCli").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
});
$("#apellidoCli").on("keyup", function () {
    validarkeyup(
        /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/,
        $(this),
        $("#sapellidoCli"),
        "Apellido Inválido - Solo letras (entre 3 y 100 caracteres)",
    );
});

// Validación de teléfono
$("#tlfCli").on("keypress", function (e) {
    validarkeypress(/^[0-9\-+ ]*$/, e);
});
$("#tlfCli").on("keyup", function () {
    validarkeyup(
        /^[0-9\-+ ]{11,12}$/,
        $(this),
        $("#stlfCli"),
        "Teléfono Inválido - Formato: 0000-0000000",
    );
});

// Validación de referencia
$("#refPago").on("keypress", function (e) {
    validarkeypress(/^[0-9\-+ ]*$/, e);
});
$("#refPago").on("keyup", function () {
    validarkeyup(
        /^[0-9\-+ ]{11,12}$/,
        $(this),
        $("#srefPago"),
        "Referencia Inválida - Formato: 0000",
    );
});

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

// Esta función no está en uso aun porque no está programado el envío a pedidos / el registro del cliente...
function validarEnvio() {
    if (
        validarkeyup(
            /^[0-9\.]{7,11}$/,
            $("#cedulaCli"),
            $("#scedulaCli"),
            "Cédula Inválida - El formato debe ser: 999999999",
        ) === 0
    ) {
        mostrarMensaje("Cédula Inválida <br/>(El formato debe ser: 99999999)");
        return false;
    } else if (
        validarkeyup(
            /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/,
            $("#nombreCli"),
            $("#snombreCli"),
            "Nombre Inválido - Solo letras (entre 3 y 100 caracteres)",
        ) === 0
    ) {
        mostrarMensaje(
            "Nombre Inválido <br>(Solo letras entre 3 y 100 caracteres)",
        );
        return false;
    } else if (
        validarkeyup(
            /^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/,
            $("#apellidoCli"),
            $("#sapellidoCli"),
            "Apellido Inválido - Solo letras (entre 3 y 100 caracteres)",
        ) === 0
    ) {
        mostrarMensaje(
            "Apellido Inválido <br>(Solo letras entre 3 y 100 caracteres)",
        );
        return false;
    } else if (
        validarkeyup(
            /^[0-9\-+ ]{11,12}$/,
            $("#tlfCli"),
            $("#stlfCli"),
            "Teléfono Inválido - Formato: 0000-0000000",
        ) === 0
    ) {
        mostrarMensaje("Teléfono Inválido <br>(Formato: 0000-0000000)");
        return false;
    }

    return true;
}

// ENVIAR AJAX
function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=catalogo",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function () {},
        timeout: 10000,
        success: function (respuesta) {
            console.log(respuesta);
            try {
                var lee = JSON.parse(respuesta);

                if (
                    lee.resultado == "cargarProductos" ||
                    lee.resultado == "listar_productos" ||
                    lee.resultado == "buscar"
                ) {
                    $("#cuadricula_items").html(lee.mensaje);
                } else if (lee.resultado == "categorias_cargadas") {
                    $("#contenedor_categorias").html(lee.mensaje);
                } else if (lee.resultado == "cargarCarrito") {
                    $("#contenedorCarrito").html(lee.mensaje);
                    if (lee.subtotal !== undefined) {
                        $("#subtotalCompra").text("$" + lee.subtotal);
                        $("#costoTotal").text("$" + lee.subtotal);
                    }
                } else if (lee.resultado == "cuentasCargadas") {
                    $("#cuentaDestino").html(lee.mensaje);
                } else if (lee.resultado == "mostrarDetalleCuenta") {
                    $("#modalCuentaCliCont").html(lee.mensaje);
                    $("#modalCuentaCli").modal("show");
                } else if (lee.resultado == "error") {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                alert(
                    "Error en JSON: " + e.name + " | Respuesta: " + respuesta,
                );
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mostrarMensaje("Servidor ocupado, intente de nuevo.");
            } else {
                mostrarMensaje("ERROR: <br/>" + status + " " + err);
            }
        },
        complete: function () {},
    });
}