<?php
/**
 * Servicio de Ventas
 * Contiene la lógica de negocio para el registro de ventas
 * Separa la lógica del controlador siguiendo buenas prácticas
 */

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Venta.php';

class VentaService
{
    private Producto $productoModel;
    private Venta $ventaModel;

    public function __construct()
    {
        $this->productoModel = new Producto();
        $this->ventaModel = new Venta();
    }

    /**
     * Registra una venta validando stock y actualizando inventario
     * @param int $productoId
     * @param int $cantidad
     * @return array ['success' => bool, 'errors' => array]
     */
    public function registrarVenta(int $productoId, int $cantidad): array
    {
        // Obtener producto
        $producto = $this->productoModel->getById($productoId);

        if (!$producto) {
            return ['success' => false, 'errors' => ['El producto seleccionado no existe.']];
        }

        // Validar datos de la venta
        $errors = Venta::validate($productoId, $cantidad, $producto['stock']);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Registrar la venta
        $ventaCreada = $this->ventaModel->create($productoId, $cantidad, $producto['precio']);

        if (!$ventaCreada) {
            return ['success' => false, 'errors' => ['Error al registrar la venta.']];
        }

        // Actualizar stock del producto
        $stockActualizado = $this->productoModel->updateStock($productoId, $cantidad);

        if (!$stockActualizado) {
            return ['success' => false, 'errors' => ['Error al actualizar el stock.']];
        }

        return ['success' => true, 'errors' => []];
    }
}
