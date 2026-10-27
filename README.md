# 🫓 Mr. Peto — Tienda de arepas

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black)

</div>

> Tienda en línea con **catálogo, carrito de compras, checkout y panel de administración**.

---

## 📋 Descripción

E-commerce de arepas: los usuarios pueden navegar el catálogo, buscar productos, agregarlos al carrito, finalizar la compra y contactar al negocio. El administrador gestiona productos y pedidos desde un panel privado.

## ✨ Características

- 🏠 Página de inicio con productos destacados
- 🔍 Búsqueda de productos (`buscar.php`)
- 🛒 Carrito de compras persistente en sesión
- 💳 Checkout y confirmación de pedido
- 👤 Login de clientes y administrador
- 🛠️ Panel de administración para altas/bajas de productos
- 📄 Términos y condiciones, contacto y confirmación

## 🛠️ Stack

`PHP` · `MySQL` · `JavaScript` · `CSS`

## 🚀 Instalación

### Requisitos

- [XAMPP](https://www.apachefriends.org/) (PHP 8+ y MySQL)

### Pasos

1. **Clonar** en `htdocs`:

```bash
cd C:/xampp/htdocs
git clone https://github.com/Ricy24/arepas.git
```

2. **Importar la base de datos**: en `http://localhost/phpmyadmin` crea la base de datos e importa [`database/arepas.sql`](database/arepas.sql).

3. **Configurar la conexión** en [`config/db.php`](config/db.php) con tus credenciales.

4. **Ejecutar**: abre `http://localhost/arepas/`

## 📁 Estructura

```text
arepas/
├── index.php               # Inicio con destacados
├── productos.php           # Catálogo
├── carrito.php             # Carrito de compras
├── checkout.php            # Finalizar compra
├── buscar.php              # Búsqueda
├── admin.php               # Panel de administración
├── config/db.php           # Conexión a MySQL
├── database/*.sql          # Esquema y datos
├── includes/               # Componentes reutilizables
├── login/                  # Autenticación
└── img/ · productos/       # Imágenes y recursos
```

## 📄 Licencia

Proyecto desarrollado con **fines académicos**.
