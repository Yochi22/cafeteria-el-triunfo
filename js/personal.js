// funcion para la lista de personal
function consultar(){
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviaAjax(datos);
}

$(document).ready(function(){

    consultar();

    // VALIDACION DE DATOS
    // -- validacion de cedula --
    $("#cedulaPer").on("keypress",function(e){
        validarkeypress(/^[0-9-\.]*$/,e);
    });
    $("#cedulaPer").on("keyup",function(){
        validarkeyup(/^[0-9\.]{7,12}$/,$(this), $("#scedulaPer"),"Cédula Inválida - El Formato debe ser: 99999999");
    });
    
    // -- validacion de nombrePer --
    $("#nombrePer").on("keypress",function(e){
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
    });
    $("#nombrePer").on("keyup",function(){
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this),$("#snombrePer"),"nombrePer Inválido - Solo letras entre 3 y 100 caracteres");
    });

    // -- Validacion de apellidoPer --
    $("#apellidoPer").on("keypress",function(e){
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
    });
    $("#apellidoPer").on("keyup",function(){
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $(this),$("#sapellidoPer"),"apellidoPer Inválido - Solo letras entre 3 y 100 caracteres");
    });

    // -- validacion de contraseña --
    $("#password").on("keyup",function(){
        validarkeyup(/^.{4,100}$/,$(this), $("#spassword"),"Contraseña Inválida - Mínimo 4 caracteres");
    });

   
    $("#rol").on("keypress",function(e){
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/,e);
    });
    $("#rol").on("keyup",function(){
        
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $(this),$("#srol"),"Rol Inválido - Solo letras entre 3 y 50 caracteres");
    });
    // Fin de Validaciones de datos

    //Control de botones
    $("#btnGuardar").on("click", function(){
        // BOTON INCLUIR
        if($(this).text() == 'incluir'){
            if(validarEnvio()){
              var datos = new FormData();
              datos.append('accion', 'incluir');
              datos.append('cedulaPer', $("#cedulaPer").val());
              datos.append('nombrePer', $("#nombrePer").val());
              datos.append('apellidoPer', $("#apellidoPer").val());
              datos.append('password', $("#password").val());
              datos.append('rol', $("#rol").val());
              enviaAjax(datos);
            }
        }
        // BOTON MODIFICAR
        else if($(this).text() == 'modificar'){
            if(validarEnvio()){
                var datos = new FormData();
                datos.append('accion', 'modificar');
                datos.append('cedulaPer', $("#cedulaPer").val());
                datos.append('nombrePer', $("#nombrePer").val());
                datos.append('apellidoPer', $("#apellidoPer").val());
                datos.append('password', $("#password").val());
                datos.append('rol', $("#rol").val());
                enviaAjax(datos);
            }
        }
        // BOTON ELIMINAR
        if($(this).text() == 'eliminar'){
            if(validarkeyup(/^[0-9\.]{7,12}$/,$("#cedulaPer"), 
            $("#scedulaPer"),"El formato debe ser 9999999")==0){ 
                mostrarMensaje("Cédula Inválida - El formato debe ser: 99999999");
            } else{
                var datos = new FormData();
                datos.append('accion', 'eliminar');
                datos.append('cedulaPer', $("#cedulaPer").val());
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

    $("#valorBusqueda").on("keyup", function() {
        ejecutarBusqueda();
    });

    $("#btnBuscar").on("click", function() {
        ejecutarBusqueda();
    });

    // BOTON CONFIRMAR ELIMINACION DE PERSONAL
    $("#btnEliminar").on("click", function(){
        var cedulaEliminada = $("#cedulaEliminar").val();

        var datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('cedulaPer', cedulaEliminada);
        enviaAjax(datos);
    });

    $("#incluir").on("click", function(){
        limpia();
        $("#cedulaPer").prop("readonly", false);
        $("#btnGuardar").text("incluir");
        $("#modal_personal").modal("show");
    });
});

//validacion de los datos antes de enviarlos
function validarEnvio(){
    // -- validacion de envio de cedula --
    if(validarkeyup(/^[0-9\.]{7,12}$/,$("#cedulaPer"), $("#scedulaPer"),"Cédula Inválida - (El Formato debe ser: 99999999)")==0){
        mostrarMensaje("Cédula Inválida <br/>" + "(El formato debe ser: 99999999)");    
        return false;                   
    }
    // -- validacion de envio de nombrePer --
    else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#nombrePer"),$("#snombrePer"),"nombrePer Inválido - (Solo letras entre 3 y 100 caracteres)")==0){
        mostrarMensaje("nombrePer Inválido <br>" + "(Solo letras entre 3 y 100 caracteres)");
        return false;
    }
    // validacion de envio de apellidoPer --
    else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,100}$/, $("#apellidoPer"),$("#sapellidoPer"),"apellidoPer Inválido - (Solo letras entre 3 y 100 caracteres)")==0){
        mostrarMensaje("apellidoPer Inválido <br>" + "(Solo letras entre 3 y 100 caracteres)");
        return false;
    }
    // validacion de envio de contraseña --
    if(validarkeyup(/^.{4,100}$/,$("#password"), $("#spassword"),"Contraseña Inválida - (Mínimo 4 caracteres)")==0){
        mostrarMensaje("Contraseña Inválida <br>" + "(Mínimo 4 caracteres)");    
        return false;                   
    }
    // -- validacion de envio de rol --
    else if(validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,50}$/, $("#rol"),$("#srol"),"Rol Inválido - (Solo letras entre 3 y 50 caracteres)")==0){
        mostrarMensaje("Rol Inválido <br>" + "(Solo letras entre 3 y 50 caracteres)");
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

//funcion para llenar el formulario con los datos del personal
function pone(pos){
    linea = $(pos).closest('tr');
    $("#cedulaPer").prop("readonly", true);
    var nombrePerCompleto = $(linea).find("td:eq(1)").text();
    var nombrePerSeparado = nombrePerCompleto.split(' ');

    $("#cedulaPer").val($(linea).find("td:eq(0)").text());
    $("#nombrePer").val(nombrePerSeparado[0]);
    $("#apellidoPer").val(nombrePerSeparado[1]);
    $("#password").val($(linea).find("td:eq(2)").text());
    $("#rol").val($(linea).find("td:eq(3)").text());
    $("#btnGuardar").text('modificar');
    $("#modal_personal").modal("show");
}

function eliminar(pos){
    var linea = $(pos).closest('tr');
    var cedula = $(linea).find("td:eq(0)").text();

    $("#cedulaEliminar").val(cedula);
    $("#modal_eliminar").modal("show");
}


function enviaAjax(datos){
    $.ajax({
        async: true,
        url: "index.php?pagina=personal",
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
                    $("#listaPersonal").html(lee.mensaje);
                } 
                else if(lee.resultado == 'incluir'){
                    mostrarMensaje(lee.mensaje);
                    if(lee.mensaje == 'Empleado Registrado'){
                        $("#modal_personal").modal("hide");
                        consultar();
                    }
                }
                else if(lee.resultado == 'modificar'){
                    mostrarMensaje(lee.mensaje);
                    if(lee.mensaje == 'Personal Modificado' || lee.mensaje == 'Empleado Modificado'){
                        $("#modal_personal").modal("hide");
                        consultar();
                    }
                }
                else if(lee.resultado == 'eliminar'){
                    mostrarMensaje(lee.mensaje);
                    if(lee.mensaje == 'Personal Eliminado' || lee.mensaje == 'Empleado Eliminado'){
                        $("#modal_eliminar").modal("hide");
                        consultar();
                    }
                } 
                else if(lee.resultado == 'buscar'){
                    $('#listaPersonal').html(lee.mensaje);
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

function limpia(){
    $("#cedulaPer").val("");
    $("#nombrePer").val("");
    $("#apellidoPer").val("");
    $("#password").val("");
    $("#rol").val("");
}