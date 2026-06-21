// funcion para consultar
function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function () {

    consultar();

    //validacion de keyup y keypress
    //1.1 nombre banco
    $("#nombreBanco").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombreBanco").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombreBanco"), "Nombre Invalido - Solo letras entre 3 y 100 caracteres");
    });

    //1.2 cedula titular
    $("#cedulaTitular").on("keypress", function (e) {
        validarkeypress(/^[0-9-\.]*$/, e);
    });
    $("#cedulaTitular").on("keyup", function () {
        validarkeyup(/^[0-9\.]{7,12}$/, $(this), $("#scedulaTitular"), "Cedula Invalida - Escriba de 7 a 8 numeros sin espacios.");
    });

    //1.3 teléfono
    $("#tlfCuenta").on("keypress", function (e) {
        validarkeypress(/^[0-9\-+ ]*$/, e);
    });

    $("#tlfCuenta").on("keyup", function () {
        validarkeyup(/^[0-9\-+ ]{10,12}$/, $(this), $("#stlfCuenta"), "Teléfono Invalido - Formato de 11 numeros sin espacios.");
    });

    //1.4 numero de cuenta
    $("#numCuenta").on("keypress", function (e) {
        validarkeypress(/^[0-9]*$/, e);
    });

    $("#numCuenta").on("keyup", function () {
        validarkeyup(/^[0-9]{20}$/, $(this), $("#snumCuenta"), "Cuenta Inválida - Debe tener exactamente 20 números.");
    });

    //validacion de SELECT
    //1.5 tipo cuenta
    $("#tipoCuenta").on("change", function () {
        validarSelect(
            $(this),
            $("#stipoCuenta"),
            "Tipo de Cuenta Inválido - Debe seleccionar una opción"
        );
    });



    $("#f").on("submit", function (e) {
        e.preventDefault();
        let accion = $("#accion").val();
        if (accion == "incluir" || accion == "modificar") {
            if (validarEnvio()) {
                var datos = new FormData(this);
                enviaAjax(datos);
            }
        }
    });

    //Botones
    $("#btnGuardar").on("click", function () {
        // 2.1 Incluir
        if ($(this).text() == 'incluir') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('nombreBanco', $("#nombreBanco").val());
                datos.append('cedulaTitular', $("#cedulaTitular").val());
                datos.append('tlfCuenta', $("#tlfCuenta").val());
                datos.append('tipoCuenta', $("#tipoCuenta").val());
                datos.append('numCuenta', $("#numCuenta").val());
                enviaAjax(datos);
            }
        }

        //2.2 modificar
        else if ($(this).text() == 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('nombreBanco', $("#nombreBanco").val());
                datos.append('cedulaTitular', $("#cedulaTitular").val());
                datos.append('tlfCuenta', $("#tlfCuenta").val());
                datos.append('tipoCuenta', $("#tipoCuenta").val());
                datos.append('numCuenta', $("#numCuenta").val());
                enviaAjax(datos);
            }
        }
    });

    // confirmar eliminacion de cuenta - esta conectada a otra funcion mas abajo.
    $("#btnEliminar").on("click", function () {
        var cuentaEliminada = $("#eliminar").val();
        console.log("Cuenta a eliminar:", cuentaEliminada);

        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('numCuenta', cuentaEliminada);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#numCuenta").prop('readonly', false);
        $("#btnGuardar").text("incluir");
        $("#modal_cuentas").modal("show");
    });

    //boton buscar
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

}); //cierre de funciones principales


//validacion de datos para enviar
function validarEnvio() {
    // -- validacion de envio de nombre de Banco
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombreBanco"), $("#snombreBanco"), "Nombre Invalido - Solo letras entre 3 y 100 caracteres)") == 0) {
        mostrarMensaje("Nombre Invalido <br>" + "Solo letras entre 3 y 100 caracteres)");
        return false;
    }
    // validacion de envio de cedula titular
    else if (validarkeyup(/^[0-9\.]{7,12}$/, $("#cedulaTitular"), $("#scedulaTitular"), "Cedula Invalida - Escriba de 7 a 8 numeros sin espacios.)") == 0) {
        mostrarMensaje("Cedula Invalida <br>" + "Escriba de 7 a 8 numeros sin espacios.");
        return false;
    }
    //validacion de envio de teléfono
    else if (validarkeyup(/^[0-9\-+ ]{10,12}$/, $("#tlfCuenta"), $("#stlfCuenta"), "tlfCuenta Invalido - Formato de 11 numeros sin espacios.") == 0) {
        mostrarMensaje("Teléfono Invalido <br>" + "Formato de 11 numeros sin espacios.");
        return false;
    }
    //validacion de envio de num cuenta
    if (validarkeyup(/^[0-9]{20}$/, $("#numCuenta"), $("#snumCuenta"), "Cuenta Inválida - Debe tener exactamente 20 números.") == 0) {
        mostrarMensaje("Cuenta Inválida <br>" + "Debe tener exactamente 20 números.");
        return false;
    }
    //validacion de envio de tipo cuenta
    if (validarSelect($("#tipoCuenta"), $("#stipoCuenta"), "Tipo de Cuenta Inválido - Debe seleccionar una opción") == 0) {
        mostrarMensaje("Tipo de Cuenta Inválido <br> Por favor seleccione si es Ahorro o Corriente.");
        return false;
    }
    return true;
}

// funcion para mostrar el modal del mensaje
function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () { $("#mostrarModal").modal("hide") }, 5000);
}

//Función para validar keypresses
function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault();
    }
}

//Función para validar keyups
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

// funcion para VALIDAR LOS SELECT.
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

//funcion para llenar el formulario
function pone(pos) {
    linea = $(pos).closest('tr');

    $("#nombreBanco").val($(linea).find("td:eq(0)").text().trim());
    $("#cedulaTitular").val($(linea).find("td:eq(1)").text().trim());
    $("#tlfCuenta").val($(linea).find("td:eq(2)").text().trim());
    $("#tipoCuenta").val($(linea).find("td:eq(3)").text().trim());
    $("#numCuenta").val($(linea).find("td:eq(4)").text().trim());
    $("#btnGuardar").text('modificar');
    $("#modal_cuentas").modal("show");

    $("#numCuenta").prop('readonly', true);
}

//boton conectado a funcion ELIMINAR
function eliminar(pos) {
    var linea = $(pos).closest('tr');
    var cuenta = $(linea).find("td:eq(4)").text().trim();

    $("#eliminar").val(cuenta);
    $("#modal_eliminar").modal("show");
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=cuentas",
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
                if (lee.resultado == 'consultar') {
                    $("#listaCuentas").html(lee.mensaje);
                }
                else if (lee.resultado == 'incluir') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Cuenta Registrada') {
                        $("#modal_cuentas").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'modificar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Cuenta Modificada') {
                        $("#modal_cuentas").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'eliminar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Cuenta Eliminada') {
                        $("#modal_eliminar").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'buscar') {
                    $('#listaCuentas').html(lee.mensaje);
                }
                else if (lee.resultado == "error") {
                    mostrarMensaje(lee.mensaje);
                }
            } catch (e) {
                alert("Error en JSON" + e.name);
            }
        },
        error: function (request, status, err) {
            if (status == "timeout") {
                mostrarMensaje("Servidor Ocupado, Intente de Nuevo");
            } else {
                mostrarMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () { },
    });
}

//LIMPIAR
function limpia() {
    $("#nombreBanco").val("");
    $("#cedulaTitular").val("");
    $("#tlfCuenta").val("");
    $("#tipoCuenta").val("");
    $("#numCuenta").val("");
}