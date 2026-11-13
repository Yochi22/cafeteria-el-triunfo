// Función para la lista de pedidos
function consultar(estado = "Todos") {
    var datos = new FormData();
    datos.append("accion", "consultar");
    datos.append("estadoFiltro", estado);
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar("Todos");

    // Filtrar pedidos por estado
    $("#filtroEstado").on("change", function () {
        consultar($(this).val());
    });

    // Control de visibilidad de datos de pago según estado del pedido
    $("#estadoPedido").on("change", function () {
        $("#datosPagoPedido").toggle($(this).val() === "Finalizado");
    });

    // Control de campos según método de pago
    $("#tipoPagoPedido").on("change", function () {
        var esMovil = $(this).val() === "Pago Móvil";
        $("#cuentaPagoPedidoCont, #cedulaPagoPedidoCont, #tlfPagoPedidoCont, #referenciaPagoPedidoCont").toggle(esMovil);
        $("#cedulaPagoPedido, #tlfPagoPedido, #referenciaPagoPedido").prop("required", esMovil);
        if ($(this).val() !== "" && !esMovil) {
            $("#cedulaPagoPedido, #tlfPagoPedido, #referenciaPagoPedido").val("");
            $("#scedulaPagoPedido, #stlfPagoPedido, #sreferenciaPagoPedido").text("");
        }
    });

    // VALIDACIÓN DE DATOS
    // -- Validación de comprobante --
    $("#numComprobante").on("keypress", function (e) { validarkeypress(/^[0-9]*$/, e); });
    $("#numComprobante").on("keyup", function () {
        validarkeyup(/^[0-9]{4}$/, $(this), $("#snumComprobante"), "Comprobante Inválido");
    });

    // -- Validación de cédula de pago --
    $("#cedulaPagoPedido").on("keypress", function (e) { validarkeypress(/^[0-9\-\.]*$/, e); });
    $("#cedulaPagoPedido").on("keyup", function () {
        validarkeyup(/^[0-9\.]{7,12}$/, $(this), $("#scedulaPagoPedido"), "Cédula Inválida - Formato: 999999999");
    });

    // -- Validación de teléfono de pago --
    $("#tlfPagoPedido").on("keypress", function (e) { validarkeypress(/^[0-9\-+ ]*$/, e); });
    $("#tlfPagoPedido").on("keyup", function () {
        validarkeyup(/^[0-9\-+]{11,12}$/, $(this), $("#stlfPagoPedido"), "Teléfono Inválido - Formato: 0000-0000000");
    });

    // -- Validación de referencia de pago --
    $("#referenciaPagoPedido").on("keypress", function (e) { validarkeypress(/^[0-9\-+ ]*$/, e); });
    $("#referenciaPagoPedido").on("keyup", function () {
        validarkeyup(/^[0-9\-+]{4}$/, $(this), $("#sreferenciaPagoPedido"), "Referencia Inválida");
    });
    // Fin de Validaciones de datos

    // Control de botones auxiliares de cuentas
    $("#btnBuscarCuentaPago").on("click", function () { $("#modal_cuentas").modal("show"); });
    $("#btnQuitarCuentaPago").on("click", function () { $("#idCuenta").val(""); $("#cuentaInfo, #cuentaPagoPedidoInfo").val(""); });

    // Carga inicial de datos para los modales de selección
    var datosCli = new FormData();
    datosCli.append("accion", "modalclientes");
    enviaAjax(datosCli);

    var datosCue = new FormData();
    datosCue.append("accion", "modalcuentas");
    enviaAjax(datosCue);

    var datosProd = new FormData();
    datosProd.append("accion", "modalproductos");
    enviaAjax(datosProd);

    // Evitar envío por Enter
    $("#f").on("submit", function (e) {
        e.preventDefault();
    });

    // Control de Botones Guardar (Incluir / Modificar)
    $("#btnGuardar").on("click", function () {
        if (!validarEnvio()) return;
        var datos = new FormData($("#f")[0]);
        datos.set("tipoPago", $("#tipoPagoPedido").val());
        datos.set("cedulaPago", $("#cedulaPagoPedido").val());
        datos.set("tlfPago", $("#tlfPagoPedido").val());
        datos.set("referenciaPago", $("#referenciaPagoPedido").val());

        if ($(this).text().trim() === 'incluir' || $(this).text().trim() === 'Procesar Pedido') {
            datos.append("accion", "incluir");
            enviaAjax(datos);
        } else if ($(this).text().trim() === 'modificar') {
            datos.append("accion", "modificar");
            enviaAjax(datos);
        }
    });

    // Control de modales de búsqueda
    $("#btnBuscarCliente").on("click", function () {
        $("#modal_clientes").modal("show");
    });

    $("#btnBuscarCuenta").on("click", function () {
        $("#modal_cuentas").modal("show");
    });

    $("#btnBuscarProducto").on("click", function () {
        $("#modal_productos").modal("show");
    });

    // Abrir modal de inclusión
    $("#incluir").on("click", function () {
        limpia();
        $("#modal_pedido").modal("show");
    });
});

