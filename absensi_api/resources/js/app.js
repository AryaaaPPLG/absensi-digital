import './bootstrap';

const iconMap = {
    success: 'fa-circle-check',
    error: 'fa-circle-xmark',
    warning: 'fa-triangle-exclamation',
    info: 'fa-circle-info',
};

const paletteMap = {
    success: 'app-alert-success',
    error: 'app-alert-error',
    warning: 'app-alert-warning',
    info: 'app-alert-info',
};

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

function closeAlert(modal) {
    modal.classList.add('is-leaving');
    window.setTimeout(() => modal.remove(), 180);
}

function fireAppAlert(options = {}) {
    const previous = document.querySelector('.app-alert-backdrop');
    if (previous) previous.remove();

    const type = options.icon || 'info';
    const title = options.title || '';
    const body = options.html ?? (options.text ? `<p>${escapeHtml(options.text)}</p>` : '');
    const showConfirm = options.showConfirmButton !== false;
    const confirmText = options.confirmButtonText || 'OK';
    const timer = Number(options.timer || 0);

    const backdrop = document.createElement('div');
    backdrop.className = `app-alert-backdrop ${paletteMap[type] || paletteMap.info}`;
    backdrop.innerHTML = `
        <section class="app-alert" role="dialog" aria-modal="true" aria-live="polite">
            <div class="app-alert-icon">
                <i class="fas ${iconMap[type] || iconMap.info}"></i>
            </div>
            ${title ? `<h2>${escapeHtml(title)}</h2>` : ''}
            <div class="app-alert-body">${body}</div>
            ${showConfirm ? `<button type="button" class="app-alert-confirm">${escapeHtml(confirmText)}</button>` : ''}
            ${timer && options.timerProgressBar ? '<div class="app-alert-progress"></div>' : ''}
        </section>
    `;

    document.body.appendChild(backdrop);

    const alert = backdrop.querySelector('.app-alert');
    const confirmButton = backdrop.querySelector('.app-alert-confirm');
    const progress = backdrop.querySelector('.app-alert-progress');

    requestAnimationFrame(() => {
        backdrop.classList.add('is-visible');
        if (progress && timer) progress.style.animationDuration = `${timer}ms`;
    });

    const removeAlert = () => {
        document.removeEventListener('keydown', onKeydown);
        closeAlert(backdrop);
    };

    const onKeydown = (event) => {
        if (event.key === 'Escape' && showConfirm) removeAlert();
    };
    document.addEventListener('keydown', onKeydown);

    if (confirmButton) {
        confirmButton.focus({ preventScroll: true });
        confirmButton.addEventListener('click', removeAlert);
    }

    backdrop.addEventListener('click', (event) => {
        if (!alert.contains(event.target) && showConfirm) removeAlert();
    });

    if (timer) {
        window.setTimeout(() => {
            if (document.body.contains(backdrop)) removeAlert();
        }, timer);
    }

    return Promise.resolve({ isConfirmed: true });
}

window.AppAlert = {
    fire: fireAppAlert,
    mixin(defaults = {}) {
        return {
            fire(options = {}) {
                return fireAppAlert({ ...defaults, ...options });
            },
        };
    },
};

window.Swal = window.AppAlert;
