<?php
// Incluimos la conexión (ajusta la ruta según dónde guardes este archivo respecto a tu conexión)
include("../base-datos/conexion-base-datos.php");

// Consultar los libros de la base de datos
try {
    $query = $conexion->query("SELECT * FROM tb_libros");
    $libros = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al cargar los libros: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Libros - PowerLetters</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        /* Formulario */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .input-group {
            display: flex;
            flex-direction: column;
        }
        .input-group label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        .input-group input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        /* Botones */
        .btn-container {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-add { background-color: #2ecc71; color: white; }
        .btn-add:hover { background-color: #27ae60; }
        .btn-update { background-color: #3498db; color: white; }
        .btn-update:hover { background-color: #2980b9; }
        .btn-delete { background-color: #e74c3c; color: white; }
        .btn-delete:hover { background-color: #c0392b; }
        
        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:hover {
            background-color: #f1f2f6;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Catálogo de Libros - PowerLetters</h2>

    <!-- Formulario / Cuadros de texto superiores -->
    <form id="libroForm" action="" method="POST">
        <input type="hidden" id="id_libro" name="id_libro">
        
        <div class="form-grid">
            <div class="input-group">
                <label for="titulo">Título del Libro</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div class="input-group">
                <label for="precio">Precio ($)</label>
                <input type="number" step="0.01" id="precio" name="precio" required>
            </div>
            <div class="input-group">
                <label for="existencias">Existencias</label>
                <input type="number" id="existencias" name="existencias" required>
            </div>
            <div class="input-group">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion">
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="btn-container">
            <button type="submit" name="accion" value="crear" class="btn-add">Añadir</button>
            <button type="submit" name="accion" value="actualizar" class="btn-update">Actualizar</button>
            <button type="submit" name="accion" value="eliminar" class="btn-delete" onclick="return confirm('¿Estás segura de eliminar este libro?');">Eliminar</button>
        </div>
    </form>

    <!-- Tabla que muestra los datos de la base de datos -->
    <h3>Lista de Registros</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Precio</th>
                <th>Existencias</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($libros)) { ?>
                <?php foreach ($libros as $libro) { ?>
                    <tr onclick="seleccionarFila(this)" 
                        data-id="<?php echo $libro['id_libro']; ?>"
                        data-titulo="<?php echo htmlspecialchars($libro['titulo']); ?>"
                        data-precio="<?php echo $libro['existencias']; ?>" 
                        data-precio-real="<?php echo $libro['precio']; ?>"
                        data-existencias="<?php echo $libro['existencias']; ?>"
                        data-descripcion="<?php echo htmlspecialchars($libro['descripcion']); ?>">
                        
                        <td><?php echo $libro['id_libro']; ?></td>
                        <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                        <td>$<?php echo number_format($libro['precio'], 2); ?></td>
                        <td><?php echo $libro['existencias']; ?></td>
                        <td><?php echo htmlspecialchars($libro['descripcion']); ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5" style="text-align: center;">No hay libros registrados.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Script de JavaScript para el autocompletado al hacer clic -->
<script>
    function seleccionarFila(fila) {
        // Obtenemos los datos guardados en los atributos 'data-*' de la fila
        const id = fila.getAttribute('data-id');
        const titulo = fila.getAttribute('data-titulo');
        const precio = fila.getAttribute('data-precio-real');
        const existencias = fila.getAttribute('data-existencias');
        const descripcion = fila.getAttribute('data-descripcion');

        // Rellenamos los cuadros de texto superiores automáticamente
        document.getElementById('id_libro').value = id;
        document.getElementById('titulo').value = titulo;
        document.getElementById('precio').value = precio;
        document.getElementById('existencias').value = existencias;
        document.getElementById('descripcion').value = descripcion;
    }
</script>

</body>
</html>