document.addEventListener('DOMContentLoaded', function () {
    const sections = document.querySelectorAll('section'); 
    const navLinks = document.querySelectorAll('nav ul li a');

    navLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const targetId = link.getAttribute('href').substring(1);
            sections.forEach(section => section.style.display = 'none');
            const targetSection = document.getElementById(targetId);
            targetSection.style.display = 'block';

            if (targetId === 'disfraces-list') {
                setTimeout(function() {
                    const seccionDisfraces = document.getElementById('disfraces-list');
                    seccionDisfraces.style.display = 'grid';
                    seccionDisfraces.style.gridTemplateColumns = 'repeat(3, 1fr)';
                }, 100);
            }
        });
    });

    // Función para actualizar el conteo de votos de un disfraz específico
    function actualizarConteoVotos(idDisfraz) {
        fetch(`../php/obtener_votos.php?iddisfraz=${idDisfraz}`)
            .then(response => response.json())
            .then(data => {
                const conteoElement = document.querySelector(`.disfraz[data-iddisfraz="${idDisfraz}"] .conteo-votos`);
                conteoElement.textContent = `Votos: ${data.votos}`;
            })
            .catch(error => console.error("Error al actualizar conteo de votos:", error));
    }

    const votarButtons = document.querySelectorAll(".votar");

    votarButtons.forEach((button) => {
        button.addEventListener("click", (event) => {
            const canVote = button.getAttribute("data-votar") === "true";
            const idDisfraz = button.closest('.disfraz').getAttribute('data-iddisfraz');

            if (canVote) {
                const data = { iddisfraz: idDisfraz };

                fetch('/_desafio_halloween_/php/votacion.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams(data),
                })
                .then(response => response.json())
                .then(responseData => {
                    alert(responseData.message);
                    if (responseData.success) {
                        actualizarConteoVotos(idDisfraz); // Actualizar el conteo de votos
                    }
                })
                .catch(error => {
                    alert("Hubo un error al procesar tu voto.");
                    console.error("Error:", error);
                });
            } else {
                alert("Inicia sesión como Visitante para poder votar");
            }
        });
    });
});
