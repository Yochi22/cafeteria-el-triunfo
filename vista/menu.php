<!DOCTYPE html>
<html lang="en">
<?php require_once("comunes/encabezado.php"); ?>
<?php require_once("comunes/menu_usuario.php"); ?>

<body>
    <main>
        <div class="container-fluid p-0 px-md-5 mb-5">

            <div class="row g-0">
                <div class="col-12">
                    <div class="card mb-3 border border-0 rounded-0">
                        <img src="img/imagen.jpeg" class="card-img-top" alt="...">
                        <div class="card-body px-3 px-md-0"> 
                            <div class="row row-cols-1 row-cols-md-2 d-flex align-items-center g-3">
                                <div class="col d-flex align-items-center mt-3">
                                    <h1 class="card-title text-dashboard mb-0 fs-3 fs-md-1"><i class="bi bi-cup-hot-fill"></i> Catalogo</h1>
                                    <button type="button" class="btn btn-invisible btn-lg fw-bolder text-start text-md-center py-0">
                                        <i class="bi bi-record-circle text-success"></i> Abierto
                                    </button>
                                </div>
                                <div class="col col-md-4 ms-auto d-flex align-items-center mt-3">
                                    <div class="input-group bg-white border rounded-3 px-2 py-2 align-items-center w-100">
                                        <button class="btn p-0 border-0 link-secondary" type="button" id="btnBuscar">
                                            <i class="bi bi-search fs-5"></i>
                                        </button>
                                        <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 py-0 py-2 shadow-none" placeholder="Buscar...">
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
                        <div class="nav nav-tabs fs-5 fs-md-4 flex-nowrap flex-md-wrap" id="nav-tab" role="tablist">
                            <button class="nav-link text-dashboard active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">destacado</button>
                            <button class="nav-link text-dashboard" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">menu del dia</button>
                            <button class="nav-link text-dashboard" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">postre</button>
                        </div>
                    </nav>
                    
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                            <h3 class="text-dashboard mb-4 mt-2" style="color: #FF8C00;">Destacado</h3>

                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 justify-content-center justify-content-md-start">
                                <div class="col">
                                    <div class="card border-0 rounded-4 h-100 shadow-sm">
                                        <img src="https://img.magnific.com/foto-gratis/primer-plano-carne-asada-salsa-verduras-patatas-fritas-plato-sobre-mesa_181624-35847.jpg?semt=ais_hybrid&w=740&q=80" class="card-img-top rounded-top-4" alt="papas con carne">
                                        <div class="card-body">
                                            <h4 class="card-title text-dashboard fw-bold">Bs. 3,50</h4>
                                            <h6 class="card-text fw-bold text-muted">Papas con Carne</h6>
                                        </div>
                                        <div class="card-footer bg-transparent border-0 pb-3">
                                            <div class="d-flex justify-content-center">
                                                <div class="d-flex align-items-center justify-content-between qty-controls p-1 w-75 bg-warning rounded-pill">
                                                    <button class="btn btn-sm text-secondary p-0 px-2"><i class="bi bi-dash"></i></button>
                                                    <span class="fw-bold small my-1">1</span>
                                                    <button class="btn btn-sm p-0 px-2"><i class="bi bi-plus"></i></button>
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