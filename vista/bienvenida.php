<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body class="login-bg">
    <div class="container py-3 py-md-4 d-flex flex-column min-vh-100">
        <nav class="navbar navbar-dark py-2">
            <div class="container-fluid px-2 px-sm-3 d-flex align-items-center justify-content-end">
                <div id="btnEstadoLocal" class="btn btn-invisible bg-transparent border-0 fs-4 fs-md-3 fw-bolder text-white d-inline-flex align-items-center gap-2 px-3 py-2">
                    <i id="iconoEstado" class="bi bi-record-circle text-success fs-3 fs-md-2"></i>
                    <span id="textoEstado">Abierto</span>
                </div>
            </div>
        </nav>

        <div class="row align-items-center justify-content-center my-auto g-4 g-lg-5 py-3">
            <div class="col-12 col-md-7 col-lg-6 text-center text-white px-3 px-sm-4">
                <h1 class="fs-2 fs-sm-1 display-md-6 fw-bold mb-3 lh-sm">
                    <i class="bi bi-emoji-wink"></i> Bienvenid@s a la Cafetería <br class="d-none d-sm-inline">"El Triunfo"
                </h1>
                <h6 class="mb-2 fw-normal lh-base">
                    <i class="bi bi-cup-hot-fill"></i> En Local <span class="mx-1">|</span>
                    <i class="bi bi-bag"></i> Para Llevar <span class="mx-1">|</span>
                    <i class="bi bi-bicycle"></i> Delivery
                </h6>
                <h6 class="mb-2 fw-normal lh-base">
                    <i class="bi bi-geo-alt-fill"></i> Av. Principal, Edif. El Triunfo, Local 1
                </h6>
                <h6 class="mb-4 fw-normal lh-base">
                    <i class="bi bi-clock"></i> Horario de hoy: <span id="lblHorarioHoy" class="fw-semibold">Cargando...</span>
                </h6>

                <div class="d-flex justify-content-center align-items-center gap-4">
                    <a href="https://wa.me/584143505152?text=Hola%20Cafeter%C3%ADa%20El%20Triunfo%2C%20quisiera%20hacer%20un%20pedido%20o%20consultar%20el%20men%C3%BA." target="_blank" rel="noopener noreferrer" class="text-white text-decoration-none fs-2 fs-md-1 lh-1" aria-label="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="https://www.instagram.com/eltriunfocoffeebar?avpt=Y25pZHlndW1ucHk=" target="_blank" rel="noopener noreferrer" class="text-white text-decoration-none fs-2 fs-md-1 lh-1" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://www.tiktok.com/@eltriunfocoffeebar?_r=1&_t=ZS-9BiLK50ekzM" target="_blank" rel="noopener noreferrer" class="text-white text-decoration-none fs-2 fs-md-1 lh-1" aria-label="Facebook">
                        <i class="bi bi-tiktok"></i>
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-5 col-lg-6 text-center px-3">
                <div class="d-grid col-10 col-sm-7 col-md-9 col-lg-7 mx-auto mt-2 mt-md-0">
                    <a href="?pagina=catalogo" class="btn btn-crear btn-lg fw-bolder py-3 shadow" rel="noopener noreferrer">VER MENÚ</a>
                </div>
            </div>
        </div>
    </div>

    <?php require_once("comunes/modal.php"); ?>
    <script src="js/bienvenida.js"></script>
</body>

</html>