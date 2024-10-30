
(function() {
    const savedTheme = localStorage.getItem('theme');
    const isDarkMode = savedTheme === 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme || 'light');
    const themeSwitch = document.getElementById('toggle-theme-switch');
    if (themeSwitch) themeSwitch.checked = isDarkMode;

    const themeLabel = document.getElementById('theme-label');
    if (themeLabel) themeLabel.textContent = isDarkMode ? 'Tema Claro' : 'Tema Oscuro';
})();

document.addEventListener('DOMContentLoaded', function() {
    const themeSwitch = document.getElementById('toggle-theme-switch');
    const themeLabel = document.getElementById('theme-label');

    if (themeSwitch) {
        themeSwitch.addEventListener('change', () => {
            const newTheme = themeSwitch.checked ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
    
        });
    }
});
