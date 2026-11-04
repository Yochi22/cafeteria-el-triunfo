function consultar(estado = "Todos") {
    var datos = new FormData();
    datos.append("accion", "consultar");
    datos.append("estadoFiltro", estado);
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar("Todos");

    $("#filtroEstado").on("change", function () {
        consultar($(this).val());
    });

    var datosCli = new FormData();
    datosCli.append("accion", "modalclientes");
    enviaAjax(datosCli);

    var datosCue = new FormData();
    datosCue.append("accion", "modalcuentas");
    enviaAjax(datosCue);

    var datosProd = new FormData();
    datosProd.append("accion", "modalproductos");
    enviaAjax(datosProd);

    $("#btnGuardar").on("click", function () {
        if ($("#idCliente").val() == "") {
            mostrarMensaje("Debe seleccionar un cliente antes de procesar.");
            return;
        }
        if ($("#detallesdeventa tr").length == 0) {
            mostrarMensaje("Debe agregar al menos un producto al pedido.");
            return;
        }

        var datos = new FormData($("#f")[0]);
        if ($(this).data("accion") == "modificar") {
            datos.append("accion", "modificar");
        } else {
            datos.append("accion", "incluir");
        }
        enviaAjax(datos);
    });

    $("#btnBuscarCliente").on("click", function () {
        $("#modal_clientes").modal("show");
    });

    $("#btnBuscarCuenta").on("click", function () {
        $("#modal_cuentas").modal("show");
    });

    $("#btnBuscarProducto").on("click", function () {
        $("#modal_productos").modal("show");
    });

    $("#btnEliminar").on("click", function () {
        var idPedido = $("#eliminar").val();
        var datos = new FormData();
        datos.append("accion", "eliminar");
        datos.append("idPedido", idPedido);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#modal_pedido").modal("show");
    });
});

function seleccionarCliente(id, cedula, nombre) {
    $("#idCliente").val(id);
    $("#clienteInfo").val(cedula + " - " + nombre);
    $("#modal_clientes").modal("hide");
}

function seleccionarCuenta(id, banco, numero) {
    $("#idCuenta").val(id);
    $("#cuentaInfo").val(banco + " - " + numero);
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
    $("#btnGuardar").data("accion", "incluir");
    $("#btnGuardar").html('<i class="bi bi-check-circle"></i> Procesar Pedido');
}

function pone(idPedido) {
    var datos = new FormData();
    datos.append("accion", "consultar_uno");
    datos.append("idPedido", idPedido);
    enviaAjax(datos);
}

function eliminar(idPedido) {
    $("#eliminar").val(idPedido);
    $("#modal_eliminar").modal("show");
}

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
                    $("#detallesdeventa").html(lee.detalleHtml);
                    calculaTotal();
                    $("#divEstadoPedido").show();
                    $("#btnGuardar").data("accion", "modificar");
                    $("#btnGuardar").html(
                        '<i class="bi bi-check-circle"></i> Modificar Pedido',
                    );
                    $("#modal_pedido").modal("show");
                } else if (
                    lee.resultado == "incluir" ||
                    lee.resultado == "modificar"
                ) {
                    mostrarMensaje(lee.mensaje);
                    $("#modal_pedido").modal("hide");
                    consultar($("#filtroEstado").val());
                } else if (lee.resultado == "eliminar") {
                    mostrarMensaje(lee.mensaje);
                    $("#modal_eliminar").modal("hide");
                    consultar($("#filtroEstado").val());
                } else if (lee.resultado == "error") {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                console.error("Error al procesar JSON: ", e);
            }
        },
        error: function (request, status, error) {
            mostrarMensaje("Error de conexión: " + request.responseText);
        },
    });
}

function mostrarMensaje(mensaje) {
    if ($("#mostrarModal").length) {
        $(".modal").modal("hide");
        $("#contenidoModal").text(mensaje);
        setTimeout(function () {
            $("#mostrarModal").modal("show");
        }, 200);
    } else {
        alert(mensaje);
    }
}