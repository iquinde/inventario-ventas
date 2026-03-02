<?php
/**
 * Página principal del sistema
 * Muestra el menú de navegación
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario y Ventas</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        .navbar {
            background-color: #2c3e50;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            color: white;
            font-size: 1.3rem;
        }

        .navbar nav a {
            color: #ecf0f1;
            text-decoration: none;
            margin-left: 20px;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .navbar nav a:hover, .navbar nav a.active {
            background-color: #3498db;
        }

        .container {
            max-width: 900px;
            margin: 60px auto;
            padding: 0 20px;
            text-align: center;
        }

        .welcome h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .welcome p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 40px;
        }

        .cards {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 40px 30px;
            width: 300px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            color: #333;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .card h3 {
            font-size: 1.4rem;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .card p {
            color: #777;
        }

        .card .icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #999;
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Inventario & Ventas</h1>
        <nav>
            <a href="index.php" class="active">Inicio</a>
            <a>Productos</a>
            <a>Ventas</a>
            <a href="productos.php">Productos</a>
            <a href="ventas.php">Ventas</a>
        </nav>
    </div>

    <div class="container">
        <div class="welcome">
            <h2>Bienvenido al Sistema de Inventario y Ventas</h2>
            <p>Gestiona tus productos y registra ventas de manera sencilla y eficiente.</p>
        </div>

        <div class="cards">
            <a href="productos.php" class="card">
                <div class="icon">📦</div>
                <h3>Productos</h3>
                <p>Administra tu inventario: crea, edita, elimina y consulta productos.</p>
            </a>

            <a href="ventas.php" class="card">
                <div class="icon">🛒</div>
                <h3>Ventas</h3>
                <p>Registra nuevas ventas y consulta el historial de transacciones.</p>
            </a>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 - Sistema de Inventario y Ventas</p>
    </footer>
</body>
</html>
