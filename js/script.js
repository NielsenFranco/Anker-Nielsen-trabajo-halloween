// Lógica para mostrar y ocultar las secciones al hacer clic en los enlaces de navegación
document.addEventListener('DOMContentLoaded', function () {
    const sections = document.querySelectorAll('section'); // Selecciona todas las secciones
    const navLinks = document.querySelectorAll('nav ul li a'); // Selecciona todos los enlaces de navegación

    navLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // Evita el comportamiento predeterminado del enlace
            const targetId = link.getAttribute('href').substring(1); // Obtiene el ID de la sección destino
            sections.forEach(section => section.style.display = 'none'); // Oculta todas las secciones
            document.getElementById(targetId).style.display = 'block'; // Muestra la sección correspondiente
        });
    });

    // Lógica para manejar los botones de votación
    const votarButtons = document.querySelectorAll(".votar");

    votarButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            // Comprueba si el botón tiene el atributo `data-votar` para determinar si se puede votar
            const canVote = button.getAttribute("data-votar") === "true";

            if (canVote) {
                alert("¡Gracias por tu voto!");
                // Aquí puedes agregar la lógica para enviar el voto al servidor utilizando AJAX.
            } else {
                alert("Inicia sesión como Visitante para poder votar");
            }
        });
    });
});
