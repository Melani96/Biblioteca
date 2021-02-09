<?php
        // Recogemos los datos que se introduciran
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $fechaNacimiento = $_POST['fechaNacimiento'];
        $email = $_POST['email'];
        $direccion = $_POST['direccion'];
        $poblacion = $_POST['poblacion'];
        $cPostal = $_POST['cPostal'];
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['pass'];
        $contrasena2 = $_POST['pass2'];
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        if ($nombre == "" && $apellidos == "" && $fechaNacimiento == "" && $email == "" && $direccion == "" && $poblacion == "" && $cPostal == "" && $usuario == "" && $contrasena == "" && $contrasena2 == "") {
            echo "Introduce todos los campos";
        } else if ($nombre == "" || $apellidos == "" || $fechaNacimiento == "" || $email == "" || $direccion == "" || $poblacion == "" || $cPostal == "" || $usuario == "" || $contrasena == "" || $contrasena2 == "") {
            echo "Introduce los campos que faltan";
        } else {
            // Datos de conexión a bd
            $servername = "localhost";
            $username = "root";
            $pass = "";
            $db = "biblioteca";

            // Creamos sesión
            $conn = mysqli_connect($servername, $username, $pass, $db);
            // Comprobamos si la conexión es correcta
            if (! $conn) {
                die("Conexión fallida: " . mysqli_connect_error());
            }

            // Preparamos la query con la que vamos a introducir los datos en la base de datos
            $sql = "INSERT INTO usuario (Nombre, Apellidos, Fecha_Nacimiento, Email, Direccion, Poblacion, Cod_Postal, Usuario, Contrasena)
                    VALUES ('$nombre', '$apellidos', '$fechaNacimiento', '$email', '$direccion', '$poblacion', '$cPostal', '$usuario', '$hash')";

            /*
             * Si la query funciona correctamente nos mostrara un mensaje de que ha ido bien,
             * si no es asi nos mostrara un mensaje con un error
             */
            if ($contrasena == $contrasena2) {
                if (mysqli_query($conn, $sql)) {
                    header("Location: index.php");
                } else {
                    echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
                }
            }

            // Cerramos la conexión
            mysqli_close($conn);
    }