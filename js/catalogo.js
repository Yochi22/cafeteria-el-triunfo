let tasaDolar = 0;

$(document).ready(function () {
    try {
        cargarCategorias();
        cargarProductos();
        obtenerTasaActual()
        cargarCarritoLocal();
        cargarCuentas();
        actualizarContadorCarrito();
        if (typeof actualizarTasaCatalogo === "function") {
            actualizarTasaCatalogo();
        }
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

    $("#f").on("submit", function (e) {
        e.preventDefault();
    });
});

function cargarCategorias() {
    var datos = new FormData();
    datos.append("accion", "obtenerCategorias");
    enviaAjax(datos);
}

function cargarProductos() {
    var datos = new FormData();
    datos.append("accion", "cargarProductos");
    $("#titulo_categoria_activo").text("Todos los productos");
    enviaAjax(datos);
}

function listarProductos(idCategoria, nombreCat) {
    var datos = new FormData();
    datos.append("accion", "listarProductos");
    datos.append("idCategoria", idCategoria);
    $("#titulo_categoria_activo").text(nombreCat);
    enviaAjax(datos);
}

function obtenerTasaActual() {
    let datos = new FormData();
    datos.append("accion", "obtenerTasaHoy");
    enviaAjax(datos);
}

function abrirModalDetalle(id, nombre, descripcion, precio, imagen) {
    $("#modal-id-producto").val(id);
    $("#modal-titulo").text(nombre);
    $("#modal-descripcion").text(descripcion);
    $("#modal-precio").text("$ " + parseFloat(precio).toFixed(2));
    $("#modal-precio").attr("precioOriginal", precio);
    $("#modal-img").attr("src", imagen);
    $("#modal-cantidad").text("1");

    let precioEnBs = parseFloat(precio) * tasaDolar;

    if (tasaDolar > 0) {
        $("#modal-precioBs").text(`Bs. ${precioEnBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
    } else {
        $("#modal-precioBs").text("Bs. 0,00 (Sin tasa)");
    }

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

function cargarCarritoLocal() {
    let carritoLocal = localStorage.getItem("carrito") || "[]";
    actualizarContadorCarrito();
    var datos = new FormData();
    datos.append("accion", "cargarCarrito");
    datos.append("carrito", carritoLocal);
    enviaAjax(datos);
}

document.addEventListener("DOMContentLoaded", function () {
    JSON.parse(localStorage.getItem("carrito") || "[]");
});

$("#carritoModal").on("show.bs.modal", function () {
    cargarCarritoLocal();
});

$("#modal-btn-agregar").on("click", function () {
    let nuevoProd = {
        codigoProd: $("#modal-id-producto").val(),
        nombreProd: $("#modal-titulo").text(),
        precioProd: parseFloat($("#modal-precio").attr("precioOriginal")) || 0,
        fotoProd: $("#modal-img").attr("src"),
        cantidad: parseInt($("#modal-cantidad").text()) || 1,
    };

    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];

    let index = carrito.findIndex((p) => p.nombreProd === nuevoProd.nombreProd);
    if (index !== -1) {
        carrito[index].cantidad += nuevoProd.cantidad;
    } else {
        carrito.push(nuevoProd);
    }

    localStorage.setItem("carrito", JSON.stringify(carrito));

    cargarCarritoLocal();
    $("#modalDetalleProducto").modal("hide");
    mostrarAlerta("", "¡Producto agregado al carrito!", "<i class='bi bi-cart-check-fill' style='color: #FF8C00;'></i>");
});

function actualizarContadorCarrito() {
    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    let totalUnidades = 0;

    for (let i = 0; i < carrito.length; i++) {
        totalUnidades += parseInt(carrito[i].cantidad) || 0;
    }

    $("#contador-carrito").text(totalUnidades);
}

function actualizarPreciosEnBs() {
    let subtotalTexto = $('#subtotalCompra').text().replace('$', '').trim();
    let subtotalUsd = parseFloat(subtotalTexto) || 0;

    let precioDelivery = parseFloat($("#zonaDelivery").data("precioDelivery")) || 0;
    let totalUsdFinal = subtotalUsd + precioDelivery;

    let subtotalBs = subtotalUsd * tasaDolar;
    let precioDeliveryBs = precioDelivery * tasaDolar;

    $('#subtotalFinalBs').text(`Bs. ${subtotalBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
    $('#subtotalFinal').text("$" + subtotalUsd.toFixed(2));
    $('#deliveryTotalBs').text(`Bs. ${precioDeliveryBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
    $('#deliveryTotal').text("$" + precioDelivery.toFixed(2));

    $('#montoTotal').text("$" + totalUsdFinal.toFixed(2));

    if (tasaDolar <= 0) return;

    let precioModalUsd = parseFloat($("#modal-precio").attr("precioOriginal")) || 0;
    if (precioModalUsd > 0) {
        let precioModalBs = precioModalUsd * tasaDolar;
        $("#modal-precioBs").text(`Bs. ${precioModalBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
    }

    $('#contenedorCarrito .carritoContenido').each(function () {
        let precioUsd = parseFloat($(this).attr('data-precio')) || 0;
        let cantidad = parseInt($(this).attr('data-cantidad')) || 1;
        let totalItemBs = precioUsd * cantidad * tasaDolar;

        $(this).find('.precioProdBs').text(`Bs. ${totalItemBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`);
    });

    let totalGeneralBs = totalUsdFinal * tasaDolar;
    let formatoBs = `Bs. ${totalGeneralBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

    $('#subtotalCompraBs').text(formatoBs);
    $('#montoTotalBs').text(formatoBs);
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
    let index = $(this).attr("data-index");

    let carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    carrito.splice(index, 1);
    localStorage.setItem("carrito", JSON.stringify(carrito));
    cargarCarritoLocal();
});

$("#zonaDelivery").on("change", function () {
    var zonaSeleccionada = $(this).val();

    if (!zonaSeleccionada) {
        $("#zonaDelivery").data("precioDelivery", 0);
        actualizarPreciosEnBs();
        return;
    }

    var datos = new FormData();
    datos.append("accion", "obtenerPrecioDelivery");
    datos.append("zona", zonaSeleccionada);
    enviaAjax(datos);
});

function actualizarTotalConDelivery(precioDelivery) {
    let subtotalTexto = $('#subtotalCompra').text().replace('$', '').trim();
    let subtotalUsd = parseFloat(subtotalTexto) || 0;
    let totalUsdFinal = subtotalUsd + precioDelivery;

    $('#montoTotal').text("$" + totalUsdFinal.toFixed(2));

    if (tasaDolar > 0) {
        let totalGeneralBs = totalUsdFinal * tasaDolar;
        let formatoBs = `Bs. ${totalGeneralBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        $('#montoTotalBs').text(formatoBs);
    }
}

//AUTOCOMPLETADO DE FORMULARIO
function autocompletarCliente(cedula) {
    let datos = new FormData();
    datos.append("accion", "autocompletarCliente");
    datos.append("cedulaCli", cedula);
    enviaAjax(datos);
}

$("#cedulaCli").on("keyup blur", function () {
    const cedula = $(this).val().trim();
    if (validarkeyup(/^[0-9]{7,8}$/, $("#cedulaCli"), $("#scedulaCli"), "Cédula Inválida") === 1) {
        autocompletarCliente(cedula) }
});

// elimina el autocompletado al modificar la cedula
$("#cedulaCli").on("keydown", function () {
    $("#nombreCli").val("");
    $("#apellidoCli").val("");
    $("#tlfCli").val("");
});


$("#cuentaDestino").on("change", function () {
    let idCuenta = $(this).val();

    if (idCuenta) {
        $("#btnAbrirModal").prop("disabled", false);

        let montoTotal = $("#montoTotalBs").text() || "0.00";

        var datos = new FormData();
        datos.append('accion', 'detalleCuenta');
        datos.append('idCuenta', idCuenta);
        datos.append('montoTotal', montoTotal);
        enviaAjax(datos);
    } else {
        $("#btnAbrirModal").prop("disabled", true);
    }
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
    let montoTotal = $("#montoTotalBs").text() || "0.00";

    navigator.clipboard.writeText(banco + "\n" + cedula + "\n" + telefono + "\n" + cuenta + "\n" + montoTotal)
        .then(() => {
            mostrarAlerta("", "Texto copiado al portapapeles", "info");
        })
        .catch((err) => {
            console.error("Error al copiar:", err);
        });
});

$("#btnAbrirModal").on("click", function () {
    $("#modalCuentaCli").modal('show');
});

$("#cedulaCli").on("keypress", function (e) {
    validarkeypress(/^[0-9]*$/, e);
});
$("#cedulaCli").on("keyup", function () {
    validarkeyup(/^[0-9]{7,8}$/, $(this), $("#scedulaCli"), "Cédula Inválida - Debe tener entre 7 y 8 dígitos numéricos.");
});

$("#nombreCli").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
});
$("#nombreCli").on("keyup", function () {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombreCli"), "Nombre Inválido - Solo letras (entre 3 y 100 caracteres).");
});

$("#apellidoCli").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
});
$("#apellidoCli").on("keyup", function () {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#sapellidoCli"), "Apellido Inválido - Solo letras (entre 3 y 100 caracteres).");
});

$("#tlfCli").on("keypress", function (e) {
    validarkeypress(/^[0-9]*$/, e);
});
$("#tlfCli").on("keyup", function () {
    validarkeyup(/^(0412|0414|0416|0422|0424|0426|02[0-9]{2})[0-9]{7}$/, $(this), $("#stlfCli"), "Teléfono Inválido - Formato : 04241234567.");
});

$("#calleDelivery").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC\-\#\.,]*$/, e);
});
$("#calleDelivery").on("keyup", function () {
    validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC\-\#\.,]{3,100}$/, $(this), $("#scalleDelivery"), "Calle Inválida - Entre 3 y 100 caracteres.");
});

