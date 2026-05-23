class SprintsManager {
    constructor(apiBase) {
        this._apiBase = apiBase;
        this._sprints = [];
        this._editId  = null;

        this._tbody = document.getElementById('sprints-tbody');
        this._form = {
            nombre:      document.getElementById('sprint-nombre'),
            fechaInicio: document.getElementById('sprint-fecha-inicio'),
            fechaFin:    document.getElementById('sprint-fecha-fin'),
        };

        this._bindEvents();
    }

    _bindEvents() {
        document.getElementById('btn-nuevo-sprint')
            .addEventListener('click', () => this._abrirModal());
        document.getElementById('btn-guardar-sprint')
            .addEventListener('click', () => this._guardar());
        document.getElementById('btn-cancelar-sprint')
            .addEventListener('click', () => this._cerrarModal());
    }

    async cargar() {
        try {
            const res  = await fetch(`${this._apiBase}/sprints`);
            const data = await res.json();
            this._sprints = data;
            this._renderTabla();
            return this._sprints;
        } catch (err) {
            console.error('Error cargando sprints:', err);
            this._tbody.innerHTML = `<tr><td colspan="6" class="table__empty">No se pudo conectar al servidor</td></tr>`;
            return [];
        }
    }

    async _guardar() {
        const payload = {
            nombre:       this._form.nombre.value.trim(),
            fecha_inicio: this._form.fechaInicio.value,
            fecha_fin:    this._form.fechaFin.value,
        };

        if (!payload.nombre || !payload.fecha_inicio || !payload.fecha_fin) {
            modalManager.toast('Completa todos los campos obligatorios', 'error');
            return;
        }

        try {
            let res;
            if (this._editId) {
                res = await fetch(`${this._apiBase}/sprint/${this._editId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });
            } else {
                res = await fetch(`${this._apiBase}/sprint`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                });
            }

            if (res.status === 201 || res.status === 200) {
                modalManager.toast(this._editId ? 'Sprint actualizado' : 'Sprint creado');
                this._cerrarModal();
                await this.cargar();
                window.app && window.app.onSprintsActualizados();
            } else {
                modalManager.toast('Error al guardar el sprint', 'error');
            }
        } catch (err) {
            modalManager.toast('Error de conexión', 'error');
        }
    }

    async eliminar(id) {
        try {
            const res = await fetch(`${this._apiBase}/sprint/${id}`, { method: 'DELETE' });
            if (res.status === 200) {
                modalManager.toast('Sprint eliminado');
                await this.cargar();
                window.app && window.app.onSprintsActualizados();
            } else {
                modalManager.toast('No se pudo eliminar el sprint', 'error');
            }
        } catch (err) {
            modalManager.toast('Error de conexión', 'error');
        }
    }

    _renderTabla() {
        if (this._sprints.length === 0) {
            this._tbody.innerHTML = `<tr><td colspan="6" class="table__empty">No hay sprints registrados</td></tr>`;
            return;
        }
        this._tbody.innerHTML = '';
        this._sprints.forEach((sprint, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="font-family:'DM Mono',monospace;color:var(--text-muted)">${idx + 1}</td>
                <td style="font-weight:600">${this._escapeHtml(sprint.nombre)}</td>
                <td>${this._formatFecha(sprint.fecha_inicio)}</td>
                <td>${this._formatFecha(sprint.fecha_fin)}</td>
                <td><span style="font-family:'DM Mono',monospace;color:var(--accent)">${sprint._historias_count ?? '—'}</span></td>
                <td>
                    <div style="display:flex;gap:6px">
                        <button class="btn btn--icon btn--edit" data-id="${sprint.id}" data-action="editar">✎ Editar</button>
                        <button class="btn btn--icon btn--del"  data-id="${sprint.id}" data-action="eliminar">✕ Eliminar</button>
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
                        '¿Seguro que deseas eliminar este sprint?',
                        () => this.eliminar(id)
                    );
                }
            });
        });
    }

    _abrirModal(sprint = null) {
        this._editId = sprint ? sprint.id : null;
        document.getElementById('modal-sprint-title').textContent = sprint ? 'Editar Sprint' : 'Nuevo Sprint';
        this._form.nombre.value      = sprint ? sprint.nombre : '';
        this._form.fechaInicio.value = sprint ? sprint.fecha_inicio : '';
        this._form.fechaFin.value    = sprint ? sprint.fecha_fin : '';
        modalManager.show('modal-sprint');
    }

    _abrirEditar(id) {
        const sprint = this._sprints.find(s => s.id === id);
        if (sprint) this._abrirModal(sprint);
    }

    _cerrarModal() {
        modalManager.hide('modal-sprint');
        this._editId = null;
    }

    getSprints() { return this._sprints; }

    _formatFecha(fecha) {
        if (!fecha) return '—';
        const [y, m, d] = fecha.split('-');
        return `${d}/${m}/${y}`;
    }

    _escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
}