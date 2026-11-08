$(document).ready(function () {
	consultar();
	cargarModales();
	actualizarFechaHoy();

	// VALIDACIONES DE PAGO
	$("#cedulaPago").on("keypress", function (e) {
		validarkeypress(/^[0-9\-\.]*$/, e);
	});
	$("#cedulaPago").on("keyup", function () {
		validarkeyup(/^[0-9\.]{7,12}$/, $(this), $("#scedulaPago"), "Cédula Inválida - El formato debe ser: 99999999",);
	});

	$("#tlfPago").on("keypress", function (e) {
		validarkeypress(/^[0-9\-+ ]*$/, e);
	});
	$("#tlfPago").on("keyup", function () {
		validarkeyup(/^[0-9\-+ ]{11,12}$/, $(this), $("#stlfPago"), "Teléfono Inválido - Formato: 0000-0000000",);
	});

	$("#refPago").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z0-9]*$/, e);
	});
	$("#refPago").on("keyup", function () {
		validarkeyup(/^[A-Za-z0-9]{4}$/, $(this), $("#srefPago"), "Referencia Inválida - Solo letras y números (mín. 4 caracteres)",);
	});

	$("#tipoPago").on("change", function () {
		validarSelect($(this), $("#stipoPago"), "Debe seleccionar un tipo de pago");
	});

	$("#zonaDelivery").on("change", function () {
		validarSelect($(this), $("#szonaDelivery"), "Debe seleccionar una zona de delivery");
	});

	$("#calleDelivery").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s#\-\.,]*$/, e);
	});
	$("#calleDelivery").on("keyup", function () {
		validarkeyup(/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s#\-\.,]{3,50}$/, $(this), $("#scalleDelivery"), "Calle Inválida - Solo letras, números y espacios (mín. 3 caracteres)");
	});

	$("#numCasaDelivery").on("keypress", function (e) {
		validarkeypress(/^[A-Za-z0-9\-\#]*$/, e);
	});
	$("#numCasaDelivery").on("keyup", function () {
		validarkeyup(/^[A-Za-z0-9\-\#]{1,10}$/, $(this), $("#snumCasaDelivery"), "N° de Casa Inválido (Ej: 12-A o 45)");
	});

	$("#form_venta_completa").on("submit", function (e) {
		e.preventDefault();
	});

	// CONTROL DE BOTONES Y MODALES
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
			mostrarMensaje("Debe seleccionar un personal.");
			return;
		}
		$("#modal_venta").modal("hide");
		$("#modal_detallesdepedido").modal("show");
	});

	$("#btnSiguientePago").on("click", function () {
		if ($("#idCliente").val() == "") {
			mostrarMensaje("Debe seleccionar un cliente.");
			return;
		}
		if ($("#detallesdeventa tr").length == 0) {
			mostrarMensaje("Debe agregar al menos un producto al pedido.");
			return;
		}
		if (
			validarSelect($("#zonaDelivery"), $("#szonaDelivery"), "Seleccione una zona") === 0 ||
			validarkeyup(/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s#\-\.,]{3,50}$/, $("#calleDelivery"), $("#scalleDelivery"), "Calle Inválida") === 0 ||
			validarkeyup(/^[A-Za-z0-9\-\#]{1,10}$/, $("#numCasaDelivery"), $("#snumCasaDelivery"), "N° de Casa Inválido") === 0
		) {
			mostrarMensaje("Por favor complete correctamente los datos de la dirección de delivery.");
			return;
		}

		$("#modal_detallesdepedido").modal("hide");
		$("#modal_detallesdepago").modal("show");
	});

	$("#btnFinalizarVenta").on("click", function () {
		if (validarEnvioVenta()) {
			var datos = new FormData($("#form_venta_completa")[0]);
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

	$("#buscarVenta").on("keyup", function () {
		ejecutarBusquedaVenta();
	});

	$("#buscarFecha").on("change", function () {
		var fecha = $(this).val();
		if (fecha) {
			var datos = new FormData();
			datos.append("accion", "buscar_fecha");
			datos.append("fecha", fecha);
			enviaAjax(datos);
		} else {
			consultar();
		}
	});

	$("#tipoPago").on("change", function () {
		var esPagoMovil = $(this).val() === "Pago Movil";
		$("#datosPagoMovil").toggle(esPagoMovil);
		$("#cuentaDestino, #refPago, #cedulaPago, #tlfPago").prop(
			"required",
			esPagoMovil,
		);
		if (!esPagoMovil) {
			$("#cuentaDestino, #refPago, #cedulaPago, #tlfPago").val("");
			$("#srefPago, #scedulaPago, #stlfPago").text("");
		}
	});
});

function consultar() {
	var datos = new FormData();
	datos.append("accion", "consultar");
	enviaAjax(datos);
}

function eliminar(idVenta) {
	$("#eliminar").val(idVenta);
	$("#modal_eliminar").modal("show");
}

function consultarDetalle(idVenta) {
	var datos = new FormData();
	datos.append("accion", "consultar_detalle");
	datos.append("idVenta", idVenta);
	enviaAjax(datos);
}

function ejecutarBusquedaVenta() {
	var valor = $("#buscarVenta").val();

	if (valor.trim().length > 0) {
		var datos = new FormData();
		datos.append("accion", "buscar");
		datos.append("valorBusqueda", valor);
		enviaAjax(datos);
	} else {
		consultar();
	}
}

function cargarModales() {
	var datosPer = new FormData();
	datosPer.append("accion", "modal_personal");
	enviaAjax(datosPer);

	var datosCli = new FormData();
	datosCli.append("accion", "modal_clientes");
	enviaAjax(datosCli);

	var datosProd = new FormData();
	datosProd.append("accion", "modal_productos");
	enviaAjax(datosProd);

	var datosCuen = new FormData();
	datosCuen.append("accion", "modal_cuentas");
	enviaAjax(datosCuen);
}

function colocapersonal(tr) {
	var id = $(tr).find("td:eq(0)").text();
	var cedula = $(tr).find("td:eq(1)").text();
	var nombre = $(tr).find("td:eq(2)").text();

	$("#idpersonal").val(id);
	$("#personalInfo").val(cedula + " - " + nombre);
	$("#modal_personal").modal("hide");
}

function colocacliente(tr) {
	var id = $(tr).find("td:eq(0)").text();
	var cedula = $(tr).find("td:eq(1)").text();
	var nombre = $(tr).find("td:eq(2)").text();

	$("#idCliente").val(id);
	$("#clienteInfo").val(cedula + " - " + nombre);
	$("#modal_clientes").modal("hide");
}

function colocacuentas(tr) {
	var id = $(tr).find("td:eq(0)").text();
	var banco = $(tr).find("td:eq(1)").text();
	var numero = $(tr).find("td:eq(2)").text();

	$("#idcuenta").val(id);
	$("#cuentasInfo").val(banco + " - " + numero);
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

function eliminalineadetalle(boton) {
	$(boton).closest("tr").remove();
	calcularTotalGeneral();
}

function calcularTotalGeneral() {
	var total = 0;
	$("#detallesdeventa tr").each(function () {
		var cant = parseInt($(this).find("input[name='cantidad[]']").val());
		var precio = parseFloat($(this).find("input[name='precio[]']").val());
		total += cant * precio;
	});
	$("#totalPrecio").text("$" + total.toFixed(2));
}

function pone(idVenta) {
	limpia();
	var datos = new FormData();
	datos.append("accion", "consultar_uno");
	datos.append("idVenta", idVenta);
	enviaAjax(datos);
}

// VALIDACION ANTES DE ENVIAR
function validarEnvioVenta() {
	if (
		validarSelect($("#zonaDelivery"), $("#szonaDelivery"), "Seleccione una zona") === 0 ||
		validarkeyup(/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ\s#\-\.,]{3,50}$/, $("#calleDelivery"), $("#scalleDelivery"), "Calle Inválida") === 0 ||
		validarkeyup(/^[A-Za-z0-9\-\#]{1,10}$/, $("#numCasaDelivery"), $("#snumCasaDelivery"), "N° de Casa Inválido") === 0
	) {
		mostrarMensaje("Por favor verifique los datos de la dirección de delivery en el paso anterior.");
		return false;
	}

	if (validarSelect($("#tipoPago"), $("#stipoPago"), "Seleccione una opción") === 0) {
		mostrarMensaje("Tipo de Pago Inválido <br>(Debe seleccionar un método de pago)",);
		return false;
	}

	var tipo = $("#tipoPago").val();

	if (tipo === "Pago Movil") {
		if ($("#idcuenta").val() == "") {
			mostrarMensaje("Debe seleccionar una cuenta destino.");
			return false;
		}
		if (validarkeyup(/^[0-9\.]{7,12}$/, $("#cedulaPago"), $("#scedulaPago"), "Cédula Inválida",) === 0) {
			mostrarMensaje("Cédula de Titular Inválida <br>(El formato debe ser: 99999999)",);
			return false;
		}
		if (validarkeyup(/^[0-9\-+ ]{11,12}$/, $("#tlfPago"), $("#stlfPago"), "Teléfono Inválido",) === 0) {
			mostrarMensaje("Teléfono de Titular Inválido <br>(Formato: 0414-0000000)",);
			return false;
		}
		if (validarkeyup(/^[A-Za-z0-9]{4}$/, $("#refPago"), $("#srefPago"), "Referencia Inválida",) === 0) {
			mostrarMensaje("Número de Referencia Inválido <br>(Debe contener solo letras y números)",);
			return false;
		}
	}

	return true;
}

// Función para rellenar los datos de la venta al modificar
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

	$("#zonaDelivery").val(v.zonaDelivery);
	$("#calleDelivery").val(v.calleDelivery);
	$("#numCasaDelivery").val(v.numCasaDelivery);

	$("#tipoPago").val(v.tipoPago);

	$("#cedulaPago").val(v.cedulaPago);
	$("#tlfPago").val(v.tlfPago);
	$("#refPago").val(v.referencia);

	if (v.tipoPago === "Pago Movil") {
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
			"<button type='button' class='btn btn-outline-danger btn-sm' onclick='eliminalineadetalle(this)'><i class='bi bi-x-lg</i></button>" +
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
		etiquetamensaje.text(mensaje);
		return 0;
	}
}

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

function mostrarMensaje(mensaje) {
	$("#contenidoModal").html(mensaje);
	$("#mostrarModal").modal("show");
}

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
				var lee = JSON.parse(respuesta);

				if (lee.resultado == "consultar") {
					$("#listaVentas").html(lee.mensaje);
				}
				else if (lee.resultado == "modal_personal") {
					$("#listaPersonalModal").html(lee.mensaje);
				}
				else if (lee.resultado == "modal_clientes") {
					$("#listaClientesModal").html(lee.mensaje);
				}
				else if (lee.resultado == "modal_productos") {
					$("#listaProductosModal").html(lee.mensaje);
				}
				else if (lee.resultado == "modal_cuentas") {
					$("#listaCuentasModal").html(lee.mensaje);
				}
				else if (lee.resultado == "incluir" || lee.resultado == "modificar" || lee.resultado == "eliminar") {
					mostrarMensaje(lee.mensaje);
					$("#modal_detallesdepago").modal("hide");
					consultar();
				}
				else if (lee.resultado == "consultar_detalle") {
					$("#contenidoDetalleVenta").html(lee.mensaje);
					$("#modal_ver_detalle").modal("show");
				}
				else if (lee.resultado == "consultar_uno") {
					cargarDatosVenta(lee.venta, lee.productos);
				}
				else if (lee.resultado === "buscar" || lee.resultado === "buscar_fecha") {
					$("#listaVentas").html(lee.mensaje);
				}
				else if (lee.resultado == "error") {
					mostrarMensaje(lee.mensaje);
				}
			} catch (e) {
				mostrarMensaje("Error al procesar la respuesta del servidor.");
			}
		},
		error: function () {
			mostrarMensaje("Error de comunicación.");
		},
	});
}

function limpia() {
	$("#form_venta_completa")[0].reset();
	$("#idVenta").val("");
	$("#idDetalle").val("");
	$("#idPedido").val("");
	$("#idpersonal").val("");
	$("#idCliente").val("");
	$("#idcuenta").val("");
	$("#cuentasInfo").val("");

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