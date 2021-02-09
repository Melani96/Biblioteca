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
            echo "<form method='POST' action='fichaLibro.php'>";
            echo "<input type='submit' value='Modificar Perfil' name='btnModificarPerfil'>";
            echo "<input type='submit' value='Cerrar sesión' name='btnCerrar'>";
            echo "</form>";
            echo "</div>";
            
            //Cuando pulsemos el botón cerrar nos enviara al php de logout
            if(isset($_POST["btnCerrar"])){
                header('Location: logout.php');
            }
            
            if (isset($_POST["btnModificarPerfil"])) {
                header('Location: modificarPerfil.php');
            }
        //Si no se introducen los datos y no se inicia la sesión nos enviara a la página principal de login
        }else{
            header('Location: index.php');
        }
        ?>
       </header>
       <div id="fichaLibro">
       		<!-- Mostramos la ficha del libro -->
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
       		
       		//Recogemos el código del libro que nos proporciona la página bienvenidaUsuario.php
       		$codigoLibro = $_GET["LibroID"];
       		
       		$consulta = "SELECT * FROM libro WHERE Codigo_Libro = $codigoLibro";

       		if ($libro = mysqli_query($conn, $consulta)) {
       		    $resultadoLibro = mysqli_fetch_array($libro);
       		    echo "<img style='float:left;margin-left:20%;' src='data:image/jpeg;base64,".base64_encode($resultadoLibro['Portada'])."'/>";
       		    echo "<div id='fLibro'>";
       		    echo "<p><strong>Título: </strong>".$resultadoLibro['Titulo']."</p>";
       		    echo "<p><strong>Autor: </strong>".$resultadoLibro['Autor']."</p>";
       		    echo "<p><strong>Género: </strong>".$resultadoLibro['Genero']."</p>";
       		    echo "<p><strong>Sinopsis: </strong>".$resultadoLibro['Sinopsis']."</p>";
       		    echo "<p><strong>Editorial: </strong>".$resultadoLibro['Editorial']."</p>";
       		    echo "<p><strong>Fecha publicación: </strong>".$resultadoLibro['Fecha_Publicado']."</p>";
       		    echo "<form action='fichaLibro.php' method='POST'>";
       		    echo "<input type='hidden' name='idLibro' value='".$resultadoLibro['Codigo_Libro']."'>";
       		    echo "<input type='submit' name='btnPedir' value='Pedir'>";
       		    echo "<input type='submit' name='btnVolver' value='Volver'>";
       		    echo "</form>";
       		    echo "</div>";
       		}
       		
       		mysqli_close($conn);
       		?>
       		<!-- Aqui vamos a hacer que cuando el usuario presione el botón de pedir prestado se le guarde como recogida el dia
       		 de hoy y de entrega 20 dias despues, si el usuario quiere pedir mas dias debera indicarselo al bibliotecario -->
       		<?php 
       		if (isset($_SESSION['usuario'])) {
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
       		    
       		    if (isset($_POST["btnPedir"])) {
       		        //Recogemos los datos a introducir
       		        $fecha_Actual = date("Y-m-d");
       		        $fecha_Entrega = date("Y-m-d", strtotime($fecha_Actual.'+ 20 days'));
       		        $idUsuario = $_SESSION['idUsuario'];
       		        $id_Libro = $_POST["idLibro"];
       		        
       		        //Creamos la query con la cual vamos a añadir el libro como prestamo
       		        $consulta = "INSERT INTO prestamo (Fecha_Retirada, Fecha_Entrega, Codigo_Usuario, Codigo_Libro)
                                    VALUES ('$fecha_Actual', '$fecha_Entrega', '$idUsuario', '$id_Libro')";
       		        
       		        if (mysqli_query($conn, $consulta)) {
       		            header("Refresh:0; url=bienvenidaUsuario.php");
       		            echo '<script language="javascript">alert("Puede ir a recoger su libro ya, a partir del dia de hoy tiene 20 días para devolverlo, si necesita más dias mande una sugerencia al bibliotecari@")</script>';
       		        }else{
       		            echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
       		        }
       		    }
       		    mysqli_close($conn);
       		}
       		?>
       		<!-- Si pulsamos el botón volver este nos devolvera a la página anterior -->
       		<?php 
       		   if (isset($_POST["btnVolver"])) {
       		       header("Location: bienvenidaUsuario.php");
       		   }
       		?>
       </div>
       <footer>
       		<p>Propiedad de Melania Gallego</p>
       </footer>
   </body>
 </html>