// Validación de los datos antes de enviarlos
function validarEnvio() {
    if ($("#idCliente").val() == "") {
        mostrarAlerta("Atención", "Debe seleccionar un cliente antes de procesar.", "warning");
        return false;
    }
    if ($("#detallesdeventa tr").length == 0) {
        mostrarAlerta("Atención", "Debe agregar al menos un producto al pedido.", "warning");
        return false;
    }
    if ($("#numComprobante").val().trim() !== "") {
        if (validarkeyup(/^[0-9]{4}$/, $("#numComprobante"), $("#snumComprobante"), "Comprobante Inválido") === 0) {
            mostrarAlerta("Atención", "Número de Comprobante Inválido", "warning");
            return false;
        }
    }
    if ($("#estadoPedido").val() === "Finalizado") {
        if (!$("#tipoPagoPedido").val()) {
            mostrarAlerta("Atención", "Confirme el método de pago para finalizar el pedido.", "warning");
            return false;
        }
        if ($("#tipoPagoPedido").val() === "Pago Móvil") {
            if (!$("#idCuenta").val()) {
                mostrarAlerta("Atención", "Seleccione la cuenta destino del Pago Móvil.", "warning");
                return false;
            }
            if (validarkeyup(/^[0-9\-+ ]{4}$/, $("#referenciaPagoPedido"), $("#sreferenciaPagoPedido"), "Referencia Inválida") === 0) {
                mostrarAlerta("Atención", "Referencia inválida. Revise el formato indicado.", "warning");
                return false;
            }
            if (validarkeyup(/^[0-9\.]{7,12}$/, $("#cedulaPagoPedido"), $("#scedulaPagoPedido"), "Cédula Inválida") === 0) {
                mostrarAlerta("Atención", "Cédula inválida del pagador.", "warning");
                return false;
            }
            if (validarkeyup(/^[0-9\-+ ]{11,12}$/, $("#tlfPagoPedido"), $("#stlfPagoPedido"), "Teléfono Inválido") === 0) {
                mostrarAlerta("Atención", "Teléfono inválido del pagador.", "warning");
                return false;
            }
        }
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

// Funciones de selección desde modales auxiliares
function seleccionarCliente(id, cedula, nombre) {
    $("#idCliente").val(id);
    $("#clienteInfo").val(cedula + " - " + nombre);
    $("#modal_clientes").modal("hide");
}

function seleccionarCuenta(id, banco, numero) {
    $("#idCuenta").val(id);
    $("#cuentaInfo").val(banco + " - " + numero);
    $("#cuentaPagoPedidoInfo").val(banco + " - " + numero);
    $("#modal_cuentas").modal("hide");
}

function seleccionarProducto(id, codigo, nombre, precio) {
    var encontro = false;

    $("#detallesdeventa tr").each(function () {
        if (id == $(this).find("input[name='idProducto[]']").val()) {
            encontro = true;
            var inputCant = $(this).find("input[name='cantidad[]']");
            inputCant.val(parseInt(inputCant.val()) + 1);
            modificasubtotal(inputCant[0]);
        }
    });

    if (!encontro) {
        var fila =
            "<tr>" +
            "<td class='align-middle fw-bold'><input type='hidden' name='idProducto[]' value='" +
            id +
            "'>" +
            codigo +
            "</td>" +
            "<td class='align-middle'>" +
            nombre +
            "</td>" +
            "<td class='align-middle'><input type='hidden' name='precio[]' value='" +
            precio +
            "'>$" +
            precio +
            "</td>" +
            "<td class='align-middle' style='width: 120px;'><input type='number' class='form-control form-control-sm text-center' value='1' name='cantidad[]' min='1' onchange='modificasubtotal(this)' onkeyup='modificasubtotal(this)'></td>" +
            "<td class='align-middle text-success fw-bold subtotal-cell'>$" +
            precio +
            "</td>" +
            "<td class='align-middle'><button type='button' class='btn btn-outline-danger btn-sm' onclick='eliminalineadetalle(this)'><i class='bi bi-x-lg'></i></button></td>" +
            "</tr>";
        $("#detallesdeventa").append(fila);
        calculaTotal();
    }
    $("#modal_productos").modal("hide");
}

// Gestión de subtotales y total general
function modificasubtotal(input) {
    var fila = $(input).closest("tr");
    var precio = parseFloat(fila.find("input[name='precio[]']").val());
    var cantidad = parseInt($(input).val());

    if (isNaN(cantidad) || cantidad < 1) {
        cantidad = 1;
        $(input).val(1);
    }

    var subtotal = precio * cantidad;
    fila.find(".subtotal-cell").text("$" + subtotal.toFixed(2));
    calculaTotal();
}

function eliminalineadetalle(btn) {
    $(btn).closest("tr").remove();
    calculaTotal();
}

function calculaTotal() {
    var total = 0;

    $("#detallesdeventa tr").each(function () {
        var precio = parseFloat($(this).find("input[name='precio[]']").val());
        var cantidad = parseInt($(this).find("input[name='cantidad[]']").val());
        if (!isNaN(precio) && !isNaN(cantidad)) {
            total += precio * cantidad;
        }
    });
    $("#totalGeneral").text("$" + total.toFixed(2));
}

// Función keypress
function validarkeypress(er, e) {
    var key = e.keyCode;
    var tecla = String.fromCharCode(key);
    var permitido = er.test(tecla);

    if (!permitido) e.preventDefault();
}

// Función keyup
function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    var valido = er.test(etiqueta.val());

    if (valido) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

// Consultar un pedido específico para modificar
function pone(idPedido) {
    var datos = new FormData();
    datos.append("accion", "consultar_uno");
    datos.append("idPedido", idPedido);
    enviaAjax(datos);
}

// Función Eliminar
function eliminar(idPedido) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡Deseas eliminar este pedido del sistema!",
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
            datos.append("accion", "eliminar");
            datos.append("idPedido", idPedido);
            enviaAjax(datos);
        }
    });
}

