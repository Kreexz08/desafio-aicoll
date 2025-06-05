# Desafío Aicoll - API de Gestión de Empresas

Este repositorio contiene la implementación de una API REST para la gestión de empresas, desarrollada como parte del desafío técnico de Aicoll. La API permite realizar operaciones CRUD sobre un registro de empresas, siguiendo una arquitectura limpia y principios de diseño robustos. 

## Características Principales

* **Creación de Empresas:** Agregar nuevas empresas con validación de datos. 
* **Actualización de Datos:** Modificar nombre, dirección, teléfono y estado de una empresa existente. 
* **Consulta de Empresas:** Obtener una empresa específica por su NIT o listar todas las empresas con filtros y paginación. 
* **Eliminación de Empresas:** Realizar un borrado de una empresa por nit de una empresa inactiva o un borrado masivo de todas las empresas inactivas. 
* **Arquitectura Avanzada:** Implementación de CQRS (Command Query Responsibility Segregation) y principios de Diseño Guiado por el Dominio (DDD). 
* **Manejo de Errores:** Sistema de excepciones personalizadas para respuestas de API claras y consistentes. 
* **Pruebas Unitarias:** Cobertura de pruebas para asegurar la fiabilidad y el correcto funcionamiento de la lógica de negocio. 

## Arquitectura y Estructura del Proyecto

El proyecto está organizado siguiendo los principios de la Arquitectura Limpia y el patrón CQRS para separar las responsabilidades y maximizar la mantenibilidad y escalabilidad. Las capas principales son:

### Capa de Dominio (`app/Domain`)
Es el núcleo de la aplicación. Contiene toda la lógica de negocio y no depende de ninguna otra capa.
* **Entidades (`Entities`):** Representan los objetos de negocio principales (ej. `Empresa`).
* **Value Objects (`ValueObjects`):** Objetos inmutables que representan valores simples del dominio con sus propias reglas de validación (ej. `Nit`, `Estado`).
* **Interfaces de Repositorio (`Interfaces`):** Definen los contratos para la persistencia de datos, abstrayendo la capa de dominio de los detalles de la base de datos.
* **Excepciones de Dominio (`Exceptions`):** Errores específicos de las reglas de negocio (ej. `DuplicateNitException`).
* **Casting (`Casts`):** Clases para convertir automáticamente datos de la BD a Value Objects del Dominio.

### Capa de Aplicación (`app/Application`)
Orquesta los flujos de trabajo y casos de uso. No contiene lógica de negocio, sino que dirige a las entidades de dominio para que la ejecuten.
* **Comandos (`Commands`):** Objetos que representan una intención de cambiar el estado del sistema (ej. `CreateEmpresaCommand`).
* **Consultas (`Queries`):** Objetos que representan una solicitud de datos (ej. `GetEmpresaQuery`).
* **Manejadores (`Handlers`):** Clases que procesan los Comandos y Consultas, interactuando con el dominio y el repositorio.


### Capa de Infraestructura (`app/Infrastructure`)
Contiene los detalles de implementación de tecnologías externas, como la base de datos.
* **Modelos Eloquent (`Models`):** Implementación concreta para interactuar con la base de datos usando el ORM de Laravel (ej. `EmpresaModel`).
* **Repositorios (`Repositories`):** Implementación concreta de las interfaces de repositorio definidas en el Dominio (ej. `EloquentEmpresaRepository`).


### Capa de Presentación (`app/Presenter`)
Es el punto de entrada a la aplicación. Para esta API, maneja las solicitudes y respuestas HTTP.
* **Controladores (`Http`):** Controladores delgados (a menudo de acción única) que reciben la solicitud HTTP.
* **Form Requests (`Http`):** Clases que manejan la validación de la entrada HTTP antes de que llegue al controlador.
* **Manejador de Excepciones (`Exceptions`):** Personalización del manejador de excepciones de Laravel para devolver respuestas JSON estructuradas.

## Requisitos Técnicos

* PHP >= 8.2
* Composer
* Laravel >= 10.x 
* Un motor de base de datos soportado por Laravel (ej. MySQL, PostgreSQL, SQLite). 

## Instalación y Configuración

Sigue estos pasos para poner en marcha el proyecto en un entorno local:

1.  **Clonar el repositorio:**
    ```bash
    git clone [https://github.com/Kreexz08/desafio-aicoll.git](https://github.com/Kreexz08/desafio-aicoll.git)
    cd desafio-aicoll
    ```

2.  **Instalar dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Configurar el archivo de entorno:**
    * Copia el archivo de ejemplo `.env.example` a un nuevo archivo llamado `.env`:
        ```bash
        cp .env.example .env
        ```
    * Abre el archivo `.env` y configura tus credenciales de base de datos (DB_DATABASE, DB_USERNAME, DB_PASSWORD, etc.).

4.  **Generar la clave de la aplicación:**
    ```bash
    php artisan key:generate
    ```

5.  **Ejecutar las migraciones y los seeders:**
    * Este comando creará la tabla `empresas` y la poblará con datos de prueba.
        ```bash
        php artisan migrate:fresh --seed
        ```

6.  **Iniciar el servidor de desarrollo:**
    ```bash
    php artisan serve
    ```
    La API estará disponible en `http://127.0.0.1:8000`.

