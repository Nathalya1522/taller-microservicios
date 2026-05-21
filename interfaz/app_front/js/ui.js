const app =
document.getElementById('app');

function renderDashboard(){

    app.innerHTML = `

        <header class="navbar">

            <h1>
                Dashboard General
            </h1>

        </header>

        <section class="dashboard-cards">

            <div class="card total">

                <h3>Total Historias</h3>

                <h1 id="totalHistorias">0</h1>

            </div>

            <div class="card active">

                <h3>Historias Activas</h3>

                <h1 id="historiasActivas">0</h1>

            </div>

            <div class="card completed">

                <h3>Finalizadas</h3>

                <h1 id="historiasFinalizadas">0</h1>

            </div>

            <div class="card blocked">

                <h3>Impedimentos</h3>

                <h1 id="historiasBloqueadas">0</h1>

            </div>

        </section>

        <section class="kanban-board">

            <div class="kanban-column">

                <h2>Nuevas</h2>

                <div id="nuevas"></div>

            </div>

            <div class="kanban-column">

                <h2>Activas</h2>

                <div id="activas"></div>

            </div>

            <div class="kanban-column">

                <h2>Finalizadas</h2>

                <div id="finalizadas"></div>

            </div>

            <div class="kanban-column">

                <h2>Impedimentos</h2>

                <div id="impedimentos"></div>

            </div>

        </section>
    `;

    cargarDashboard();
}

function renderHistorias(){

    app.innerHTML = `

        <header class="navbar">

            <h1>
                Gestión de Historias
            </h1>

        </header>

        <form id="formHistoria" class="form">

            <input
            type="text"
            id="titulo"
            placeholder="Título">

            <textarea
            id="descripcion"
            placeholder="Descripción"></textarea>

            <input
            type="text"
            id="responsable"
            placeholder="Responsable">

            <select id="estado">

                <option value="nueva">
                    Nueva
                </option>

                <option value="activa">
                    Activa
                </option>

                <option value="finalizada">
                    Finalizada
                </option>

                <option value="impedimento">
                    Impedimento
                </option>

            </select>

            <button type="submit">

                Guardar Historia

            </button>

        </form>

        <div id="listaHistorias"></div>
    `;
    iniciarFormularioHistorias();
    cargarHistorias();
}

function renderSprints(){

    app.innerHTML = `

        <header class="navbar">

            <h1>
                Gestión de Sprints
            </h1>

        </header>

        <form id="formSprint" class="form">

            <input
            type="text"
            id="nombreSprint"
            placeholder="Nombre Sprint"
            required>

            <input
            type="date"
            id="fechaInicio"
            required>

            <input
            type="date"
            id="fechaFin"
            required>

            <button type="submit">

                Crear Sprint

            </button>

        </form>

        <div id="listaSprints"></div>
    `;
    iniciarFormularioSprint();
    cargarSprints();
}

function renderReportes(){
    app.innerHTML = `
        <header class="navbar">
            <h1>
                Reportes Scrum
            </h1>

        </header>

        <section class="dashboard-cards">

            <div class="card total">

                <h3>Total Historias</h3>

                <h1 id="reporteTotal">
                    0
                </h1>

            </div>

            <div class="card active">

                <h3>Activas</h3>

                <h1 id="reporteActivas">
                    0
                </h1>

            </div>

            <div class="card completed">

                <h3>Finalizadas</h3>

                <h1 id="reporteFinalizadas">
                    0
                </h1>

            </div>

            <div class="card blocked">

                <h3>Impedimentos</h3>

                <h1 id="reporteBloqueadas">
                    0
                </h1>

            </div>

        </section>
    `;
    cargarReportes();
}

document
.querySelectorAll('.menu-item')
.forEach(item => {

    item.addEventListener('click', () => {

        document
        .querySelectorAll('.menu-item')
        .forEach(i =>
            i.classList.remove('active')
        );

        item.classList.add('active');

        const section =
        item.dataset.section;

        switch(section){

            case 'dashboard':
                renderDashboard();
            break;

            case 'historias':
                renderHistorias();
            break;

            case 'sprints':
                renderSprints();
            break;

            case 'reportes':
                renderReportes();
            break;
        }
    });
});

renderDashboard();