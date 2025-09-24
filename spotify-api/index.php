<?php /* index.php - Página única: Registro + Búsqueda */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Spotify Tool | Inicio</title>
<style>
  :root { --green:#1DB954; --bg:#111; --text:#fff; --muted:#999; }
  body{font-family:Arial, sans-serif;background:var(--bg);color:var(--text);margin:0;min-height:100vh;display:flex;flex-direction:column;align-items:center}
  h1,h2{color:var(--green);font-weight:700}
  .wrap{width:100%;max-width:760px;padding:32px}
  .card{background:#161616;border:1px solid #222;border-radius:12px;padding:24px;box-shadow:0 8px 30px rgba(0,0,0,.2)}
  .hidden{display:none}
  label{display:block;margin:10px 0 6px}
  input,select,button{width:100%;padding:12px;border-radius:8px;border:1px solid #333;background:#0f0f0f;color:#fff;font-size:16px}
  input::placeholder{color:#666}
  button{background:var(--green);border:none;color:#fff;cursor:pointer;font-weight:700}
  button:disabled{opacity:.6;cursor:not-allowed}
  .row{display:grid;gap:12px}
  .msg{margin-top:12px;font-size:14px}
  .msg.ok{color:#67ff9d}
  .msg.err{color:#ff6b6b}
  footer{margin:40px 0;color:var(--muted);font-size:12px;text-align:center}
</style>
</head>
<body>
  <div class="wrap">
    <h1>🎵 Herramienta de Análisis Musical (TFG)</h1>

    <!-- Paso A: Registro -->
    <section id="step-register" class="card">
      <h2>Registro de usuario</h2>
      <form id="formRegister" class="row" autocomplete="off">
        <div>
          <label for="reg_username">Nombre de usuario</label>
          <input id="reg_username" name="username" type="text" placeholder="Tu nombre" required />
        </div>
        <div>
          <label for="reg_email">Correo electrónico</label>
          <input id="reg_email" name="email" type="email" placeholder="tucorreo@ejemplo.com" required />
        </div>
        <button type="submit">Registrarme</button>
        <div id="reg_msg" class="msg"></div>
      </form>
    </section>

    <!-- Paso B: Búsqueda -->
    <section id="step-search" class="card hidden">
      <h2>Buscar artista</h2>
      <div class="row">
        <div>
          <label for="user_id">Selecciona un usuario</label>
          <select id="user_id"></select>
        </div>
        <div>
          <label for="artista">Nombre del artista</label>
          <input id="artista" type="text" placeholder="Ej: Quevedo" />
        </div>
        <button id="btnBuscar">Buscar</button>
        <div id="search_msg" class="msg"></div>
      </div>
    </section>

    <footer>
      <p>© 2025 TFG Christian Abad González - DISEÑO DE HERRAMIENTA DE BÚSQUEDA Y ANÁLISIS DE INFORMACIÓN MUSICAL UTILIZANDO LA BASE DE DATOS DE SPOTIFY. Todos los derechos reservados.</p>
    </footer>
  </div>

<script>
const API = `${location.origin}/spotify-api/`;  // <- base absoluta

const $ = (sel)=>document.querySelector(sel);
const stepRegister = $('#step-register');
const stepSearch = $('#step-search');
const regForm = $('#formRegister');
const regMsg = $('#reg_msg');
const userSelect = $('#user_id');
const artistaInput = $('#artista');
const btnBuscar = $('#btnBuscar');
const searchMsg = $('#search_msg');

const showRegister = ()=>{ stepRegister.classList.remove('hidden'); stepSearch.classList.add('hidden'); };
const showSearch   = ()=>{ stepSearch.classList.remove('hidden'); stepRegister.classList.add('hidden'); };
const opt = (id, txt) => `<option value="${id}">${txt}</option>`;

async function loadUsers(preselectId=null) {
  try {
    const res  = await fetch(API + 'get_users.php', { headers:{'X-Requested-With':'XMLHttpRequest'}, cache:'no-store' });
    const text = await res.text();
    let users;
    try { users = JSON.parse(text); } catch { throw new Error('Respuesta no JSON:\n' + text); }

    if (!Array.isArray(users)) throw new Error('Respuesta inesperada del servidor');

    if (users.length === 0) { showRegister(); return; }

    userSelect.innerHTML = users.map(u => opt(u.id, u.username)).join('');
    if (preselectId) userSelect.value = String(preselectId);
    showSearch();
  } catch (err) {
    showRegister();
    regMsg.className = 'msg err';
    regMsg.textContent = 'Error al cargar usuarios. Comprueba XAMPP/Apache/MySQL.';
    console.error(err);
  }
}

regForm.addEventListener('submit', async (e)=>{
  e.preventDefault();
  regMsg.textContent = ''; regMsg.className = 'msg';

  const fd = new FormData(regForm);
  try {
    const res  = await fetch(API + 'register_user.php', {
      method:'POST', body:fd,
      headers:{'X-Requested-With':'XMLHttpRequest'},
      cache:'no-store', credentials:'same-origin'
    });
    const text = await res.text();
    let data;
    try { data = JSON.parse(text); } catch { throw new Error('Respuesta no JSON:\n' + text); }
    if (!res.ok || data.success === false) throw new Error(data.error || 'No se pudo registrar');

    regMsg.className = 'msg ok';
    regMsg.textContent = 'Usuario registrado correctamente. Entrando...';

    await loadUsers(data.user?.id ?? data.user_id ?? null); // preselecciona el nuevo
  } catch (err) {
    regMsg.className = 'msg err';
    regMsg.textContent = 'Error: ' + err.message;
    console.error(err);
  }
});

btnBuscar.addEventListener('click', async ()=>{
  searchMsg.textContent = ''; searchMsg.className = 'msg';
  const artista = artistaInput.value.trim();
  const uid     = userSelect.value;

  if (!artista) {
    searchMsg.className = 'msg err';
    searchMsg.textContent = 'Introduce un nombre de artista.';
    return;
  }

  btnBuscar.disabled = true;
  searchMsg.textContent = 'Procesando...';
  try {
    const res  = await fetch(`${API}buscar_artista.php?user_id=${encodeURIComponent(uid)}&nombre=${encodeURIComponent(artista)}`, {
      headers:{'X-Requested-With':'XMLHttpRequest'}, cache:'no-store'
    });
    const text = await res.text();
    let data;
    try { data = JSON.parse(text); } catch { throw new Error('Respuesta no JSON:\n' + text); }
    if (data.status !== 'ok') throw new Error(data.mensaje || 'Error en la consulta');

    searchMsg.className = 'msg ok';
    searchMsg.textContent = data.mensaje || 'Operación realizada.';
  } catch (err) {
    searchMsg.className = 'msg err';
    searchMsg.textContent = 'Error al buscar el artista.';
    console.error(err);
  } finally {
    btnBuscar.disabled = false;
  }
});

// carga inicial
loadUsers();
</script>
</body>
</html>
