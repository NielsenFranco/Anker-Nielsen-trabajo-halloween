<?php

?>
<section id="disfraces-list" class="seccion-disfraces">

    <div class="disfraz">
            <h2>Krampus</h2>
            <h3>Disfraz de Krampus, demonio de la navidad</h3>
            <p><img src="imagenes/krampus.jpg" width="100%"></p>
            <?php
            // Verificar si se ha iniciado sesión y si el idusuario es 2
            if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
                echo '<button class="votar" data-votar="true">Votar</button>';
            } else {
                echo '<button class="votar" data-votar="false">Votar</button>';
            }
            ?>
        </div>

        <div class="disfraz">
            <h2>Chucky</h2>
            <h3>Disfraz de Chucky</h3>
            <p><img src="imagenes/chucky.jpg" width="100%"></p>
            <?php
            if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
                echo '<button class="votar" data-votar="true">Votar</button>';
            } else {
                echo '<button class="votar" data-votar="false">Votar</button>';
            }
            ?>
        </div>

        <div class="disfraz">
            <h2>Demon Angel</h2>
            <h3>Disfraz de Angel demoníaco</h3>
            <p><img src="imagenes/angel-demonio.jpg" width="100%"></p>
            <?php
            if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
                echo '<button class="votar" data-votar="true">Votar</button>';
            } else {
                echo '<button class="votar" data-votar="false">Votar</button>';
            }
            ?>
        </div>

        <div class="disfraz">
            <h2>Art el Payaso</h2>
            <h3>Disfraz de Art el payaso de la película Terrifier</h3>
            <p><img src="imagenes/payaso.jpg" width="100%"></p>
            <?php
            if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
                echo '<button class="votar" data-votar="true">Votar</button>';
            } else {
                echo '<button class="votar" data-votar="false">Votar</button>';
            }
            ?>
        </div>
        <?php
        // Consulta para obtener los disfraces de la base de datos
        $query = "SELECT iddisfraz, nombre, descripcion, foto FROM disfraces";
        $result = $con->query($query);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="disfraz">';
                echo '<h3>' . htmlspecialchars($row['nombre']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['descripcion']) . '</p>';
                echo '<img src="' . htmlspecialchars($row['foto']) . '" width="100%">';
            }
            if (isset($_SESSION['idusuario']) && $_SESSION['idrol'] == 2) {
                echo '<button class="votar" data-votar="true">Votar</button>';
            } else {
                echo '<button class="votar" data-votar="false">Votar</button>';
            }
        }
        
        ?>
        
</section>