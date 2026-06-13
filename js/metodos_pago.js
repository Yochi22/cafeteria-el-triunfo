// funcion para la lista
function consultar(){
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function(){

    consultar();

    //validacion de datos
    //1.1 nombre banco
    $("#nombreBanco").on("keypress", function (e) {
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
    });
    $("#nombreBanco").on("keyup", function () {
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this),$("#snombreBanco"),"Nombre Invalido - Solo letras entre 3 y 100 caracteres");
    });

    //1.2 cedula titular
    $("#cedulaTitular").on("keypress", function (e) {
        validarkeypress(/^[0-9-\.]*$/,e);
    });
    $("#cedulaTitular").on("keyup", function () {
        validarkeyup(/^[0-9\.]{7,12}$/,$(this), $("#scedulaTitular"),"Cedula Invalida - Escriba de 7 a 8 numeros sin espacios.");
    });

    //1.3 teléfono
    $("#tlfCuenta").on("keypress", function (e) {
        validarkeypress(/^[0-9\-+ ]*$/, e);
    });

    $("#tlfCuenta").on("keyup", function () {
        validarkeyup(/^[0-9\-+ ]{10,12}$/,$(this), $("#stlfCuenta"),"Teléfono Invalido - Formato de 11 numeros sin espacios.");
    });

    //1.4 cuenta
    $("#cuenta").on("keypress", function (e) {
    validarkeypress(/^[0-9]*$/, e);
    });

    $("#cuenta").on("keyup", function () {
        validarkeyup(/^[0-9]{20}$/, $(this), $("#scuenta"), "Cuenta Inválida - Debe tener exactamente 20 números.");
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
    $("#btnGuardar").on("click", function(){
        // 2.1 Incluir
        if($(this).text() == 'incluir'){
            if(validarEnvio()){
              var datos = new FormData();
              datos.append('accion', 'incluir');
              datos.append('nombreBanco', $("#nombreBanco").val());
              datos.append('cedulaTitular', $("#cedulaTitular").val());
              datos.append('tlfCuenta', $("#tlfCuenta").val());
              datos.append('cuenta', $("#cuenta").val());
              enviaAjax(datos);
            }
        }

        //2.2 modificar
        else if($(this).text() == 'modificar'){
            if(validarEnvio()){
              var datos = new FormData();
              datos.append('accion', 'modificar');
              datos.append('nombreBanco', $("#nombreBanco").val());
              datos.append('cedulaTitular', $("#cedulaTitular").val());
              datos.append('tlfCuenta', $("#tlfCuenta").val());
              datos.append('cuenta', $("#cuenta").val());
              enviaAjax(datos);
            }
        }

        //2.3 eliminar
        if($(this).text() == 'eliminar'){
        if (validarkeyup(/^[0-9]{20}$/, $("#cuenta"), 
        $("#scuenta"), "El formato debe ser 20 numeros sin espacios") == 0) {
            mostrarMensaje("Verifique la Cuenta (Debe tener 20 números)");
        } else{
             var datos = new FormData();
             datos.append('accion', 'eliminar');
             datos.append('cuenta', $("#cuenta").val());
             enviaAjax(datos);
            }
        }
    });

    // confirmar eliminacion de metodo de pago - esta conectada a otra funcion mas abajo.
    $("#btnEliminar").on("click", function(){
        var cuentaEliminada = $("#cuentaEliminar").val();
        console.log("Cuenta a eliminar:", cuentaEliminada);

        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('cuenta', cuentaEliminada);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function(){
        limpia();
        $("#btnGuardar").text("incluir");
        $("#modal_metodo").modal("show");
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

    $("#valorBusqueda").on("keyup", function() {
        ejecutarBusqueda();
    });

    $("#btnBuscar").on("click", function() {
        ejecutarBusqueda();
    });

}); //cierre de funciones principales


//validacion de datos para enviar
function validarEnvio(){
    // -- validacion de envio de nombre de Banco
    if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/,$("#nombreBanco"),$("#snombreBanco"),"Nombre Invalido - Solo letras entre 3 y 100 caracteres)")==0){
        mostrarMensaje("Nombre Invalido <br>" + "Solo letras entre 3 y 100 caracteres)");
        return false;
    }
    // validacion de envio de cedula titular
    else if(validarkeyup(/^[0-9\.]{7,12}$/,$("#cedulaTitular"),$("#scedulaTitular"),"Cedula Invalida - Escriba de 7 a 8 numeros sin espacios.)")==0){
        mostrarMensaje("Cedula Invalida <br>" + "Escriba de 7 a 8 numeros sin espacios.");
        return false;
    }	
    //validacion de envio de teléfono
    else if(validarkeyup(/^[0-9\-+ ]{10,12}$/,$("#tlfCuenta"),$("#stlfCuenta"),"tlfCuenta Invalido - Formato de 11 numeros sin espacios.")==0){
        mostrarMensaje("Teléfono Invalido <br>" + "Formato de 11 numeros sin espacios.");
        return false;
    }
    //validacion de envio de num cuenta
    if(validarkeyup(/^[0-9]{20}$/, $("#cuenta"), $("#scuenta"),"Cuenta Inválida - Debe tener exactamente 20 números.")==0){
        mostrarMensaje("Cuenta Inválida <br>" + "Debe tener exactamente 20 números.");
        return false;
    }
    return true;
}

// funcion para mostrar el modal del mensaje
function mostrarMensaje(mensaje){
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function() {$("#mostrarModal").modal("hide")}, 5000);
}

