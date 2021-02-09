<?php
$usuario = $_GET["usuario"];
$pass = $_GET["pass"];

//Conectamos con base de datos y comparamos el usuario
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "biblioteca";

//Creamos conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);
//Comprobamos la conexión
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

//Preparamos la query
$sql = $conn->query("SELECT * FROM usuario WHERE Usuario = '$usuario'");
$result = mysqli_fetch_array($sql,MYSQLI_ASSOC);
$hash = $result["Contrasena"];

/*
 * Comprobamos los datos, si el usuario es el Bibliotecario iniciaremos session y nos redirigira a
 * 'bienvenidaBibliotecario', en el siguiente nivel si la query funciona pillaremos el usuario y
 * la id del usuario.
 */
if (password_verify($pass, $hash)) {
    if($usuario == "bibliotecario"){
        session_start();
        $_SESSION['usuario'] = $usuario;
        //header('Location: bienvenidaBibliotecario.php');
        echo "Bibliotecario";
    }else{
        session_start();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['idUsuario'] = $result['Codigo_Usuario'];
        //echo "<script type='text/javascript'>window.locationf='bienvenidaUsuario.php';</script>";
        echo "Usuario";
    }
}else{
    echo utf8_encode("Se ha equivocado de Usuario y Contraseña");
}

mysqli_close($conn);