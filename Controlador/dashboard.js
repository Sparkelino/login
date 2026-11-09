document.addEventListener('DOMContentLoaded', () => {
    const logueado = localStorage.getItem('logueado');
    
    if (logueado !== 'true') {
        window.location.href = 'login.html';
        return;
    }

    const nombre = localStorage.getItem('nombre');
    const rol = localStorage.getItem('rol');

    const nombreUsuarioEl = document.getElementById('nombreUsuario');
    if (nombreUsuarioEl && nombre) {
        nombreUsuarioEl.textContent = `${nombre} (${rol})`;
    }

    const contenedorAdmin = document.getElementById('tarjetaAdmin');
    const contenedorAsociado = document.getElementById('tarjetaAsociado');

    if (rol === 'admin') {
        if (contenedorAdmin) contenedorAdmin.classList.remove('d-none');
        if (contenedorAsociado) contenedorAsociado.classList.add('d-none');
    } else {
        if (contenedorAdmin) contenedorAdmin.classList.add('d-none');
        if (contenedorAsociado) contenedorAsociado.classList.remove('d-none');
    }    
});

function cerrarSesion() {
    localStorage.clear();
    window.location.href = 'login.html';
}
