# Sistema de Inventario y Ventas

Sistema web desarrollado en **PHP + MySQL** que permite gestionar productos (CRUD completo) y registrar ventas

## Descripción del Sistema

El sistema cuenta con dos módulos principales:

- **Módulo de Productos**: Permite crear, listar, editar y eliminar productos del inventario con validaciones de datos.
- **Módulo de Ventas**: Permite registrar ventas seleccionando un producto y cantidad, actualizando automáticamente el stock disponible.

### Características
- Arquitectura por capas (Modelos, Controladores, Servicios, Vistas)
- Validaciones del lado del servidor
- Conexión segura con PDO y prepared statements
- Interfaz web responsive y amigable
- Control de stock automático al registrar ventas

## Requisitos

- **PHP** 8.0 o superior
- **MySQL** 5.7 o superior
- **Servidor web**: Apache (XAMPP)
- **Navegador web** moderno

## Pasos para Instalación

1. **Clonar el repositorio**:
   ```bash
   git clone https://github.com/tu-usuario/inventario-ventas.git
   ```

2. **Copiar el proyecto** a la carpeta de tu servidor web:
   - Para XAMPP: `C:\xampp\htdocs\inventario-ventas`

3. **Crear la base de datos**:
   - Abrir phpMyAdmin
   - Ejecutar el script SQL ubicado en `database/inventario.sql`

4. **Configurar la conexión a la base de datos**:
   - Editar el archivo `config/database.php`
   - Modificar las credenciales según tu entorno:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'inventario_ventas');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     ```

5. **Acceder al sistema**:
   - Abrir el navegador y visitar: `http://localhost/inventario-ventas/public/`
   - Tambien puede modificar el archivo "httpd.conf" de Apache que se encuentra en la opcion Config ajusta la directiva "DocumentRoot" y "<Directory>" para que apunten a la carpeta "public" y visitar: `http://localhost/inventario-ventas/`

## Script SQL

El script de la base de datos se encuentra en: `database/inventario.sql`

Contiene:
- Creación de la base de datos `inventario_ventas`
- Tabla `productos` con campos: id, nombre, descripcion, precio, stock, created_at, updated_at
- Tabla `ventas` con campos: id, producto_id, cantidad, precio_unitario, total, fecha
- Datos de prueba (5 productos de ejemplo)

## Estructura del Proyecto

```
/inventario-ventas
├── /config
│   └── database.php          # Configuración de conexión a MySQL
├── /models
│   ├── Producto.php           # Modelo de productos (CRUD)
│   └── Venta.php              # Modelo de ventas
├── /controllers
│   ├── ProductoController.php # Controlador de productos
│   └── VentaController.php    # Controlador de ventas
├── /services
│   └── VentaService.php       # Lógica de negocio de ventas
├── /public
│   ├── index.php              # Página principal
│   ├── productos.php          # Vista CRUD de productos
│   └── ventas.php             # Vista de registro de ventas
├── /database
│   └── inventario.sql         # Script SQL de la base de datos
└── README.md                  # Documentación del proyecto
```

## Validaciones Implementadas

### Productos
- Nombre no vacío (obligatorio)
- Precio mayor a 0
- Stock mayor o igual a 0
- No se permite stock negativo

### Ventas
- Producto seleccionado válido
- Cantidad mayor a 0
- Validación de stock disponible antes de registrar la venta
- Actualización automática del stock

## Usuario de Prueba

No se requiere autenticación para acceder al sistema. El acceso es directo desde el navegador.

## Tecnologías Utilizadas

- **PHP 8+** — Lenguaje del lado del servidor
- **MySQL** — Base de datos relacional
- **PDO** — Conexión segura a la base de datos
- **HTML5 / CSS3** — Estructura y estilos de la interfaz
- **JavaScript** — Interactividad en formularios

## Autor

Proyecto desarrollado como actividad integradora para la materia Programación de Sistemas Web.
