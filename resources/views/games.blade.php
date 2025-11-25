<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Laravel/Retrofit Games</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 p-8">
    <nav class="bg-cyan-700 text-white p-4 rounded flex justify-between mb-6 shadow">
        <div class="font-semibold text-lg">Panel CRUD</div>
        <div class="space-x-6">
            <a href="/games" class="underline font-bold">Juegos</a>
            <a href="/developers" class="hover:underline">Desarrolladoras</a>
        </div>
    </nav>

    <h1 class="text-2xl font-semibold mb-6">Juegos</h1>

    <div class="flex gap-3">
        <button onclick="openCreateDialog()" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded">
            Crear Juego
        </button>

        <button onclick="loadGames()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            <i class="fa-solid fa-rotate-right mr-2"></i>Recargar
        </button>
    </div>

    <table class="min-w-full mt-6 bg-white shadow rounded-lg overflow-hidden">
        <thead class="bg-cyan-600 text-white">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Titulo</th>
                <th class="p-3 text-left">Descripcion</th>
                <th class="p-3 text-left">Año</th>
                <th class="p-3 text-left">Desarrolladora</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>

        <tbody id="gamesTable"></tbody>
    </table>

    <div id="dialog" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-96 shadow-xl animate-fadeIn">
            <h2 id="dialogTitle" class="text-xl font-semibold mb-4"></h2>

            <input type="hidden" id="gameId">

            <label class="block mb-2">Titulo:</label>
            <input id="gameTitle" class="w-full border p-2 rounded mb-4">

            <label class="block mb-2">Descripcion:</label>
            <input id="gameDescription" class="w-full border p-2 rounded mb-4">

            <label class="block mb-2">Año:</label>
            <input id="gameYear" class="w-full border p-2 rounded mb-4" type="number">

            <label class="block mb-2">Desarrolladora:</label>
            <select id="developerSelect" class="w-full border p-2 rounded mb-4"></select>

            <div class="flex justify-end gap-3">
                <button onclick="closeDialog()" class="px-3 py-2 rounded border">Cancelar</button>
                <button id="dialogSubmit" class="px-3 py-2 rounded bg-cyan-600 text-white"></button>
            </div>
        </div>
    </div>

<script>
const API = "http://localhost:8000/api/v1";

document.addEventListener("DOMContentLoaded", () => {
    loadDevelopers();
    loadGames();
});

function toast(msg, color = "#0ea5e9") {
    Toastify({
        text: msg,
        duration: 2000,
        gravity: "top",
        position: "right",
        style: { background: color }
    }).showToast();
}

function loadGames() {
    fetch(`${API}/games`)
        .then(r => r.json())
        .then(data => {
            console.log(data)
            const table = document.getElementById("gamesTable");
            table.innerHTML = "";

            data.forEach(g => {
                table.innerHTML += `
                    <tr class="border-b">
                        <td class="p-3">${g.id}</td>
                        <td class="p-3">${g.title}</td>
                        <td class="p-3">${g.description}</td>
                        <td class="p-3">${g.year}</td>
                        <td class="p-3">${g.developer.name}</td>
                        <td class="p-3 space-x-3">

                            <button onclick="openEditDialog(${g.id}, '${g.title}', '${g.description}', ${g.year}, ${g.developer_id})"
                                class="text-blue-600 text-lg">
                                <i class="fa-solid fa-pencil"></i>
                            </button>

                            <button onclick="deleteGame(${g.id})"
                                class="text-red-600 text-lg">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                        </td>
                    </tr>
                `;
            });
        })
        .catch(() => toast("Error cargando juegos", "red"));
}

function loadDevelopers() {
    fetch(`${API}/developers`)
        .then(r => r.json())
        .then(data => {
            let select = document.getElementById("developerSelect");
            select.innerHTML = "";
            data.forEach(d => {
                select.innerHTML += `<option value="${d.id}">${d.name}</option>`;
            });
        });
}

function openCreateDialog() {
    document.getElementById("dialogTitle").textContent = "Crear Juego";
    document.getElementById("dialogSubmit").textContent = "Guardar";
    document.getElementById("gameId").value = "";
    document.getElementById("gameTitle").value = "";
    document.getElementById("gameDescription").value = "";
    document.getElementById("gameYear").value = "";
    document.getElementById("dialogSubmit").onclick = createGame;
    document.getElementById("dialog").classList.remove("hidden");
}

function openEditDialog(id, title, description, year, developerId) {
    document.getElementById("dialogTitle").textContent = "Editar Juego";
    document.getElementById("dialogSubmit").textContent = "Actualizar";

    document.getElementById("gameId").value = id;
    document.getElementById("gameTitle").value = title;
    document.getElementById("gameDescription").value = description;
    document.getElementById("gameYear").value = year;
    document.getElementById("developerSelect").value = developerId;

    document.getElementById("dialogSubmit").onclick = updateGame;

    document.getElementById("dialog").classList.remove("hidden");
}

function closeDialog() {
    document.getElementById("dialog").classList.add("hidden");
}

function createGame() {
    const title = document.getElementById("gameTitle").value;
    const description = document.getElementById("gameDescription").value;
    const dev = document.getElementById("developerSelect").value;
    const year = document.getElementById("gameYear").value;

    fetch(`${API}/games`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ title, description, year, developer_id: dev })
    })
    .then(() => {
        closeDialog();
        toast("Juego creado");
        loadGames();
    })
    .catch(() => toast("Error al crear", "red"));
}

function updateGame() {
    const id = document.getElementById("gameId").value;
    const title = document.getElementById("gameTitle").value;
    const description = document.getElementById("gameDescription").value;
    const year = document.getElementById("gameYear").value;
    const dev = document.getElementById("developerSelect").value;

    fetch(`${API}/games/${id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ title, description, year, developer_id: dev })
    })
    .then(() => {
        closeDialog();
        toast("Juego actualizado", "#22c55e");
        loadGames();
    })
    .catch(() => toast("Error al actualizar", "red"));
}

function deleteGame(id) {
    if (!confirm("¿Eliminar este juego?")) return;

    fetch(`${API}/games/${id}`, { method: "DELETE" })
        .then(() => {
            toast("Juego eliminado", "orange");
            loadGames();
        })
        .catch(() => toast("Error al eliminar", "red"));
}
</script>
</body>
</html>
