// funcion para la lista de clientes
function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function () {

    consultar();

    // VALIDACION DE DATOS
    // -- validacion de cedula --
    $("#cedulaCli").on("keypress", function (e) {
        validarkeypress(/^[0-9-\.]*$/, e);
    });
    $("#cedulaCli").on("keyup", function () {
        validarkeyup(/^[0-9\.]{7,12}$/, $(this), $("#scedulaCli"), "Cedula Invalida - El Formato debe ser: 999999999");
    });

    // -- validacion de nombre --
    $("#nombreCli").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombreCli").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#snombreCli"), "Nombre Invalido - Solo letras entre 3 y 100 caracteres");
    });

    // -- Validacion de apellido --
    $("#apellidoCli").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#apellidoCli").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this), $("#sapellidoCli"), "Apellido Invalido - Solo letras entre 3 y 100 caracteres");
    });

    // -- validacion de telefono --
    $("#tlfCli").on("keypress", function (e) {
        validarkeypress(/^[0-9\-+ ]*$/, e);
    });
    $("#tlfCli").on("keyup", function () {
        validarkeyup(/^[0-9\-+ ]{11,12}$/, $(this), $("#stlfCli"), "Telefono Invalido - Formato de: 0000-0000000");
    });
    // Fin de Validaciones de datos

    //Control de botones
    $("#btnGuardar").on("click", function () {
        // BOTON INCLUIR
        if ($(this).text() == 'incluir') {
            if (validarEnvio()) {

                var datos = new FormData();
                datos.append('accion', 'incluir');
                datos.append('cedulaCli', $("#cedulaCli").val());
                datos.append('nombreCli', $("#nombreCli").val());
                datos.append('apellidoCli', $("#apellidoCli").val());
                datos.append('tlfCli', $("#tlfCli").val());
                enviaAjax(datos);
            }
        }
        // BOTON MODIFICAR
        else if ($(this).text() == 'modificar') {
            if (validarEnvio()) {
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('cedulaCli', $("#cedulaCli").val());
                datos.append('nombreCli', $("#nombreCli").val());
                datos.append('apellidoCli', $("#apellidoCli").val());
                datos.append('tlfCli', $("#tlfCli").val());
                enviaAjax(datos);
            }
        }
    });

    //BOTON BUSCAR
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

    // BOTON CONFIMAR ELIMINACION DE CLIENTE
    $("#btnEliminar").on("click", function () {
        var cedulaEliminada = $("#eliminar").val();

        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('cedulaCli', cedulaEliminada);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function () {
        limpia();
        $("#cedulaCli").prop('readonly', false);
        $("#btnGuardar").text("incluir");
        $("#modal_cliente").modal("show");
    });
});

//validacion de los datos antes de enviarlos
function validarEnvio() {
    // -- validacion de envio de cedula --
    if (validarkeyup(/^[0-9\.]{7,11}$/, $("#cedulaCli"), $("#scedulaCli"), "Cedula Invalida - (El Formato debe ser: 999999999)") == 0) {
        mostrarMensaje("Cedula Invalida <br/>" + "(El formato debe ser: 99999999)");
        return false;
    }
    // -- validacion de envio de nombre --
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombreCli"), $("#snombreCli"), "Nombre Invalido - (Solo letras entre 3 y 100 caracteres)") == 0) {
        mostrarMensaje("Nombre Invalido <br>" + "(Solo letras entre 3 y 100 caracteres)");
        return false;
    }
    // validacion de envio de apellido --
    else if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#apellidoCli"), $("#sapellidoCli"), "apellido Invalido - (Solo letras entre 3 y 100 caracteres)") == 0) {
        mostrarMensaje("Apellido Invalido <br>" + "(Solo letras entre 3 y 100 caracteres)");
        return false;
    }
    // validacion de envio de telefono --
    else if (validarkeyup(/^[0-9\-+ ]{11,12}$/, $("#tlfCli"), $("#stlfCli"), "Telefono Invalido - (Formato de: 0000-0000000)") == 0) {
        mostrarMensaje("Telefono Invalido <br>" + "(Formato de: 0000-0000000)");
        return false;
    }
    return true;
}

// funcion para mostrar del modal del mensaje
function mostrarMensaje(mensaje) {
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function () { $("#mostrarModal").modal("hide") }, 5000);
}

//Función para validar por Keypress
function validarkeypress(er, e) {
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) {
        e.preventDefault();
    }
}

//Función para validar por keyup
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

//funcion para llenar el formulario con los datos del cliente
function pone(pos) {
    linea = $(pos).closest('tr');

    var nombreCompleto = $(linea).find("td:eq(1)").text();
    var nombreSeparado = nombreCompleto.split(' ');

    $("#cedulaCli").val($(linea).find("td:eq(0)").text());
    $("#nombreCli").val(nombreSeparado[0]);
    $("#apellidoCli").val(nombreSeparado[1]);
    $("#tlfCli").val($(linea).find("td:eq(2)").text());
    $("#btnGuardar").text('modificar');
    $("#modal_cliente").modal("show");

    $("#cedulaCli").prop('readonly', true);
}

function eliminar(pos) {
    var linea = $(pos).closest('tr');
    var cedula = $(linea).find("td:eq(0)").text().trim();

    $("#eliminar").val(cedula);
    $("#modal_eliminar").modal("show");
}

function enviaAjax(datos) {
    $.ajax({
        async: true,
        url: "index.php?pagina=clientes",
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
                    $("#listaClientes").html(lee.mensaje);
                }
                else if (lee.resultado == 'incluir') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Cliente Registrado') {
                        $("#modal_cliente").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'modificar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Cliente Modificado') {
                        $("#modal_cliente").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'eliminar') {
                    mostrarMensaje(lee.mensaje);
                    if (lee.mensaje == 'Cliente Eliminado') {
                        $("#modal_eliminar").modal("hide");
                        consultar();
                    }
                }
                else if (lee.resultado == 'buscar') {
                    $('#listaClientes').html(lee.mensaje);
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

function limpia() {
    $("#cedulaCli").val("");
    $("#nombreCli").val("");
    $("#apellidoCli").val("");
    $("#tlfCli").val("");
}