//Función para validar keypresses
function validarkeypress(er,e){
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
	if(!a){
        e.preventDefault();
    }
}

//Función para validar keyups
function validarkeyup(er,etiqueta,etiquetamensaje,mensaje){
    a = er.test(etiqueta.val());
    if(a){
        etiquetamensaje.text("");
        return 1;
    } else{
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

//funcion para llenar el formulario
function pone(pos){
    linea = $(pos).closest('tr');

    $("#nombreBanco").val($(linea).find("td:eq(0)").text().trim());
    $("#cedulaTitular").val($(linea).find("td:eq(1)").text().trim());
    $("#tlfCuenta").val($(linea).find("td:eq(2)").text().trim());
    $("#cuenta").val($(linea).find("td:eq(3)").text().trim());
    $("#btnGuardar").text('modificar');
    $("#modal_metodo").modal("show");
}

//boton conectado a funcion ELIMINAR
function eliminar(pos){
    var linea = $(pos).closest('tr');
    var cuenta = $(linea).find("td:eq(3)").text().trim();

    $("#cuentaEliminar").val(cuenta);
    $("#modal_eliminar").modal("show");
}

function enviaAjax(datos){
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
        success: function (respuesta){
            console.log(respuesta);
            try{
                var lee = JSON.parse(respuesta);
                if(lee.resultado == 'consultar'){
                    $("#listaMetodos").html(lee.mensaje);
                } 
                else if(lee.resultado == 'incluir'){
                    mostrarMensaje(lee.mensaje);
                    if(lee.mensaje == 'Metodo de Pago Registrado'){
                        $("#modal_metodo").modal("hide");
                        consultar();
                    }
                }
                else if(lee.resultado == 'modificar'){
                    mostrarMensaje(lee.mensaje);
                    if(lee.mensaje == 'Metodo de Pago Modificado'){
                        $("#modal_metodo").modal("hide");
                        consultar();
                    }
                }
                else if(lee.resultado == 'eliminar'){
                    mostrarMensaje(lee.mensaje);
                    if(lee.mensaje == 'Metodo de Pago Eliminado'){
                        $("#modal_eliminar").modal("hide");
                        consultar();
                    }
                }
                else if(lee.resultado == 'buscar'){
                    $('#listaMetodos').html(lee.mensaje);
                }
                else if(lee.resultado == "error"){
                    mostrarMensaje(lee.mensaje);
                }
                } catch(e){
                alert("Error en JSON" + e.name);
            }
        },
        error: function(request, status, err){
            if(status == "timeout"){
                mostrarMensaje("Servidor Ocupado, Intente de Nuevo");
            } else {
                mostrarMensaje("ERROR: <br/>" + request + status + err);
            }
        },
        complete: function () {},
    });
}

//LIMPIAR
function limpia(){
    $("#nombreBanco").val("");
    $("#cedulaTitular").val("");
    $("#tlfCuenta").val("");
    $("#cuenta").val("");
}