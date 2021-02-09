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

//Recogemos lo introducido en la barra del buscador
$sugerencia = $_REQUEST['sugerencia'];
$fechaActual = date("Y-m-d");

//Si se introduce algo en la barra se procedera a mostrar las distintas opciones
if($sugerencia !== ""){
    
    $resultadoTitulo = mysqli_query($conn, "SELECT l.Codigo_Libro, l.Titulo FROM libro l LEFT JOIN prestamo p ON l.Codigo_Libro = p.Codigo_Libro
                    WHERE (l.Titulo LIKE '%$sugerencia%') AND (p.Codigo_Prestamo IS NULL OR (p.Fecha_Entrega <= '$fechaActual' AND
                    p.Fecha_Retirada >= '$fechaActual'))");
    $resultadoAutor = mysqli_query($conn, "SELECT l.Codigo_Libro, l.Autor FROM libro l LEFT JOIN prestamo p ON l.Codigo_Libro = p.Codigo_Libro
                    WHERE (l.Autor LIKE '%$sugerencia%') AND (p.Codigo_Prestamo IS NULL OR (p.Fecha_Entrega <= '$fechaActual' AND
                    p.Fecha_Retirada >= '$fechaActual')) LIMIT 1");
    $resultadoGenero = mysqli_query($conn, "SELECT l.Codigo_Libro, l.Genero FROM libro l LEFT JOIN prestamo p ON l.Codigo_Libro = p.Codigo_Libro
                    WHERE (l.Genero LIKE '%$sugerencia%') AND (p.Codigo_Prestamo IS NULL OR (p.Fecha_Entrega <= '$fechaActual' AND
                    p.Fecha_Retirada >= '$fechaActual')) LIMIT 1");
    $resultadoEditorial = mysqli_query($conn, "SELECT l.Codigo_Libro, l.Editorial FROM libro l LEFT JOIN prestamo p ON l.Codigo_Libro = p.Codigo_Libro
                    WHERE (l.Editorial LIKE '%$sugerencia%') AND (p.Codigo_Prestamo IS NULL OR (p.Fecha_Entrega <= '$fechaActual' AND
                    p.Fecha_Retirada >= '$fechaActual')) LIMIT 1");
    
    //Si la query es correcta mostraremos las opciones
    while ($fila = mysqli_fetch_assoc($resultadoTitulo)) {
        echo '<option value="'. $fila['Titulo'] .'"></option>';
    }
    
    while ($fila = mysqli_fetch_assoc($resultadoAutor)) {
        echo '<option value="'. $fila['Autor'] .'"></option>';
    }
    
    while ($fila = mysqli_fetch_assoc($resultadoGenero)) {
        echo '<option value="'. $fila['Genero'] .'"></option>';
    }
    
    while ($fila = mysqli_fetch_assoc($resultadoEditorial)) {
        echo '<option value="'. $fila['Editorial'] .'"></option>';
    }
    
}

mysqli_close($conn);
