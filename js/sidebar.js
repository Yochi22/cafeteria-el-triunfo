$(function () {
    const $sidebarMenu = $('#sidebarMenu');
    const $btnToggle = $('#btn-toggle-sidebar');

    $(document).on('click', function (event) {
        if ($sidebarMenu.length && $btnToggle.length) {
            if ($sidebarMenu.hasClass('show')) {
                if (!$sidebarMenu[0].contains(event.target) && !$btnToggle[0].contains(event.target)) {
                    ocultarMenuMovil();
                }
            }
        }
    });

    if ($sidebarMenu.length) {
        $sidebarMenu.find('.nav-link').on('click', function () {
            if ($sidebarMenu.hasClass('show')) {
                ocultarMenuMovil();
            }
        });
    }

    function ocultarMenuMovil() {
        let bsCollapse = bootstrap.Collapse.getInstance($sidebarMenu[0]);

        if (!bsCollapse) {
            bsCollapse = new bootstrap.Collapse($sidebarMenu[0], { toggle: false });
        }

        bsCollapse.hide();
    }
});