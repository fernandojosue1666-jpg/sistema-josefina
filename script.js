// script.js
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const user = document.getElementById('username').value.trim();
    const pass = document.getElementById('password').value.trim();
    const msg = document.getElementById('message');
    
    if (!user || !pass) return mostrarMsg(msg, 'Complete todos los campos', 'error');
    
    try {
        const formData = new FormData();
        formData.append('usuario', user);
        formData.append('password', pass);
        
        const data = await (await fetch('login.php', { method: 'POST', body: formData })).json();
        
        if (data.success) {
            mostrarMsg(msg, data.message, 'success');
            sessionStorage.setItem('usuarioActual', JSON.stringify(data.usuario));
            setTimeout(() => window.location.href = 'dashboard.html', 1500);
        } else {
            mostrarMsg(msg, data.message, 'error');
        }
    } catch {
        mostrarMsg(msg, 'Error al conectar con el servidor', 'error');
    }
});

function mostrarMsg(el, texto, tipo) {
    el.textContent = texto;
    el.className = tipo;
    el.style.display = 'block';
}