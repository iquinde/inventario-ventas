<?php
/**
 * Controlador de Productos
 * Maneja las acciones CRUD para productos
 */

require_once __DIR__ . '/../models/Producto.php';

class ProductoController
{
    private Producto $modelo;

    public function __construct()
    {
        $this->modelo = new Producto();
    }

    /**
     * Lista todos los productos
     * @return array
     */
    public function index(): array
    {
        return $this->modelo->getAll();
    }

    /**
     * Obtiene un producto por ID
     * @param int $id
     * @return array|false
     */
    public function show(int $id): array|false
    {
        return $this->modelo->getById($id);
    }

    /**
     * Crea un nuevo producto con validación
     * @param array $data
     * @return array ['success' => bool, 'errors' => array]
     */
    public function store(array $data): array
    {
        $nombre = trim($data['nombre'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $precio = floatval($data['precio'] ?? 0);
        $stock = intval($data['stock'] ?? 0);

        // Validar datos
        $errors = Producto::validate($nombre, $precio, $stock);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $result = $this->modelo->create($nombre, $descripcion, $precio, $stock);

        if ($result) {
            return ['success' => true, 'errors' => []];
        }

        return ['success' => false, 'errors' => ['Error al guardar el producto.']];
    }

    /**
     * Actualiza un producto existente con validación
     * @param int $id
     * @param array $data
     * @return array ['success' => bool, 'errors' => array]
     */
    public function update(int $id, array $data): array
    {
        $nombre = trim($data['nombre'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $precio = floatval($data['precio'] ?? 0);
        $stock = intval($data['stock'] ?? 0);

        // Validar datos
        $errors = Producto::validate($nombre, $precio, $stock);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Verificar que el producto existe
        $producto = $this->modelo->getById($id);
        if (!$producto) {
            return ['success' => false, 'errors' => ['El producto no existe.']];
        }

        $result = $this->modelo->update($id, $nombre, $descripcion, $precio, $stock);

        if ($result) {
            return ['success' => true, 'errors' => []];
        }

        return ['success' => false, 'errors' => ['Error al actualizar el producto.']];
    }

    /**
     * Elimina un producto
     * @param int $id
     * @return array ['success' => bool, 'errors' => array]
     */
    public function destroy(int $id): array
    {
        $producto = $this->modelo->getById($id);
        if (!$producto) {
            return ['success' => false, 'errors' => ['El producto no existe.']];
        }

        try {
            $result = $this->modelo->delete($id);
            if ($result) {
                return ['success' => true, 'errors' => []];
            }
            return ['success' => false, 'errors' => ['Error al eliminar el producto.']];
        } catch (PDOException $e) {
            return ['success' => false, 'errors' => ['No se puede eliminar el producto porque tiene ventas asociadas.']];
        }
    }
}
