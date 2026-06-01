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
            try {
                let categorias = JSON.parse(respuesta);
                let html = '';

                if (categorias.error) {
                    console.error(categorias.error);
                    return;
                }

                categorias.forEach(function(r) {
                    let foto = (r.foto && r.foto.trim() !== '') ? r.foto : 'img/principal.jpg';
                    html += `
                    <div class="col-md-4 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <img src="${foto}" class="card-img-top" style="height:180px; object-fit:cover;">
                            <div class="card-body">
                                <h5 class="text-dashboard fw-bold mb-1">${r.nombre}</h5>
                                <p class="text-muted small mb-3">${r.descripcion}</p>
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="editar(${r.idCategoria})">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="eliminar(${r.idCategoria})">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                });

                $("#cuadricula_categorias").html(html);

            } catch (e) {
                console.error("Error Procesando los Datos: ", e);
            }
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
            try {
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
            } catch (e) {
                console.error("Error al obtener la categoría: ", e);
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