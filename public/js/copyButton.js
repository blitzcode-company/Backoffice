document.addEventListener('DOMContentLoaded', function() {
    const copyButtons = document.querySelectorAll('.copy-btn');
    copyButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const data = this.getAttribute('data-copy');
            const icon = this.querySelector('.copy-icon');
            const status = this.nextElementSibling;
            const tempInput = document.createElement('input');
            tempInput.value = data;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            status.textContent = '¡Copiado!';
            icon.classList.remove('fa-copy');
            icon.classList.add('fa-check');
            setTimeout(() => {
                status.textContent = 'Copiar';
                icon.classList.remove('fa-check');
                icon.classList.add('fa-copy');
            }, 2000);
        });
    });
});