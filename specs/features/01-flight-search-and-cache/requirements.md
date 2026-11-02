# F01: Búsqueda de Vuelos y Caché Inteligente

> **Estado:** Completa  
> **ID:** F01 · **Slug:** `01-flight-search-and-cache`  
> **Prerequisitos:** Ninguno

---

## 1. Historias de Usuario

- **US1.1:** Como viajero, quiero seleccionar mi ciudad de origen, destino y fecha de viaje para consultar las opciones y tarifas de vuelos disponibles.
- **US1.2:** Como viajero, quiero poder intercambiar rápidamente origen y destino con un solo clic para planificar mi regreso sin reescribir datos.
- **US1.3:** Como usuario del sistema, quiero recibir respuestas instantáneas cuando una ruta ya ha sido consultada recientemente sin esperar llamadas lentas a proveedores externos.

---

## 2. Criterios de Aceptación (EARS)

### Happy Path

### R1 — Consulta de vuelos válida
DONDE un usuario autenticado se encuentra en la pantalla de búsqueda,  
CUANDO selecciona un aeropuerto de origen válido, un aeropuerto de destino válido diferente al de origen y una fecha mayor o igual a la actual,  
EL SISTEMA DEBE obtener las ofertas de vuelos disponibles y redirigir al usuario a la vista de resultados  
SIN perder los parámetros de búsqueda seleccionados.

### R2 — Intercambio de origen y destino (Swap)
DONDE el usuario tiene seleccionadas ciudades de origen y destino,  
CUANDO presiona el botón de intercambio,  
EL SISTEMA DEBE permutar los valores entre ambos campos de forma inmediata en la interfaz reactiva.

### R3 — Recuperación desde caché para búsquedas idénticas
CUANDO se solicita la búsqueda de una ruta con el mismo origen, destino y fecha consultada dentro de la ventana de validez de 6 horas,  
EL SISTEMA DEBE retornar los resultados desde la capa de caché  
SIN realizar una llamada HTTP al proveedor externo de datos.

### Validación y Error

### R4 — Validación de parámetros de búsqueda
CUANDO el usuario intenta buscar con campos vacíos, códigos de aeropuerto inválidos o una fecha en el pasado,  
EL SISTEMA DEBE rechazar la petición mostrando mensajes de validación claros en el formulario  
SIN ejecutar consultas a proveedores externos ni persistir registros inválidos.

### R5 — Tolerancia a fallos del proveedor externo
SI el proveedor externo de vuelos no responde o retorna un error de comunicación,  
EL SISTEMA DEBE registrar el incidente en logs y notificar al usuario con un mensaje comprensible  
SIN romper la sesión ni mostrar errores no controlados.