// Función AJAX
function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);

                if (lee.resultado == "consultar") {
                    $("#contenedor_pedidos").html(lee.mensaje);
                } else if (lee.resultado == "modalclientes") {
                    $("#listaClientesModal").html(lee.mensaje);
                } else if (lee.resultado == "modalcuentas") {
                    $("#listaCuentasModal").html(lee.mensaje);
                } else if (lee.resultado == "modalproductos") {
                    $("#listaProductosModal").html(lee.mensaje);
                } else if (lee.resultado == "consultar_uno") {
                    limpia();
                    $("#idPedido").val(lee.idPedido);
                    $("#idCliente").val(lee.idCliente);
                    $("#clienteInfo").val(lee.clienteInfo);
                    $("#idCuenta").val(lee.idCuenta);
                    $("#cuentaInfo").val(lee.cuentaInfo);
                    $("#numComprobante").val(lee.numComprobante);
                    $("#estadoPedido").val(lee.estadoPedido);
                    $("#tipoPagoPedido").val(lee.tipoPago || "");
                    $("#cedulaPagoPedido").val(lee.cedulaPago || "");
                    $("#tlfPagoPedido").val(lee.tlfPago || "");
                    $("#referenciaPagoPedido").val(lee.numComprobante || "");
                    $("#cuentaPagoPedidoInfo").val(lee.cuentaInfo || "");
                    if (lee.estadoPedido === "Finalizado") $("#datosPagoPedido").show();
                    $("#tipoPagoPedido").trigger("change");
                    $("#detallesdeventa").html(lee.detalleHtml);
                    calculaTotal();
                    $("#divEstadoPedido").show();
                    $("#btnGuardar").text("modificar");
                    $("#modal_pedido").modal("show");
                } else if (lee.resultado == "incluir") {
                    if (lee.mensaje.toLowerCase().includes("éxito") || lee.mensaje.toLowerCase().includes("exito")) {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                    $("#modal_pedido").modal("hide");
                    consultar($("#filtroEstado").val());
                } else if (lee.resultado == "modificar") {
                    if (lee.mensaje.toLowerCase().includes("éxito") || lee.mensaje.toLowerCase().includes("exito")) {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                    } else {
                        mostrarAlerta("Información", lee.mensaje, "info");
                    }
                    $("#modal_pedido").modal("hide");
                    consultar($("#filtroEstado").val());
                } else if (lee.resultado == "eliminar") {
                    if (lee.mensaje.toLowerCase().includes("éxito") || lee.mensaje.toLowerCase().includes("exito")) {
                        mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                    } else {
                        mostrarAlerta("Atención", lee.mensaje, "warning");
                    }
                    consultar($("#filtroEstado").val());
                } else if (lee.resultado == "error") {
                    mostrarAlerta("Error", lee.mensaje, "error");
                }
            } catch (e) {
                mostrarAlerta("Error de sistema", "Error al procesar JSON: " + e.name, "error");
            }
        },
        error: function (request, status, error) {
            mostrarAlerta("Error de conexión", "No se pudo comunicar con el servidor.", "error");
        },
    });
}

// Función para limpiar formulario y campos
function limpia() {
    $("#f")[0].reset();
    $("#idPedido").val("");
    $("#idCliente").val("");
    $("#idCuenta").val("");
    $("#numComprobante").val("");
    $("#clienteInfo").val("");
    $("#cuentaInfo").val("");
    $("#detallesdeventa").empty();
    $("#totalGeneral").text("$0.00");
    $("#divEstadoPedido").hide();
    $("#datosPagoPedido").hide();
    $("#tipoPagoPedido, #cedulaPagoPedido, #tlfPagoPedido, #referenciaPagoPedido, #cuentaPagoPedidoInfo").val("");
    $("#cuentaPagoPedidoCont, #cedulaPagoPedidoCont, #tlfPagoPedidoCont, #referenciaPagoPedidoCont").hide();
    $("#snumComprobante, #scedulaPagoPedido, #stlfPagoPedido, #sreferenciaPagoPedido").text("");
    $("#btnGuardar").text("Procesar Pedido");
}