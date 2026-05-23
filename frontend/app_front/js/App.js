class App {
    constructor() {
        this._apiBase   = 'http://127.0.0.1:8000';
        this._sprints   = new SprintsManager(this._apiBase);
        this._historias = new HistoriasManager(this._apiBase);
        this._dashboard = new DashboardManager();
        this._reportes  = new ReportesManager();

        this._bindNavegacion();
        this._cargarTodo();
        window.app = this;
    }

    _bindNavegacion() {
        document.querySelectorAll('.nav__item').forEach(btn => {
            btn.addEventListener('click', () => this._navegarA(btn.dataset.page));
        });
    }

    _navegarA(pagina) {
        document.querySelectorAll('.nav__item').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
        document.getElementById(`nav-${pagina}`).classList.add('active');
        document.getElementById(`page-${pagina}`).classList.add('active');
    }

    async _cargarTodo() {
        const [sprints, historias] = await Promise.all([
            this._sprints.cargar(),
            this._historias.cargar(),
        ]);
        this._sincronizarDatos(sprints, historias);
    }

    _sincronizarDatos(sprints, historias) {
        this._historias.actualizarSprints(sprints);
        this._dashboard.actualizar(historias, sprints);
        this._reportes.actualizarDatos(historias, sprints);
    }

    async onSprintsActualizados() {
        this._sincronizarDatos(this._sprints.getSprints(), this._historias.getHistorias());
    }

    async onHistoriasActualizadas() {
        this._sincronizarDatos(this._sprints.getSprints(), this._historias.getHistorias());
    }
}

document.addEventListener('DOMContentLoaded', () => { new App(); });