// Función para recargar DataTable de ventas
function consultar() {
    if ($.fn.DataTable.isDataTable('#tblVentas')) {
        $("#tblVentas").DataTable().ajax.reload(null, false);
    }
}

$(document).ready(function () {
    // Inicializar DataTables con AJAX
    var table = $("#tblVentas").DataTable({
        ajax: {
            url: "index.php?pagina=ventas",
            type: "POST",
            data: { accion: "consultar" },
            dataSrc: ""
        },
        columns: [
            {
                data: "idVenta",
                className: "text-center fw-bold",
                render: function (data) {
                    return "#" + String(data).padStart(3, '0');
                }
            },
            { data: "nombrePer", className: "text-center" },
            {
                data: "totalVenta",
                className: "text-center",
                render: function (data) {
                    return "<span class='text-success fw-bold'>$" + parseFloat(data).toFixed(2) + "</span>";
                }
            },
            { data: "tipoPago", className: "text-center" },
            {
                data: null,
                className: "text-center",
                render: function (data, type, row) {
                    return `
                        <button type='button' class='btn btn-outline-info btn-sm d-inline-flex align-items-center gap-1 m-1' 
                            data-id='${row.idVenta}' 
                            onclick='consultarDetalle("${row.idVenta}")'>
                            <i class='bi bi-eye-fill'></i><span class='d-none d-sm-inline'> Ver Detalle</span>
                        </button>
                    `;
                }
            },
            {
                data: null,
                className: "text-center",
                render: function (data, type, row) {
                    return `
                        <button type='button' class='btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1 my-1' 
                            data-id='${row.idVenta}' 
                            onclick='pone("${row.idVenta}")'>
                            <i class='bi bi-pencil-square'></i><span class='d-none d-sm-inline'> Modificar</span>
                        </button>
                        <button type='button' class='btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1 m-1' 
                            data-id='${row.idVenta}' 
                            onclick='eliminar("${row.idVenta}")'>
                            <i class='bi bi-trash-fill'></i><span class='d-none d-sm-inline'> Eliminar</span>
                        </button>
                    `;
                }
            },
            { data: "fechaVenta", visible: false }
        ],
        dom: 'rt<"row mt-3 align-items-center"<"col-md-6 text-muted small"i><"col-md-6 d-flex justify-content-end"p>>',
        language: {
            "decimal": "",
            "emptyTable": "No hay ventas registradas.",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": `<div class='text-center text-muted py-4'>
                                <span><i class='bi bi-receipt fs-1' style='color: #FF8C00'></i></span>
                                <h5 class='text-dashboard'>No se encontraron registros</h5>
                                <h6 class='text-secondary'>Intenta de nuevo.</h6>
                            </div>`,
            "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });

    // Buscador por texto
    $("#buscarVenta").on("keyup", function () {
        table.search(this.value).draw();
    });

    // Buscador por fecha
    $("#buscarFecha").on("change", function () {
        var fechaSeleccionada = $(this).val();
        table.search(fechaSeleccionada).draw();
    });

    cargarModales();
    actualizarFechaHoy();

    $("#aplicaDelivery").on("change", function () {
        var conDelivery = $(this).val() === "Sí";
        $("#seccionDelivery").toggle(conDelivery);
        if (!conDelivery) {
            $("#zonaDelivery, #calleDelivery, #numCasaDelivery").val("");
            $("#szonaDelivery, #scalleDelivery, #snumCasaDelivery").text("");
        }
    });

    // ==== VALIDACIONES ====
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

    $("#refPago").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9]*$/, e);
    });
    $("#refPago").on("keyup", function () {
        validarkeyup(/^[0-9]{4}$/, $(this), $("#srefPago"), "Referencia Inválida - Solo números (Últimos 4 digitos).");
    });

    $("#tipoPago").on("change", function () {
        validarSelect($(this), $("#stipoPago"), "Debe seleccionar un tipo de pago.");
    });

    $("#zonaDelivery").on("change", function () {
        validarSelect($(this), $("#szonaDelivery"), "Debe seleccionar una zona de delivery.");
    });

    $("#calleDelivery").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s#\-\.,]*$/, e);
    });
    $("#calleDelivery").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s#\-\.,]{3,50}$/, $(this), $("#scalleDelivery"), "Calle Inválida - Solo letras, números y espacios (mín. 3 caracteres).");
    });

    $("#numCasaDelivery").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\-\#]*$/, e);
    });
    $("#numCasaDelivery").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\-\#]{1,10}$/, $(this), $("#snumCasaDelivery"), "N° de Casa Inválido (Ej: 12-A o 45).");
    });

    $("#formVentas").on("submit", function (e) {
        e.preventDefault();
    });

    $("#listarpersonal").on("click", function () {
        $("#modal_personal").modal("show");
    });

    $("#listarcliente").on("click", function () {
        $("#modal_clientes").modal("show");
    });

    $("#listarproducto").on("click", function () {
        $("#modal_productos").modal("show");
    });

    $("#listarcuentas").on("click", function () {
        $("#modal_cuentas").modal("show");
    });

    $("#btnSiguienteDetalles").on("click", function () {
        if ($("#idpersonal").val() == "") {
            mostrarAlerta("Atención", "Debe seleccionar un personal.", "warning");
            return;
        }
        $("#modal_venta").modal("hide");
        $("#modal_detallesdepedido").modal("show");
    });

    $("#btnSiguientePago").on("click", function () {
        if ($("#idCliente").val() == "") {
            mostrarAlerta("Atención", "Debe seleccionar un cliente.", "warning");
            return;
        }
        if ($("#detallesdeventa tr").length == 0) {
            mostrarAlerta("Atención", "Debe agregar al menos un producto al pedido.", "warning");
            return;
        }

        if ($("#aplicaDelivery").val() === "Sí") {
            if (
                validarSelect($("#zonaDelivery"), $("#szonaDelivery"), "Seleccione una zona.") === 0 ||
                validarkeyup(/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s#\-\.,]{3,50}$/, $("#calleDelivery"), $("#scalleDelivery"), "Calle Inválida.") === 0 ||
                validarkeyup(/^[A-Za-z0-9\-\#]{1,10}$/, $("#numCasaDelivery"), $("#snumCasaDelivery"), "N° de Casa Inválido.") === 0
            ) {
                mostrarAlerta("Atención", "Por favor complete correctamente los datos de la dirección de delivery.", "warning");
                return;
            }
        }

        $("#modal_detallesdepedido").modal("hide");
        $("#modal_detallesdepago").modal("show");
    });

    $("#btnFinalizarVenta").on("click", function () {
        if (validarEnvioVenta()) {
            var datos = new FormData($("#formVentas")[0]);
            var accionActual = $("#btnFinalizarVenta").data("accion") || "incluir";
            datos.set("accion", accionActual);

            enviaAjax(datos);
        }
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#modal_venta").modal("show");
    });

    $("#btnEliminar").on("click", function () {
        var idVenta = $("#eliminar").val();
        var datos = new FormData();
        datos.append("accion", "eliminar");
        datos.append("idVenta", idVenta);
        $("#modal_eliminar").modal("hide");
        enviaAjax(datos);
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
});

// Función de eliminación 
function eliminar(idVenta) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar esta venta del sistema?",
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
            datos.append("idVenta", idVenta);
            enviaAjax(datos);
        }
    });
}

// Función para consultar el detalle de la venta
function consultarDetalle(idVenta) {
    var datos = new FormData();
    datos.append("accion", "consultarDetalle");
    datos.append("idVenta", idVenta);
    enviaAjax(datos);
}

// Función que carga los modales 
function cargarModales() {
    var datosPer = new FormData();
    datosPer.append("accion", "listarPersonal");
    enviaAjax(datosPer);

    var datosCli = new FormData();
    datosCli.append("accion", "listarClientes");
    enviaAjax(datosCli);

    var datosProd = new FormData();
    datosProd.append("accion", "listarProductos");
    enviaAjax(datosProd);

    var datosCuen = new FormData();
    datosCuen.append("accion", "listarCuentas");
    enviaAjax(datosCuen);
}

// Función que coloca el personal en el formulario de venta
function colocapersonal(tr) {
    var id = $(tr).find("td:eq(0)").text();
    var cedula = $(tr).find("td:eq(1)").text();
    var nombre = $(tr).find("td:eq(2)").text();

    $("#idpersonal").val(id);
    $("#personalInfo").val(cedula + " - " + nombre);
    $("#modal_personal").modal("hide");
}

// Función que coloca el cliente en el formulario de venta
function colocacliente(tr) {
    var id = $(tr).find("td:eq(0)").text();
    var cedula = $(tr).find("td:eq(1)").text();
    var nombre = $(tr).find("td:eq(2)").text();

    $("#idCliente").val(id);
    $("#clienteInfo").val(cedula + " - " + nombre);
    $("#modal_clientes").modal("hide");
}

// Función que coloca la cuenta en el formulario de venta
function colocacuentas(tr) {
    var id = $(tr).find("td:eq(0)").text();
    var banco = $(tr).find("td:eq(1)").text();
    var numero = $(tr).find("td:eq(2)").text();

    $("#idcuenta").val(id);
    $("#cuentasInfo").val(banco + " - " + numero);
    $("#modal_cuentas").modal("hide");
}

// Función que coloca el producto en el formulario de venta
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
        var l =
            "<tr>" +
            "<td class='align-middle fw-bold'>" +
            "<input type='hidden' name='idProducto[]' value='" +
            id +
            "'>" +
            codigo +
            "</td>" +
            "<td class='align-middle'>" +
            nombre +
            "</td>" +
            "<td class='align-middle'>" +
            "<input type='hidden' name='precio[]' value='" +
            precio +
            "'>" +
            "$" +
            parseFloat(precio).toFixed(2) +
            "</td>" +
            "<td class='align-middle' style='width: 120px;'>" +
            "<input type='number' class='form-control form-control-sm text-center' value='1' name='cantidad[]' min='1' onchange='modificasubtotal(this)' onkeyup='modificasubtotal(this)'>" +
            "</td>" +
            "<td class='align-middle text-success fw-bold subtotal-cell'>" +
            "$" +
            parseFloat(precio).toFixed(2) +
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

// Función que modifica el subtotal de la linea de detalle
function modificasubtotal(input) {
    var linea = $(input).closest("tr");
    var cant = parseInt($(input).val());

    if (isNaN(cant) || cant <= 0) {
        cant = 1;
        $(input).val(1);
    }

    var precio = parseFloat($(linea).find("input[name='precio[]']").val());
    var subtotal = cant * precio;
    $(linea)
        .find(".subtotal-cell")
        .text("$" + subtotal.toFixed(2));

    calcularTotalGeneral();
}

// Función que elimina la linea de detalle
function eliminalineadetalle(boton) {
    $(boton).closest("tr").remove();
    calcularTotalGeneral();
}

// Función que calcula el total general de la venta
function calcularTotalGeneral() {
    var total = 0;
    $("#detallesdeventa tr").each(function () {
        var cant = parseInt($(this).find("input[name='cantidad[]']").val());
        var precio = parseFloat($(this).find("input[name='precio[]']").val());
        total += cant * precio;
    });
    $("#totalPrecio").text("$" + total.toFixed(2));
}

// Función para poner el id de la venta a consultar
function pone(idVenta) {
    limpia();
    var datos = new FormData();
    datos.append("accion", "consultarUno");
    datos.append("idVenta", idVenta);
    enviaAjax(datos);
}

// VALIDACIÓN ANTES DE ENVIAR
function validarEnvioVenta() {
    if ($("#aplicaDelivery").val() === "Sí") {
        if (
            validarSelect($("#zonaDelivery"), $("#szonaDelivery"), "Seleccione una zona") === 0 ||
            validarkeyup(/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s#\-\.,]{3,50}$/, $("#calleDelivery"), $("#scalleDelivery"), "Calle Inválida") === 0 ||
            validarkeyup(/^[A-Za-z0-9\-\#]{1,10}$/, $("#numCasaDelivery"), $("#snumCasaDelivery"), "N° de Casa Inválido.") === 0
        ) {
            mostrarAlerta("Atención", "Por favor verifique los datos de la dirección de delivery en el paso anterior.", "warning");
            return false;
        }
    }

    if (validarSelect($("#tipoPago"), $("#stipoPago"), "Seleccione una opción.") === 0) {
        mostrarAlerta("Atención", "Tipo de Pago Inválido <br>(Debe seleccionar un método de pago).", "warning");
        return false;
    }

    var tipo = $("#tipoPago").val();

    if (tipo === "Pago Móvil") {
        if ($("#idcuenta").val() == "") {
            mostrarAlerta("Atención", "Debe seleccionar una cuenta destino.", "warning");
            return false;
        }
        if (validarkeyup(/^[0-9]{7,8}$/, $("#cedulaPago"), $("#scedulaPago"), "Cédula Inválida") === 0) {
            mostrarAlerta("Atención", "Cédula Inválida <br>Debe tener entre 7 y 8 dígitos numéricos.", "warning");
            return false;
        }
        if (validarkeyup(/^(0412|0414|0416|0422|0424|0426|02[0-9]{2})[0-9]{7}$/, $("#tlfPago"), $("#stlfPago"), "Teléfono Inválido") === 0) {
            mostrarAlerta("Atención", "Teléfono Inválido <br>Formato : 04241234567.", "warning");
            return false;
        }
        if (validarkeyup(/^[0-9]{4}$/, $("#refPago"), $("#srefPago"), "Referencia Inválida") === 0) {
            mostrarAlerta("Atención", "Referencia Inválida - Solo números (Últimos 4 digitos).", "warning");
            return false;
        }
    }

    return true;
}

// Función que carga los datos de la venta en el formulario para modificar
function cargarDatosVenta(v, prods) {
    $("#idVenta").val(v.idVenta);
    $("#idDetalle").val(v.idDetalle);
    $("#idPedido").val(v.idPedido);

    $("#idpersonal").val(v.idPersonal);
    $("#personalInfo").val(
        v.cedulaPer + " - " + v.nombrePer + " " + v.apellidoPer,
    );

    if (v.idCliente) {
        $("#idCliente").val(v.idCliente);
        $("#clienteInfo").val(v.cedulaCli + " - " + v.nombreCli);
    }

    if (v.idCuenta) {
        $("#idcuenta").val(v.idCuenta);
        $("#cuentasInfo").val(v.nombreBanco + " - " + v.numCuenta);
    }

    if (v.zonaDelivery && v.zonaDelivery !== "N/A" && v.zonaDelivery !== "") {
        $("#aplicaDelivery").val("Sí").trigger("change");
        $("#zonaDelivery").val(v.zonaDelivery);
        $("#calleDelivery").val(v.calleDelivery);
        $("#numCasaDelivery").val(v.numCasaDelivery);
    } else {
        $("#aplicaDelivery").val("No").trigger("change");
    }

    $("#tipoPago").val(v.tipoPago);
    $("#cedulaPago").val(v.cedulaPago);
    $("#tlfPago").val(v.tlfPago);
    $("#refPago").val(v.referencia);

    if (v.tipoPago === "Pago Móvil") {
        $("#datosPagoMovil").show();
        $("#cuentaDestino, #refPago, #cedulaPago, #tlfPago").prop("required", true);
    } else {
        $("#datosPagoMovil").hide();
        $("#cuentaDestino, #refPago, #cedulaPago, #tlfPago").prop("required", false);
    }

    $("#detallesdeventa").html("");
    prods.forEach(function (p) {
        var sub = parseFloat(p.precioUnitario) * parseInt(p.cantidad);
        var l =
            "<tr>" +
            "<td class='align-middle fw-bold'>" +
            "<input type='hidden' name='idProducto[]' value='" +
            p.idProducto +
            "'>" +
            p.codigoProd +
            "</td>" +
            "<td class='align-middle'>" +
            p.nombreProd +
            "</td>" +
            "<td class='align-middle'>" +
            "<input type='hidden' name='precio[]' value='" +
            p.precioUnitario +
            "'>" +
            "$" +
            parseFloat(p.precioUnitario).toFixed(2) +
            "</td>" +
            "<td class='align-middle' style='width: 120px;'>" +
            "<input type='number' class='form-control form-control-sm text-center' value='" +
            p.cantidad +
            "' name='cantidad[]' min='1' onchange='modificasubtotal(this)' onkeyup='modificasubtotal(this)'>" +
            "</td>" +
            "<td class='align-middle text-success fw-bold subtotal-cell'>" +
            "$" +
            sub.toFixed(2) +
            "</td>" +
            "<td class='align-middle'>" +
            "<button type='button' class='btn btn-outline-danger btn-sm' onclick='eliminalineadetalle(this)'><i class='bi bi-x-lg'></i></button>" +
            "</td>" +
            "</tr>";
        $("#detallesdeventa").append(l);
    });

    calcularTotalGeneral();

    $("#btnFinalizarVenta")
        .data("accion", "modificar")
        .html('<i class="bi bi-save"></i> Guardar Cambios');
    $("#modal_venta").modal("show");
}

// Función que actualiza la fecha de hoy en el encabezado de la tabla de ventas
function actualizarFechaHoy() {
    const meses = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    const fecha = new Date();
    const dia = fecha.getDate();
    const mes = meses[fecha.getMonth()];
    const anio = fecha.getFullYear();

    const fechaTexto = `${dia} de ${mes}, ${anio}`;

    $("#fechaAcutal").html(
        `<i class="bi bi-calendar3"></i> Ventas del día: ${fechaTexto}`,
    );
}

// Función para validar keypress
function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);

    a = er.test(tecla);
    if (!a) {
        e.preventDefault();
    }
}

// Función para validar keyup
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

// Función para validar select
function validarSelect(etiqueta, etiquetamensaje, mensaje) {
    let valor = etiqueta.val();

    if (valor === "" || valor === null) {
        etiquetamensaje.text(mensaje);
        return 0;
    } else {
        etiquetamensaje.text("");
        return 1;
    }
}

// Función para mostrar alerta
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

// Función que envia los datos al servidor mediante AJAX
function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=ventas",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function (respuesta) {
            try {
                console.log(respuesta);
                var lee = JSON.parse(respuesta);

                if (lee.resultado == "listarPersonal") {
                    $("#listaPersonalModal").html(lee.mensaje);
                }
                else if (lee.resultado == "listarClientes") {
                    $("#listaClientesModal").html(lee.mensaje);
                }
                else if (lee.resultado == "listarProductos") {
                    $("#listaProductosModal").html(lee.mensaje);
                }
                else if (lee.resultado == "listarCuentas") {
                    $("#listaCuentasModal").html(lee.mensaje);
                }
                else if (lee.resultado == "incluir" || lee.resultado == "modificar" || lee.resultado == "eliminar") {
                    mostrarAlerta("¡Éxito!", lee.mensaje, "success");
                    $("#modal_detallesdepago").modal("hide");
                    consultar();
                }
                else if (lee.resultado == "consultar_detalle") {
                    $("#contenidoDetalleVenta").html(lee.mensaje);
                    $("#modal_ver_detalle").modal("show");
                }
                else if (lee.resultado == "consultarUno") {
                    cargarDatosVenta(lee.venta, lee.productos);
                }
                else if (lee.resultado == "error") {
                    mostrarAlerta("Error", lee.mensaje, "error");
                }
            } catch (e) {
                mostrarAlerta("Error de sistema", "Error al procesar la respuesta del servidor.", "error");
            }
        },
        error: function () {
            mostrarAlerta("Error crítico", "Error de comunicación con el servidor.", "error");
        },
    });
}

// Función que limpia el formulario de venta
function limpia() {
    $("#formVentas")[0].reset();
    $("#idVenta").val("");
    $("#idDetalle").val("");
    $("#idPedido").val("");
    $("#idpersonal").val("");
    $("#idCliente").val("");
    $("#idcuenta").val("");
    $("#cuentasInfo").val("");

    $("#aplicaDelivery").val("No").trigger("change");
    $("#zonaDelivery").val("");
    $("#calleDelivery").val("");
    $("#numCasaDelivery").val("");
    $("#szonaDelivery").text("");
    $("#scalleDelivery").text("");
    $("#snumCasaDelivery").text("");

    $("#detallesdeventa").html("");
    $("#totalPrecio").text("$0.00");

    $("#stipoPago").text("");
    $("#scedulaPago").text("");
    $("#stlfPago").text("");
    $("#srefPago").text("");
    $("#datosPagoMovil").hide();

    $("#listarpersonal").prop("disabled", false);
    $("#listarproducto").prop("disabled", false);

    $("#btnFinalizarVenta")
        .data("accion", "incluir")
        .html('Finalizar Venta <i class="bi bi-check2-all"></i>');
}