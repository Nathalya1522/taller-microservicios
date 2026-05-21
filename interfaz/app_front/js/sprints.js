function iniciarFormularioSprint(){

    const form =
    document.getElementById(
        'formSprint'
    );

    form.addEventListener(
        'submit',
        async (e) => {

        e.preventDefault();

        const data = {

            nombre:
            document.getElementById(
                'nombreSprint'
            ).value,

            fecha_inicio:
            document.getElementById(
                'fechaInicio'
            ).value,

            fecha_fin:
            document.getElementById(
                'fechaFin'
            ).value
        };

        try{

            const response =
            await fetch(API.sprints, {

                method:'POST',

                headers:{
                    'Content-Type':
                    'application/json'
                },

                body:JSON.stringify(data)
            });

            const text =
            await response.text();

            console.log(text);

            alert(
                'Sprint creado'
            );

            form.reset();

            cargarSprints();

        }catch(error){

            console.error(error);

            alert(
                'Error al guardar sprint'
            );
        }
    });
}

async function cargarSprints(){

    try{

        const response =
        await fetch(API.sprints);

        const sprints =
        await response.json();

        const container =
        document.getElementById(
            'listaSprints'
        );

        container.innerHTML = '';

        sprints.forEach(sprint => {

            container.innerHTML += `

                <div class="historia-card">

                    <h3>
                        ${sprint.nombre}
                    </h3>

                    <p>
                        Inicio:
                        ${sprint.fecha_inicio}
                    </p>

                    <p>
                        Fin:
                        ${sprint.fecha_fin}
                    </p>

                </div>
            `;
        });

    }catch(error){

        console.error(error);
    }
}