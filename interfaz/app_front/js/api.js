const BASE_URL = 'http://localhost/taller-microservicios/microservicios/public/index.php';

async function request(url, method = 'GET', body = null) {
    const opciones = {
        method,
        headers: { 'Content-Type': 'application/json' },
    };
    if (body) opciones.body = JSON.stringify(body);
    const res  = await fetch(url, opciones);
    const data = await res.json();
    return data;
}

const Sprints = {
    getAll:  ()           => request(`${BASE_URL}/sprints`),
    getById: (id)         => request(`${BASE_URL}/sprints/${id}`),
    create:  (datos)      => request(`${BASE_URL}/sprints`, 'POST', datos),
    update:  (id, datos)  => request(`${BASE_URL}/sprints/${id}`, 'PUT', datos),
    delete:  (id)         => request(`${BASE_URL}/sprints/${id}`, 'DELETE'),
};

const Historias = {
    getAll:  ()           => request(`${BASE_URL}/historias`),
    getById: (id)         => request(`${BASE_URL}/historias/${id}`),
    create:  (datos)      => request(`${BASE_URL}/historias`, 'POST', datos),
    update:  (id, datos)  => request(`${BASE_URL}/historias/${id}`, 'PUT', datos),
    delete:  (id)         => request(`${BASE_URL}/historias/${id}`, 'DELETE'),
};

const RetroItems = {
    getAll:  ()           => request(`${BASE_URL}/reportes`),
    getById: (id)         => request(`${BASE_URL}/reportes/${id}`),
    create:  (datos)      => request(`${BASE_URL}/reportes`, 'POST', datos),
    update:  (id, datos)  => request(`${BASE_URL}/reportes/${id}`, 'PUT', datos),
    delete:  (id)         => request(`${BASE_URL}/reportes/${id}`, 'DELETE'),
};