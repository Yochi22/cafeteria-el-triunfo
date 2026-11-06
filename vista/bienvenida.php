<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body class="login-bg">
    <div class="container py-3 d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-md-end" id="navbarNav">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-2 mt-3 mt-md-0">
                        <button type="button" id="btnEstadoLocal" class="btn btn-invisible btn-lg fw-bolder text-center text-white">
                            <i id="iconoEstado" class="bi bi-record-circle text-success"></i> <span id="textoEstado">Abierto</span>
                        </button>
                        <a href="?pagina=catalogo" class="btn btn-crear btn-lg fw-bolder" rel="noopener noreferrer">Menu</a>
                        <div class="text-center ms-md-2">
                            <a href="#"><i class="bi bi-whatsapp text-white fs-2"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="row align-items-center justify-content-center my-auto g-4">
            <div class="col-12 col-md-6 text-center text-white px-3">
                <h1 class="display-6 fw-bold mb-3"><i class="bi bi-emoji-wink"></i> Bienvenid@s a la Cafetería <br>"El Triunfo"</h1>
                <h6 class="mb-2"><i class="bi bi-cup-hot-fill"></i> En Local | <i class="bi bi-bag"></i> Para Llevar | <i class="bi bi-bicycle"></i> Delivery</h6>
                <h6 class="mb-2"><i class="bi bi-geo-alt-fill"></i> Av. Principal, Edif. El Triunfo, Local 1</h6>
                <h6 class="mb-4"><i class="bi bi-clock"></i> Horario de hoy: <span id="lblHorarioHoy">Cargando...</span></h6>

                <div class="d-flex justify-content-center">
                    <a href="#" class="text-white fs-1"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="text-white fs-1 ms-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white fs-1 ms-3"><i class="bi bi-facebook"></i></a>
                </div>
            </div>

            <div class="col-12 col-md-6 text-center">
                <div class="d-grid gap-2 col-8 col-sm-6 col-md-7 mx-auto mt-4 mt-md-0">
                    <a href="?pagina=catalogo" class="btn btn-crear btn-lg fw-bolder py-3 shadow" rel="noopener noreferrer">VER MENÚ</a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("comunes/modal.php"); ?>
    <script src="js/bienvenida.js"></script>
</body>

</html>