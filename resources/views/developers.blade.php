<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Laravel/Retrofit Developers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100 p-8">

    <nav class="bg-cyan-700 text-white p-4 rounded flex justify-between mb-6 shadow">
        <div class="font-semibold text-lg">Panel CRUD</div>
        <div class="space-x-6">
            <a href="/games" class="hover:underline">Juegos</a>
            <a href="/developers" class="underline font-bold">Desarrolladoras</a>
        </div>
    </nav>

    <h1 class="text-2xl font-semibold mb-6">Desarrolladoras</h1>

    <div class="flex gap-3">
        <button onclick="openCreateDialog()" class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded">
            Crear Desarrolladora
        </button>

        <button onclick="loadDevelopers()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
            <i class="fa-solid fa-rotate-right mr-2"></i>Recargar
        </button>
    </div>

    <table class="min-w-full mt-6 bg-white shadow rounded-lg overflow-hidden">
        <thead class="bg-cyan-600 text-white">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Nombre</th>
                <th class="p-3 text-left">Pais</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>

        <tbody id="developersTable"></tbody>
    </table>

    <div id="dialog" class="hidden fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-96 shadow-xl animate-fadeIn">
            <h2 id="dialogTitle" class="text-xl font-semibold mb-4"></h2>

            <input type="hidden" id="developerId">

            <label class="block mb-2">Nombre:</label>
            <input id="developerName" class="w-full border p-2 rounded mb-4">

            <label class="block mb-2">Pais:</label>
            <input id="developerCountry" class="w-full border p-2 rounded mb-4">

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

function loadDevelopers() {
    fetch(`${API}/developers`)
        .then(r => r.json())
        .then(data => {
            const table = document.getElementById("developersTable");
            table.innerHTML = "";

            data.forEach(d => {
                table.innerHTML += `
                    <tr class="border-b">
                        <td class="p-3">${d.id}</td>
                        <td class="p-3">${d.name}</td>
                        <td class="p-3">${d.country}</td>
                        <td class="p-3 space-x-3">

                            <button onclick="openEditDialog(${d.id}, '${d.name}', '${d.country}')"
                                class="text-blue-600 text-lg">
                                <i class="fa-solid fa-pencil"></i>
                            </button>

                            <button onclick="deleteDeveloper(${d.id})"
                                class="text-red-600 text-lg">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                        </td>
                    </tr>
                `;
            });
        })
        .catch(() => toast("Error cargando desarrolladoras", "red"));
}

function openCreateDialog() {
    document.getElementById("dialogTitle").textContent = "Crear Desarrolladora";
    document.getElementById("dialogSubmit").textContent = "Guardar";

    document.getElementById("developerId").value = "";
    document.getElementById("developerName").value = "";
    document.getElementById("developerCountry").value = "";

    document.getElementById("dialogSubmit").onclick = createDeveloper;

    document.getElementById("dialog").classList.remove("hidden");
}

function openEditDialog(id, name, country) {
    document.getElementById("dialogTitle").textContent = "Editar Desarrolladora";
    document.getElementById("dialogSubmit").textContent = "Actualizar";

    document.getElementById("developerId").value = id;
    document.getElementById("developerName").value = name;
    document.getElementById("developerCountry").value = country;

    document.getElementById("dialogSubmit").onclick = updateDeveloper;

    document.getElementById("dialog").classList.remove("hidden");
}

function closeDialog() {
    document.getElementById("dialog").classList.add("hidden");
}

function createDeveloper() {
    const name = document.getElementById("developerName").value;
    const country = document.getElementById("developerCountry").value;

    fetch(`${API}/developers`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, country })
    })
    .then(() => {
        closeDialog();
        toast("Desarrolladora creada");
        loadDevelopers();
    })
    .catch(() => toast("Error al crear", "red"));
}

function updateDeveloper() {
    const id = document.getElementById("developerId").value;
    const name = document.getElementById("developerName").value;
    const country = document.getElementById("developerCountry").value;

    fetch(`${API}/developers/${id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, country })
    })
    .then(() => {
        closeDialog();
        toast("Desarrolladora actualizada", "#22c55e");
        loadDevelopers();
    })
    .catch(() => toast("Error al actualizar", "red"));
}

function deleteDeveloper(id) {
    if (!confirm("¿Eliminar esta desarrolladora?")) return;

    fetch(`${API}/developers/${id}`, { method: "DELETE" })
        .then(() => {
            toast("Desarrolladora eliminada", "orange");
            loadDevelopers();
        })
        .catch(() => toast("Error al eliminar", "red"));
}
</script>

</body>
</html>
