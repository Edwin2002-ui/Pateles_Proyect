# 🍰 Sistema de Gestión de Pastelería - Prueba Técnica Ecosaba

Este repositorio contiene la solución a la prueba técnica para el puesto de desarrollo. El sistema gestiona las operaciones básicas de una pastelería, contando con un Backend API y un Frontend moderno.

**👨‍💻 Candidato:** Edwin Alexander Chiquitó Burrión

---

## 🛠️ Requisitos del Sistema

Para ejecutar este proyecto, asegúrate de cumplir con las siguientes versiones en tu entorno local:

* **PHP:** Versión `8.2.29` estrictamente recomendada.
* **Node.js:** Versión `> 20`.
* **Base de Datos:** MySQL / MariaDB.

---

## 🚀 Guía de Instalación y Configuración

Sigue estos pasos en orden para desplegar la aplicación correctamente.

### 1. Base de Datos 🗄️
En la raíz del proyecto (o carpeta `database`) encontrarás un archivo `.sql`. Este archivo incluye:
1.  La estructura completa de las tablas y sus relaciones.
2.  Un **Dump de datos** pre-cargados para facilitar la revisión.

**Instrucción:** Importa este archivo en tu gestor de base de datos favorito.

#### 👤 Credenciales de Acceso Rápido
Si importaste el dump de datos, puedes iniciar sesión  con el siguiente usuario de prueba:

* **Correo:** `juan@example.com`
* **Contraseña:** `123456`

> **Nota:** Si prefieres probar el flujo completo, el módulo de **Registro** funciona correctamente, por lo que puedes crear tu propio usuario nuevo sin problemas.

### 2. Configuración del Backend (PHP) ⚙️

#### Variables de Entorno (.env)
Configura tu archivo `.env` (o renombra el de ejemplo) con los siguientes puntos clave:

**A. URL de la Aplicación (`APP_URL`)**
Dependiendo de tu servidor local, ajusta la variable `APP_URL` a la ruta raíz del backend:
* Si usas **XAMPP/WAMP**: `http://localhost/nombre_carpeta_proyecto`
* Si usas **Laragon**: `http://nombre_proyecto.test`

**B. Seguridad JWT (`JWT_SECRET_KEY`)**
El proyecto incluye la siguiente clave solo con fines de demostración/ejemplo:
`JWT_SECRET_KEY=247e8a9ee293d83b443f99f443330083925623ac849feb2361fb45124bbbfb9d`

**Recomendación:** Puedes generar una nueva clave aleatoria y segura ejecutando el siguiente comando en tu terminal:
```bash
php -r "echo bin2hex(random_bytes(32));"