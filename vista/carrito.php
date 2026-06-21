<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>
<?php require_once("comunes/menu_usuario.php"); ?>

<body class="bg-light">

    <main class="container py-5">
        <h2 class="mb-4 fw-bold"><i class="bi bi-cart-fill me-2"></i>Tu Carrito</h2>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="row g-3">
                    
                    <div class="col-12 col-md-6">
                        <div class="card border-0 rounded-4 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-3">
                                <div class="me-3">
                                <img src="https://img.magnific.com/foto-gratis/primer-plano-carne-asada-salsa-verduras-patatas-fritas-plato-sobre-mesa_181624-35847.jpg?semt=ais_hybrid&w=740&q=80" alt="Producto" class="img-fluid rounded-3 shadow-sm" style="height: 90px; width: 90px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">Carne con Papas</h6>
                                    <div class="small fw-bold mb-2 text-success">
                                        <i class="bi bi-star-fill text-warning"></i> $4.5
                                    </div>
                                    <button class="btn btn-sm btn-light text-danger border btn-eliminar px-2 py-1 shadow-sm">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </div>
                                <div class="d-flex flex-column align-items-end ms-2 h-100 justify-content-center">
                                    <div class="d-flex flex-column align-items-center qty-controls p-1">
                                        <button class="btn btn-sm text-secondary p-0 px-2"><i class="bi bi-dash"></i></button>
                                        <span class="fw-bold small my-1">1</span>
                                        <button class="btn btn-sm text-orange p-0 px-2"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card border-0 rounded-4 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-3">
                                <div class="me-3">
                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQewdvz0HQg1CNrO1U2TTbBfyzw5d9CIINWpw&s" alt="Producto" class="img-fluid rounded-3 shadow-sm" style="height: 90px; width: 90px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">Hamburguesa Clásica</h6>
                                    <div class="small fw-bold mb-2 text-success">
                                        <i class="bi bi-star-fill text-warning"></i> $4.8
                                    </div>
                                    <button class="btn btn-sm btn-light text-danger border btn-eliminar px-2 py-1 shadow-sm">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </div>
                                <div class="d-flex flex-column align-items-end ms-2 h-100 justify-content-center">
                                    <div class="d-flex flex-column align-items-center qty-controls p-1">
                                        <button class="btn btn-sm text-secondary p-0 px-2"><i class="bi bi-dash"></i></button>
                                        <span class="fw-bold small my-1">2</span>
                                        <button class="btn btn-sm text-orange p-0 px-2"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card border-0 rounded-4 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-3">
                                <div class="me-3">
                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSkgThIr0g6VzE5AKX8QHOXO89FLYHWB1n3cQ&s" alt="Producto" class="img-fluid rounded-3 shadow-sm" style="height: 90px; width: 90px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">Empanadas Criollas</h6>
                                    <div class="small fw-bold mb-2 text-success">
                                        <i class="bi bi-star-fill text-warning"></i> $4.5
                                    </div>
                                    <button class="btn btn-sm btn-light text-danger border btn-eliminar px-2 py-1 shadow-sm">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </div>
                                <div class="d-flex flex-column align-items-end ms-2 h-100 justify-content-center">
                                    <div class="d-flex flex-column align-items-center qty-controls p-1">
                                        <button class="btn btn-sm text-secondary p-0 px-2"><i class="bi bi-dash"></i></button>
                                        <span class="fw-bold small my-1">1</span>
                                        <button class="btn btn-sm text-orange p-0 px-2"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card border-0 rounded-4 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-3">
                                <div class="me-3">
                                    <img src="ruta_de_tu_imagen_4.jpg" alt="Producto" class="img-fluid rounded-3 shadow-sm" style="height: 90px; width: 90px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">Papas Fritas</h6>
                                    <div class="small fw-bold mb-2 text-success">
                                        <i class="bi bi-star-fill text-warning"></i> $4.9
                                    </div>
                                    <button class="btn btn-sm btn-light text-danger border btn-eliminar px-2 py-1 shadow-sm">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </div>
                                <div class="d-flex flex-column align-items-end ms-2 h-100 justify-content-center">
                                    <div class="d-flex flex-column align-items-center qty-controls p-1">
                                        <button class="btn btn-sm text-secondary p-0 px-2"><i class="bi bi-dash"></i></button>
                                        <span class="fw-bold small my-1">1</span>
                                        <button class="btn btn-sm text-orange p-0 px-2"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card border-0 rounded-4 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-3">
                                <div class="me-3">
                                    <img src="ruta_de_tu_imagen_5.jpg" alt="Producto" class="img-fluid rounded-3 shadow-sm" style="height: 90px; width: 90px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">Jugo Natural</h6>
                                    <div class="small fw-bold mb-2 text-success">
                                        <i class="bi bi-star-fill text-warning"></i> $4.6
                                    </div>
                                    <button class="btn btn-sm btn-light text-danger border btn-eliminar px-2 py-1 shadow-sm">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </div>
                                <div class="d-flex flex-column align-items-end ms-2 h-100 justify-content-center">
                                    <div class="d-flex flex-column align-items-center qty-controls p-1">
                                        <button class="btn btn-sm text-secondary p-0 px-2"><i class="bi bi-dash"></i></button>
                                        <span class="fw-bold small my-1">2</span>
                                        <button class="btn btn-sm text-orange p-0 px-2"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card border-0 rounded-4 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center p-3">
                                <div class="me-3">
                                    <img src="ruta_de_tu_imagen_6.jpg" alt="Producto" class="img-fluid rounded-3 shadow-sm" style="height: 90px; width: 90px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">Ración de Tequeños</h6>
                                    <div class="small fw-bold mb-2 text-success">
                                        <i class="bi bi-star-fill text-warning"></i> $5.0
                                    </div>
                                    <button class="btn btn-sm btn-light text-danger border btn-eliminar px-2 py-1 shadow-sm">
                                        <i class="bi bi-trash me-1"></i>Eliminar
                                    </button>
                                </div>
                                <div class="d-flex flex-column align-items-end ms-2 h-100 justify-content-center">
                                    <div class="d-flex flex-column align-items-center qty-controls p-1">
                                        <button class="btn btn-sm text-secondary p-0 px-2"><i class="bi bi-dash"></i></button>
                                        <span class="fw-bold small my-1">1</span>
                                        <button class="btn btn-sm text-orange p-0 px-2"><i class="bi bi-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 rounded-4 shadow-sm p-4 sticky-lg-top" style="top: 20px;">
                    <h5 class="fw-bold mb-4">Resumen del Pedido</h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fw-semibold">Subtotal</span>
                        <span class="fw-bold text-dark">$129.69</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-4">
                        <span class="text-muted fw-semibold">Costo de Envío (Delivery)</span>
                        <span class="fw-bold text-dark">$0.00</span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-5 text-brown">Costo Total</span>
                        <span class="fw-bold fs-5 text-dark">$129.69</span>
                    </div>
                    
                    <button class="btn btn-checkout btn-lg w-100 rounded-3 fw-bold shadow-sm">
                        Procesar Pago
                    </button>
                </div>
            </div>

        </div>
    </main>

</body>
</html>