$(document).ready(function () {
    consultar();

    $("#f").on("submit", function (e) {
        e.preventDefault();
        enviar();
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
        success: function (respuesta) {
            $("#listaMetodospago").html(respuesta);
        }
    });
}

function nuevo() {
    $("#f")[0].reset();
    $("#idMetodo").val("");
    $("#accion").val("incluir");
    $("#modal_metodopago_label").text("Nuevo Método de Pago");
    $("#btnGuardar").text("Guardar Método de Pago");
    $("#modal_metodopago").modal("show");
}

function editar(id) {
    let datos = new FormData();
    datos.append('accion', 'consultar_id'); 
    datos.append('idMetodo', id);

    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function (respuesta) {
            let data = JSON.parse(respuesta);
            
            if (data.resultado === 'encontro') {
                $("#idMetodo").val(data.idMetodo);
                $("#nombreBanco").val(data.nombreBanco);
                $("#cedulaTitular").val(data.cedulaTitular);
                $("#telefono").val(data.telefono);
                $("#cuenta").val(data.cuenta);

                $("#accion").val("modificar");
                $("#modal_metodopago_label").text("Editar Método de Pago");
                $("#btnGuardar").text("Modificar Método de Pago");
                $("#modal_metodopago").modal("show");
            } else {
                alert("No se Encontró el Método de Pago");
            }
        }
    });
}

    function eliminar(id) {
    if (confirm("¿Estás Seguro de eliminar este Método de Pago?")) {
        let datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('idMetodo', id);

        $.ajax({
            url: '',
            type: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function (respuesta) {
                alert(respuesta);
                consultar();
            }
        });
      }
}

    function enviar() {
    let datos = new FormData($("#f")[0]);

    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function (respuesta) {
            alert(respuesta);
            $("#modal_metodopago").modal("hide");
            consultar();
        }
    });

}
