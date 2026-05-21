async function cargarDashboard(){

    const response =
    await fetch(API.historias);

    const historias =
    await response.json();

    document.getElementById(
        'totalHistorias'
    ).innerHTML = historias.length;

    let activas = 0;
    let finalizadas = 0;
    let bloqueadas = 0;

    historias.forEach(historia => {

        const card = `
            <div class="kanban-card">

                <h3>${historia.titulo}</h3>

                <p>${historia.descripcion}</p>

                <small>
                    ${historia.responsable}
                </small>

            </div>
        `;

        switch(historia.estado){

            case 'nueva':
                document
                .getElementById('nuevas')
                .innerHTML += card;
            break;

            case 'activa':

                activas++;

                document
                .getElementById('activas')
                .innerHTML += card;
            break;

            case 'finalizada':

                finalizadas++;

                document
                .getElementById('finalizadas')
                .innerHTML += card;
            break;

            case 'impedimento':

                bloqueadas++;

                document
                .getElementById('impedimentos')
                .innerHTML += card;
            break;
        }
    });

    document.getElementById(
        'historiasActivas'
    ).innerHTML = activas;

    document.getElementById(
        'historiasFinalizadas'
    ).innerHTML = finalizadas;

    document.getElementById(
        'historiasBloqueadas'
    ).innerHTML = bloqueadas;
}

cargarDashboard();