<!DOCTYPE html>
<!-- Iniciamos la conexión a base de datos -->
<?php 
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
?>
 <html>
   <head>
      <meta http-equiv=”Content-Type” content=”text/html; charset=UTF-8″ />
      <title>RS</title>
      <link rel="stylesheet" href="general0.css">
   </head>
   <body>
       <header>
       <img src="imagenes/logo.PNG">
       <?php
        session_start();
        
        //Si se ha creado la sessión se creara la bienvenida
        if (isset($_SESSION['usuario'])) {
            echo "<div class='nombreUsuario'>";
            echo "<p>Bienvenid@ " . $_SESSION['usuario'] . "</p>";
            echo "<form method='POST' action='modificarPerfil.php'>";
            echo "<input type='submit' value='Cerrar sesión' name='btnCerrar'>";
            echo "</form>";
            echo "</div>";
            
            //Cuando pulsemos el botón cerrar nos enviara al php de logout
            if(isset($_POST["btnCerrar"])){
                header('Location: logout.php');
            }
        //Si no se introducen los datos y no se inicia la sesión nos enviara a la página principal de login
        }else{
            header('Location: index.php');
        }
        ?>
       </header>
       <div>
           <!-- Creamos el formulario de modificacion de los datos personales -->
           <div id="formularioPersonal">
               <h3>Datos personales</h3>
               <?php 
               //Recogemos la id del usuario por session para utilizarla a continuación en la query
               $codigoUsuario = $_SESSION['idUsuario'];
               
               //Utilizamos esta query para mostrar los datos que el usuario ya tiene en el formulario, haciendo asi mas sencilla la modificación
               $sql = "SELECT * FROM usuario WHERE Codigo_Usuario = $codigoUsuario";
               
               if ($datos = mysqli_query($conn, $sql)) {
                   $datosUsuario = mysqli_fetch_array($datos);
                   
                   echo "<form action='modificarPerfil.php' method='POST'>";
                   echo "<p>Nombre: <input type='text' name='nombreM' value='".$datosUsuario['Nombre']."'></p>";
                   echo "<p>Apellidos: <input type='text' name='apellidosM' value='".$datosUsuario['Apellidos']."'></p>";
                   echo "<p>Fecha Nacimiento: <input type='date' name='nacimientoM' value='".$datosUsuario['Fecha_Nacimiento']."'></p>";
                   echo "<p>Email: <input type='text' name='emailM' value='".$datosUsuario['Email']."'></p>";
                   echo "<p>Dirección: <input type='text' name='direccionM' value='".$datosUsuario['Direccion']."'></p>";
                   echo "<p>Población: <input type='text' name='poblacionM' value='".$datosUsuario['Poblacion']."'></p>";
                   echo "<p>C.Postal: <input type='text' name='postalM' value='".$datosUsuario['Cod_Postal']."'></p>";
                   echo "<p>Usuario: <input type='text' name='usuario' value='".$datosUsuario['Usuario']."'></p>";
                   echo "<input type='submit' name='btnGuardarPersonal' value='Guardar' style='background-color:#1166ea;'>";
                   echo "</form><br>";
               }
               
               if (isset($_POST["btnGuardarPersonal"])) {
                   //Recogemos los datos del formulario personal
                   $nombre = $_POST["nombreM"];
                   $apellidos = $_POST["apellidosM"];
                   $fechaNacimiento = $_POST["nacimientoM"];
                   $email = $_POST["emailM"];
                   $direccion = $_POST["direccionM"];
                   $poblacion = $_POST["poblacionM"];
                   $cPostal = $_POST["postalM"];
                   $usuario = $_POST["usuario"];
                   
                   //Creamos query en la que modificamos los datos
                   $modificar = "UPDATE usuario SET Nombre='$nombre', Apellidos='$apellidos', Fecha_Nacimiento='$fechaNacimiento',
                             Email='$email', Direccion='$direccion', Poblacion='$poblacion', Cod_Postal='$cPostal', Usuario='$usuario'
                             WHERE Codigo_Usuario = $codigoUsuario";
                   
                   if (mysqli_query($conn, $modificar)) {
                       header("Refresh:0; url=modificarPerfil.php");
                       echo '<script language="javascript">alert("Datos nodificados con éxito")</script>';
                   }else{
                       echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
                   }
               }
               
               ?>
           </div>
           <!-- Creamos el formulario de modificacion de la contraseña -->
           <div id="formularioPass">
               <h3>Cambiar contraseña</h3>
               <form action='modificarPerfil.php' method='POST'>
               <p>Contraseña Actual: <input type='password' name='passActual'></p>
               <p>Nueva contraseña: <input type='password' name='passNueva'></p>
               <p>Repetir contraseña nueva: <input type='password' name='passNueva2'></p>
               <input type="submit" value="Guardar" name="btnGuardarPass" style="background-color:#1166ea;">
               </form><br>
               <?php 
               if (isset($_POST["btnGuardarPass"])) {
                   //Recogemos los datos del formulario
                   $passActual = $_POST["passActual"];
                   $passNueva = $_POST["passNueva"];
                   $passNueva2 = $_POST["passNueva2"];
                   $hashNueva = password_hash($passNueva, PASSWORD_DEFAULT);
                   
                   //Preparamos la query con la que comparar la contraseña
                   $sql = $conn->query("SELECT * FROM usuario WHERE Codigo_Usuario = $codigoUsuario");
                   $result = mysqli_fetch_array($sql,MYSQLI_ASSOC);
                   $hash = $result['Contrasena'];
                   
                   //Query de modificación de la contraseña
                   $modificarPass = "UPDATE usuario SET Contrasena='$hashNueva' WHERE Codigo_Usuario = $codigoUsuario";
                   
                   /*
                    * Comparamos si la contraseña actual que se introduce es la misma que la de la base de datos,
                    * si es correcta procederemos a cambiar la contraseña, evidentemente también la codificaremos.
                    */
                   if (password_verify($passActual, $hash)) {
                       //Si no se introducen contraseñas nuevas saltara el siguiente mensaje
                       if ($passNueva == "" && $passNueva2 == "") {
                           echo '<script language="javascript">alert("Debes introducir una nueva contraseña")</script>';
                        //Comprobamos que las dos nuevas contraseñas sean iguales
                       }else if ($passNueva == $passNueva2) {
                               //Ejecutamos la query
                               if (mysqli_query($conn, $modificarPass)) {
                                   header("Refresh:0; url=index.php");
                                   echo '<script language="javascript">alert("Contraseña modificada con éxito")</script>';
                               }else{
                                   echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
                               }
                       //Si las contraseñas no coinciden mostrara el siguiente mensaje
                       } else{
                           echo '<script language="javascript">alert("Las contraseñas nuevas no coinciden")</script>';
                       }
                    //Si la contraseña actual no es correcta mostrara el siguiente mensaje
                   }else{
                       echo '<script language="javascript">alert("La contraseña introducida es incorrecta")</script>';
                   }
               }
               
               ?>
           </div>
               <form action='modificarPerfil.php' method='POST' id="volverPerfil">
               <input type="submit" value="Volver" name="btnVolver">
               </form>
       </div>
       <!-- Si pulsamos el botón volver volveremos a la página anterior -->
       <?php 
           if (isset($_POST["btnVolver"])) {
               header("Location: bienvenidaUsuario.php");
           }
       ?>
       <!-- Cerramos la conexión con la base de datos -->
       <?php 
           mysqli_close($conn);
       ?>
       <footer>
       		<p>Propiedad de Melania Gallego</p>
       </footer>
   </body>
 </html>