## Documentación de la API (Endpoints)

Todas las respuestas y envíos de datos son en formato JSON.

### 1. Listar Empresas
-   **Funcionalidad:** Obtiene una lista de todas las empresas. Soporta paginación y filtrado por estado. 
-   **Método:** `GET`
-   **URI:** `/empresas`
-   **Parámetros de Consulta (Opcionales):**
    -   `estado` (string): Filtra por `Activo` o `Inactivo`.
    -   `page` (integer): Número de página para la paginación.
    -   `perPage` (integer): Número de resultados por página.
-   **Respuesta Exitosa (200 OK):**
    ```json
    {
        "data": [
            {
                "id": 1,
                "nit": "900111222-1",
                "nombre": "TecnoSoluciones Activa SAS",
                "direccion": "Avenida Siempre Viva 742",
                "telefono": "3012345678",
                "estado": "Activo",
                "created_at": "...",
                "updated_at": "..."
            }
        ],
        "pagination": {
            "total": 5,
            "per_page": 15,
            "current_page": 1,
            "last_page": 1
        }
    }
    ```

### 2. Obtener una Empresa por NIT
-   **Funcionalidad:** Devuelve los detalles de una empresa específica. 
-   **Método:** `GET`
-   **URI:** `/empresas/{nit}`
-   **Ejemplo:** `/empresas/900111222-1`
-   **Respuesta Exitosa (200 OK):**
    ```json
    {
        "id": 1,
        "nit": "900111222-1",
        "nombre": "TecnoSoluciones Activa SAS",
        "direccion": "Avenida Siempre Viva 742",
        "telefono": "3012345678",
        "estado": "Activo",
        "created_at": "...",
        "updated_at": "..."
    }
    ```
-   **Respuesta de Error (404 Not Found):** Si la empresa con el NIT especificado no existe.

### 3. Crear una Nueva Empresa
-   **Funcionalidad:** Registra una nueva empresa. El estado por defecto es 'Activo'. 
-   **Método:** `POST`
-   **URI:** `/empresas`
-   **Cuerpo de la Solicitud (JSON):**
    ```json
    {
    "id": 1,
    "nit": "900111222-1",
    "nombre": "TecnoSoluciones Activa SAS",
    "direccion": "Avenida Siempre Viva 742",
    "telefono": "3012345678",
    "estado": "Activo",
    "created_at": "2025-06-05 23:09:09",
    "updated_at": "2025-06-05 23:09:09"
    }
    ```
-   **Respuesta Exitosa (201 Created):** Devuelve el objeto de la empresa recién creada.
-   **Respuestas de Error:**
    -   **409 Conflict:** Si el NIT ya existe. 
    -   **422 Unprocessable Entity:** Si los datos de entrada no superan la validación. 

### 4. Actualizar una Empresa
-   **Funcionalidad:** Actualiza los datos de una empresa existente (nombre, dirección, teléfono, estado). 
-   **Método:** `PUT`
-   **URI:** `/empresas/{nit}`
-   **Cuerpo de la Solicitud (JSON):**
    ```json
    {
        
        "id": 1,
        "nit": "900111222-1",
        "nombre": "TecnoSoluciones Activa SAS",
        "direccion": "Avenida Siempre Viva 487",
        "telefono": "3003000000",
        "estado": "Activo",
        "created_at": "2025-06-05 23:09:09",
        "updated_at": "2025-06-05 23:10:24"

    }
    ```
-   **Respuesta Exitosa (200 OK):** Devuelve el objeto de la empresa con los datos actualizados.
-   **Respuestas de Error:**
    -   **404 Not Found:** Si la empresa con el NIT especificado no existe.
    -   **422 Unprocessable Entity:** Si los datos de entrada no superan la validación.

### 5. Eliminar Todas las Empresas Inactivas
-   **Funcionalidad:** Realiza un borrado masivo de todas las empresas con estado "Inactivo". 
-   **Método:** `POST`
-   **URI:** `/empresas/delete-inactive`
-   **Respuesta Exitosa (200 OK):**
    ```json
    {
        "message": "Se eliminaron 2 empresas inactivas."
    }
    ```

### 6. Eliminar una Empresa por NIT
-   **Funcionalidad:** Elimina una empresa específica. La lógica implementada solo permite eliminar empresas que ya se encuentren en estado "Inactivo".
-   **Método:** `DELETE`
-   **URI:** `/empresas/{nit}`
-   **Respuesta Exitosa (204 No Content):** No devuelve cuerpo.
-   **Respuestas de Error:**
    -   **404 Not Found:** Si la empresa no existe.
    -   **422 Unprocessable Entity:** Si se intenta eliminar una empresa con estado "Activo".

## Pruebas

El proyecto incluye un conjunto de pruebas unitarias para validar la lógica del dominio y la aplicación, demostrando el conocimiento en esta área como se sugiere en los criterios de evaluación. 

-   **Para ejecutar todas las pruebas:**
    ```bash
    php artisan test
    ```
-   **Para ejecutar un archivo de prueba específico:**
    ```bash
    php artisan test tests/Unit/Domain/Empresa/ValueObjects/NitTest.php
    ```
