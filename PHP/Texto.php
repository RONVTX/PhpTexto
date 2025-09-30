<?php

$archivo_xml = 'tareas.xml';
// Crear el archivo XML si no existe
if (!file_exists($archivo_xml)) {
    $contenido_inicial = '<?xml version="1.0" encoding="UTF-8"?><tareas></tareas>';
    file_put_contents($archivo_xml, $contenido_inicial);
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['tarea'])) {
    $nuevaTarea = trim($_POST['tarea']);
    
    if ($nuevaTarea !== '') {
        // Cargar el archivo XML
        $xml = simplexml_load_file($archivo_xml);
        
        // Agregar la nueva tarea como un hijo del elemento raíz
        $xml->addChild('tarea', htmlspecialchars($nuevaTarea, ENT_XML1, 'UTF-8'));
        
        // Guardar los cambios en el archivo XML
        $xml->asXML($archivo_xml);
    }
    
    // Redirigir para evitar reenvío del formulario
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Leer las tareas del archivo XML
$tareas = [];
if (file_exists($archivo_xml)) {
    $xml = simplexml_load_file($archivo_xml);
    
    // Convertir las tareas XML a un array
    if ($xml && isset($xml->tarea)) {
        foreach ($xml->tarea as $tarea) {
            $tareas[] = (string)$tarea;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tareas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 2em;
        }
        
        .formulario {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        input[type="text"] {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .lista-tareas {
            list-style: none;
        }
        
        .lista-tareas li {
            background: #f8f9fa;
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .lista-tareas li:hover {
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }
        
        .vacio {
            text-align: center;
            color: #999;
            padding: 40px;
            font-style: italic;
        }
        
        .info-xml {
            background: #e8f4f8;
            border-left: 4px solid #2196F3;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Tareas</h1>
        
        <div class="info-xml">
            Las tareas se guardan en <strong>tareas.xml</strong>
        </div>
        
        <form method="POST" class="formulario">
            <input 
                type="text" 
                name="tarea" 
                placeholder="Escribe una nueva tarea..." 
                required
                autofocus
            >
            <button type="submit">Agregar</button>
        </form>
        
        <?php if (empty($tareas)): ?>
            <p class="vacio">No hay tareas. ¡Agrega tu primera tarea!</p>
        <?php else: ?>
            <ul class="lista-tareas">
                <?php foreach ($tareas as $tarea): ?>
                    <li><?php echo htmlspecialchars($tarea); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>