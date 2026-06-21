<aside class="sidebar">
    <div class="collapse d-md-block" id="sidebarMenu">

        <div class="text-center mb-4 d-none d-md-block">
            <img src="img/principal.jpg" alt="Logo El Triunfo" class="sidebar-logo">
        </div>

        <nav class="nav flex-column">
            
            <a class="nav-link py-1 <?= $pagina == 'principal' ? 'active' : '' ?>" href="?pagina=principal">
                <i class="bi bi-house-door"></i> Inicio
            </a>
            <a class="nav-link py-1 <?= $pagina == 'pedidos' ? 'active' : '' ?>" href="?pagina=pedidos">
                <i class="bi bi-journal-check"></i> Pedidos
            </a>

            <span class="text-uppercase text-white-50 fw-bold small mt-2 mb-2 px-3" style="letter-spacing: 1px; font-size: 0.75rem;">
            Inventario
            </span>
            <a class="nav-link py-1 <?= $pagina == 'categorias' ? 'active' : '' ?>" href="?pagina=categorias">
                <i class="bi bi-tags"></i> Categorías
            </a>
            <a class="nav-link py-1 <?= $pagina == 'productos' ? 'active' : '' ?>" href="?pagina=productos">
                <i class="bi bi-egg-fried"></i> Productos
            </a>


            <span class="text-uppercase text-white-50 fw-bold small mt-2 mb-2 px-3" style="letter-spacing: 1px; font-size: 0.75rem;">
            Gestión
            </span>
            <a class="nav-link py-1 <?= $pagina == 'ventas' ? 'active' : '' ?>" href="?pagina=ventas">
                <i class="bi bi-graph-up-arrow"></i> Ventas
            </a>

            <a class="nav-link py-1 <?= $pagina == 'reporte' ? 'active' : '' ?>" href="?pagina=reporte">
                <i class="bi bi-file-earmark-bar-graph"></i> Reporte
            </a>
            
            <span class="text-uppercase text-white-50 fw-bold small mt-2 mb-2 px-3" style="letter-spacing: 1px; font-size: 0.75rem;">
            Finanzas
            </span>
            <a class="nav-link py-1 <?= $pagina == 'cuentas' ? 'active' : '' ?>" href="?pagina=cuentas">
                <i class="bi bi-cash-stack"></i> Cuentas
            </a>

            <span class="text-uppercase text-white-50 fw-bold small mt-2 mb-2 px-3" style="letter-spacing: 1px; font-size: 0.75rem;">
            Usuarios
            </span>
            <a class="nav-link py-1 <?= $pagina == 'clientes' ? 'active' : '' ?>" href="?pagina=clientes">
                <i class="bi bi-people"></i> Clientes
            </a>
            <a class="nav-link py-1 <?= $pagina == 'personal' ? 'active' : '' ?>" href="?pagina=personal">
                <i class="bi bi-person-badge"></i> Empleados
            </a>

            <a class="nav-link py-1 logout-link text-black-50" href="?pagina=login">
                <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
            </a>
        </nav>
    </div>
</aside>

<script src="js/sidebar.js"></script>