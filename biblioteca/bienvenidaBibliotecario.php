 <!DOCTYPE html>
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
            echo "<form method='POST' action='bienvenidaBibliotecario.php'>";
            echo "<input type=submit value='Cerrar sesión' name='btnCerrar'>";
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
       		<form action="bienvenidaBibliotecario.php" method="POST" id="bibliotecarioSeleccion">
       			<h3>Gestiona uno de estos tres elementos</h3>
       			<input type="submit" value="Gestión Usuarios" name="btnGestionUsuario">
       			<input type="submit" value="Gestión Libros" name="btnGestionLibros">
       			<input type="submit" value="Gestión Prestados" name="btnGestionPrestados">
       		</form>
       		<form action="anadirLibro.php" method="POST" style="float: right; margin-top: -2%; margin-right: 6%;">
       			<input type="submit" value="Añadir libro" name="btnAnadirLibro">
       		</form>
       </div>
       <div id="datos">
       <!-- Mostramos los datos al pulsar el bóton correspondiente -->
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
       
       /*
        * Botón gestión de usuarios
        */
       if (isset($_POST["btnGestionUsuario"])) {
           $datosUsuarios = mysqli_query($conn, "SELECT * FROM usuario");
           $usuarioFilas = mysqli_num_rows($datosUsuarios);
           
           //Mostramos la tabla con los datos del usuario y la opción de modificar o eliminar
           if ($usuarioFilas > 0) {
               echo "<table id='tablaDatosUsuario'>";
               echo "<tr><th>Nombre</th><th>Apellidos</th><th>Fecha Nacimiento</th><th>Email</th><th>Dirección</th><th>Población</th><th>Código Postal</th><th>Usuario</th><th>Modificar</th><th>Eliminar</th></tr>";
               for ($i = 0; $i < $usuarioFilas; $i++) {
                   $filaUsuario = mysqli_fetch_array($datosUsuarios);
                   echo "<tr>";
                   echo "<td>".$filaUsuario["Nombre"]."</td>";
                   echo "<td>".$filaUsuario["Apellidos"]."</td>";
                   echo "<td>".$filaUsuario["Fecha_Nacimiento"]."</td>";
                   echo "<td>".$filaUsuario["Email"]."</td>";
                   echo "<td>".$filaUsuario["Direccion"]."</td>";
                   echo "<td>".$filaUsuario["Poblacion"]."</td>";
                   echo "<td>".$filaUsuario["Cod_Postal"]."</td>";
                   echo "<td>".$filaUsuario["Usuario"]."</td>";
                   echo "<form action='bienvenidaBibliotecario.php' method='GET'>";
                   echo "<input type='hidden' name='codUsuario' value='".$filaUsuario["Codigo_Usuario"]."'>";
                   echo "<td><input type='submit' name='btnModificarUsuario' value='Modificar' style='background-color:#1166ea;'></td>";
                   echo "<td><input type='submit' name='btnEliminarUsuario' value='Eliminar' style='background-color:#ea3711;'></td>";
                   echo "</form>";
                   echo "</tr>";
               }
               echo "</table>";
           }
           
       }
       
       /*
        * Botón gestión de libros
        */
       if (isset($_POST["btnGestionLibros"])) {
           $datosLibros = mysqli_query($conn, "SELECT * FROM libro");
           $librosFilas = mysqli_num_rows($datosLibros);
           
           //Mostramos la tabla con los datos del libro y la opción de modificar o eliminar
           if ($librosFilas > 0) {
               echo "<table id='tablaDatosUsuario'>";
               echo "<tr><th></th><th>Titulo</th><th>Autor</th><th>Género</th><th>Publicación</th><th>Editorial</th><th>Sinopsis</th><th>Modificar</th><th>Eliminar</th></tr>";
               for ($i = 0; $i < $librosFilas; $i++) {
                   $filaLibros = mysqli_fetch_array($datosLibros);
                   echo "<tr>";
                   echo "<td><img id='portadaBibliotecario' src='data:image/jpeg;base64,".base64_encode($filaLibros['Portada'])."'/></td>";
                   echo "<td>".$filaLibros["Titulo"]."</td>";
                   echo "<td>".$filaLibros["Autor"]."</td>";
                   echo "<td>".$filaLibros["Genero"]."</td>";
                   echo "<td>".$filaLibros["Fecha_Publicado"]."</td>";
                   echo "<td>".$filaLibros["Editorial"]."</td>";
                   echo "<td>".$filaLibros["Sinopsis"]."</td>";
                   echo "<form action='bienvenidaBibliotecario.php' method='GET'>";
                   echo "<input type='hidden' name='codLibro' value='".$filaLibros["Codigo_Libro"]."'>";
                   echo "<td><input type='submit' name='btnModificarLibros' value='Modificar' style='background-color:#1166ea;'></td>";
                   echo "<td><input type='submit' name='btnEliminarLibros' value='Eliminar' style='background-color:#ea3711;'></td>";
                   echo "</form>";
                   echo "</tr>";
               }
               echo "</table>";
           }
       }
       
       /*
        * Botón gestión de libros prestados
        */
       if (isset($_POST["btnGestionPrestados"])) {
           $datosPrestados = mysqli_query($conn, "SELECT l.Titulo, u.Nombre, p.Fecha_Retirada, p.Fecha_Entrega, p.Codigo_Prestamo
                                                  FROM prestamo p, libro l, usuario u WHERE p.Codigo_Usuario = u.Codigo_Usuario 
                                                  AND p.Codigo_Libro = l.Codigo_Libro ");
           $prestadosFilas = mysqli_num_rows($datosPrestados);
           
           //Mostramos la tabla con los datos del prestamo y la opción de modificar o eliminar
           if ($prestadosFilas > 0) {
               echo "<table id='tablaDatosUsuario'>";
               echo "<tr><th>Titulo</th><th>Nombre Usuario</th><th>Fecha Retirada</th><th>Fecha Entrega</th><th>Modificar</th><th>Eliminar</th></tr>";
               for ($i = 0; $i < $prestadosFilas; $i++) {
                   $filaPrestados = mysqli_fetch_array($datosPrestados);
                   echo "<tr>";
                   echo "<td>".$filaPrestados["Titulo"]."</td>";
                   echo "<td>".$filaPrestados["Nombre"]."</td>";
                   echo "<td>".$filaPrestados["Fecha_Retirada"]."</td>";
                   echo "<td>".$filaPrestados["Fecha_Entrega"]."</td>";
                   echo "<form action='bienvenidaBibliotecario.php' method='GET'>";
                   echo "<input type='hidden' name='codPrestamo' value='".$filaPrestados["Codigo_Prestamo"]."'>";
                   echo "<td><input type='submit' name='btnModificarPrestamo' value='Modificar' style='background-color:#1166ea;'></td>";
                   echo "<td><input type='submit' name='btnEliminarPrestamo' value='Eliminar' style='background-color:#ea3711;'></td>";
                   echo "</form>";
                   echo "</tr>";
               }
               echo "</table>";
           }
       }
       
       //Cerramos la conexión
       mysqli_close($conn);
       ?>
       
       <!-- Formulario Modificaciones -->
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
       
       /*
        * Formulario datos del usuario y contraseña
        */
       if (isset($_GET["btnModificarUsuario"])) {
           $codUsuario = $_GET["codUsuario"];
           
           $modificarUsuario = mysqli_query($conn, "SELECT * FROM usuario WHERE Codigo_Usuario = '$codUsuario'");
           $mUsuario = mysqli_fetch_array($modificarUsuario);
           
           //Formulario datos
           echo "<form action='bienvenidaBibliotecario.php' method='POST' id='formularioMUsuario'>";
           echo "<h3>Datos a modificar</h3>";
           echo "<input type='hidden' name='codigoUsuario' value='".$mUsuario["Codigo_Usuario"]."'>";
           echo "<p>Nombre: <input type='text' name='nombre' value='".$mUsuario['Nombre']."'></p>";
           echo "<p>Apellidos: <input type='text' name='apellidos' value='".$mUsuario['Apellidos']."'></p>";
           echo "<p>Fecha Nacimiento: <input type='text' name='fNacimiento' value='".$mUsuario['Fecha_Nacimiento']."'></p>";
           echo "<p>Email: <input type='text' name='email' value='".$mUsuario['Email']."'></p>";
           echo "<p>Dirección: <input type='text' name='direccion' value='".$mUsuario['Direccion']."'></p>";
           echo "<p>Población: <input type='text' name='poblacion' value='".$mUsuario['Poblacion']."'></p>";
           echo "<p>Código Postal: <input type='number' name='cPostal' value='".$mUsuario['Cod_Postal']."'></p>";
           echo "<p>Usuario: <input type='text' name='usuario' value='".$mUsuario['Usuario']."'></p>";
           echo "<input type='submit' name='btnModificarUsuarioOK' value='Modificar' style='background-color:#1166ea;'>";
           echo "</form>";
           
           //Formulario contraseña
           echo "<form action='bienvenidaBibliotecario.php' method='POST' id='formularioMPass'>";
           echo "<h3>Modificar contraseña</h3>";
           echo "<input type='hidden' name='codigo_Usuario' value='".$mUsuario["Codigo_Usuario"]."'>";
           echo "<p>Nueva contraseña: <input type='password' name='passNueva'></p>";
           echo "<p>Repetir contraseña nueva: <input type='password' name='passNueva2'></p>";
           echo "<input type='submit' value='Guardar' name='btnGuardarPass' style='background-color:#1166ea;'>";
           echo "</form>";
       }
       
       /*
        * Formulario Libros
        */
       if (isset($_GET["btnModificarLibros"])) {
           //Recogemos la id del libro
           $idLibro = $_GET["codLibro"];
           
           $modificarLibro = mysqli_query($conn, "SELECT * FROM libro WHERE Codigo_Libro = '$idLibro'");
           $mLibro = mysqli_fetch_array($modificarLibro);
           
           //Formulario datos
           echo "<form action='bienvenidaBibliotecario.php' method='POST' id='formularioMLibro'>";
           echo "<h3>Datos a modificar</h3>";
           echo "<input type='hidden' name='codigoLibro' value='".$mLibro["Codigo_Libro"]."'>";
           echo "<p>Titulo: <input type='text' name='titulo' value='".$mLibro['Titulo']."'></p>";
           echo "<p>Autor: <input type='text' name='autor' value='".$mLibro['Autor']."'></p>";
           echo "<p>Género: <input type='text' name='genero' value='".$mLibro['Genero']."'></p>";
           echo "<p>Publicación: <input type='date' name='fPublicado' value='".$mLibro['Fecha_Publicado']."'></p>";
           echo "<p>Editorial: <input type='text' name='editorial' value='".$mLibro['Editorial']."'></p>";
           echo "<p>Sinopsis: <textarea rows='4' cols='40' name='sinopsis'>".$mLibro['Sinopsis']."</textarea></p>";
           echo "<input type='submit' name='btnModificarLibroOK' value='Modificar' style='background-color:#1166ea;'>";
           echo "</form>";
           
           //Formulario Portada
           echo "<form action='bienvenidaBibliotecario.php' method='POST' enctype='multipart/form-data' id='formularioMPortada'>";
           echo "<h3>Modificar portada</h3>";
           echo "<input type='hidden' name='codigo_Libro' value='".$mLibro["Codigo_Libro"]."'>";
           echo "<p>Portada: <input type='file' name='portadaL'></p>";
           echo "<input type='submit' name='btnModificarPortadaOK' value='Modificar' style='background-color:#1166ea;'>";
           echo "</form>";
       }
       
       /*
        * Formulario Préstamo
        */
       if (isset($_GET["btnModificarPrestamo"])) {
           //Recogemos la id del préstamo
           $idPrestamo = $_GET["codPrestamo"];
           
           $modificarPrestamo = mysqli_query($conn, "SELECT * FROM prestamo WHERE Codigo_Prestamo = '$idPrestamo'");
           $mPrestamo = mysqli_fetch_array($modificarPrestamo);
           
           //Formulario datos
           echo "<form action='bienvenidaBibliotecario.php' method='POST' id='formularioMPrestamo'>";
           echo "<h3>Datos a modificar</h3>";
           echo "<input type='hidden' name='codigoPrestamo' value='".$mPrestamo["Codigo_Prestamo"]."'>";
           echo "<p>Código Libro: <input type='text' name='libro' value='".$mPrestamo['Codigo_Libro']."'></p>";
           echo "<p>Código Usuario: <input type='text' name='usuario' value='".$mPrestamo['Codigo_Usuario']."'></p>";
           echo "<p>Fecha Retirada: <input type='date' name='fRetirada' value='".$mPrestamo['Fecha_Retirada']."'></p>";
           echo "<p>Fecha Entrega: <input type='date' name='fEntrega' value='".$mPrestamo['Fecha_Entrega']."'></p>";
           echo "<input type='submit' name='btnModificarPrestamoOK' value='Modificar' style='background-color:#1166ea;'>";
           echo "</form>";
       }
       
       mysqli_close($conn);
       ?>
       
       <!-- Modificación se lleva a cabo -->
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
       
       /*
        * Modificaciones usuario
        */
       if (isset($_POST['btnModificarUsuarioOK'])) {
           //Recogemos los datos del formulario personal
           $nombre = $_POST["nombre"];
           $apellidos = $_POST["apellidos"];
           $fechaNacimiento = $_POST["fNacimiento"];
           $email = $_POST["email"];
           $direccion = $_POST["direccion"];
           $poblacion = $_POST["poblacion"];
           $cPostal = $_POST["cPostal"];
           $usuario = $_POST["usuario"];
           $codigoUsuario = $_POST["codigoUsuario"];
           
           //Creamos query en la que modificamos los datos
           $modificar = "UPDATE usuario SET Nombre='$nombre', Apellidos='$apellidos', Fecha_Nacimiento='$fechaNacimiento',
                             Email='$email', Direccion='$direccion', Poblacion='$poblacion', Cod_Postal='$cPostal', Usuario='$usuario'
                             WHERE Codigo_Usuario = $codigoUsuario";
           
           if (mysqli_query($conn, $modificar)) {
               echo '<script language="javascript">alert("Datos nodificados con éxito")</script>';
           }else{
               echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
           }
       }
       
       /*
        * Modificación contraseña usuario
        */
       if (isset($_POST["btnGuardarPass"])) {
           //Recogemos los datos necesarios como el código del usuario y la contraseña que quiere introducir
           $codigo_Usuario = $_POST["codigo_Usuario"];
           $passNueva = $_POST["passNueva"];
           $passNueva2 = $_POST["passNueva2"];
           $hashNueva = password_hash($passNueva, PASSWORD_DEFAULT);
           
           //Query de modificación de la contraseña
           $modificarPass = "UPDATE usuario SET Contrasena='$hashNueva' WHERE Codigo_Usuario = $codigo_Usuario";
           
           if ($passNueva == "" && $passNueva2 == "") {
               echo '<script language="javascript">alert("Debes introducir una nueva contraseña")</script>';
           //Comprobamos que las dos nuevas contraseñas sean iguales
           }else if ($passNueva == $passNueva2) {
           //Ejecutamos la query
               if (mysqli_query($conn, $modificarPass)) {
                   echo '<script language="javascript">alert("Contraseña modificada con éxito")</script>';
               }else{
                   echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
               }
           //Si las contraseñas no coinciden mostrara el siguiente mensaje
           } else{
               echo '<script language="javascript">alert("Las contraseñas nuevas no coinciden")</script>';
           }
       }
       
       /*
        * Modificaciones libro
        */
       if (isset($_POST["btnModificarLibroOK"])) {
           //Recogemos los datos del formulario personal
           $titulo = $_POST["titulo"];
           $autor = $_POST["autor"];
           $genero = $_POST["genero"];
           $fPublicado = $_POST["fPublicado"];
           $editorial = $_POST["editorial"];
           $sinopsis = $_POST["sinopsis"];
           $codigoLibro = $_POST["codigoLibro"];
           
           //Creamos query en la que modificamos los datos
           $modificarLibro = "UPDATE libro SET Titulo='$titulo', Autor='$autor', Genero='$genero',
                             Fecha_Publicado='$fPublicado', Editorial='$editorial', Sinopsis='$sinopsis'
                             WHERE Codigo_Libro = $codigoLibro";
           
           if (mysqli_query($conn, $modificarLibro)) {
               echo '<script language="javascript">alert("Datos nodificados con éxito")</script>';
           }else{
               echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
           }
       }
       
       /*
        * Modificar portada de libro
        */
       if (isset($_POST["btnModificarPortadaOK"])) {
           //Recogemos los datos necesarios
           $codigo_Libro = $_POST["codigo_Libro"];
           //Sirve para comprobar si la imagen se ha seleccionado
           $check = getimagesize($_FILES["portadaL"]["tmp_name"]);
           $portadaL = $_FILES["portadaL"]["tmp_name"];
           $portadaLContent = addslashes(file_get_contents($portadaL));
           
           $modificarPortada = "UPDATE libro SET Portada='$portadaLContent' WHERE Codigo_Libro = $codigo_Libro";
           
            if ($check == FALSE) {
                header("Refresh:0; url=bienvenidaBibliotecario.php");
                echo '<script language="javascript">alert("Seleccione el archivo")</script>';
            }else{
               if (mysqli_query($conn, $modificarPortada)) {
                   echo '<script language="javascript">alert("Portada modificada con éxito")</script>';
               }else{
                   echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
               }
           }
       }
       
       /*
        * Modificaciones Préstamos
        */
       if (isset($_POST["btnModificarPrestamoOK"])) {
           //Recogemos los datos del formulario personal
           $codigoPrestamo = $_POST["codigoPrestamo"];
           $libro = $_POST["libro"];
           $usuario = $_POST["usuario"];
           $fRetirada = $_POST["fRetirada"];
           $fEntrega = $_POST["fEntrega"];
           
           //Creamos query en la que modificamos los datos
           $modificarPrestamo = "UPDATE prestamo SET Codigo_Libro='$libro', Codigo_Usuario='$usuario', Fecha_Retirada='$fRetirada',
                             Fecha_Entrega='$fEntrega' WHERE Codigo_Prestamo = $codigoPrestamo";
           
           if (mysqli_query($conn, $modificarPrestamo)) {
               echo '<script language="javascript">alert("Datos nodificados con éxito")</script>';
           }else{
               echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
           }
       }
       
       mysqli_close($conn);
       ?>
       
       <!-- Eliminar usuario, libro o préstamo -->
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
       
       /*
        * Eliminar usuario
        */
       if (isset($_GET["btnEliminarUsuario"])) {
           //Recogemos el código de usuario
           $cUsuario = $_GET["codUsuario"];
           
           //Preparamos la query con la que vamos eliminar los datos de la base de datos
           $eliminarUsuario = "DELETE FROM usuario WHERE Codigo_Usuario='$cUsuario'";
           
           /*Si la query funciona correctamente nos mostrara un mensaje de que ha ido bien,
            * si no es asi nos mostrara un mensaje con un error
            */
           if (mysqli_query($conn, $eliminarUsuario)) {
               echo '<script language="javascript">alert("Datos eliminados correctamente de la base de datos")</script>';
           }else{
               echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
           }
       }
       
       /*
        * Eliminar libro
        */
       if (isset($_GET["btnEliminarLibros"])) {
           //Recogemos el código del libro seleccionado
           $cLibro = $_GET["codLibro"];
           
           //Preparamos la query con la que vamos eliminar los datos de la base de datos
           $eliminarLibro = "DELETE FROM libro WHERE Codigo_Libro='$cLibro'";
           
           /*Si la query funciona correctamente nos mostrara un mensaje de que ha ido bien,
            * si no es asi nos mostrara un mensaje con un error
            */
           if (mysqli_query($conn, $eliminarLibro)) {
               echo '<script language="javascript">alert("Datos eliminados correctamente de la base de datos")</script>';
           }else{
               echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
           }
       }
       
       /*
        * Eliminar Préstamo
        */
       if (isset($_GET["btnEliminarPrestamo"])) {
           //Recogemos el código del libro seleccionado
           $cPrestamo = $_GET["codPrestamo"];
           
           //Preparamos la query con la que vamos eliminar los datos de la base de datos
           $eliminarPrestamo = "DELETE FROM prestamo WHERE Codigo_Prestamo='$cPrestamo'";
           
           /*Si la query funciona correctamente nos mostrara un mensaje de que ha ido bien,
            * si no es asi nos mostrara un mensaje con un error
            */
           if (mysqli_query($conn, $eliminarPrestamo)) {
               echo '<script language="javascript">alert("Datos eliminados correctamente de la base de datos")</script>';
           }else{
               echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
           }
       }
       
       mysqli_close($conn);
       ?>
       </div>
       <footer>
       		<p>Propiedad de Melania Gallego</p>
       </footer>
   </body>
 </html>