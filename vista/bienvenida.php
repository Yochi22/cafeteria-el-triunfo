<!DOCTYPE html>
<html lang="en">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <div class="container mt-3">
        <nav class="navbar navbar-expand-md bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand"></a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-md-end" id="navbarNav">
                    <div class="d-flex flex-column flex-md-row gap-2 mt-3 mt-md-0">
                        <button type="button" class="btn btn-invisible btn-lg fw-bolder text-start text-md-center">
                            <i class="bi bi-record-circle text-success"></i> Abierto
                        </button>
                        <a href="?pagina=menu" class="btn btn-crear btn-lg fw-bolder" rel="noopener noreferrer">Menu</a>
                        <a href="#"><i class="bi bi-whatsapp text-success fs-2 ms-3"></i></a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="row m-5">
            <div class="col">
            </div>
        </div>
        <div class="row m-5">
            <div class="col">
            </div>
        </div>
        <div class="row m-5">
            <div class="col">
            </div>
        </div>

        <div class="row m-5">
            <div class="col-6 text-center">
                <h1 class="text-dashboard"><i class="bi bi-emoji-wink"></i> Bienvenid@s a la Cafetería <br>"El Triunfo"</h1>
                <h6><i class="bi bi-cup-hot-fill"></i> En Local | <i class="bi bi-bag"></i> Para Llevar | <i class="bi bi-bicycle"></i> Delivery</h6>
                <h6 class="card-text"> <i class="bi bi-geo-alt-fill"></i> Av. Principal, Edif. El Triunfo, Local 1</h6>
                <h6><i class="bi bi-clock"></i> Horario de atención: 08:00 AM - 08:00 PM</h6>
                <div class="text-center">
                    <a href="#" class="text-success fs-1"><i class="bi bi-whatsapp"></i></a>
                    <a href="#" class="text-danger fs-1 ms-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-primary fs-1 ms-3"><i class="bi bi-facebook"></i></a>
                </div>
            </div>
            <div class="col-6">
                <div class="d-grid gap-2 col-9 mx-auto mt-5">
                    <a href="?pagina=menu" class="btn btn-crear btn-lg fw-bolder" rel="noopener noreferrer">MENU</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>