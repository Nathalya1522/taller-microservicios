class DashboardManager {
    constructor() {
        this._stats = {
            total:        document.getElementById('dash-total'),
            activas:      document.getElementById('dash-activas'),
            finalizadas:  document.getElementById('dash-finalizadas'),
            impedimentos: document.getElementById('dash-impedimentos'),
        };
        this._kanban = {
            nueva:       document.getElementById('kanban-nueva'),
            activa:      document.getElementById('kanban-activa'),
            finalizada:  document.getElementById('kanban-finalizada'),
            impedimento: document.getElementById('kanban-impedimento'),
        };
    }

    actualizar(historias, sprints) {
        this._actualizarStats(historias);
        this._actualizarKanban(historias, sprints);
    }

    _actualizarStats(historias) {
        this._animarNumero(this._stats.total,        historias.length);
        this._animarNumero(this._stats.activas,      historias.filter(h => h.estado === 'activa').length);
        this._animarNumero(this._stats.finalizadas,  historias.filter(h => h.estado === 'finalizada').length);
        this._animarNumero(this._stats.impedimentos, historias.filter(h => h.estado === 'impedimento').length);
    }

    _actualizarKanban(historias, sprints) {
        ['nueva', 'activa', 'finalizada', 'impedimento'].forEach(estado => {
            const col   = this._kanban[estado];
            const items = historias.filter(h => h.estado === estado);
            col.innerHTML = '';
            if (items.length === 0) {
                col.innerHTML = '<div class="kanban__empty">Sin historias</div>';
                return;
            }
            items.forEach(h => {
                const sprint = sprints.find(s => s.id === h.sprint_id);
                const card   = document.createElement('div');
                card.className = 'kanban__card';
                card.innerHTML = `
                    <div class="kanban__card-title">${this._escapeHtml(h.titulo)}</div>
                    <div class="kanban__card-meta">
                        <span>${this._escapeHtml(h.responsable)}</span>
                        <span class="kanban__card-pts">${h.puntos} pts</span>
                    </div>
                    ${sprint ? `<div style="font-size:0.7rem;color:var(--text-muted);margin-top:4px">${this._escapeHtml(sprint.nombre)}</div>` : ''}
                `;
                col.appendChild(card);
            });
        });
    }

    _animarNumero(el, target) {
        const start     = parseInt(el.textContent) || 0;
        const duration  = 500;
        const startTime = performance.now();
        const step = (now) => {
            const progress = Math.min((now - startTime) / duration, 1);
            el.textContent = Math.round(start + (target - start) * progress);
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    }

    _escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }
}