class ReportesManager {
    constructor() {
        this._historias = [];
        this._sprints   = [];
        this._container = document.getElementById('reporte-container');
        this._filterSelect = document.getElementById('filter-sprint-reporte');
        this._bindEvents();
    }

    _bindEvents() {
        document.getElementById('btn-generar-reporte')
            .addEventListener('click', () => this.generar());
    }

    actualizarDatos(historias, sprints) {
        this._historias = historias;
        this._sprints   = sprints;
        this._poblarSelect();
    }

    _poblarSelect() {
        const valorActual = this._filterSelect.value;
        this._filterSelect.innerHTML = '<option value="">Todos los sprints</option>';
        this._sprints.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.nombre;
            this._filterSelect.appendChild(opt);
        });
        if (valorActual) this._filterSelect.value = valorActual;
    }

    generar() {
        const filtroId  = this._filterSelect.value;
        const historias = filtroId
            ? this._historias.filter(h => String(h.sprint_id) === String(filtroId))
            : this._historias;

        this._container.innerHTML = '';

        if (historias.length === 0) {
            this._container.innerHTML = `<div class="reporte-empty">No hay historias para el filtro seleccionado.</div>`;
            return;
        }

        this._container.appendChild(this._crearResumenGeneral(historias, filtroId));
        this._container.appendChild(this._crearResumenPorResponsable(historias));

        if (filtroId) {
            const sprint = this._sprints.find(s => String(s.id) === String(filtroId));
            if (sprint) this._container.appendChild(this._crearDetalleHistorias(historias, sprint));
        }
    }

    _crearResumenGeneral(historias, filtroId) {
        const total        = historias.length;
        const nuevas       = historias.filter(h => h.estado === 'nueva').length;
        const activas      = historias.filter(h => h.estado === 'activa').length;
        const finalizadas  = historias.filter(h => h.estado === 'finalizada').length;
        const impedimentos = historias.filter(h => h.estado === 'impedimento').length;
        const totalPuntos  = historias.reduce((a, h) => a + (parseInt(h.puntos) || 0), 0);
        const puntosFinalizados = historias
            .filter(h => h.estado === 'finalizada')
            .reduce((a, h) => a + (parseInt(h.puntos) || 0), 0);
        const pct    = total > 0 ? Math.round((finalizadas / total) * 100) : 0;
        const sprint = filtroId ? this._sprints.find(s => String(s.id) === String(filtroId)) : null;
        const titulo = sprint ? `Resumen: ${sprint.nombre}` : 'Resumen General';

        const card = document.createElement('div');
        card.className = 'reporte-card';
        card.innerHTML = `
            <div class="reporte-card__title">${this._escapeHtml(titulo)}</div>
            <div class="reporte-resumen">
                <div class="reporte-stat">
                    <span class="reporte-stat__num">${total}</span>
                    <span class="reporte-stat__lbl">Total</span>
                </div>
                <div class="reporte-stat reporte-stat--blue">
                    <span class="reporte-stat__num">${nuevas}</span>
                    <span class="reporte-stat__lbl">Nuevas</span>
                </div>
                <div class="reporte-stat reporte-stat--amber">
                    <span class="reporte-stat__num">${activas}</span>
                    <span class="reporte-stat__lbl">Activas</span>
                </div>
                <div class="reporte-stat reporte-stat--green">
                    <span class="reporte-stat__num">${finalizadas}</span>
                    <span class="reporte-stat__lbl">Finalizadas</span>
                </div>
                <div class="reporte-stat reporte-stat--red">
                    <span class="reporte-stat__num">${impedimentos}</span>
                    <span class="reporte-stat__lbl">Impedimentos</span>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:16px;margin-top:8px">
                <div style="flex:1">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:0.78rem;color:var(--text-secondary)">
                        <span>Progreso general</span>
                        <span style="font-family:'DM Mono',monospace;color:var(--green)">${pct}%</span>
                    </div>
                    <div class="progress-bar" style="height:10px">
                        <div class="progress-bar__fill" style="width:${pct}%"></div>
                    </div>
                </div>
                <div style="text-align:right">
                    <span style="font-family:'DM Mono',monospace;font-size:1.2rem;color:var(--accent)">${puntosFinalizados}</span>
                    <span style="color:var(--text-muted);font-size:0.75rem"> / ${totalPuntos} pts completados</span>
                </div>
            </div>
        `;
        return card;
    }

    _crearResumenPorResponsable(historias) {
        const mapa = new Map();
        historias.forEach(h => {
            const resp = h.responsable || 'Sin asignar';
            if (!mapa.has(resp)) mapa.set(resp, { total:0, nueva:0, activa:0, finalizada:0, impedimento:0, puntos:0 });
            const s = mapa.get(resp);
            s.total++;
            s[h.estado]++;
            s.puntos += parseInt(h.puntos) || 0;
        });

        let rows = '';
        mapa.forEach((s, nombre) => {
            const pct = s.total > 0 ? Math.round((s.finalizada / s.total) * 100) : 0;
            rows += `
                <tr>
                    <td style="font-weight:600">${this._escapeHtml(nombre)}</td>
                    <td style="text-align:center;font-family:'DM Mono',monospace">${s.total}</td>
                    <td style="text-align:center"><span class="badge badge--activa">${s.activa}</span></td>
                    <td style="text-align:center"><span class="badge badge--finalizada">${s.finalizada}</span></td>
                    <td style="text-align:center"><span class="badge badge--impedimento">${s.impedimento}</span></td>
                    <td style="font-family:'DM Mono',monospace;text-align:center;color:var(--accent)">${s.puntos}</td>
                    <td style="min-width:100px">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="progress-bar" style="flex:1">
                                <div class="progress-bar__fill" style="width:${pct}%"></div>
                            </div>
                            <span style="font-size:0.73rem;color:var(--green);font-family:'DM Mono',monospace">${pct}%</span>
                        </div>
                    </td>
                </tr>`;
        });

        const card = document.createElement('div');
        card.className = 'reporte-card';
        card.innerHTML = `
            <div class="reporte-card__title">Resumen por Responsable</div>
            <table class="responsable-table">
                <thead>
                    <tr>
                        <th>Responsable</th>
                        <th style="text-align:center">Total</th>
                        <th style="text-align:center">Activas</th>
                        <th style="text-align:center">Finalizadas</th>
                        <th style="text-align:center">Impedimentos</th>
                        <th style="text-align:center">Puntos</th>
                        <th>Progreso</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>`;
        return card;
    }

    _crearDetalleHistorias(historias, sprint) {
        const rows = historias.map(h => `
            <tr>
                <td style="font-weight:600">${this._escapeHtml(h.titulo)}</td>
                <td>${this._escapeHtml(h.responsable)}</td>
                <td><span class="badge badge--${h.estado}">${h.estado}</span></td>
                <td style="font-family:'DM Mono',monospace;color:var(--accent)">${h.puntos}</td>
                <td style="font-size:0.78rem;color:var(--text-muted)">${this._formatFecha(h.fecha_creacion)}</td>
                <td style="font-size:0.78rem;color:${h.fecha_finalizacion ? 'var(--green)' : 'var(--text-muted)'}">
                    ${this._formatFecha(h.fecha_finalizacion) || '—'}
                </td>
            </tr>`).join('');

        const card = document.createElement('div');
        card.className = 'reporte-card';
        card.innerHTML = `
            <div class="reporte-card__title">Detalle — ${this._escapeHtml(sprint.nombre)}</div>
            <div style="font-size:0.8rem;color:var(--text-secondary);margin-bottom:14px">
                ${this._formatFecha(sprint.fecha_inicio)} → ${this._formatFecha(sprint.fecha_fin)}
            </div>
            <table class="responsable-table">
                <thead>
                    <tr>
                        <th>Título</th><th>Responsable</th><th>Estado</th>
                        <th>Puntos</th><th>Creación</th><th>Finalización</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>`;
        return card;
    }

    _formatFecha(fecha) {
        if (!fecha) return null;
        const [y, m, d] = fecha.split('-');
        return `${d}/${m}/${y}`;
    }

    _escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }
}