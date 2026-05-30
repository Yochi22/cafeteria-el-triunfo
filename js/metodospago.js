//java de Jose, no esta bien adaptado a este codigo aun

$(document).ready(function() {
    consultar();

    $("#btnGuardar").on("click", function() {
        guardar();
    });
});

function consultar() {
    let datos = new FormData();
    datos.append('accion', 'consultar');
    
    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            $("#lista_metodos").html(respuesta);
        }
    });
}

function nuevoMetodo() {
    $("#f_metodo")[0].reset();
    $("#idMetodoPago").val("");
    $("#modalTitulo").text("Añadir método de pago");
    $("#modal_metodopago").modal("show");
}

function guardar() {
    let accion = ($("#idMetodoPago").val() == "") ? "incluir" : "modificar";
    let datos = new FormData();
    datos.append('accion', accion);
    datos.append('idMetodoPago', $("#idMetodoPago").val());
    datos.append('nombreBanco', $("#nombreBanco").val());
    datos.append('cedulaTitular', $("#cedulaTitular").val());
    datos.append('telefono', $("#telefono").val());
    datos.append('cuenta', $("#cuenta").val());
    
    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            alert(respuesta);
            $("#modal_metodopago").modal("hide");
            consultar();
        }
    });
}

function editar(id) {
    let datos = new FormData();
    datos.append('accion', 'buscar');
    datos.append('idMetodoPago', id);
    
    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function(respuesta) {
            let r = JSON.parse(respuesta);
            $("#idMetodoPago").val(r.idMetodoPago);
            $("#nombreBanco").val(r.nombreBanco);
            $("#cedulaTitular").val(r.cedulaTitular);
            $("#telefono").val(r.telefono);
            $("#cuenta").val(r.cuenta);
            
            $("#modalTitulo").text("Editar método de pago");
            $("#modal_metodopago").modal("show");
        }
    });
}

function eliminar(id) {
    if(confirm("¿Desea eliminar este método de pago?")) {
        let datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('idMetodoPago', id);
        
        $.ajax({
            url: '',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function(respuesta) {
                alert(respuesta);
                consultar();
            }
        });
    }
}