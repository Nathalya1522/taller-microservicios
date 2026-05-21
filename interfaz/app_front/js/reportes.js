async function cargarReportes(){

    try{

        const response =
        await fetch(API.historias);

        const historias =
        await response.json();

        let activas = 0;

        let finalizadas = 0;

        let bloqueadas = 0;

        historias.forEach(historia => {

            switch(historia.estado){

                case 'activa':
                    activas++;
                break;

                case 'finalizada':
                    finalizadas++;
                break;

                case 'impedimento':
                    bloqueadas++;
                break;
            }
        });

        document.getElementById(
            'reporteTotal'
        ).innerHTML =
        historias.length;

        document.getElementById(
            'reporteActivas'
        ).innerHTML =
        activas;

        document.getElementById(
            'reporteFinalizadas'
        ).innerHTML =
        finalizadas;

        document.getElementById(
            'reporteBloqueadas'
        ).innerHTML =
        bloqueadas;

    }catch(error){

        console.error(error);
    }
}