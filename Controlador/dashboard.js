document.addEventListener('DOMContentLoaded', ()  => {
    const logueado = localStorage.getItem('logueado');
    
    if (logueado !== 'true') {
        window.location.href = 'login.html';
        return;
    }

    const nombre = localStorage.getItem('nombre');
    const rol = localStorage.getItem('rol');

    document.getElementById('nombreUsuario').textContent = nombre;

    if (rol === 'admin') {
        document.getElementById('tarjeta Admin').classList.remove('d-none');
        document.getElementById('tarjeta Asociado').classList.add('d-none');
    }else{
        document.getElementById('tarjeta Admin').classList.add('d-none');
        document.getElementById('tarjeta Asociado').classList.add('d-none');
    }    
});


function cerrarSesion() {
    localStorage.clear();
    window.location.href = '../Vista/login.html';
}