$("#numCasaDelivery").on("keypress", function (e) {
    validarkeypress(/^[0-9]*$/, e);
});
$("#numCasaDelivery").on("keyup", function () {
    validarkeyup(/^[0-9]{1,6}$/, $(this), $("#snumCasaDelivery"), "Número Inválido - Solo números (1 a 6 dígitos).");
});

$("#refPago").on("keypress", function (e) {
    validarkeypress(/^[0-9\-+]*$/, e);
});
$("#refPago").on("keyup", function () {
    validarkeyup(/^[0-9\-+]{4}$/, $(this), $("#srefPago"), "Referencia Inválida - Solo 4 últimos dígitos.");
});

$("#cedulaPago").on("keypress", function (e) {
    validarkeypress(/^[0-9]*$/, e);
});
$("#cedulaPago").on("keyup", function () {
    validarkeyup(/^[0-9]{7,8}$/, $(this), $("#scedulaPago"), "Cédula Inválida - Debe tener entre 7 y 8 dígitos numéricos.");
});

$("#tlfPago").on("keypress", function (e) {
    validarkeypress(/^[0-9]*$/, e);
});
$("#tlfPago").on("keyup", function () {
    validarkeyup(/^(0412|0414|0416|0422|0424|0426|02[0-9]{2})[0-9]{7}$/, $(this), $("#stlfPago"), "Teléfono Inválido - Formato : 04241234567.");
});

