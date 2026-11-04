$(document).ready(function () {
    ejecutarFiltrado();

    //boton para filtrar fechas
    $("#btnFiltrar").on("click", function () {
        ejecutarFiltrado();
    });

    // boton para limpiar filtro
    $("#btnLimpiar").on("click", function () {
        $("#fecIni").val("");
        $("#pedFinalizados").val("");
        ejecutarFiltrado();
    });

    // boton para exportar PDF enviando los filtros actuales
    $("#btnGenerarPDF").on("click", function () {
        var fInicio = $("#fecIni").val();
        var fFin = $("#pedFinalizados").val();

        var form = $('<form action="" method="POST" target="_blank">' +
            '<input type="hidden" name="accion" value="generar_pdf">' +
            '<input type="hidden" name="fecIni" value="' + fInicio + '">' +
            '<input type="hidden" name="pedFinalizados" value="' + fFin + '">' +
            '</form>');
        $('body').append(form);
        form.submit();
        form.remove();
    });
});

function ejecutarFiltrado() {
    var datos = new FormData();
    datos.append("accion", "filtrar");
    datos.append("fecIni", $("#fecIni").val());
    datos.append("pedFinalizados", $("#pedFinalizados").val());

    $.ajax({
        async: true,
        url: "index.php?pagina=reporte",
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        success: function (respuesta) {
            try {
                var lee = JSON.parse(respuesta);
                if (lee.resultado == "exito") {
                    $("#totalVen").text(lee.totalVen);
                    $("#promedio").text(lee.promedio);
                    $("#pedFinalizados").text(lee.pedFinalizados);
                    $("#pedCancelados").text(lee.pedCancelados);
                }
            } catch (e) {
                console.error("Error al procesar la respuesta: ", e);
            }
        },
        error: function () {
            console.error("Error de comunicación con el servidor.");
        }
    });
}