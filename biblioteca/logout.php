<?php
//Iniciamos sesi�n, la cerramos y nos envia a la p�gina de login
session_start();
session_destroy();
header('Location: index.php');