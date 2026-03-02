<?php
/**
 * Controlador de Ventas
 * Maneja las acciones de registro y listado de ventas
 */

require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../services/VentaService.php';

class VentaController
{
    private VentaService $ventaService;
    private Producto $productoModel;

    public function __construct()
    {
        $this->ventaService = new VentaService();
        $this->productoModel = new Producto();
    }

    /**
     * Lista todas las ventas
     * @return array
     */
    public function index(): array
    {
        $ventaModel = new Venta();
        return $ventaModel->getAll();
    }

    /**
     * Obtiene todos los productos disponibles (con stock > 0)
     * @return array
     */
    public function getProductosDisponibles(): array
    {
        $productos = $this->productoModel->getAll();
        return array_filter($productos, fn($p) => $p['stock'] > 0);
    }

    /**
     * Registra una nueva venta
     * @param array $data
     * @return array ['success' => bool, 'errors' => array]
     */
    public function store(array $data): array
    {
        $productoId = intval($data['producto_id'] ?? 0);
        $cantidad = intval($data['cantidad'] ?? 0);

        return $this->ventaService->registrarVenta($productoId, $cantidad);
    }
}
