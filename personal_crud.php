<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Personal</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .crud-container {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 1100px;
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .crud-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .crud-header h2 { color: #1e3c72; }
        .btn-agregar {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
        }
        .btn-agregar:hover { background: #218838; }
        .btn-editar {
            background: #ffc107;
            color: #333;
            padding: 6px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-editar:hover { background: #e0a800; }
        .btn-eliminar {
            background: #dc3545;
            color: white;
            padding: 6px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-eliminar:hover { background: #c82333; }
        .btn-volver {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-volver:hover { background: #5a6268; }
        .table-container { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
        }
        thead th {
            color: white !important;
            background: transparent !important;
        }
        thead tr {
            background: transparent !important;
        }
        thead tr:hover {
            background: transparent !important;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #e0e7ff;
            font-size: 14px;
        }
        tbody tr:hover { 
            background: #f8f9fa !important; 
        }
        .rol-badge-crud {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .rol-docente { background: #e3f2fd; color: #1565c0; }
        .rol-administrativo { background: #f3e5f5; color: #7b1fa2; }
        .rol-obrero { background: #fff3e0; color: #e65100; }
        .rol-director { background: #e8f5e9; color: #2e7d32; }
        .acciones { display: flex; gap: 5px; flex-wrap: wrap; }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 20px;
            max-width: 500px;
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }
        .modal-content h3 { color: #1e3c72; margin-bottom: 20px; }
        .modal-content label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .modal-content input, .modal-content select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 2px solid #e0e7ff;
            border-radius: 10px;
            font-size: 14px;
        }
        .modal-content input:focus, .modal-content select:focus {
            outline: none;
            border-color: #2a5298;
        }
        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 15px;
        }
        .btn-guardar {
            background: #28a745;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-guardar:hover { background: #218838; }
        .btn-cancelar {
            background: #6c757d;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }
        .btn-cancelar:hover { background: #5a6268; }
        .cerrar-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #999;
            background: none;
            border: none;
        }
        .cerrar-modal:hover { color: #333; }
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.5);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }
        .loading-overlay.active {
            display: flex;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #2a5298;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @media (max-width: 768px) {
            .crud-container { padding: 15px; }
            .crud-header { flex-direction: column; align-items: stretch; }
            table { font-size: 12px; }
            th, td { padding: 6px; }
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <div class="crud-container">
        <div class="crud-header">
            <h2>Gestion de Personal</h2>
            <div>
                <a href="dashboard.html" class="btn-volver">Volver</a>
                <button class="btn-agregar" onclick="abrirModalAgregar()">Agregar Personal</button>
            </div>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Cedula</th>
                        <th>Telefono</th>
                        <th>Fecha Ingreso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tablaPersonal">
                    <tr><td colspan="7" style="text-align:center;">Cargando datos...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal" id="modalPersonal">
        <div class="modal-content">
            <button class="cerrar-modal" onclick="cerrarModal()">&times;</button>
            <h3 id="modalTitle">Agregar Personal</h3>
            <form id="formPersonal">
                <input type="hidden" id="id" name="id">
                <label>Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label>Apellido:</label>
                <input type="text" id="apellido" name="apellido" required>
                <label>Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>
                <label>Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Dejar en blanco para mantener">
                <label>Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="">Seleccionar...</option>
                    <option value="Docente">Docente</option>
                    <option value="Administrativo">Administrativo</option>
                    <option value="Obrero">Obrero</option>
                    <option value="Director">Director</option>
                </select>
                <label>Cedula:</label>
                <input type="text" id="cedula" name="cedula" required>
                <label>Telefono:</label>
                <input type="text" id="telefono" name="telefono">
                <label>Direccion:</label>
                <input type="text" id="direccion" name="direccion">
                <label>Fecha de Ingreso:</label>
                <input type="date" id="fecha_ingreso" name="fecha_ingreso" required>
                <div class="modal-buttons">
                    <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn-guardar">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Función unificada para llamar a la API
        function api(action, data = null) {
            const options = { method: 'GET' };
            if (data) {
                options.method = 'POST';
                if (data instanceof FormData) {
                    options.body = data;
                } else {
                    options.headers = { 'Content-Type': 'application/json' };
                    options.body = JSON.stringify(data);
                }
            }
            return fetch(`personal_api.php?action=${action}`, options)
                .then(r => { if (!r.ok) throw new Error('Error en el servidor'); return r.json(); });
        }

        // Cargar tabla
        function cargarTabla() {
            const tbody = document.getElementById('tablaPersonal');
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Cargando...</td></tr>';
            
            api('listar')
                .then(data => {
                    if (data.success && data.data.length) {
                        tbody.innerHTML = data.data.map(p => `
                            <tr>
                                <td>${escape(p.nombre)} ${escape(p.apellido)}</td>
                                <td>${escape(p.usuario)}</td>
                                <td><span class="rol-badge-crud rol-${p.rol.toLowerCase()}">${escape(p.rol)}</span></td>
                                <td>${escape(p.cedula)}</td>
                                <td>${escape(p.telefono || '')}</td>
                                <td>${formatoFecha(p.fecha_ingreso)}</td>
                                <td>
                                    <button class="btn-editar" onclick="editar(${p.id})">Editar</button>
                                    <button class="btn-eliminar" onclick="eliminar(${p.id})">Eliminar</button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No hay registros</td></tr>';
                    }
                })
                .catch(() => tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:red;">Error al cargar</td></tr>');
        }

        // Funciones auxiliares
        function escape(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatoFecha(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
        }

        function cerrarModal() {
            document.getElementById('modalPersonal').classList.remove('active');
        }

        function abrirModalAgregar() {
            document.getElementById('modalTitle').textContent = 'Agregar Nuevo Personal';
            document.getElementById('formPersonal').reset();
            document.getElementById('id').value = '';
            document.getElementById('password').required = true;
            document.getElementById('password').placeholder = 'Ingresa una contraseña';
            document.getElementById('modalPersonal').classList.add('active');
        }

        function editar(id) {
            document.getElementById('modalTitle').textContent = 'Editar Personal';
            document.getElementById('password').required = false;
            document.getElementById('password').placeholder = 'Dejar en blanco para mantener';
            
            api('obtener', null, `&id=${id}`)
                .then(data => {
                    if (data.success) {
                        const p = data.data;
                        document.getElementById('id').value = p.id;
                        document.getElementById('nombre').value = p.nombre;
                        document.getElementById('apellido').value = p.apellido;
                        document.getElementById('usuario').value = p.usuario;
                        document.getElementById('rol').value = p.rol;
                        document.getElementById('cedula').value = p.cedula;
                        document.getElementById('telefono').value = p.telefono || '';
                        document.getElementById('direccion').value = p.direccion || '';
                        document.getElementById('fecha_ingreso').value = p.fecha_ingreso;
                        document.getElementById('password').value = '';
                        document.getElementById('modalPersonal').classList.add('active');
                    }
                })
                .catch(() => alert('Error al cargar datos'));
        }

        function eliminar(id) {
            if (!confirm('¿Estas seguro de eliminar este registro?')) return;
            document.getElementById('loadingOverlay').classList.add('active');
            
            api('eliminar', { id })
                .then(data => {
                    document.getElementById('loadingOverlay').classList.remove('active');
                    if (data.success) cargarTabla();
                    else alert('Error: ' + data.message);
                })
                .catch(() => {
                    document.getElementById('loadingOverlay').classList.remove('active');
                    alert('Error al eliminar');
                });
        }

        // Enviar formulario
        document.getElementById('formPersonal').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('id').value;
            const nombre = document.getElementById('nombre').value.trim();
            const apellido = document.getElementById('apellido').value.trim();
            const usuario = document.getElementById('usuario').value.trim();
            const password = document.getElementById('password').value.trim();
            const rol = document.getElementById('rol').value;
            const cedula = document.getElementById('cedula').value.trim();
            const fecha_ingreso = document.getElementById('fecha_ingreso').value;
            
            if (!nombre || !apellido || !usuario || !rol || !cedula || !fecha_ingreso) {
                alert('Todos los campos obligatorios deben estar llenos');
                return;
            }
            
            if (!id && !password) {
                alert('La contraseña es obligatoria para nuevos registros');
                return;
            }
            
            const formData = new FormData(this);
            document.getElementById('loadingOverlay').classList.add('active');
            
            api('guardar', formData)
                .then(data => {
                    document.getElementById('loadingOverlay').classList.remove('active');
                    if (data.success) {
                        cerrarModal();
                        cargarTabla();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(() => {
                    document.getElementById('loadingOverlay').classList.remove('active');
                    alert('Error al guardar');
                });
        });

        // Cerrar modal al hacer clic fuera
        document.getElementById('modalPersonal').addEventListener('click', function(e) {
            if (e.target === this) cerrarModal();
        });

        // Cargar al iniciar
        document.addEventListener('DOMContentLoaded', cargarTabla);
    </script>
</body>
</html>