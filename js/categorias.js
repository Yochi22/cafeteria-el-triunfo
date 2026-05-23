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
            $("#cuadricula_categorias").html(respuesta);
        }
    });
}

function nuevo() {
    $("#f")[0].reset();
    $("#idCategoria").val("");
    $("#accion").val("incluir");
    $("#modal_categoria_label").text("Nueva Categoría");
    $("#btn_guardar").text("Guardar Categoría");
    $("#modal_categoria").modal("show");
}

function editar(id) {
    let datos = new FormData();
    datos.append('accion', 'consultar_id'); 
    datos.append('idCategoria', id);

    $.ajax({
        url: '',
        type: 'POST',
        data: datos,
        processData: false,
        contentType: false,
        success: function (respuesta) {
            let data = JSON.parse(respuesta);
            
            if (data.resultado === 'encontro') {
                $("#idCategoria").val(data.idCategoria);
                $("#nombre").val(data.nombre);
                $("#descripcion").val(data.descripcion);
                $("#foto").val(data.foto);

                $("#accion").val("modificar");
                $("#modal_categoria_label").text("Editar Categoría");
                $("#btn_guardar").text("Modificar Categoría");
                $("#modal_categoria").modal("show");
            } else {
                alert("No se Encontró la Categoría");
            }
        }
    });
}

function eliminar(id) {
    if (confirm("¿Estás Seguro de Eliminar esta Categoría?")) {
        let datos = new FormData();
        datos.append('accion', 'eliminar');
        datos.append('idCategoria', id);

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
            $("#modal_categoria").modal("hide");
            consultar();
        }
    });
}