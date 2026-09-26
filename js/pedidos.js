$(document).ready(function () {
    consultar();
    cargarModales();

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

    $("#btnBuscarProducto").on("click", function () {
        $("#modal_productos").modal("show");
    });

    $("#btnEliminar").on("click", function () {
        var numPedido = $("#eliminar").val();
        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('numPedido', numPedido);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#modal_pedido").modal("show");
    });
});

function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

function cargarModales() {
    var datosCli = new FormData();
    datosCli.append('accion', 'modalclientes');
    enviaAjax(datosCli);

    var datosProd = new FormData();
    datosProd.append('accion', 'modalproductos');
    enviaAjax(datosProd);
}

function seleccionarCliente(id, cedula, nombre) {
    $("#idCliente").val(id);
    $("#clienteInfo").val(cedula + " - " + nombre);
    $("#modal_clientes").modal("hide");
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

function eliminar(numPedido) {
    $("#eliminar").val(numPedido);
    $("#modal_eliminar").modal("show");
}

function pone(numPedido) {
    limpia();
    var datos = new FormData();
    datos.append('accion', 'consultar_uno');
    datos.append('numPedido', numPedido);
    enviaAjax(datos);
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

        success: function (respuesta) {
            console.log(respuesta);
            try {
                var lee = JSON.parse(respuesta);

                if (lee.resultado == 'consultar') {
                    $("#contenedor_pedidos").html(lee.mensaje);
                } else if (lee.resultado == 'modalclientes') {
                    $("#listaClientesModal").html(lee.mensaje);
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
                    var p = lee.pedido;
                    $("#numPedido").val(p.numPedido);
                    $("#idCliente").val(p.idCliente);
                    $("#clienteInfo").val(p.clienteInfo);

                    $.each(lee.detalles, function (i, d) {
                        seleccionarProducto(d.idProducto, d.codigoProd, d.nombreProd, d.precioProd);

                        var ultimaFila = $("#detallesdeventa tr:last");
                        var inputCant = ultimaFila.find("input[name='cantidad[]']");

                        inputCant.val(d.cantidadProd);
                        modificasubtotal(inputCant[0]);
                    });

                    $("#btnGuardar").data("accion", "modificar").html('<i class="bi bi-save"></i> Guardar Cambios');
                    $("#modal_pedido").modal("show");
                } else if (lee.resultado == 'error') {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                mostrarMensaje("Error al procesar la respuesta del servidor.");
            }
        },
        error: function () {
            mostrarMensaje("Error de comunicación.");
        }
    });
}

function limpia() {
    $("#numPedido").val("");
    $("#idCliente").val("");
    $("#clienteInfo").val("");
    $("#detallesdeventa").html("");
    $("#totalGeneral").text("$0.00");
    $("#btnGuardar").data("accion", "incluir").html('<i class="bi bi-check-circle"></i> Procesar Pedido');
}

function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () { $("#mostrarModal").modal("hide") }, 3000);
}