$(document).ready(function () {
    try {
        consultar();
    } catch (e) {
        console.error(e);
    }

    $("#nombreBanco").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z0-9\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });

    $("#cedulaTitular").on("keypress", function (e) {
        validarkeypress(/^[VvEeJjGgP0-9-]*$/, e);
    });

    $("#telefono").on("keypress", function (e) {
        validarkeypress(/^[0-9\-\+\s]*$/, e);
    });

    $("#cuenta").on("keypress", function (e) {
        if (e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '-') {
            e.preventDefault();
        }
        validarkeypress(/^[0-9]*$/, e);
    });

    $("#nombreBanco").on("keyup", function () {
        validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,40}$/, $(this), $("#snombreBanco"), "");
    });

    $("#cedulaTitular").on("keyup", function () {
        validarkeyup(/^[VvEeJjGgP]-?[0-9]{6,10}$|^[0-9]{6,10}$/, $(this), $("#scedulaTitular"), "");
    });

    $("#telefono").on("keyup", function () {
        validarkeyup(/^[0-9\-\+\s]{10,15}$/, $(this), $("#stelefono"), "");
    });

    $("#cuenta").on("keyup", function () {
        validarkeyup(/^[0-9]{20}$/, $(this), $("#scuenta"), "");
    });

    $("#f").on("submit", function (e) {
        e.preventDefault();
        let accion = $("#accion").val();
        if (accion == "incluir" || accion == "modificar") {
            if (validarenvio()) {
                var datos = new FormData(this);
                enviaAjax(datos);
            }
        }
    });
});

function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

function nuevo() {
    limpia();
    $("#accion").val("incluir");
    $("#modal_metodo_label").text("Nuevo Método de Pago");
    $("#btn_guardar").text("Guardar");
    $("#modal_metodo").modal("show");
}

function validarenvio() {
    if (validarkeyup(/^[A-Za-z0-9\b\s\u00f1\u00d1\u00E0-\u00FC]{3,40}$/, $("#nombreBanco"), $("#snombreBanco"), "") == 0) {
        muestraMensaje("Verifique el Nombre del Banco");
        return false;
    }
    if (validarkeyup(/^[VvEeJjGgP]-?[0-9]{6,10}$|^[0-9]{6,10}$/, $("#cedulaTitular"), $("#scedulaTitular"), "") == 0) {
        muestraMensaje("Verifique la Cédula (Ej: V-12345678)");
        return false;
    }
    if (validarkeyup(/^[0-9\-\+\s]{10,15}$/, $("#telefono"), $("#stelefono"), "") == 0) {
        muestraMensaje("Verifique el Teléfono");
        return false;
    }
    if (validarkeyup(/^[0-9]{20}$/, $("#cuenta"), $("#scuenta"), "") == 0) {
        muestraMensaje("Verifique la Cuenta (Debe tener 20 números)");
        return false;
    }
    return true;
}

function muestraMensaje(mensaje) {
    alert(mensaje);
}

function validarkeypress(er, e) {
    let key = e.keyCode || e.which;
    let tecla = String.fromCharCode(key);
    if (!er.test(tecla) && key != 8 && key != 13) {
        e.preventDefault();
    }
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    if (er.test(etiqueta.val())) {
        etiqueta.css("border-color", "green");
        return 1;
    } else {
        etiqueta.css("border-color", "red");
        return 0;
    }
}

function pone(boton, accion) {
    let fila = $(boton).closest('tr');

    let id = fila.find('td:eq(0)').text();
    let banco = fila.find('td:eq(1)').text();
    let cedula = fila.find('td:eq(2)').text();
    let telefono = fila.find('td:eq(3)').text();
    let cuenta = fila.find('td:eq(4)').text();

    if (accion == 0) {
        $("#accion").val("modificar");
        $("#idMetodo").val(id);
        $("#nombreBanco").val(banco);
        $("#cedulaTitular").val(cedula);
        $("#telefono").val(telefono);
        $("#cuenta").val(cuenta);
        
        $("#modal_metodo_label").text("Editar Datos Bancarios");
        $("#btn_guardar").text("Modificar");
        
        $("#modal_metodo").modal("show");
    } else {
        if (confirm("¿Estás seguro de eliminar estos datos bancarios?")) {
            var datos = new FormData();
            datos.append('accion', 'eliminar');
            datos.append('idMetodo', id);
            enviaAjax(datos);
        }
    }
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=metodos_pago",
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
                
                if (lee.resultado == 'consultar') {
                    $("#tabla_metodos").html(lee.mensaje);
                } 
                else if (lee.resultado == 'incluir') {
                    muestraMensaje(lee.mensaje);
                    if (lee.mensaje == 'Método Registrado') { 
                        $("#modal_metodo").modal("hide");
                        consultar();
                    }
                } 
                else if (lee.resultado == 'modificar') {
                    muestraMensaje(lee.mensaje);
                    if (lee.mensaje == 'Método Modificado') {
                        $("#modal_metodo").modal("hide");
                        consultar();
                    }
                } 
                else if (lee.resultado == 'eliminar') {
                    muestraMensaje(lee.mensaje);
                    if (lee.mensaje == 'Método Eliminado') {
                        consultar();
                    }
                } 
                else if (lee.resultado == 'error') {
                    muestraMensaje(lee.mensaje);
                }
            } catch (e) {
                alert("Error en JSON" + e.name);
            }
        },
        error: function(request, status, err) {
            if (status == "timeout") {
                muestraMensaje("Servidor Ocupado, Intente de Nuevo");
            } else {
                muestraMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () {},
    });
}

function limpia() {
    $("#f")[0].reset();
    $("#idMetodo").val("");
    $("#nombreBanco, #cedulaTitular, #telefono, #cuenta").css("border-color", "");
}