$("#tipoPago").on("change", function () {
    var esPagoMovil = $(this).val() === "Pago Móvil";
    $("#datosPagoMovil").toggle(esPagoMovil);
    $("#cuentaDestino, #refPago, #cedulaPago, #tlfPago").prop("required", esPagoMovil);
    if (!esPagoMovil) {
        $("#cuentaDestino, #refPago, #cedulaPago, #tlfPago").val("");
        $("#srefPago, #scedulaPago, #stlfPago").text("");
    }
});

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
        etiquetamensaje.removeClass("text-success").addClass("text-danger").text(mensaje);
        return 0;
    }
}

function validarEnvio() {
    if (validarkeyup(/^[0-9]{7,8}$/, $("#cedulaCli"), $("#scedulaCli"), "Cédula Inválida") === 0) {
        mostrarAlerta("", "Cédula Inválida <br/>(El formato debe ser entre 7 y 8 dígitos).", "warning");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombreCli"), $("#snombreCli"), "Nombre Inválido") === 0) {
        mostrarAlerta("", "Nombre Inválida <br/>(Solo letras entre 3 y 100 caracteres).", "warning");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#apellidoCli"), $("#sapellidoCli"), "Apellido Inválido") === 0) {
        mostrarAlerta("", "Apellido Inválido <br/>(Solo letras entre 3 y 100 caracteres).", "warning");
        return false;
    }
    else if (validarkeyup(/^(0412|0414|0416|0422|0424|0426|02[0-9]{2})[0-9]{7}$/, $("#tlfCli"), $("#stlfCli"), "Teléfono Inválido") === 0) {
        mostrarAlerta("", "Teléfono Inválido <br/>(Formato : 04241234567).", "warning");
        return false;
    }

    if (!$("#zonaDelivery").val()) {
        mostrarAlerta("", "Seleccione una zona de delivery.", "warning");
        return false;
    }
    else if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC\-\#\.,]{3,100}$/, $("#calleDelivery"), $("#scalleDelivery"), "Calle Inválida") === 0) {
        mostrarAlerta("", "Calle Inválida <br/>(Entre 3 y 100 caracteres)", "warning");
        return false;
    }
    else if (validarkeyup(/^[0-9]{1,6}$/, $("#numCasaDelivery"), $("#snumCasaDelivery"), "Número Inválido") === 0) {
        mostrarAlerta("", "Número de Casa Inválido <br/>(Solo números enteros)", "warning");
        return false;
    }

    else if ($("#tipoPago").val() === "") {
        mostrarAlerta("", "Seleccione un método de pago.", "warning");
        return false;
    }

    if ($("#tipoPago").val() === "Pago Móvil") {
        if (validarkeyup(/^[0-9\-+]{4}$/, $("#refPago"), $("#srefPago"), "Referencia Inválida") === 0) {
            mostrarAlerta("", "Referencia Inválida <br/>(Solo 4 últimos dígitos)", "warning");
            return false;
        }
        if (validarkeyup(/^[0-9]{7,8}$/, $("#cedulaPago"), $("#scedulaPago"), "Cédula Inválida") === 0) {
            mostrarAlerta("", "Cédula Inválida de quien pagó <br/>(El formato debe ser entre 7 y 8 dígitos).", "warning");
            return false;
        }
        if (validarkeyup(/^(0412|0414|0416|0422|0424|0426|02[0-9]{2})[0-9]{7}$/, $("#tlfPago"), $("#stlfPago"), "Teléfono Inválido") === 0) {
            mostrarAlerta("", "Teléfono Inválido de quien pagó <br/>(Formato : 04241234567).", "warning");
            return false;
        }
        if (!$("#cuentaDestino").val()) {
            mostrarAlerta("", "Seleccione la cuenta destino del Pago Móvil.", "warning");
            return false;
        }
    }

    return true;
}

