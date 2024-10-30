document.addEventListener('DOMContentLoaded', function () {
    const accordionButton = document.querySelector('.accordion-button');
    const icon = document.getElementById('toggle-icon');

    accordionButton.addEventListener('click', function () {
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    });
});
