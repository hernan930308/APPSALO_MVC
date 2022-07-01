<h1 class="nombre-pagina">Acutalizar Servicios</h1>
<p class="descripcion-pagina">Modifica los servicios</p>

<?php
include_once __DIR__ . '/../templates/barra.php';
include_once __DIR__ . '/../templates/alertas.php';
?>

<form method="POST" class="formlulario">
    <?php include_once __DIR__ . '/formulario.php'; ?>
    <input type="submit" class="boton" value="Actualizar Servicio">
</form>