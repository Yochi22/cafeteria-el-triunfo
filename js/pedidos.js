function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function () {
    consultar();

    var datosCli = new FormData();
    datosCli.append('accion', 'modalclientes');
    enviaAjax(datosCli);

    var datosCue = new FormData();
    datosCue.append('accion', 'modalcuentas');
    enviaAjax(datosCue);

    var datosProd = new FormData();
    datosProd.append('accion', 'modalproductos');
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
            datos.append('accion', 'modificar');
        } else {
            datos.append('accion', 'incluir');
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
        datos.append('accion', 'eliminar');
        datos.append('idPedido', idPedido);
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
        var l = "<tr>" +
            "<td class='align-middle fw-bold'>" +
            "<input type='hidden' name='idProducto[]' value='" + id + "'>" +
            codigo +
            "</td>" +
            "<td class='align-middle'>" + nombre + "</td>" +
            "<td class='align-middle'>" +
            "<input type='hidden' name='precio[]' value='" + precio + "'>" +
            "$" + parseFloat(precio).toFixed(2) +
            "</td>" +
            "<td class='align-middle' style='width: 120px;'>" +
            "<input type='number' class='form-control form-control-sm text-center' value='1' name='cantidad[]' min='1' onchange='modificasubtotal(this)' onkeyup='modificasubtotal(this)'>" +
            "</td>" +
            "<td class='align-middle text-success fw-bold subtotal-cell'>" +
            "$" + parseFloat(precio).toFixed(2) +
            "</td>" +
            "<td class='align-middle'>" +
            "<button type='button' class='btn btn-outline-danger btn-sm' onclick='eliminalineadetalle(this)'><i class='bi bi-x-lg'></i></button>" +
            "</td>" +
            "</tr>";

        $("#detallesdeventa").append(l);
    }

    calcularTotalGeneral();
    $("#modal_productos").modal("hide");
}

function modificasubtotal(input) {
    var linea = $(input).closest('tr');
    var cant = parseInt($(input).val());

    if (isNaN(cant) || cant <= 0) {
        cant = 1;
        $(input).val(1);
    }

    var precio = parseFloat($(linea).find("input[name='precio[]']").val());
    var subtotal = cant * precio;
    $(linea).find(".subtotal-cell").text("$" + subtotal.toFixed(2));

    calcularTotalGeneral();
}

function eliminalineadetalle(boton) {
    $(boton).closest('tr').remove();
    calcularTotalGeneral();
}

function calcularTotalGeneral() {
    var total = 0;
    $("#detallesdeventa tr").each(function () {
        var cant = parseInt($(this).find("input[name='cantidad[]']").val());
        var precio = parseFloat($(this).find("input[name='precio[]']").val());
        total += (cant * precio);
    });
    $("#totalGeneral").text("$" + total.toFixed(2));
}

function eliminar(idPedido) {
    $("#eliminar").val(idPedido);
    $("#modal_eliminar").modal("show");
}

function pone(idPedido) {
    limpia();
    var datos = new FormData();
    datos.append('accion', 'consultar_uno');
    datos.append('idPedido', idPedido);
    enviaAjax(datos);
}

function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () { $("#mostrarModal").modal("hide") }, 3000);
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=pedidos",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function () { },
        timeout: 10000,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);

                if (lee.resultado == 'consultar') {
                    $("#contenedor_pedidos").html(lee.mensaje);
                } else if (lee.resultado == 'modalclientes') {
                    $("#listaClientesModal").html(lee.mensaje);
                } else if (lee.resultado == 'modalcuentas') {
                    $("#listaCuentasModal").html(lee.mensaje);
                } else if (lee.resultado == 'modalproductos') {
                    $("#listaProductosModal").html(lee.mensaje);
                } else if (lee.resultado == 'incluir' || lee.resultado == 'modificar') {
                    mostrarMensaje(lee.mensaje);
                    $("#modal_pedido").modal("hide");
                    consultar();
                } else if (lee.resultado == 'eliminar') {
                    mostrarMensaje(lee.mensaje);
                    $("#modal_eliminar").modal("hide");
                    consultar();
                } else if (lee.resultado == 'consultar_uno') {
                    $("#idPedido").val(lee.idPedido);
                    $("#idCliente").val(lee.idCliente);
                    $("#clienteInfo").val(lee.clienteInfo);
                    $("#idCuenta").val(lee.idCuenta);
                    $("#cuentaInfo").val(lee.cuentaInfo);
                    $("#estadoPedido").val(lee.estadoPedido);

                    $("#divEstadoPedido").show();

                    $("#detallesdeventa").html(lee.detalleHtml);
                    calcularTotalGeneral();

                    $("#btnGuardar").data("accion", "modificar").html('<i class="bi bi-save"></i> Guardar Cambios');
                    $("#modal_pedido").modal("show");
                } else if (lee.resultado == 'error') {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                alert("Error en JSON " + e.name);
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mostrarMensaje("Servidor ocupado, intente de nuevo");
            } else {
                mostrarMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () { }
    });
}

function limpia() {
    $("#idPedido").val("");
    $("#idCliente").val("");
    $("#clienteInfo").val("");
    $("#idCuenta").val("");
    $("#cuentaInfo").val("");
    $("#estadoPedido").val("En Proceso");
    $("#divEstadoPedido").hide();
    $("#detallesdeventa").html("");
    $("#totalGeneral").text("$0.00");
    $("#btnGuardar").data("accion", "incluir").html('<i class="bi bi-check-circle"></i> Procesar Pedido');
}