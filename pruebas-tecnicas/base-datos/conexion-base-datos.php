<?php
$host = "127.0.0.1";
$dbname = "powerletters"; // Cambia a "base_pruebas" si el guion medio te da error
$username = "root";
$password = ""; // Escribe tu contraseña si le asignaste una en HeidiSQL
$port = "3306";

try {
    // Crear la instancia de PDO
    $conexion = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configurar el modo de errores para excepciones
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "¡Conexión exitosa a la base de datos!";

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>