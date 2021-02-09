<?php
//Iniciamos sesión, la cerramos y nos envia a la página de login
session_start();
session_destroy();
header('Location: index.php');