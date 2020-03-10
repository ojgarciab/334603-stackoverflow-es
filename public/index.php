<?php
/* Si aún no hemos configurado el proyecto, cargamos la pantalla de configuración */
if (file_exists('../client_secret.json') === false) {
    header('Location: configuracion.html');
    die();
}
