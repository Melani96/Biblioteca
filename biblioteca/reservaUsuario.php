<!DOCTYPE html>
 <html>
   <head>
      <meta http-equiv=”Content-Type” content=”text/html; charset=UTF-8″ />
      <title>RS</title>
      <link rel="stylesheet" href="general0.css">
   </head>
   <body>
   	<!-- Aqui se inicia la sesión para indicar que usuario esta conectado y darle la bienvenida -->
       <header>
       <img src="imagenes/logo.PNG">
       <?php
        session_start();
        
        //Si se ha creado la sessión se creara la bienvenida
        if (isset($_SESSION['usuario'])) {
            echo "<div class='nombreUsuario'>";
            echo "<p>Bienvenid@ " . $_SESSION['usuario'] . "</p>";
            echo "<form method='POST' action='reservaUsuario.php'>";
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
       <!-- Formulario para enviar sugerencias -->
       		<form action="reservaUsuario.php" method="POST" id="formSugerencias">
       			<h2>Escribe tu sugerencia</h2>
       			<h3>Asunto</h3>
       			<input type="text" name="asunto">
       			<h3>Mensaje</h3>
       			<textarea rows="4" cols="40" name="mensaje" placeholder="Escribe aquí tu sugerencia"></textarea>
       			<br></br>
       			<input type="submit" name="btnEnviar">
       		</form>
       		<?php 
       		if (isset($_SESSION['usuario'])) {   		    
       		    //Conectamos con base de datos y comparamos el usuario
       		    $servername = "localhost";
       		    $username = "root";
       		    $password = "";
       		    $dbname = "biblioteca";
       		    
       		    $codigoUsuario = $_SESSION['idUsuario'];
       		    
       		    //Creamos conexión
       		    $conn = mysqli_connect($servername, $username, $password, $dbname);
       		    //Comprobamos la conexión
       		    if (!$conn) {
       		        die("Conexión fallida: " . mysqli_connect_error());
       		    }
       		    
       		    $consulta = "SELECT * FROM usuario WHERE Codigo_Usuario = $codigoUsuario";

       		    if(isset($_POST['btnEnviar'])){
       		        if ($mailUsu = mysqli_query($conn, $consulta)) {
       		            $usuario = mysqli_fetch_array($mailUsu);
       		            
       		            //Datos al enviar para enviar el mail
       		            $mailUsuario = $usuario["Email"];
       		            $asunto = $_POST["asunto"];
       		            $mensaje = $_POST["mensaje"];
       		            $mailBibliotecario = "bibliotecarioRS@gmail.com";
       		            
                        if(mail($mailBibliotecario, $asunto, $mensaje, $mailUsuario)){
                            echo '<script language="javascript">alert("Mensaje Enviado");</script>';
       		            }else{
       		                echo '<script language="javascript">alert("Error");</script>';
       		            }
       		            
       		        }
       		    }
       		    
       		}
       		?>
       <!-- Aqui mostramos las reservas que ha hecho el usuario -->
       		<form action="reservaUsuario.php" method="POST" id="usuarioReservas">
       			<h3>Tus Prestamos</h3>
       			<table id="tablaReserva">
       			<tr>
       				<th>Titulo</th>
       				<th>Fecha Retirada</th>
       				<th>Fecha Entrega</th>
       				<th></th>
       			</tr>
       			<?php 
       			if (isset($_SESSION['usuario'])) {
       			    $sql = "SELECT * FROM prestamo p JOIN libro l ON p.Codigo_Libro = l.Codigo_Libro WHERE p.Codigo_Usuario = $codigoUsuario";
       			    
       			    //Si la query se ejecuta correctamente mostraremos la tabla con las reservas del usuario
       			    if ($resultado = mysqli_query($conn, $sql)) {
       			        $nfilas = mysqli_num_rows($resultado);
       			        if ($nfilas > 0) {
       			            for ($i = 0; $i < $nfilas; $i++) {
       			                $fila = mysqli_fetch_array($resultado);
       			                echo "<tr>";
       			                echo "<td>".$fila["Titulo"]."</td>";
       			                echo "<td>".$fila["Fecha_Retirada"]."</td>";
       			                echo "<td>".$fila["Fecha_Entrega"]."</td>";
       			                echo "</tr>";
       			            }
       			        }
       			    } else {
       			        echo '<script language="javascript">alert("Error");</script>';
       			    }
       			    
       			    mysqli_close($conn);
       			    
       			}else{
       			    header('Location: index.php');
       			}
           			
       			?>
       			</table><br>
       			<input type=submit value="Volver" name="btnVolver">
       			<!-- Si pulsamos el botón de volver nos rediriguira a la página anterior -->
       			<?php 
       			if(isset($_POST["btnVolver"])){
       			    header('Location: bienvenidaUsuario.php');
       			}
       			?>
       		</form>
       </div>
       <footer>
       		<p>Propiedad de Melania Gallego</p>
       </footer>
   </body>
 </html>