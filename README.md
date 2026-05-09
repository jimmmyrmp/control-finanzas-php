# Control de Finanzas — Documentación del Proyecto
**Persona 1: Base de datos, Login y estructura del proyecto**

---

## Estructura de carpetas

```
finanzas/                        ← Raíz del proyecto (colocar en htdocs/finanzas/)
│
├── index.php                    ← Login (página de inicio)
├── dashboard.php                ← Dashboard después del login
├── registrar_entrada.php        ← Formulario Registrar Entrada
├── registrar_salida.php         ← Formulario Registrar Salida
├── ver_entradas.php             ← Tabla de entradas con visor de facturas
├── ver_salidas.php              ← Tabla de salidas con visor de facturas
├── balance.php                  ← Reporte + gráfico + exportar PDF
├── logout.php                   ← Cierre de sesión
├── database.sql                 ← Script SQL (importar en phpMyAdmin)
│
├── classes/
│   ├── Conexion.php             ← Conexión PDO (Singleton)
│   ├── Login.php                ← Autenticación y gestión de sesión
│   ├── Entradas.php             ← CRUD de entradas + subida de factura
│   ├── Salidas.php              ← CRUD de salidas + subida de factura
│   └── ReporteBalance.php       ← Lógica del reporte y porcentajes
│
├── includes/
│   └── sidebar.php              ← Barra lateral compartida (menú)
│
├── assets/
│   └── css/
│       └── base.css             ← CSS compartido de páginas internas
│
└── uploads/                     ← Carpeta donde se guardan las facturas
```

---

## Pasos para instalar en XAMPP

1. Copia la carpeta `finanzas/` dentro de `C:\xampp\htdocs\`
2. Abre XAMPP y enciende **Apache** y **MySQL**
3. Abre **phpMyAdmin** (http://localhost/phpmyadmin)
4. Crea una base de datos llamada `finanzas_db` (o deja que el SQL la cree)
5. Ve a la pestaña **SQL** y pega el contenido de `database.sql`, luego ejecuta
6. Abre el navegador en: `http://localhost/finanzas/`

---

## Credenciales de prueba

| Campo    | Valor                |
|----------|----------------------|
| Email    | admin@finanzas.com   |
| Password | admin123             |

---

## Descripción de las clases (POO)

### `Conexion` (Singleton)
- Crea una sola instancia de conexión PDO a MySQL
- Método: `Conexion::obtener()` → retorna el objeto PDO

### `Login`
- `iniciarSesion($email, $password)` → valida y crea la sesión
- `verificarSesion()` → redirige al login si no hay sesión activa (estático)
- `cerrarSesion()` → destruye la sesión (estático)

### `Entradas`
- `registrar($tipo, $monto, $fecha, $archivo)` → inserta en BD y sube factura
- `obtenerTodas()` → devuelve array con todos los registros
- `totalEntradas()` → suma de todos los montos

### `Salidas`
- `registrar($tipo, $monto, $fecha, $archivo)` → inserta en BD y sube factura
- `obtenerTodas()` → devuelve array con todos los registros
- `totalSalidas()` → suma de todos los montos

### `ReporteBalance`
- Usa `Entradas` y `Salidas` internamente
- `getBalance()` → totalEntradas - totalSalidas
- `getPorcentajes()` → retorna array con % para el gráfico de pastel

---

## Librerías externas utilizadas (CDN)
- **Chart.js** — Gráfico de pastel en `balance.php`
- **html2pdf.js** — Exportar reporte a PDF en `balance.php`
- **Google Fonts** — Tipografías Syne + DM Sans
