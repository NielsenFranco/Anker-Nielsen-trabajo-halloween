<section id="disfraces-list" class="seccion-disfraces">
    <?php
    $query = "SELECT iddisfraz, nombre, descripcion, foto FROM disfraces";
    $result = $con->query($query);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="disfraz" data-iddisfraz="' . $row['iddisfraz'] . '">';
            echo '<h2>' . htmlspecialchars($row['nombre']) . '</h2>';
            echo '<h3>' . htmlspecialchars($row['descripcion']) . '</h3>';

            $fotoBase64 = base64_encode($row['foto']);
            echo '<p><img src="data:image/jpeg;base64,' . $fotoBase64 . '" width="100%"></p>';

            // Mostrar botón de votar y el conteo de votos
            if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
                echo '<button class="votar" data-votar="true">Votar</button>';
            } else {
                echo '<button class="votar" data-votar="false">Votar</button>';
            }

            // Elemento para mostrar el conteo de votos
            echo '<p class="conteo-votos">Votos: 0</p>';
            echo '</div>';
        }
    }
    ?>
</section>
