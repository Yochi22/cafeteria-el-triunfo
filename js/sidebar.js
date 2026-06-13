document.addEventListener('DOMContentLoaded', function () {
    const sidebarMenu = document.getElementById('sidebarMenu');
    const btnToggle = document.getElementById('btn-toggle-sidebar');

    document.addEventListener('click', function (event) {
        if (sidebarMenu && btnToggle) {
            if (sidebarMenu.classList.contains('show')) {
                if (!sidebarMenu.contains(event.target) && !btnToggle.contains(event.target)) {
                    ocultarMenuMovil();
                }
            }
        }
    });

    if (sidebarMenu) {
        const enlacesNav = sidebarMenu.querySelectorAll('.nav-link');
        enlacesNav.forEach(enlace => {
            enlace.addEventListener('click', function () {
                if (sidebarMenu.classList.contains('show')) {
                    ocultarMenuMovil();
                }
            });
        });
    }

    function ocultarMenuMovil() {
        let bsCollapse = bootstrap.Collapse.getInstance(sidebarMenu);

        if (!bsCollapse) {
            bsCollapse = new bootstrap.Collapse(sidebarMenu, { toggle: false });
        }

        bsCollapse.hide();
    }
});