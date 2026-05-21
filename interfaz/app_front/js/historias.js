const form =
document.getElementById('formHistoria');

form.addEventListener(
    'submit',
    async (e) => {

    e.preventDefault();

    const data = {

         titulo:
    document.getElementById(
        'titulo'
    ).value,

    descripcion:
    document.getElementById(
        'descripcion'
    ).value,

    responsable:
    document.getElementById(
        'responsable'
    ).value,

    estado:
    document.getElementById(
        'estado'
    ).value,

    puntos: 1,

    fecha_creacion:
    new Date()
    .toISOString()
    .split('T')[0],

    sprint_id: 1
    };

    try{

        const response = await fetch(API.historias, {

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

        alert('Historia creada');

        location.reload();

    }catch(error){

        console.error(error);

        alert('Error al guardar');
    }
});