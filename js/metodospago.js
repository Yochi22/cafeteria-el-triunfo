// funcion para la lista de clientes
function consultar(){
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function(){

    consultar();

    // Validacion de Datos
    
    //Nombre de Banco
    $("#nombreBanco").on("keypress",function(e){
		validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
	});
	$("#nombreBanco").on("keyup",function(){
		validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this),$("#snombreBanco"),"Nombre Invalido - Solo letras entre 3 y 100 caracteres");
	});

    // Cedula
    $("#cedulaTitular").on("keypress",function(e){
        validarkeypress(/^[0-9-\.]*$/,e);
    });
    $("#cedulaTitular").on("keyup",function(){
        validarkeyup(/^[0-9\.]{7,12}$/,$(this), $("#scedulaTitular"),"Cedula Invalida - El Formato debe ser: 999999999");
	});

    // telefono
    $("#tlfCliente").on("keypress",function(e){
        validarkeypress(/^[0-9\-+ ]*$/, e);
    });
    $("#tlfCliente").on("keyup",function(){
        validarkeyup(/^[0-9\-+ ]{10,12}$/,$(this), $("#stelefono"),"Telefono Invalido - Formato de: 0000-0000000");
	});

    // num cuenta
    $("#cuenta").on("keypress", function(e){
    validarkeypress(/^[0-9\-]*$/, e);
    });
    $("#cuenta").on("keyup", function(){
    validarkeyup(/^[0-9]{20}$/,$(this), $("#scuenta"),"Cuenta Inválida - Debe contener exactamente 20 números sin guiones ni espacios.");
    });
    // Fin de Validaciones de datos

    //Control de botones
    $("#btnGuardar").on("click", function(){
        // BOTON INCLUIR
        if($(this).text() == 'incluir'){
            if(validarEnvio()){
              var datos = new FormData();
              datos.append('accion', 'incluir');
              datos.append('nombreBanco', $("#nombreBanco").val());
              datos.append('cedulaTitular', $("#cedulaTitular").val());
              datos.append('telefono', $("#telefono").val());
              datos.append('cuenta', $("#cuenta").val());
              enviaAjax(datos);
            }
        }
        // BOTON MODIFICAR
        else if($(this).text() == 'modificar'){
            if(validarEnvio()){
              var datos = new FormData();
              datos.append('nombreBanco', $("#nombreBanco").val());
              datos.append('cedulaTitular', $("#cedulaTitular").val());
              datos.append('telefono', $("#telefono").val());
              datos.append('cuenta', $("#cuenta").val());
              enviaAjax(datos);
            }
        }
        // BOTON ELIMINAR
        if($(this).text() == 'eliminar'){
            if(validarkeyup(/^[0-9]{20}$/,$("#cuenta"), 
            $("#cuenta"),"El formato debe ser 9999999")==0){ 
                mostrarMensaje("Numero de Cuenta Invalido - Debe contener exactamente 20 números sin guiones ni espacios");
            } else{
                var datos = new FormData();
                datos.append('accion', 'eliminar');
                datos.append('cuenta', $("#cuenta").val());
                enviaAjax(datos);
            }
        }
    });

    $("#incluir").on("click", function(){
        limpia();
        $("#btnGuardar").text("incluir");
        $("#modal_cliente").modal("show");
    });
});

//validacion de los datos antes de enviarlos
function validarEnvio(){
    if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombreBanco"),$("#snombreBanco"),"Nombre Invalido <br/>" + "(Solo letras entre 3 y 100 caracteres)")==0){
	mostrarMensaje("Nombre Invalido <br>" + "(Solo letras entre 3 y 100 caracteres)");
	return false;
    }
    // -- validacion de envio de cedula --
    else if(validarkeyup(/^[0-9\.]{7,11}$/,$("#cedulaTitular"), $("#scedulaTitular"),"Cedula Invalida <br>" + "(El Formato debe ser: 999999999)")==0){
        mostrarMensaje("Cedula Invalida <br/>"+ "(El formato debe ser: 99999999)");	
		return false;					
	}
    // validacion de envio de apellido --
    else if(validarkeyup(/^[0-9\-+ ]{7,12}$/,$("#tlfCliente"), $("#stlfCliente"),"Telefono Invalido <br>" + "(Formato de: 0000-0000000)")==0){
        mostrarMensaje("Telefono Invalido <br>" + "(Formato de: 0000-0000000)");	
		return false;					
	}
    // -- validacion de envio de num cuenta --
    else if(validarkeyup(/^[0-9]{20}$/, $("#cuentaBancaria"), $("#scuentaBancaria"), "Cuenta Inválida <br>" + "(El formato debe ser de 20 dígitos)") == 0){
    mostrarMensaje("Cuenta Inválida <br/>" + "(Debe ingresar exactamente 20 números, sin guiones ni espacios)");  
    return false;
    }
    return true;
}

// funcion para mostrar del modal del mensaje
function mostrarMensaje(mensaje){
    $("#contenidoModal").html(mensaje);
    $("#mostrarModal").modal("show");
    setTimeout(function() {$("#mostrarModal").modal("hide")}, 5000);
}

//Función para validar por Keypress
function validarkeypress(er,e){
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
	if(!a){
        e.preventDefault();
    }
}

//Función para validar por keyup
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

//funcion para llenar el formulario con los datos del cliente
function pone(pos, accion){
    linea = $(pos).closest('tr');
    $("#nombreBanco").val($(linea).find("td:eq(0)").text());
    $("#cedulaTitular").val($(linea).find("td:eq(2)").text());
    $("#telefono").val($(linea).find("td:eq(2)").text());
    $("#cuenta").val($(linea).find("td:eq(3)").text());
    if(accion == 0){
        $("#btnGuardar").text('modificar');
    } else {
        $("#btnGuardar").text('eliminar');
    }
    $("#modal_cliente").modal("show");
}

function enviaAjax(datos){
    $.ajax({
        async: true,
        url: "",
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
                    $("#listaMetodospago").html(lee.mensaje);
                } 
                else if(lee.resultado == 'incluir'){
                    mostrarMensaje(lee.mensaje);
                    if(lee.mensaje == 'Metodo de Pago Registrado'){
                        $("#modal_cliente").modal("hide");
                        consultar();
                    }
                }
                else if(lee.resultado == 'modificar'){
                    mostrarMensaje(lee.mensaje);
                    if(lee.mensaje == 'Metodo de Pago Modificado'){
                        $("#modal_cliente").modal("hide");
                        consultar();
                    }
                }
                else if(lee.resultado == 'eliminar'){
                    mostrarMensaje(lee.mensaje);
                    if(lee.mensaje == 'Metodo de Pago Eliminado'){
                        $("#modal_cliente").modal("hide");
                        consultar();
                    }
                } else if(lee.resultado == "error"){
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

function limpia(){
    $("#nombreBanco").val("");
	$("#cedulaTitular").val("");
	$("#telefono").val("");
    $("#cuenta").val("");
}