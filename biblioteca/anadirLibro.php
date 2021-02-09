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
            echo "<form method='POST' action='anadirLibro.php'>";
            echo "<input type='submit' value='Cerrar sesión' name='btnCerrar'>";
            echo "</form>";
            echo "</div>";
            
            //Cuando pulsemos el botón cerrar nos enviara al php de logout
            if(isset($_POST["btnCerrar"])){
                header('Location: logout.php');
            }
        //Si no se introducen los datos y no se inicia la sesión nos enviara a la página principal de login
        }else{
            header('Location: login.php');
        }
        ?>
       </header>
       <div id="anadirLibro">
           <form action="anadirLibro.php" method="POST" enctype="multipart/form-data">
           		<h3>Datos Libro</h3>
           		<p>Titulo: <input type="text" name="titulo"></p>
           		<p>Autor: <input type="text" name="autor"></p>
           		<p>Género: <input type="text" name="genero"></p>
           		<p>Publicación: <input type="date" name="publicacion"></p>
           		<p>Editorial: <input type="text" name="editorial"></p>
           		<p>Sinopsis: <textarea rows="4" cols="40" name="sinopsis"></textarea></p>
           		<p>Portada: <input type="file" name="portada"></p>
           		<input type="submit" name="btnAnadir" value="Añadir" style="background-color:#1166ea;">
           		<input type="submit" name="btnVolver" value="Volver">
           </form>
       </div>
       <?php 
       if (isset($_POST["btnAnadir"])) {
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
           
           //Recogemos los datos que vamos a añadir a la base de datos
           $titulo = $_POST["titulo"];
           $autor = $_POST["autor"];
           $genero = $_POST["genero"];
           $publicacion = $_POST["publicacion"];
           $editorial = $_POST["editorial"];
           $sinopsis = $_POST["sinopsis"];
           //Sirve para comprobar si la imagen se ha seleccionado
           $check = getimagesize($_FILES["portada"]["tmp_name"]);
           $portada = $_FILES['portada']['tmp_name'];
           $portadaContent = addslashes(file_get_contents($portada));
           
           //Query a ejecutar
           $anadirLibro = "INSERT INTO libro (Titulo, Autor, Genero, Fecha_Publicado, Editorial, Sinopsis, Portada)
                          VALUES ('$titulo', '$autor', '$genero', '$publicacion', '$editorial', '$sinopsis', '$portadaContent')";
           
           //Si no introducimos ningun dato se mostrara el siguiente mensaje
           if ($titulo == "" && $autor == "" && $genero == "" && $publicacion == "" && $editorial == "" && $sinopsis == "" && $check == false) {
               header("Refresh:0; url=anadirLibro.php");
               echo '<script language="javascript">alert("Introduce la información del libro")</script>';
           //Si falta algun dato se mostrara el siguiente mensaje
           }else if ($titulo == "" || $autor == "" || $genero == "" || $publicacion == "" || $editorial == "" || $sinopsis == "" || $check == false) {
               header("Refresh:0; url=anadirLibro.php");
               echo '<script language="javascript">alert("Introduce el dato que falta")</script>';
           //Si por el contrario todo ha sido introducido se ejecutaran las siguientes lineas
           }else{
               if (mysqli_query($conn, $anadirLibro)){
                   header("Refresh:0; url=anadirLibro.php");
                   echo '<script language="javascript">alert("Datos añadidos correctamente")</script>';
               }else{
                   echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
               }
           }
           
           //Cerramos la conexión con la base de datos
           mysqli_close($conn);
       }
       
       //Bóton volver
       if (isset($_POST["btnVolver"])) {
           header("Location: bienvenidaBibliotecario.php");
       }
       ?>
       <footer>
       		<p>Propiedad de Melania Gallego</p>
       </footer>
   </body>
 </html>