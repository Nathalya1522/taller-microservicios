let sprintsData = [];

// ─── UTILS ───────────────────────────────────────────────────────

function mostrarSeccion(nombre) {
    document.querySelectorAll('.seccion').forEach(s => s.classList.add('oculto'));
    document.querySelectorAll('.menu-item').forEach(b => b.classList.remove('active'));
    document.getElementById('sec-' + nombre).classList.remove('oculto');
    document.querySelector(`[data-section="${nombre}"]`).classList.add('active');
}

function abrirModal(id) {
    document.getElementById(id).classList.add('activo');
}

function cerrarModal(id) {
    document.getElementById(id).classList.remove('activo');
}

async function cargarSprintsEnSelects() {
    const res = await Sprints.getAll();
    sprintsData = res.data || [];
    const selectH = document.getElementById('h-sprint');
    const selectR = document.getElementById('r-sprint');
    const ops = '<option value="">-- Selecciona un Sprint --</option>' +
        sprintsData.map(s => `<option value="${s.id}">${s.nombre}</option>`).join('');
    if (selectH) selectH.innerHTML = ops;
    if (selectR) selectR.innerHTML = ops;
}

function getNombreSprint(id) {
    const s = sprintsData.find(s => s.id == id);
    return s ? s.nombre : 'Sprint ' + id;
}

// ─── SPRINTS ─────────────────────────────────────────────────────

async function cargarSprints() {
    const res = await Sprints.getAll();
    const tb  = document.getElementById('tabla-sprints');
    if (!res.data || res.data.length === 0) {
        tb.innerHTML = '<tr><td colspan="4" class="empty">No hay sprints creados</td></tr>';
        return;
    }
    sprintsData = res.data;
    tb.innerHTML = res.data.map(s => `
        <tr>
            <td><strong>${s.nombre}</strong></td>
            <td>${s.fecha_inicio}</td>
            <td>${s.fecha_fin}</td>
            <td>
                <button class="btn-editar" onclick="editarSprint(${s.id},'${s.nombre}','${s.fecha_inicio}','${s.fecha_fin}')">Editar</button>
                <button class="btn-eliminar" onclick="eliminarSprint(${s.id})">Borrar</button>
            </td>
        </tr>`).join('');
    cargarSprintsEnSelects();
}

function abrirModalSprint() {
    document.getElementById('modal-sprint-titulo').textContent = 'Nuevo Sprint';
    document.getElementById('sprint-id').value = '';
    document.getElementById('s-nombre').value  = '';
    document.getElementById('s-inicio').value  = '';
    document.getElementById('s-fin').value     = '';
    abrirModal('modal-sprint');
}

function editarSprint(id, nombre, inicio, fin) {
    document.getElementById('modal-sprint-titulo').textContent = 'Editar Sprint';
    document.getElementById('sprint-id').value = id;
    document.getElementById('s-nombre').value  = nombre;
    document.getElementById('s-inicio').value  = inicio;
    document.getElementById('s-fin').value     = fin;
    abrirModal('modal-sprint');
}

async function guardarSprint() {
    const id     = document.getElementById('sprint-id').value;
    const nombre = document.getElementById('s-nombre').value;
    const inicio = document.getElementById('s-inicio').value;
    const fin    = document.getElementById('s-fin').value;
    if (!nombre || !inicio || !fin) { alert('Completa todos los campos'); return; }
    const datos = { nombre, fecha_inicio: inicio, fecha_fin: fin };
    const res   = id ? await Sprints.update(id, datos) : await Sprints.create(datos);
    alert(res.mensaje);
    cerrarModal('modal-sprint');
    cargarSprints();
}

async function eliminarSprint(id) {
    if (!confirm('¿Eliminar este sprint?')) return;
    const res = await Sprints.delete(id);
    alert(res.mensaje);
    cargarSprints();
}

// ─── HISTORIAS ───────────────────────────────────────────────────

async function cargarHistorias() {
    const res = await Historias.getAll();
    const tb  = document.getElementById('tabla-historias');
    if (!res.data || res.data.length === 0) {
        tb.innerHTML = '<tr><td colspan="6" class="empty">No hay historias creadas</td></tr>';
        return;
    }
    tb.innerHTML = res.data.map(h => `
        <tr>
            <td><strong>${h.titulo}</strong><br><small>${getNombreSprint(h.sprint_id)}</small></td>
            <td>${h.responsable || '-'}</td>
            <td>${h.estado}</td>
            <td>${h.puntos}</td>
            <td>${h.fecha_fin || '-'}</td>
            <td>
                <button class="btn-editar" onclick="editarHistoria(${h.id})">Editar</button>
                <button class="btn-eliminar" onclick="eliminarHistoria(${h.id})">Borrar</button>
            </td>
        </tr>`).join('');
}

function abrirModalHistoria() {
    document.getElementById('modal-historia-titulo').textContent = 'Nueva Historia';
    document.getElementById('historia-id').value   = '';
    document.getElementById('h-titulo').value      = '';
    document.getElementById('h-desc').value        = '';
    document.getElementById('h-responsable').value = '';
    document.getElementById('h-puntos').value      = '';
    document.getElementById('h-fin').value         = '';
    document.getElementById('h-estado').value      = 'nueva';  
    abrirModal('modal-historia');
}

