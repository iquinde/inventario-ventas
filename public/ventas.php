<?php
/**
 * Vista de Ventas
 * Permite registrar ventas y ver el historial
 */

require_once __DIR__ . '/../controllers/VentaController.php';

$controller = new VentaController();
$message = '';
$messageType = '';
$errors = [];

// Procesar formulario de nueva venta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $controller->store($_POST);
    if ($result['success']) {
        $message = 'Venta registrada correctamente.';
        $messageType = 'success';
    } else {
        $errors = $result['errors'];
        $messageType = 'error';
    }
}

// Obtener datos
$ventas = $controller->index();
$productosDisponibles = $controller->getProductosDisponibles();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas - Sistema de Inventario</title>
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
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .alert {
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .form-section h3 {
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input,
        .form-group select {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-actions {
            margin-top: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s;
        }

        .btn-primary {
            background-color: #27ae60;
            color: white;
        }

        .btn-primary:hover {
            background-color: #219a52;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        thead {
            background-color: #2c3e50;
            color: white;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #eef2f7;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .stock-info {
            font-size: 0.85rem;
            color: #888;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Inventario & Ventas</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="productos.php">Productos</a>
            <a href="ventas.php" class="active">Ventas</a>
        </nav>
    </div>

    <div class="container">
        <h2>Registro de Ventas</h2>

        <!-- Mensajes -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulario de Nueva Venta -->
        <div class="form-section">
            <h3>Nueva Venta</h3>
            <?php if (empty($productosDisponibles)): ?>
                <p style="color: #e74c3c;">No hay productos con stock disponible para vender.</p>
            <?php else: ?>
                <form method="POST" action="ventas.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="producto_id">Producto *</label>
                            <select id="producto_id" name="producto_id" required onchange="mostrarStock()">
                                <option value="">-- Seleccionar producto --</option>
                                <?php foreach ($productosDisponibles as $producto): ?>
                                    <option value="<?= $producto['id'] ?>"
                                            data-stock="<?= $producto['stock'] ?>"
                                            data-precio="<?= $producto['precio'] ?>"
                                            <?= (isset($_POST['producto_id']) && $_POST['producto_id'] == $producto['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($producto['nombre']) ?> - $<?= number_format($producto['precio'], 2) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="stock-info" id="stock-info"></span>
                        </div>

                        <div class="form-group">
                            <label for="cantidad">Cantidad *</label>
                            <input type="number" id="cantidad" name="cantidad" min="1" required
                                   value="<?= htmlspecialchars($_POST['cantidad'] ?? '') ?>"
                                   placeholder="Cantidad a vender">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Registrar Venta</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <!-- Historial de Ventas -->
        <h3 style="margin-bottom: 15px; color: #2c3e50;">Historial de Ventas</h3>

        <?php if (empty($ventas)): ?>
            <div class="empty-message">
                <p>No hay ventas registradas aún.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td><?= $venta['id'] ?></td>
                            <td><?= htmlspecialchars($venta['producto_nombre']) ?></td>
                            <td><?= $venta['cantidad'] ?></td>
                            <td>$<?= number_format($venta['precio_unitario'], 2) ?></td>
                            <td><strong>$<?= number_format($venta['total'], 2) ?></strong></td>
                            <td><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function mostrarStock() {
            const select = document.getElementById('producto_id');
            const stockInfo = document.getElementById('stock-info');
            const cantidadInput = document.getElementById('cantidad');
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption.value) {
                const stock = selectedOption.getAttribute('data-stock');
                stockInfo.textContent = 'Stock disponible: ' + stock + ' unidades';
                cantidadInput.max = stock;
            } else {
                stockInfo.textContent = '';
                cantidadInput.removeAttribute('max');
            }
        }
    </script>
</body>
</html>
