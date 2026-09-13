<!DOCTYPE html>
<?php require_once("comunes/encabezado.php"); ?>
<?php require_once("comunes/menu_usuario.php"); ?>

<head>
    <style>
        .hover-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background-color: #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <main>
        <div class="container-fluid p-0 px-md-5 mb-5">

            <div class="row g-0">
                <div class="col-12">
                    <div class="card mb-3 border-0 rounded-0">
                        <img src="img/imagen.jpeg" class="card-img-top w-100" style="object-fit: cover; max-height: 250px;" alt="Banner principal del catálogo">

                        <div class="card-body px-3 px-md-0">
                            <div class="row row-cols-1 row-cols-md-2 d-flex align-items-center g-3">

                                <div class="col d-flex align-items-center mt-3">
                                    <h1 class="card-title text-dashboard mb-0 fs-3 fs-md-1 fw-bold">
                                        <i class="bi bi-cup-hot-fill text-warning"></i> Catálogo
                                    </h1>
                                    <button type="button" class="btn btn-invisible btn-lg fw-bolder text-start text-md-center py-0" aria-label="Estado del local: Abierto">
                                        <i class="bi bi-record-circle text-success"></i> Abierto
                                    </button>
                                </div>

                                <div class="col col-md-4 ms-auto d-flex align-items-center mt-3">
                                    <div class="input-group bg-white border rounded-3 px-2 py-1 align-items-center w-100 shadow-sm transition-all">
                                        <button class="btn p-0 border-0 link-secondary" type="button" id="btnBuscar" aria-label="Buscar producto">
                                            <i class="bi bi-search fs-5"></i>
                                        </button>
                                        <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 shadow-none bg-transparent" placeholder="Buscar platillo o postre...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-0 px-3 px-md-0">
                <div class="col-12">
                    <nav class="mb-3 overflow-x-auto">
                        <div class="nav nav-tabs fs-5 fs-md-4 flex-nowrap flex-md-wrap border-bottom-0" id="nav-tab" role="tablist">
                            <button class="nav-link text-dashboard active fw-semibold" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Destacado</button>
                            <button class="nav-link text-dashboard fw-semibold" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Menú del Día</button>
                            <button class="nav-link text-dashboard fw-semibold" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Postres</button>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">

                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                            <h3 class="mb-4 mt-2 fw-bold" style="color: #FF8C00;">Destacado</h3>

                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 justify-content-center justify-content-md-start">

                                <div class="col">
                                    <div class="card border-0 rounded-4 h-100 shadow-sm hover-card">
                                        <img src="https://img.magnific.com/foto-gratis/primer-plano-carne-asada-salsa-verduras-patatas-fritas-plato-sobre-mesa_181624-35847.jpg?semt=ais_hybrid&w=740&q=80"
                                            class="card-img-top rounded-top-4"
                                            style="aspect-ratio: 4/3; object-fit: cover;"
                                            alt="Plato de papas con carne asada">

                                        <div class="card-body d-flex flex-column">
                                            <h4 class="card-title text-dashboard fw-bold mb-1">Bs. 3,50</h4>
                                            <h6 class="card-text fw-bold text-secondary mb-0">Papas con carne</h6>
                                        </div>

                                        <div class="card-footer bg-transparent border-0 pb-3 pt-0">
                                            <div class="d-flex justify-content-center">
                                                <div class="d-flex align-items-center justify-content-between p-1 w-75 bg-warning bg-opacity-75 rounded-pill shadow-sm">
                                                    <button class="btn btn-sm text-dark p-0 px-2 rounded-circle" aria-label="Disminuir cantidad">
                                                        <i class="bi bi-dash fs-5"></i>
                                                    </button>
                                                    <span class="fw-bold fs-6 my-1">1</span>
                                                    <button class="btn btn-sm text-dark p-0 px-2 rounded-circle" aria-label="Aumentar cantidad">
                                                        <i class="bi bi-plus fs-5"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>

</html>