async function editarHistoria(id) {
    const res = await Historias.getById(id);
    const h   = res.data;
    document.getElementById('modal-historia-titulo').textContent = 'Editar Historia';
    document.getElementById('historia-id').value   = h.id;
    document.getElementById('h-titulo').value      = h.titulo;
    document.getElementById('h-desc').value        = h.descripcion;
    document.getElementById('h-responsable').value = h.responsable || '';
    document.getElementById('h-sprint').value      = h.sprint_id;
    document.getElementById('h-estado').value      = h.estado;
    document.getElementById('h-puntos').value      = h.puntos;
    document.getElementById('h-fin').value         = h.fecha_fin || '';
    abrirModal('modal-historia');
}

async function guardarHistoria() {
    const id = document.getElementById('historia-id').value;
    const datos = {
        titulo:      document.getElementById('h-titulo').value,
        descripcion: document.getElementById('h-desc').value,
        responsable: document.getElementById('h-responsable').value,
        sprint_id:   document.getElementById('h-sprint').value,
        estado:      document.getElementById('h-estado').value,
        puntos:      document.getElementById('h-puntos').value,
        fecha_fin:   document.getElementById('h-fin').value || null,
    };
    if (!datos.titulo || !datos.sprint_id || !datos.puntos) {
        alert('Completa los campos obligatorios');
        return;
    }
    const res = id ? await Historias.update(id, datos) : await Historias.create(datos);
    alert(res.mensaje);
    cerrarModal('modal-historia');
    cargarHistorias();
    cargarDashboard();
}

async function eliminarHistoria(id) {
    if (!confirm('¿Eliminar esta historia?')) return;
    const res = await Historias.delete(id);
    alert(res.mensaje);
    cargarHistorias();
    cargarDashboard();
}

// ─── RETRO ITEMS ─────────────────────────────────────────────────

async function cargarReporte() {
    const res = await RetroItems.getAll();
    const tb  = document.getElementById('tabla-retro');
    if (!res.data || res.data.length === 0) {
        tb.innerHTML = '<tr><td colspan="4" class="empty">No hay retro items</td></tr>';
        return;
    }
    tb.innerHTML = res.data.map(r => `
        <tr>
            <td>${r.descripcion}</td>
            <td>${r.tipo}</td>
            <td>${getNombreSprint(r.sprint_id)}</td>
            <td>
                <button class="btn-editar" onclick="editarReporte(${r.id})">Editar</button>
                <button class="btn-eliminar" onclick="eliminarReporte(${r.id})">Borrar</button>
            </td>
        </tr>`).join('');
}

function abrirModalRetro() {
    document.getElementById('modal-retro-titulo').textContent = 'Nuevo Retro Item';
    document.getElementById('retro-id').value = '';
    document.getElementById('r-desc').value   = '';
    document.getElementById('r-tipo').value   = 'bien';
    abrirModal('modal-retro');
}

async function editarReporte(id) {
    const res = await RetroItems.getById(id);
    const r   = res.data;
    document.getElementById('modal-retro-titulo').textContent = 'Editar Retro Item';
    document.getElementById('retro-id').value  = r.id;
    document.getElementById('r-desc').value    = r.descripcion;
    document.getElementById('r-tipo').value    = r.tipo;
    document.getElementById('r-sprint').value  = r.sprint_id;
    abrirModal('modal-retro');
}

async function guardarRetro() {
    const id = document.getElementById('retro-id').value;
    const datos = {
        tipo:        document.getElementById('r-tipo').value,
        descripcion: document.getElementById('r-desc').value,
        sprint_id:   document.getElementById('r-sprint').value,
    };
    if (!datos.descripcion || !datos.sprint_id) {
        alert('Completa todos los campos');
        return;
    }
    const res = id ? await RetroItems.update(id, datos) : await RetroItems.create(datos);
    alert(res.mensaje);
    cerrarModal('modal-retro');
    cargarReporte();
}

async function eliminarReporte(id) {
    if (!confirm('¿Eliminar este item?')) return;
    const res = await RetroItems.delete(id);
    alert(res.mensaje);
    cargarReporte();
}

// ─── DASHBOARD ───────────────────────────────────────────────────

async function cargarDashboard() {
    const res = await Historias.getAll();
    const historias = res.data || [];

    document.getElementById('totalHistorias').innerHTML = historias.length;

    let activas = 0, finalizadas = 0, bloqueadas = 0;

    document.getElementById('nuevas').innerHTML       = '';
    document.getElementById('activas').innerHTML      = '';
    document.getElementById('finalizadas').innerHTML  = '';
    document.getElementById('impedimentos').innerHTML = '';

    historias.forEach(h => {
        const card = `
            <div class="kanban-card">
                <h3>${h.titulo}</h3>
                <p>${h.descripcion}</p>
                <small>${h.responsable}</small>
            </div>`;

        switch(h.estado) {
            case 'pendiente':
            case 'nueva':
                document.getElementById('nuevas').innerHTML += card;
            break;
            case 'en_progreso':
            case 'activa':
                activas++;
                document.getElementById('activas').innerHTML += card;
            break;
            case 'finalizada':
                finalizadas++;
                document.getElementById('finalizadas').innerHTML += card;
            break;
            case 'impedimento':
                bloqueadas++;
                document.getElementById('impedimentos').innerHTML += card;
            break;
        }
    });

    document.getElementById('historiasActivas').innerHTML     = activas;
    document.getElementById('historiasFinalizadas').innerHTML = finalizadas;
    document.getElementById('historiasBloqueadas').innerHTML  = bloqueadas;
}

// ─── NAVEGACIÓN ──────────────────────────────────────────────────

document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', () => {
        mostrarSeccion(item.dataset.section);
    });
});

// ─── INICIO ──────────────────────────────────────────────────────

window.onload = function() {
    cargarSprintsEnSelects();
    cargarDashboard();
    cargarHistorias();
    cargarSprints();
    cargarReporte();
};