console.log("JS loaded.");

// BUSCAR ARCHIVO
document.getElementById("buscar-archivo-inp").addEventListener("keyup", function () {
    let val = this.value.toLowerCase();
    let filas = document.querySelectorAll("table tbody tr");
    let match = 0;

    filas.forEach(f => {
        if (f.innerText.toLowerCase().includes(val)) {
            f.style.display = "table-row";
            match++;
        } else {
            f.style.display = "none";
        }
    })

    // Mostrar aviso en caso de no haber coincidencias
    if (match == 0) {
        let aviso = document.getElementById("aviso-usuario");
        aviso.style.display = "block";
    } else {
        document.getElementById("aviso-usuario").style.display = "none";
    };
})

// LIMPIAR FILTRO DE BUSQUEDA
document.getElementById("limpiar-filtro-btn").addEventListener("click", function () {
    document.getElementById("aviso-usuario").style.display = "none";
    document.getElementById("buscar-archivo-inp").value = "";
    document.querySelectorAll("table tbody tr").forEach(f => {
        f.style.display = "table-row";
    })
})

// OCULTAR MENU LATERAL EN DISPOSITIVOS MENORES A 768px DE ANCHO

let anchoDisplay = window.innerWidth;
if (anchoDisplay < 770) {
    console.log("Ancho de display menor al indicado.");
    document.getElementById("menu-lateral-desktop").style.display = "none";
    document.getElementById("menu-lateral-moviles").style.display = "block";
} else {
    document.getElementById("menu-lateral-desktop").style.display = "block";
    document.getElementById("menu-lateral-moviles").style.display = "none";
}