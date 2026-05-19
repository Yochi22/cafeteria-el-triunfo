<aside class="sidebar">
    <div class="text-center mb-4">
        <img src="img/principal.jpg" alt="Logo El Triunfo" class="sidebar-logo">
    </div>
    
    <nav class="nav flex-column">
        <a class="nav-link <?= $pagina == 'principal' ? 'active' : '' ?>" href="?pagina=principal">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        <a class="nav-link <?= $pagina == 'clientes' ? 'active' : '' ?>" href="?pagina=clientes">
            <i class="bi bi-people"></i> Clientes
        </a>
        <a class="nav-link <?= $pagina == 'pedidos' ? 'active' : '' ?>" href="?pagina=pedidos">
            <i class="bi bi-journal-check"></i> Pedidos
        </a>
        <a class="nav-link <?= $pagina == 'ventas' ? 'active' : '' ?>" href="?pagina=ventas">
            <i class="bi bi-graph-up-arrow"></i> Ventas
        </a>
        <a class="nav-link <?= $pagina == 'metodos_pago' ? 'active' : '' ?>" href="?pagina=metodos_pago">
            <i class="bi bi-cash-stack"></i> Métodos de pago
        </a>
        <a class="nav-link <?= $pagina == 'productos' ? 'active' : '' ?>" href="?pagina=productos">
            <i class="bi bi-egg-fried"></i> Productos
        </a>
        <a class="nav-link <?= $pagina == 'categorias' ? 'active' : '' ?>" href="?pagina=categorias">
            <i class="bi bi-tags"></i> Categorías
        </a>
        <a class="nav-link <?= $pagina == 'personal' ? 'active' : '' ?>" href="?pagina=personal">
            <i class="bi bi-person-badge"></i> Empleados
        </a>
        <a class="nav-link <?= $pagina == 'reporte' ? 'active' : '' ?>" href="?pagina=reporte">
            <i class="bi bi-file-earmark-bar-graph"></i> Reporte
        </a>
        
        <a class="nav-link logout-link mt-5" href="?pagina=logout">
            <i class="bi bi-box-arrow-left"></i> Cerar sesión
        </a>
    </nav>
</aside>