$("#btnGuardar").on("click", function () {
    if (validarEnvio()) {
        var carrito = JSON.parse(localStorage.getItem("carrito") || "[]");

        if (!carrito.length) {
            mostrarAlerta("", "El carrito está vacío.", "warning");
            return;
        }
        if (!$("#tipoPago").val()) {
            mostrarAlerta("", "Seleccione un método de pago.", "warning");
            return;
        }
        if (carrito.some(function (p) { return !p.codigoProd; })) {
            mostrarAlerta("", "Hay productos antiguos en el carrito. Retírelos y agréguelos nuevamente para continuar.", "warning");
            return;
        }

        var datos = new FormData($("#f")[0]);
        datos.set("accion", "registrarPedidoCliente");
        datos.append("carrito", JSON.stringify(carrito));
        enviaAjax(datos);
    }
});

function limpia() {
    $("#nombreCli").val("");
    $("#apellidoCli").val("");
    $("#cedulaCli").val("");
    $("#tlfCli").val("");
    $("#zonaDelivery").val("");
    $("#numCasaDelivery").val("");
    $("#calleDelivery").val("");
    $("#tipoPago").val("");
    $("#cuentaDestino").val("");
    $("#refPago").val("");
    $("#cedulaPago").val("");
    $("#tlfPago").val("");
    $("#datosPagoMovil").hide();

    $("#snombreCli").text("");
    $("#sapellidoCli").text("");
    $("#scedulaCli").text("");
    $("#stlfCli").text("");
    $("#srefPago").text("");
    $("#scedulaPago").text("");
    $("#stlfPago").text("");
    $("#scalleDelivery").text("");
    $("#snumCasaDelivery").text("");
}

