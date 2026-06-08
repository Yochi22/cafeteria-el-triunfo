<div class="container-fluid px-0">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <img src="img/principal.jpg" alt="Logo" style="width:45px; margin-right:10px; border-radius: 50%;">
        <label class="navbar-brand">Cafetería El Triunfo</label>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#menuPrincipal" aria-controls="menuPrincipal"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuRegistros" data-bs-toggle="dropdown" aria-expanded="false">
                        Registros
                    </a>
                    <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="menuRegistros">
                        <a class="dropdown-item" href="?pagina=clientes">Clientes</a>
                        <a class="dropdown-item" href="?pagina=productos">Productos</a>
                        <a class="dropdown-item" href="?pagina=categorias">Categorías</a>
                        <a class="dropdown-item" href="?pagina=personal">Personal</a>
                        <a class="dropdown-item" href="?pagina=metodos_pago">Métodos de Pago</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="menuOperaciones" data-bs-toggle="dropdown" aria-expanded="false">
                        Operaciones
                    </a>
                    <div class="dropdown-menu dropdown-menu-dark" aria-labelledby="menuOperaciones">
                        <a class="dropdown-item" href="?pagina=pedidos">Pedidos</a>
                        <a class="dropdown-item" href="?pagina=ventas">Ventas</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>