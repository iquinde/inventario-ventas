<?php
/**
 * Vista de Productos
 * CRUD completo de productos con validaciones
 */

require_once __DIR__ . '/../controllers/ProductoController.php';

$controller = new ProductoController();
$message = '';
$messageType = '';
$errors = [];
$editProduct = null;

// Procesar acciones
$action = $_GET['action'] ?? '';

// Eliminar producto
if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $controller->destroy($id);
    if ($result['success']) {
        $message = 'Producto eliminado correctamente.';
        $messageType = 'success';
    } else {
        $message = implode(' ', $result['errors']);
        $messageType = 'error';
    }
}

// Cargar producto para edición
if ($action === 'edit' && isset($_GET['id'])) {
    $editProduct = $controller->show(intval($_GET['id']));
}

// Procesar formulario (crear o actualizar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['action'] ?? '';

    if ($postAction === 'create') {
        $result = $controller->store($_POST);
        if ($result['success']) {
            $message = 'Producto creado correctamente.';
            $messageType = 'success';
        } else {
            $errors = $result['errors'];
            $messageType = 'error';
        }
    } elseif ($postAction === 'update' && isset($_POST['id'])) {
        $result = $controller->update(intval($_POST['id']), $_POST);
        if ($result['success']) {
            $message = 'Producto actualizado correctamente.';
            $messageType = 'success';
            $editProduct = null;
        } else {
            $errors = $result['errors'];
            $messageType = 'error';
            $editProduct = $controller->show(intval($_POST['id']));
        }
    }
}

// Obtener todos los productos
$productos = $controller->index();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Sistema de Inventario</title>
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

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
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
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-warning {
            background-color: #f39c12;
            color: white;
        }

        .btn-warning:hover {
            background-color: #e67e22;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
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

        .actions {
            display: flex;
            gap: 8px;
        }

        .stock-low {
            color: #e74c3c;
            font-weight: bold;
        }

        .empty-message {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Inventario & Ventas</h1>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="productos.php" class="active">Productos</a>
            <a href="ventas.php">Ventas</a>
        </nav>
    </div>

    <div class="container">
        <h2>Gestión de Productos</h2>

        <!-- Mensajes de éxito o error -->
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

        <!-- Formulario de Crear / Editar -->
        <div class="form-section">
            <h3><?= $editProduct ? 'Editar Producto' : 'Nuevo Producto' ?></h3>
            <form method="POST" action="productos.php">
                <input type="hidden" name="action" value="<?= $editProduct ? 'update' : 'create' ?>">
                <?php if ($editProduct): ?>
                    <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
                <?php endif; ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre">Nombre *</label>
                        <input type="text" id="nombre" name="nombre" required
                               value="<?= htmlspecialchars($editProduct['nombre'] ?? $_POST['nombre'] ?? '') ?>"
                               placeholder="Nombre del producto">
                    </div>

                    <div class="form-group">
                        <label for="precio">Precio *</label>
                        <input type="number" id="precio" name="precio" step="0.01" min="0.01" required
                               value="<?= htmlspecialchars($editProduct['precio'] ?? $_POST['precio'] ?? '') ?>"
                               placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="stock">Stock *</label>
                        <input type="number" id="stock" name="stock" min="0" required
                               value="<?= htmlspecialchars($editProduct['stock'] ?? $_POST['stock'] ?? '') ?>"
                               placeholder="0">
                    </div>

                    <div class="form-group full-width">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="3"
                                  placeholder="Descripción del producto (opcional)"><?= htmlspecialchars($editProduct['descripcion'] ?? $_POST['descripcion'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?= $editProduct ? 'Actualizar Producto' : 'Guardar Producto' ?>
                    </button>
                    <?php if ($editProduct): ?>
                        <a href="productos.php" class="btn btn-secondary">Cancelar</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Tabla de Productos -->
        <h3 style="margin-bottom: 15px; color: #2c3e50;">Listado de Productos</h3>

        <?php if (empty($productos)): ?>
            <div class="empty-message">
                <p>No hay productos registrados aún.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td><?= $producto['id'] ?></td>
                            <td><?= htmlspecialchars($producto['nombre']) ?></td>
                            <td><?= htmlspecialchars($producto['descripcion'] ?? '-') ?></td>
                            <td>$<?= number_format($producto['precio'], 2) ?></td>
                            <td class="<?= $producto['stock'] <= 5 ? 'stock-low' : '' ?>">
                                <?= $producto['stock'] ?>
                            </td>
                            <td class="actions">
                                <a href="productos.php?action=edit&id=<?= $producto['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="productos.php?action=delete&id=<?= $producto['id'] ?>" class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
