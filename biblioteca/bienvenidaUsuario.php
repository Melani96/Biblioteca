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
            echo "<form method='POST' action='bienvenidaUsuario.php'>";
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
       <div>
       		<form action="bienvenidaUsuario.php" method="POST" id="usuarioBusqueda">
       			<input type="submit" value="Prestados y Consultas" name="btnReservas" id="btnReservas"><br>
       			<h3>Búsqueda de libros</h3>
       			<input list="browser" type="text" name="bLibro" onkeyup="mostrarSugerencias(this.value)" style="width: 300px;">
       			<datalist id="browser"></datalist>
       			<input type="submit" value="Buscar" name="btnBuscar">
       		</form>
       		<!-- Script con el que mostraremos sugerencias en la barra del buscador -->
       		<script>
       		function mostrarSugerencias(str){
    			if(str.length == 0){
    				document.getElementById("browser").innerHTML = '';
    			} else {
    				// AJAX REQ
    				var xmlhttp = new XMLHttpRequest();
    				xmlhttp.onreadystatechange = function(){
    					if(this.readyState == 4 && this.status == 200){
    						document.getElementById("browser").innerHTML = this.responseText;
    					}
    				}
    				xmlhttp.open("GET", "sugerenciasLibro.php?sugerencia="+str, true);
    				xmlhttp.send();
    			}
    		}
			</script>
			
       		<!-- Haremos la busqueda con la cual se nos mostraran los libros con x parámetros -->
       		<?php 
       		   if (isset($_POST["btnBuscar"])) {
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
       		       
       		       //Recogemos el dato introducido en el buscador
       		       $buscador = $_POST["bLibro"];
       		       //Recogemos la fecha de hoy para saber que libros estan siendo utilizados
       		       $fechaActual = date("Y-m-d");
       		       
       		       //Si el libro que queremos pedir prestado ya esta en uso por una persona no saldra al buscarlo con el buscador
       		       $sql = "SELECT l.Codigo_Libro, l.Portada, l.Titulo, l.Autor, l.Genero, l.Sinopsis, l.Editorial, l.Fecha_Publicado
                    FROM libro l LEFT JOIN prestamo p ON l.Codigo_Libro = p.Codigo_Libro
                    WHERE (l.Titulo LIKE '%$buscador%' OR l.Autor LIKE '%$buscador%' OR l.Genero LIKE '%$buscador%'
                    OR l.Editorial LIKE '%$buscador%') AND (p.Codigo_Prestamo IS NULL OR (p.Fecha_Entrega <= '$fechaActual' AND
                    p.Fecha_Retirada >= '$fechaActual'))";       		 
       		       
       		       /*
       		        * Si la query funciona correctamente crearemos la tabla y solo mostraremos aquellos libros que no tienen ningun
       		        * prestamo o que ya estan disponibles
       		        */ 
       		       if ($resultados = mysqli_query($conn, $sql)) {
       		           $nfilas = mysqli_num_rows($resultados);
       		           /*
       		            * Si el resultado es solo una línea se nos redirigira a la pagína fichaLibro.php, en caso contrario
       		            * de más de 1 resultado se nos mostraran en forma de tabla
       		            */
       		           if ($nfilas == 1) {
       		               $libro = mysqli_fetch_array($resultados);
       		               $libroId = $libro["Codigo_Libro"];
       		               header("Location: fichaLibro.php?LibroID=$libroId");
       		           }else if ($nfilas > 0) {
       		               echo "<table id='tablaBuscador'>";
       		               echo "<tr><th>Portada</th><th>Título</th><th>Autor</th><th>Género</th><th>Editorial</th><th>Fecha Publicación</th><th>¿Prestar?</th></tr>";
       		               for ($i = 0; $i < $nfilas; $i++) {
       		                   $fila = mysqli_fetch_array($resultados);
       		                   echo "<tr>";
       		                   echo "<td><img id='portada' src='data:image/jpeg;base64,".base64_encode($fila['Portada'])."'/></td>";
       		                   echo "<td>".$fila["Titulo"]."</td>";
       		                   echo "<td>".$fila["Autor"]."</td>";
       		                   echo "<td>".$fila["Genero"]."</td>";
       		                   echo "<td style='width:50%;'>".$fila["Sinopsis"]."</td>";
       		                   echo "<td>".$fila["Editorial"]."</td>";
       		                   echo "<td>".$fila["Fecha_Publicado"]."</td>";
       		                   echo "<form action='bienvenidaUsuario.php' method='GET'>";
       		                   echo "<input type='hidden' name='idLibro' value='".$fila["Codigo_Libro"]."'>";
       		                   echo "<td><input type='submit' name='btnPrestado' value='Pedir'></td>";
       		                   echo "</form>";
       		                   echo "</tr>";
       		               }
       		               echo "</table>";
       		           }
       		       }else {
       		           echo '<script language="javascript">alert("Error");</script>';
       		       }
       		       
       		       mysqli_close($conn);
       		   }
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
       		    
       		    if (isset($_GET["btnPrestado"])) {
       		        //Recogemos los datos a introducir
       		        $fecha_Actual = date("Y-m-d");
       		        $fecha_Entrega = date("Y-m-d", strtotime($fecha_Actual.'+ 20 days'));
       		        $idUsuario = $_SESSION['idUsuario'];
       		        $id_Libro = $_GET["idLibro"];
       		        
       		        //Creamos la query con la cual vamos a añadir el libro como prestamo
       		        $consulta = "INSERT INTO prestamo (Fecha_Retirada, Fecha_Entrega, Codigo_Usuario, Codigo_Libro)
                                    VALUES ('$fecha_Actual', '$fecha_Entrega', '$idUsuario', '$id_Libro')";
       		        
       		        if (mysqli_query($conn, $consulta)) {
       		            echo '<script language="javascript">alert("Puede ir a recoger su libro ya, a partir del dia de hoy tiene 20 días para devolverlo, si necesita más dias mande una sugerencia al bibliotecari@")</script>';
       		        }else{
       		            echo '<script language="javascript">alert("Error: ' . mysqli_error($conn) . '")</script>';
       		        }
       		    }
       		    mysqli_close($conn);
       		}
       		?>
       </div>
       <?php 
           if(isset($_POST['btnReservas'])){
               header('Location: reservaUsuario.php');
           }
       ?>
       <footer>
       		<p>Propiedad de Melania Gallego</p>
       </footer>
   </body>
 </html>