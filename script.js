const themeSwitch = document.querySelector('.theme-switch');
const toast = document.querySelector('.toast');
const confirmButtons = document.querySelectorAll('[data-confirm]');

themeSwitch?.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    const isDark = document.body.classList.contains('dark-mode');
    themeSwitch.textContent = isDark ? 'Light Theme' : 'Dark Theme';
});

function showToast(message) {
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add('show');
    window.clearTimeout(showToast.timeoutId);
    showToast.timeoutId = window.setTimeout(() => {
        toast.classList.remove('show');
    }, 2400);
}

confirmButtons.forEach((button) => {
    button.addEventListener('click', () => {
        const message = button.dataset.confirm;
        const url = button.dataset.url;
        if (!message || !url) return;
        if (confirm(message)) {
            window.location.href = url;
        }
    });
});

const actionButtons = document.querySelectorAll('.secondary-btn, .primary-btn, .ghost-btn');
actionButtons.forEach((button) => {
    button.addEventListener('click', () => {
        if (button.matches('.primary-btn') || button.matches('.secondary-btn')) {
            showToast(`${button.textContent.trim()} selected`);
        }
    });
});
