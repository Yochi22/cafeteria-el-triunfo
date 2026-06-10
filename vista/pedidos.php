<!DOCTYPE html>
<html lang="en">
    <?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex min-vh-100 position-relative">
        <?php require_once("comunes/sidebar.php"); ?>

        <main class="main-content w-100 p-4">

            <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                <!-- menu hamburguesa -->
                <button class="btn btn-outline-dark d-md-none me-3 border-0"
                    id="btn-toggle-sidebar"
                    data-bs-toggle="collapse"
                    data-bs-target="#sidebarMenu"
                    aria-controls="sidebarMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="bi bi-list" style="font-size: 2rem;"></i>
                </button>

                <!-- Encabezado de bienvenida -->
                <div>
                    <h2 class="text-dashboard"><i class="bi bi-journal-check"></i> Pedidos</h2>
                    <p class="text-muted">Bienvenido al apartado de Pedidos, aqui se muentran los pedidos de tus clientes!</p>
                </div>

                <div>
                    <button type="button" id="incluir" class="btn btn-crear" data-bs-toggle="modal" data-bs-target="#modal_cliente">
                        <i class="bi bi-plus-circle fs-5"></i>
                        <span class="d-none d-sm-inline">Crear Pedido</span>
                    </button>
                </div>

            </div>

            <div class="row">
                <div class="col">
                    <hr class="border border-secundary">
                </div>
            </div>

            <div class="col-12 d-flex mb-3">
                <!-- boton de busqueda -->    
                <div class="col-md-4 ms-3">
                    <div class="input-group bg-white border rounded-3 align-items-center">
                        <button class="btn p-0 border-0 link-secondary py-2 " type="button" id="btnBuscar">
                            <i class="bi bi-search fs-5 m-1 ms-2"></i>
                        </button>
                        <input type="text" id="valorBusqueda" name="valorBusqueda" class="form-control border-0 py-0 py-2 shadow-none" placeholder="Buscar Pedido">
                    </div>
                </div>

            </div>
            
            <div class="card d-flex mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="card-title text-dashboard">Orden N° 001</h5>
                            <hr class="border border-secundary">
                            <div class="row">
                                <div class="col">
                                <p class="card-text fw-bold">nombre del cliente</p>
                                <p class="card-text">$20.00</p>
                            </div>
                            <div class="col">
                                <p class="card-text fw-bold">hambuerguesa con papas, jaalxndfkbdmwbd v nwsnc</p>
                            </div>
                            </div>
                        </div>

                        <div class="col-6 d-flex justify-content-end gap-2 mt-5">
                            <button type="button" class="btn btn-outline-warning d-flex flex-column align-items-center p-2 ms-2">
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <span class="d-none d-sm-inline">Ver Pedido</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary d-flex flex-column align-items-center p-2 ms-2">
                                <i class="bi bi-pencil-square"></i> 
                                <span class="d-none d-sm-inline">Editar Pedido</span>
                            </button>
                            <button type="button" class="btn btn-outline-danger d-flex flex-column align-items-center p-2 ms-2">
                                <i class="bi bi-trash-fill"></i>
                                <span class="d-none d-sm-inline">Eliminar</span>
                            </button>
                            <button type="button" class="btn btn-outline-success d-flex flex-column align-items-center p-2 ms-2">
                                <i class="bi bi-check-circle-fill"></i> 
                                <span class="d-none d-sm-inline">Finalizar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card d-flex">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="card-title text-dashboard">Orden N° 001</h5>
                            <hr class="border border-secundary">
                            <div class="row">
                                <div class="col">
                                <p class="card-text fw-bold">nombre del cliente</p>
                                <p class="card-text">$20.00</p>
                            </div>
                            <div class="col">
                                <p class="card-text fw-bold">hambuerguesa con papas, jaalxndfkbdmwbd v nwsnc</p>
                            </div>
                            </div>
                        </div>

                        <div class="col-6 d-flex justify-content-end gap-2 mt-5">
                            <button type="button" class="btn btn-outline-warning d-flex flex-column align-items-center p-2 ms-2">
                                <i class="bi bi-file-earmark-text-fill"></i>
                                <span>Ver Pedido</span>
                            </button>
                            <button type="button" class="btn btn-outline-secondary d-flex flex-column align-items-center p-2 ms-2">
                                <i class="bi bi-pencil-square"></i> 
                                <span>Editar Pedido</span>
                            </button>
                            <button type="button" class="btn btn-outline-danger d-flex flex-column align-items-center p-2 ms-2">
                                <i class="bi bi-trash-fill"></i>
                                <span>Eliminar</span>
                            </button>
                            <button type="button" class="btn btn-outline-success d-flex flex-column align-items-center p-2 ms-2">
                                <i class="bi bi-check-circle-fill"></i> 
                                <span>Finalizar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>  


        </main>
    </div>
</body>
</html>