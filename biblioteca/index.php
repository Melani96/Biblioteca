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
       <div id="registrarse">
       		<input type="submit" value="Registrarse" onclick="registro()">
       </div>
       </header>
       <div id="loguear">
           <h3>Bienvenido lector, logueate para poder gestionar</h3>
           	<p>Usuario: <input type="text" id="usuario"></p>
           	<p>Contraseña: <input type="password" id="pass"></p>
           	<input type="submit" value="Login" onclick="login()">
           	<p id="resultado"></p>
       </div>
       <div id="registra" style="display: none">
           <h3>Introduce tus datos</h3>
           <p>Nombre: <input type='text' id='nombre'></p>
           <p>Apellidos: <input type='text' id='apellidos'></p>
           <p>Fecha Nacimiento: <input type='date' id='fechaNacimiento'></p>
           <p>Email: <input type='text' id='email'></p>
           <p>Dirección: <input type='text' id='direccion'></p>
           <p>Población: <input type='text' id='poblacion'></p>
           <p>C.Postal: <input type='number' id='cPostal'></p>
           <p>Usuario: <input type='text' id='nombreUsuario'></p>
           <p>Contraseña: <input type='password' id='contrasena'></p>
           <p>Comprobar Contraseña: <input type='password' id='contrasena2'></p>
           <input type='submit' value='Volver' onclick="volver()">
           <input type='submit' value='Registrarse' onclick="registrarse()">
       </div>
       <footer>
       		<p>Propiedad de Melania Gallego</p>
       </footer>
       <!-- Script en el que mostramos el formulario de registro -->
       <script>
       function registro() {
           //Al ejecutar la función ocultaremos el formulario de login y mostraremos el de registro
			var logear = document.getElementById("loguear");
			logear.style.display="none";
			document.getElementById("registra").style.display="";
		}
       </script>
       
       <!-- Script en el cual se envian los datos del formulario de registro -->
       <script>
       function registrarse() {
			var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() { 
				if (this.readyState == 4 && this.status == 200) {
					/*
					Si la respuesta coincide con los if's se los mostrara el mensaje correspondiente, si en caso contrario ningun if
					se realiza pasaremos a llevar a cabo el registro exitoso en el que volveremos a ocultar el formulario de registro
					y mostraremos el de login junto a una alerta la cual nos indicara que todo ha salido bien.
					*/
					if (this.response == "Introduce todos los campos") {
						alert("Introduce todos los campos");
					}else if(this.response == "Introduce los campos que faltan"){
						alert("Introduce los campos que faltan");
					}else{
						document.getElementById("registra").style.display="none";
						document.getElementById("loguear").style.display="";
						alert("Registro completado con éxito");
					}
				}
			};
			//Recogemos los datos a enviar al archivo php en el que llevamos a cabo el registro
			var nombre = document.getElementById("nombre").value;
			var apellidos = document.getElementById("apellidos").value;
			var nacimiento = document.getElementById("fechaNacimiento").value;
			var email = document.getElementById("email").value;
			var direccion = document.getElementById("direccion").value;
			var poblacion = document.getElementById("poblacion").value;
			var cPostal = document.getElementById("cPostal").value;
			var usuario = document.getElementById("nombreUsuario").value;
			var pass = document.getElementById("contrasena").value;
			var pass2 = document.getElementById("contrasena2").value;
			//Enviamos los datos por post al archivo registro.php en el que se guardaran los datos introducidos en la base de datos
			xhttp.open("POST", "registro.php", true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send("nombre="+nombre+"&apellidos="+apellidos+"&fechaNacimiento="+nacimiento+"&email="+email+"&direccion="+direccion
					+"&poblacion="+poblacion+"&cPostal="+cPostal+"&usuario="+usuario+"&pass="+pass+"&pass2="+pass2);
		}
       </script>
       
       <!-- Script del boton de volver -->
       <script>
       function volver() {
    	   window.location.href = "index.php";
    	   }
       </script>
       
       <!-- Script en el cual se nos valida el login y nos muestra mensaje de error en caso de que nos equivoquemos de datos -->
       <script>
       function login() {
			var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() { 
				if (this.readyState == 4 && this.status == 200) {
					//Recogemos los los datos y dependiendo de la respuesta nos redirigira a una página u otra o mostrara un error.
					if (this.response == "Usuario") {
						window.location.href = "bienvenidaUsuario.php";
					}else if(this.response == "Bibliotecario"){
						window.location.href = "bienvenidaBibliotecario.php";
					}else{
						document.getElementById("resultado").innerHTML = this.response;
					}
				}
			};
			var usuario = document.getElementById("usuario").value;
			var pass = document.getElementById("pass").value;
			xhttp.open("GET", "login.php?usuario="+usuario+"&pass="+pass, true);
			xhttp.send();
		}
		</script>
   </body>
 </html>