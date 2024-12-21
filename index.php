<?php
// Definir el tamaño máximo del archivo (2MB)
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2 MB en bytes

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = '';
    $targetDir = 'C:/uploads/'; // Asegúrate de que la carpeta uploads esté en el disco C

    // Verifica si se cargó un archivo
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $fileName = basename($file['name']);
        $fileSize = $file['size'];
        $fileTmp = $file['tmp_name'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Obtener el nombre personalizado desde el formulario
        $customFileName = isset($_POST['customName']) ? trim($_POST['customName']) : '';
        
        // Si no se proporciona un nombre, se usa el nombre original
        if (empty($customFileName)) {
            $customFileName = pathinfo($fileName, PATHINFO_FILENAME);
        }

        // Limitar caracteres para evitar problemas
        $customFileName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $customFileName); // Limita los caracteres a lo permitido

        // Verifica el tamaño del archivo
        if ($fileSize > MAX_FILE_SIZE) {
            $error = "El archivo es demasiado grande. El tamaño máximo permitido es de 2 MB.";
        }

        // Verifica que el archivo sea en formato PNG
        if ($fileType !== 'png') {
            $error = "Solo se permiten imágenes en formato PNG.";
        }

        // Si no hay errores, mueve el archivo a la carpeta de destino
        if (!$error) {
            $targetFile = $targetDir . $customFileName . '.png'; // Aseguramos que el archivo será .png
            if (move_uploaded_file($fileTmp, $targetFile)) {
                echo "<div class='success'>La imagen se subió correctamente: <a href='$targetFile'>Ver imagen</a></div>";
            } else {
                $error = "Hubo un error al subir la imagen.";
            }
        }
    } else {
        // Mensaje específico cuando no se selecciona un archivo
        $error = "No se seleccionó ningún archivo.";
        $errorClass = 'error-black'; // Clase para el error específico de "falta de archivo"
    }

    // Mostrar mensaje de error con la clase correspondiente
    if ($error) {
        $errorClass = isset($errorClass) ? $errorClass : 'error'; // Usar error-black si es por falta de archivo
        echo "<div class='$errorClass'>$error</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Imagen</title>
    <!-- Incluir la fuente Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ff00ff, #00ffff, #ff8800);
            background-size: 400% 400%;
            animation: fondo-cambiante 15s infinite ease-in-out;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        @keyframes fondo-cambiante {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            background-color: rgba(159, 171, 219, 0.25); /* Fondo blanco con algo de transparencia */
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(2, 2, 2, 0.93);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-weight: 400;
            color: #555;
            display: block;
            margin: 10px 0 5px;
        }

        input[type="file"], input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }

        .error {
            color: #f44336;
            background-color: #ffebee;
            border: 1px solid #f44336;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .error-black {
            color: #000;
            background-color: #ffebee;
            border: 1px solid #000;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .success {
            color: #4CAF50;
            background-color: #e8f5e9;
            border: 1px solid #4CAF50;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        /* Estilo para el título fuera del contenedor */
        .outside-title {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 3em;
            font-weight: bold;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            font-family: 'Poppins', sans-serif;
            pointer-events: none; /* No interactuar con el título */
        }
    </style>
</head>
<body>
    <div class="outside-title">YACKERLS</div>
    <div class="container">
        <h1>Subir Imagen</h1>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <label for="image">Seleccionar imagen (máximo 2 MB, solo PNG):</label>
            <input type="file" name="image" id="image" required><br><br>

            <label for="customName">Nombre personalizado para la imagen:</label>
            <input type="text" name="customName" id="customName" placeholder="Escribe el nombre de la imagen" /><br><br>

            <button type="submit">Subir Imagen</button>
        </form>
    </div>
</body>
</html>
