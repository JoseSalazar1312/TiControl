# TiControl

Sistema interno para la gestión de inventario de equipos de cómputo y asignaciones a empleados, con generación automática de responsivas firmadas.

## Stack tecnológico

- **Backend:** Laravel 12 (PHP 8.4)
- **Frontend:** Livewire 3 + Blade + Tailwind CSS (vía Laravel Breeze)
- **Base de datos:** PostgreSQL 16
- **Servidor web:** Nginx
- **Contenedores:** Docker + Docker Compose

## Arquitectura

El proyecto corre en 3 contenedores conectados por una red interna de Docker:

```
┌─────────┐      ┌──────────────┐      ┌────────────┐
│  Nginx  │ ───▶ │  PHP-FPM     │ ───▶ │  Postgres  │
│ (puerto │      │  (Laravel)   │      │  (puerto   │
│  8080)  │      │              │      │   5432)    │
└─────────┘      └──────────────┘      └────────────┘
```

- **nginx**: recibe las peticiones HTTP en el puerto `8080` y las reenvía a PHP-FPM.
- **app**: contenedor con PHP 8.4-FPM donde corre el código de Laravel.
- **postgres**: base de datos PostgreSQL 16, con datos persistentes en un volumen Docker.

## Estructura de carpetas

```
TiControl/
├── docker/
│   ├── nginx/
│   │   └── default.conf       # Configuración de Nginx
│   └── php/
│       └── Dockerfile          # Imagen de PHP-FPM con extensiones necesarias
├── src/                         # Proyecto Laravel (código de la aplicación)
├── docker-compose.yml
├── .env                         # Variables para docker-compose (NO se sube a git)
├── .env.example                 # Plantilla de referencia
└── README.md
```

## Requisitos previos

- Docker y Docker Compose instalados
- Git
- Node.js y npm instalados **en la máquina host** (para compilar assets con Vite)

No necesitas tener PHP, Composer ni Postgres instalados directamente en tu máquina — todo corre dentro de los contenedores. Node.js es la única excepción porque el contenedor `app` no lo incluye.

## Instalación (primera vez)

### 1. Clonar el repositorio

```bash
git clone <url-del-repo> TiControl
cd TiControl
```

### 2. Crear los archivos de entorno

Copia las plantillas y ajusta los valores según necesites:

```bash
cp .env.example .env
cp src/.env.example src/.env
```

Edita **ambos** archivos `.env` y asegúrate de que las credenciales de base de datos coincidan exactamente entre los dos:

**`.env`** (raíz, usado por docker-compose):
```env
DB_DATABASE=ticontrol
DB_USERNAME=ticontrol_user
DB_PASSWORD=tu_password_segura
```

**`src/.env`** (usado por Laravel):
```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=ticontrol
DB_USERNAME=ticontrol_user
DB_PASSWORD=tu_password_segura
```

> **Importante:** `DB_HOST` debe ser `postgres` (el nombre del servicio en `docker-compose.yml`), no `127.0.0.1` ni `localhost`. Dentro de la red de Docker, los contenedores se encuentran por nombre de servicio.

### 3. Levantar los contenedores

```bash
docker compose up -d --build
```

Esto construye la imagen de PHP y levanta los 3 servicios en segundo plano.

### 4. Instalar dependencias de PHP

```bash
docker compose exec app composer install
```

### 5. Generar la clave de aplicación de Laravel

```bash
docker compose exec app php artisan key:generate
```

### 6. Ejecutar las migraciones

```bash
docker compose exec app php artisan migrate
```

### 7. Instalar y compilar dependencias de frontend

Este paso se ejecuta **en la máquina host** (no dentro del contenedor), posicionado en la carpeta `src`:

```bash
cd src
npm install && npm run build
```

> Los assets compilados quedan en `src/public/build/`, que está montado como volumen y es accesible por el contenedor automáticamente.

### 8. Verificar que todo funciona

Abre el navegador en [http://localhost:8080](http://localhost:8080) — deberías ver la pantalla de bienvenida de Laravel con los links **Log in** y **Register** en la esquina superior derecha.

## Comandos del día a día

| Acción | Comando |
|---|---|
| Levantar el stack | `docker compose up -d` |
| Apagar el stack | `docker compose down` |
| Ver logs en vivo | `docker compose logs -f` |
| Ver estado de contenedores | `docker compose ps` |
| Entrar a la terminal del contenedor `app` | `docker compose exec app bash` |
| Correr un comando artisan | `docker compose exec app php artisan <comando>` |
| Correr un comando composer | `docker compose exec app composer <comando>` |
| Entrar a psql directamente | `docker compose exec postgres psql -U ticontrol_user -d ticontrol` |
| Reconstruir la imagen tras cambiar el Dockerfile | `docker compose up -d --build app` |
| Compilar assets (modo desarrollo con hot reload) | `cd src && npm run dev` |
| Compilar assets (modo producción) | `cd src && npm run build` |

## Notas de desarrollo

- Si cambias el `Dockerfile` de PHP, necesitas reconstruir la imagen con `--build`.
- Si Laravel no logra conectar a Postgres en el primer arranque, espera unos segundos y reinicia el contenedor `app` (`docker compose restart app`) — Postgres puede tardar un poco más en estar listo para aceptar conexiones.
- Los datos de Postgres persisten en un volumen Docker (`ticontrol_postgres_data`). Si necesitas reiniciar la base de datos desde cero: `docker compose down -v` (esto borra el volumen, ¡y todos los datos!).
- Los assets de frontend (`node_modules/`, `public/build/`) no se suben al repositorio. Cada vez que clones el proyecto en una máquina nueva, corre `npm install && npm run build` desde la carpeta `src/`.
- Durante el desarrollo activo, puedes usar `npm run dev` en vez de `npm run build` para obtener hot reload al editar archivos Blade o CSS.

## Esquema de base de datos

El sistema maneja 13 tablas organizadas en los siguientes grupos:

| Grupo | Tablas |
|---|---|
| Catálogos | `sites` |
| Personas | `employees`, `system_users` |
| Activos | `equipment`, `belarc_reports`, `peripherals` |
| Movimientos | `assignments`, `peripheral_assignments`, `repairs` |
| Historial | `asset_history`, `responsivas` |
| Auditoría | `audit_log` *(fase posterior)* |

Las tablas `peripherals`, `peripheral_assignments` y `audit_log` tienen migración creada pero sin módulo de UI en el MVP.

## Roadmap del MVP

- [x] Stack dockerizado (Nginx + PHP-FPM + Postgres)
- [x] Laravel 12 instalado y conectado a Postgres
- [x] Migraciones ejecutadas y verificadas (13 tablas)
- [x] Autenticación instalada (Laravel Breeze + Livewire)
- [ ] Ajuste de auth para usar tabla `system_users`
- [ ] Módulo de Sedes
- [ ] Módulo de Empleados (alta, edición, baja lógica)
- [ ] Módulo de Equipos (alta, edición, cambio de estado)
- [ ] Módulo de Asignaciones (asignar, liberar, historial)
- [ ] Módulo de Reparaciones
- [ ] Parser de Belarc (PDF → datos estructurados)
- [ ] Generación automática de responsiva (.docx con plantilla)
- [ ] Dashboard (contadores y últimos movimientos)
- [ ] Módulo de Reportes (visualización filtrable)