<?php
/**
 * Modelo Venta
 * Maneja las operaciones de la tabla ventas
 */

require_once __DIR__ . '/../config/database.php';

class Venta
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getConnection();
    }

    /**
     * Obtiene todas las ventas con datos del producto
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT v.*, p.nombre AS producto_nombre 
                FROM ventas v 
                INNER JOIN productos p ON v.producto_id = p.id 
                ORDER BY v.fecha DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Registra una nueva venta
     * @param int $productoId
     * @param int $cantidad
     * @param float $precioUnitario
     * @return bool
     */
    public function create(int $productoId, int $cantidad, float $precioUnitario): bool
    {
        $total = $cantidad * $precioUnitario;
        $sql = "INSERT INTO ventas (producto_id, cantidad, precio_unitario, total) VALUES (:producto_id, :cantidad, :precio_unitario, :total)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'producto_id'    => $productoId,
            'cantidad'       => $cantidad,
            'precio_unitario' => $precioUnitario,
            'total'          => $total
        ]);
    }

    /**
     * Obtiene una venta por su ID
     * @param int $id
     * @return array|false
     */
    public function getById(int $id): array|false
    {
        $sql = "SELECT v.*, p.nombre AS producto_nombre 
                FROM ventas v 
                INNER JOIN productos p ON v.producto_id = p.id 
                WHERE v.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Valida los datos de una venta
     * @param int $productoId
     * @param int $cantidad
     * @param int $stockDisponible
     * @return array Array de errores
     */
    public static function validate(int $productoId, int $cantidad, int $stockDisponible): array
    {
        $errors = [];

        if ($productoId <= 0) {
            $errors[] = "Debe seleccionar un producto válido.";
        }

        if ($cantidad <= 0) {
            $errors[] = "La cantidad debe ser mayor a 0.";
        }

        if ($cantidad > $stockDisponible) {
            $errors[] = "No hay suficiente stock disponible. Stock actual: $stockDisponible.";
        }

        return $errors;
    }
}
