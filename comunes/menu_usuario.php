<nav class="navbar navbar-expand-lg navbar-dark py-3 px-3 px-md-5 mb-4 shadow-sm" style="background-color: #FF8C00;">
    <div class="container-fluid p-0">

        <!-- Logo y nombre -->
        <a class="navbar-brand d-flex align-items-center gap-2" href="?pagina=catalogo">
            <img src="img/principal.jpg" alt="Logo El Triunfo" width="45" height="45" class="rounded-circle object-fit-cover bg-white">
            <span class="fw-bold text-white fs-4">Cafetería El Triunfo</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Elementos del menú -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-3">
                <li class="nav-item">
                    <a class="nav-link active text-white fw-semibold fs-5" aria-current="page" href="?pagina=catalogo">
                        <i class="bi bi-house-door-fill me-2"></i>Inicio
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                
                <a href="#" class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm text-success" style="width: 42px; height: 42px;" aria-label="WhatsApp">
                    <i class="bi bi-whatsapp fs-5"></i>
                </a>

                <a href="#" data-bs-toggle="modal" data-bs-target="#carritoModal" class="btn btn-light rounded-circle position-relative p-0 d-flex align-items-center justify-content-center shadow-sm text-dark" style="width: 42px; height: 42px;" aria-label="Ver carrito de compras">
                    <i class="bi bi-cart-fill fs-5"></i>
                    <span id="contador-carrito" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.65rem;">
                        0
                    </span>
                </a>

            </div>

        </div>

    </div>
</nav>