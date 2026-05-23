class HistoriasManager {
    constructor(apiBase) {
        this._apiBase   = apiBase;
        this._historias = [];
        this._sprints   = [];
        this._editId    = null;
        this._filtroSprint = '';

        this._tbody = document.getElementById('historias-tbody');
        this._filterSelect = document.getElementById('filter-sprint-historias');
        this._form = {
            titulo:            document.getElementById('historia-titulo'),
            descripcion:       document.getElementById('historia-descripcion'),
            responsable:       document.getElementById('historia-responsable'),
            sprint:            document.getElementById('historia-sprint'),
            estado:            document.getElementById('historia-estado'),
            puntos:            document.getElementById('historia-puntos'),
            fechaCreacion:     document.getElementById('historia-fecha-creacion'),
            fechaFinalizacion: document.getElementById('historia-fecha-finalizacion'),
        };

        this._bindEvents();
    }

    _bindEvents() {
        document.getElementById('btn-nueva-historia')
            .addEventListener('click', () => this._abrirModal());
        document.getElementById('btn-guardar-historia')
            .addEventListener('click', () => this._guardar());
        document.getElementById('btn-cancelar-historia')
            .addEventListener('click', () => this._cerrarModal());
        this._filterSelect.addEventListener('change', () => {
            this._filtroSprint = this._filterSelect.value;
            this._renderTabla();
        });
    }

    async cargar() {
        try {
            const res  = await fetch(`${this._apiBase}/historias`);
            const data = await res.json();
            this._historias = data;
            this._renderTabla();
            return this._historias;
        } catch (err) {
            console.error('Error cargando historias:', err);
            this._tbody.innerHTML = `<tr><td colspan="7" class="table__empty">No se pudo conectar al servidor</td></tr>`;
            return [];
        }
    }

    async _guardar() {
        const hoy = new Date().toISOString().split('T')[0];
        const payload = {
            titulo:            this._form.titulo.value.trim(),
            descripcion:       this._form.descripcion.value.trim(),
            responsable:       this._form.responsable.value.trim(),
            sprint_id:         parseInt(this._form.sprint.value),
            estado:            this._form.estado.value,
            puntos:            parseInt(this._form.puntos.value) || 1,
            fecha_creacion:    this._form.fechaCreacion.value || hoy,
            fecha_finalizacion:this._form.fechaFinalizacion.value || null,
        };

        if (!payload.titulo || !payload.descripcion || !payload.responsable || !payload.sprint_id) {
            modalManager.toast('Completa todos los campos obligatorios', 'error');
            return;
        }

        try {
            let res;
            if (this._editId) {
                res = await fetch(`${this._apiBase}/historia/${this._editId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });
            } else {
                res = await fetch(`${this._apiBase}/historia`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });
            }

            if (res.status === 201 || res.status === 200) {
                modalManager.toast(this._editId ? 'Historia actualizada' : 'Historia creada');
                this._cerrarModal();
                await this.cargar();
                window.app && window.app.onHistoriasActualizadas();
            } else {
                modalManager.toast('Error al guardar la historia', 'error');
            }
        } catch (err) {
            modalManager.toast('Error de conexión', 'error');
        }
    }

    async eliminar(id) {
        try {
            const res = await fetch(`${this._apiBase}/historia/${id}`, { method: 'DELETE' });
            if (res.status === 200) {
                modalManager.toast('Historia eliminada');
                await this.cargar();
                window.app && window.app.onHistoriasActualizadas();
            } else {
                modalManager.toast('No se pudo eliminar la historia', 'error');
            }
        } catch (err) {
            modalManager.toast('Error de conexión', 'error');
        }
    }

    actualizarSprints(sprints) {
        this._sprints = sprints;
        this._poblarSelectSprints(this._form.sprint);
        this._poblarSelectSprints(this._filterSelect, true);
    }

    _poblarSelectSprints(selectEl, conOpcionTodos = false) {
        const valorActual = selectEl.value;
        selectEl.innerHTML = conOpcionTodos
            ? '<option value="">Todos los sprints</option>'
            : '<option value="">-- Selecciona un Sprint --</option>';
        this._sprints.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s.id;
            opt.textContent = s.nombre;
            selectEl.appendChild(opt);
        });
        if (valorActual) selectEl.value = valorActual;
    }

    _renderTabla() {
        const historiasFiltradas = this._filtroSprint
            ? this._historias.filter(h => String(h.sprint_id) === String(this._filtroSprint))
            : this._historias;

        if (historiasFiltradas.length === 0) {
            this._tbody.innerHTML = `<tr><td colspan="7" class="table__empty">No hay historias registradas</td></tr>`;
            return;
        }

        this._tbody.innerHTML = '';
        historiasFiltradas.forEach((h, idx) => {
            const sprint = this._sprints.find(s => s.id === h.sprint_id);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="font-family:'DM Mono',monospace;color:var(--text-muted)">${idx + 1}</td>
                <td>
                    <div style="font-weight:600;max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                         title="${this._escapeHtml(h.titulo)}">${this._escapeHtml(h.titulo)}</div>
                </td>
                <td style="color:var(--text-secondary)">${this._escapeHtml(h.responsable)}</td>
                <td>
                    <span style="font-size:0.78rem;background:var(--bg-elevated);padding:3px 9px;border-radius:20px;color:var(--text-secondary)">
                        ${sprint ? this._escapeHtml(sprint.nombre) : `#${h.sprint_id}`}
                    </span>
                </td>
                <td>${this._badgeEstado(h.estado)}</td>
                <td><span style="font-family:'DM Mono',monospace;color:var(--accent)">${h.puntos}</span></td>
                <td>
                    <div style="display:flex;gap:6px">
                        <button class="btn btn--icon btn--edit" data-id="${h.id}" data-action="editar">✎ Editar</button>
                        <button class="btn btn--icon btn--del"  data-id="${h.id}" data-action="eliminar">✕ Eliminar</button>
                    </div>
                </td>
            `;
            this._tbody.appendChild(tr);
        });

        this._tbody.querySelectorAll('[data-action]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = parseInt(btn.dataset.id);
                if (btn.dataset.action === 'editar') {
                    this._abrirEditar(id);
                } else {
                    modalManager.confirm(
                        '¿Seguro que deseas eliminar esta historia?',
                        () => this.eliminar(id)
                    );
                }
            });
        });
    }

    _abrirModal(historia = null) {
        this._editId = historia ? historia.id : null;
        document.getElementById('modal-historia-title').textContent =
            historia ? 'Editar Historia' : 'Nueva Historia';
        const hoy = new Date().toISOString().split('T')[0];
        this._form.titulo.value            = historia ? historia.titulo : '';
        this._form.descripcion.value       = historia ? historia.descripcion : '';
        this._form.responsable.value       = historia ? historia.responsable : '';
        this._form.sprint.value            = historia ? historia.sprint_id : '';
        this._form.estado.value            = historia ? historia.estado : 'nueva';
        this._form.puntos.value            = historia ? historia.puntos : '1';
        this._form.fechaCreacion.value     = historia ? historia.fecha_creacion : hoy;
        this._form.fechaFinalizacion.value = historia?.fecha_finalizacion ?? '';
        modalManager.show('modal-historia');
    }

    _abrirEditar(id) {
        const historia = this._historias.find(h => h.id === id);
        if (historia) this._abrirModal(historia);
    }

    _cerrarModal() {
        modalManager.hide('modal-historia');
        this._editId = null;
    }

    getHistorias() { return this._historias; }

    _badgeEstado(estado) {
        return `<span class="badge badge--${estado}">${estado}</span>`;
    }

    _escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str ?? '';
        return div.innerHTML;
    }
}