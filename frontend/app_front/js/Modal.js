class ModalManager {
    constructor() {
        this._toastTimer = null;
    }

    show(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.classList.remove('close');
    }

    hide(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.classList.add('close');
    }

    toast(msg, type = 'success') {
        const el = document.getElementById('toast');
        const msgEl = document.getElementById('toast-msg');
        msgEl.textContent = msg;
        el.classList.remove('close', 'toast--error');
        if (type === 'error') el.classList.add('toast--error');
        if (this._toastTimer) clearTimeout(this._toastTimer);
        this._toastTimer = setTimeout(() => el.classList.add('close'), 3000);
    }

    confirm(msg, onConfirm) {
        const textEl = document.getElementById('confirm-text');
        const yesBtn = document.getElementById('btn-confirm-yes');
        const noBtn  = document.getElementById('btn-confirm-no');
        textEl.textContent = msg;
        this.show('modal-confirm');

        const newYes = yesBtn.cloneNode(true);
        const newNo  = noBtn.cloneNode(true);
        yesBtn.parentNode.replaceChild(newYes, yesBtn);
        noBtn.parentNode.replaceChild(newNo, noBtn);

        newYes.addEventListener('click', () => {
            this.hide('modal-confirm');
            onConfirm();
        });
        newNo.addEventListener('click', () => this.hide('modal-confirm'));
    }
}

const modalManager = new ModalManager();

document.querySelectorAll('.modal__backdrop').forEach(backdrop => {
    backdrop.addEventListener('click', () => {
        const modal = backdrop.closest('.modal');
        if (modal) modal.classList.add('close');
    });
});