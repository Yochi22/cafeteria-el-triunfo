<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body>
    <div class="d-flex min-vh-100 position-relative bg-light">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">

            <!-- Encabezado principal -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
                <div class="d-flex align-items-center gap-3">
                    <!-- Menú hamburguesa -->
                    <button class="btn btn-outline-dark d-md-none border-0 p-1"
                        id="btn-toggle-sidebar"
                        data-bs-toggle="collapse"
                        data-bs-target="#sidebarMenu"
                        aria-controls="sidebarMenu"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                        <i class="bi bi-list" style="font-size: 2rem;"></i>
                    </button>

                    <!-- Título -->
                    <div>
                        <h2 class="text-dashboard mb-1"><i class="bi bi-journal-check text-primary"></i> Gestión de Pedidos</h2>
                        <p class="text-muted mb-0">Revisa, edita y administra los pedidos de tus clientes.</p>
                    </div>
                </div>

                <!-- Botón Crear -->
                <div>
                    <button type="button" id="incluir" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modal_cliente">
                        <i class="bi bi-plus-circle fs-5"></i>
                        <span>Crear Pedido</span>
                    </button>
                </div>
            </div>

            <hr class="text-secondary mb-4">

            <!-- Barra de búsqueda y filtros -->
            <div class="row mb-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="input-group bg-white border rounded-3 align-items-center shadow-sm">
                        <span class="input-group-text bg-transparent border-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 py-2 shadow-none" placeholder="Buscar por cliente o N° de orden...">
                    </div>
                </div>
            </div>

            <!-- Lista de Pedidos -->
            <div class="d-flex flex-column gap-3">

                <!-- Tarjeta de Pedido 1 -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <!-- Información del pedido -->
                            <div class="col-12 col-xl-7 mb-3 mb-xl-0">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <h5 class="card-title text-dashboard fw-bold mb-0">Orden #001</h5>
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">En Preparación</span>
                                    <small class="text-muted"><i class="bi bi-clock"></i> Hace 15 min</small>
                                </div>
                                <hr class="text-light-subtle my-2">
                                <div class="row mt-3">
                                    <div class="col-sm-5 mb-2 mb-sm-0">
                                        <p class="mb-1 text-muted small">Cliente</p>
                                        <p class="fw-semibold mb-0"><i class="bi bi-person-fill text-secondary"></i> Carlos Mendoza</p>
                                    </div>
                                    <div class="col-sm-7">
                                        <p class="mb-1 text-muted small">Detalle del pedido</p>
                                        <p class="mb-0 text-truncate" title="2x Hamburguesa Clásica, 1x Ración de Papas Fritas, 2x Refresco Cola">
                                            2x Hamburguesa Clásica, 1x Ración de Papas...
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones y Precio -->
                            <div class="col-12 col-xl-5 d-flex flex-column flex-sm-row justify-content-xl-end justify-content-between align-items-center gap-3 border-start-xl ps-xl-4">
                                <div class="text-center text-sm-start text-xl-end mb-3 mb-sm-0">
                                    <p class="text-muted small mb-0">Total</p>
                                    <h4 class="text-success fw-bold mb-0">$24.50</h4>
                                </div>

                                <div class="d-flex gap-2 flex-wrap justify-content-center">
                                    <button type="button" class="btn btn-outline-info btn-sm d-flex align-items-center gap-2" title="Ver detalle">
                                        <i class="bi bi-eye-fill"></i> <span class="d-none d-md-inline">Ver</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2" title="Editar">
                                        <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Editar</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm d-flex align-items-center gap-2" title="Finalizar">
                                        <i class="bi bi-check-circle-fill"></i> <span class="d-none d-md-inline">Finalizar</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2" title="Eliminar">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de Pedido 2 -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <!-- Información del pedido -->
                            <div class="col-12 col-xl-7 mb-3 mb-xl-0">
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <h5 class="card-title text-dashboard fw-bold mb-0">Orden #002</h5>
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">Pendiente</span>
                                    <small class="text-muted"><i class="bi bi-clock"></i> Hace 2 min</small>
                                </div>
                                <hr class="text-light-subtle my-2">
                                <div class="row mt-3">
                                    <div class="col-sm-5 mb-2 mb-sm-0">
                                        <p class="mb-1 text-muted small">Cliente</p>
                                        <p class="fw-semibold mb-0"><i class="bi bi-person-fill text-secondary"></i> Ana López</p>
                                    </div>
                                    <div class="col-sm-7">
                                        <p class="mb-1 text-muted small">Detalle del pedido</p>
                                        <p class="mb-0 text-truncate" title="1x Pizza Margarita Familiar, 1x Cerveza Artesanal">
                                            1x Pizza Margarita Familiar, 1x Cerveza Ar...
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Acciones y Precio -->
                            <div class="col-12 col-xl-5 d-flex flex-column flex-sm-row justify-content-xl-end justify-content-between align-items-center gap-3 border-start-xl ps-xl-4">
                                <div class="text-center text-sm-start text-xl-end mb-3 mb-sm-0">
                                    <p class="text-muted small mb-0">Total</p>
                                    <h4 class="text-success fw-bold mb-0">$18.00</h4>
                                </div>

                                <div class="d-flex gap-2 flex-wrap justify-content-center">
                                    <button type="button" class="btn btn-outline-info btn-sm d-flex align-items-center gap-2" title="Ver detalle">
                                        <i class="bi bi-eye-fill"></i> <span class="d-none d-md-inline">Ver</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2" title="Editar">
                                        <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Editar</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm d-flex align-items-center gap-2" title="Finalizar">
                                        <i class="bi bi-check-circle-fill"></i> <span class="d-none d-md-inline">Finalizar</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2" title="Eliminar">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>

</html>