function mostrarAlerta(titulo, mensaje, icono) {
    if ($('#swal-anim-style').length === 0) {
        $('<style>', {
            id: 'swal-anim-style',
            html: `
                @keyframes slideInRight {
                    0% {
                        transform: translateX(-40px);
                        opacity: 0;
                    }
                    100% {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                .animate-slide-in {
                    display: inline-block;
                    animation: slideInRight 0.5s ease-out forwards;
                }
            `
        }).appendTo('head');
    }

    let config = {
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#FF8C00',
        returnFocus: false,
        customClass: {
            popup: 'rounded-4 shadow'
        }
    };

    if (icono && icono.trim().startsWith('<')) {
        config.title = `<div class="mb-2 fs-1"><div class="animate-slide-in d-inline-block">${icono}</div></div>${titulo}`;
        config.html = mensaje;
    } else {
        config.title = titulo;
        config.html = mensaje;
        config.icon = icono;
    }

    Swal.fire(config);
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=catalogo",
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

                if (
                    lee.resultado == "cargarProductos" ||
                    lee.resultado == "listarProductos" ||
                    lee.resultado == "buscar"
                ) {
                    $("#cuadricula_items").html(lee.mensaje);

                } else if (lee.resultado == "categorias_cargadas") {
                    $("#contenedor_categorias").html(lee.mensaje);

                } else if (lee.resultado == "cargarCarrito") {
                    $("#contenedorCarrito").html(lee.mensaje);
                    if (lee.subtotal !== undefined) {
                        $("#subtotalCompra").text("$" + lee.subtotal);
                        $("#montoTotal").text("$" + lee.subtotal);
                    }
                    actualizarPreciosEnBs();

                } else if (lee.resultado == "tasaActual") {
                    tasaDolar = parseFloat(lee.precio) || 0;
                    actualizarPreciosEnBs();

                } else if (lee.resultado == "precioDeliveryObtenido") {
                    let precioDelivery = parseFloat(lee.precio) || 0;
                    $("#zonaDelivery").data("precioDelivery", precioDelivery);
                    actualizarPreciosEnBs();
                
                } else if (lee.resultado == "clienteEncontrado") {
                    if (lee.cliente) {
                        $("#nombreCli").val(lee.cliente.nombreCli);
                        $("#apellidoCli").val(lee.cliente.apellidoCli);
                        $("#tlfCli").val(lee.cliente.tlfCli); 
                        $("#scedulaCli").removeClass("text-danger").addClass("text-success").text(lee.mensaje); }
                
                } else if (lee.resultado == "cuentasCargadas") {
                    $("#cuentaDestino").html(lee.mensaje);

                } else if (lee.resultado == "mostrarDetalleCuenta") {
                    $("#modalCuentaCliCont").html(lee.mensaje);
                    $("#modalCuentaCli").modal('show');

                } else if (lee.resultado == "pedidoClienteRegistrado") {
                    $("#pagoModal").modal("hide");
                    localStorage.removeItem("carrito");
                    actualizarContadorCarrito();
                    limpia();
                    cargarCarritoLocal();
                    mostrarAlerta("¡Éxito!", lee.mensaje, "success");

                } else if (lee.resultado == "error") {
                    mostrarAlerta("Error", lee.mensaje, "error");
                }
            } catch (e) {
                alert(
                    "Error en JSON: " + e.name + " | Respuesta: " + respuesta,
                );
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mostrarAlerta("", "Servidor ocupado, intente de nuevo.", "error");
            } else {
                mostrarAlerta("", "ERROR: <br/>" + status + " " + err, "error");
            }
        },
        complete: function () { },
    });
}