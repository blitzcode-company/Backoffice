document.addEventListener('DOMContentLoaded', function () {
    const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu > .dropdown-item');

    dropdownSubmenus.forEach(function (submenu) {
        submenu.addEventListener('click', function (event) {
            event.preventDefault();
            const parent = this.parentElement;
            parent.classList.toggle('active');
        });
    });
    document.addEventListener('click', function (event) {
        if (!event.target.closest('.dropdown-submenu')) {
            dropdownSubmenus.forEach(function (submenu) {
                submenu.parentElement.classList.remove('active');
            });
        }
    });
});