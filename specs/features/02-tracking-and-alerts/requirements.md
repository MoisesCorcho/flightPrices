# F02: Monitoreo Periódico y Alertas Push (FCM)

> **Estado:** Completa  
> **ID:** F02 · **Slug:** `02-tracking-and-alerts`  
> **Prerequisitos:** F01 (`01-flight-search-and-cache`), F03 (`03-auth-and-profile`)

---

## 1. Historias de Usuario

- **US2.1:** Como viajero, quiero activar el seguimiento de un vuelo con un precio deseado (target price) para no tener que revisar la web todos los días.
- **US2.2:** Como viajero, quiero recibir una notificación push en mi celular cuando una aerolínea baje el precio por debajo o igual a mi objetivo.
- **US2.3:** Como usuario, no quiero recibir notificaciones repetidas por la misma oferta de vuelo ya notificada.

---

## 2. Criterios de Aceptación (EARS)

### Happy Path

### R1 — Activación de seguimiento de vuelo
DONDE un usuario autenticado visualiza los resultados de búsqueda,  
CUANDO define un precio objetivo y activa el rastreo,  
EL SISTEMA DEBE persistir la búsqueda como activa (`is_active = true`) asociada a su cuenta  
SIN deshabilitar el resto de sus alertas permitidas.

### R2 — Agrupamiento de rutas en el Scheduler
CUANDO el comando programado `flights:check` se ejecuta periódicamente,  
EL SISTEMA DEBE agrupar todas las búsquedas activas que requieran actualización por su ruta única (`origen-destino-fecha`),  
consultar los vuelos una sola vez por ruta y procesar los resultados para todos los usuarios de ese grupo.

### R3 — Frecuencia de rastreo adaptativa (`shouldUpdate`)
CUANDO el scheduler evalúa una búsqueda activa:
- SI la fecha del vuelo es menor a 7 días, se actualiza si pasaron 6 o más horas desde la última revisión.
- SI la fecha del vuelo está entre 7 y 30 días, se actualiza si pasaron 12 o más horas.
- SI la fecha del vuelo es mayor a 30 días, se actualiza si pasaron 24 o más horas.  
EL SISTEMA DEBE omitir búsquedas que no cumplan su ventana temporal respectiva.

### R4 — Disparo de notificación push ante baja de precio
CUANDO se procesan los resultados de un vuelo y su precio es menor o igual al `target_price` fijado por el usuario,  
EL SISTEMA DEBE crear el registro de notificación y enviar una notificación push vía FCM a todos los dispositivos registrados del usuario  
SIN bloquear la ejecución de la cola o proceso principal.

### Validación y Error

### R5 — Prevención de alertas duplicadas
CUANDO un vuelo con precio bajo ya generó una notificación previa para el mismo usuario y el mismo resultado de vuelo,  
EL SISTEMA DEBE evitar el reenvío de la notificación push.

### R6 — Comando de verificación manual
CUANDO un administrador o desarrollador ejecuta `php artisan flights:test-notify` en consola,  
EL SISTEMA DEBE enviar una alerta de prueba al primer usuario de la base de datos confirmando la correcta conectividad con Firebase.
