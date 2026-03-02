<?php
/**
 * Modelo Producto
 * Maneja todas las operaciones CRUD de la tabla productos
 */

require_once __DIR__ . '/../config/database.php';

class Producto
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getConnection();
    }

    /**
     * Obtiene todos los productos
     * @return array
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM productos ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    /**
     * Obtiene un producto por su ID
     * @param int $id
     * @return array|false
     */
    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Crea un nuevo producto
     * @param string $nombre
     * @param string $descripcion
     * @param float $precio
     * @param int $stock
     * @return bool
     */
    public function create(string $nombre, string $descripcion, float $precio, int $stock): bool
    {
        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (:nombre, :descripcion, :precio, :stock)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre'      => $nombre,
            'descripcion' => $descripcion,
            'precio'      => $precio,
            'stock'       => $stock
        ]);
    }

    /**
     * Actualiza un producto existente
     * @param int $id
     * @param string $nombre
     * @param string $descripcion
     * @param float $precio
     * @param int $stock
     * @return bool
     */
    public function update(int $id, string $nombre, string $descripcion, float $precio, int $stock): bool
    {
        $sql = "UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id'          => $id,
            'nombre'      => $nombre,
            'descripcion' => $descripcion,
            'precio'      => $precio,
            'stock'       => $stock
        ]);
    }

    /**
     * Elimina un producto por su ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Actualiza el stock de un producto (para ventas)
     * @param int $id
     * @param int $cantidad Cantidad a restar del stock
     * @return bool
     */
    public function updateStock(int $id, int $cantidad): bool
    {
        $producto = $this->getById($id);
        if (!$producto || $producto['stock'] < $cantidad) {
            return false;
        }

        $nuevoStock = $producto['stock'] - $cantidad;
        $stmt = $this->db->prepare("UPDATE productos SET stock = :stock WHERE id = :id");
        return $stmt->execute([
            'stock' => $nuevoStock,
            'id'    => $id
        ]);
    }

    /**
     * Valida los datos de un producto
     * @param string $nombre
     * @param float $precio
     * @param int $stock
     * @return array Array de errores (vacío si no hay errores)
     */
    public static function validate(string $nombre, float $precio, int $stock): array
    {
        $errors = [];

        if (empty(trim($nombre))) {
            $errors[] = "El nombre del producto no puede estar vacío.";
        }

        if ($precio <= 0) {
            $errors[] = "El precio debe ser mayor a 0.";
        }

        if ($stock < 0) {
            $errors[] = "El stock no puede ser negativo.";
        }

        return $errors;
    }
}
