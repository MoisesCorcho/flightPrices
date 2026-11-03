# F03: Autenticación Multiusuario y Aislamiento de Alertas

> **Estado:** Completa  
> **ID:** F03 · **Slug:** `03-auth-and-profile`  
> **Prerequisitos:** Ninguno

---

## 1. Historias de Usuario

- **US3.1:** Como usuario, quiero registrarme e iniciar sesión con correo y contraseña o con mi cuenta de Google para acceder de forma rápida y segura.
- **US3.2:** Como usuario autenticado, quiero que mis búsquedas y alertas sean privadas y solo visibles para mí.
- **US3.3:** Como usuario en un navegador móvil o de escritorio, quiero que mi token de dispositivo FCM se guarde en mi cuenta para recibir alertas push.

---

## 2. Criterios de Aceptación (EARS)

### Happy Path

### R1 — Autenticación con Google OAuth (Socialite)
DONDE un usuario está en las pantallas de Login o Registro,  
CUANDO presiona el botón "Continue with Google",  
EL SISTEMA DEBE redirigirlo a Google y, al recibir el callback válido, autenticar al usuario existente o registrar uno nuevo  
SIN solicitar contraseña manual.

### R2 — Aislamiento multiusuario estricto
DONDE un usuario autenticado consulta `/searches` o `/alerts`,  
EL SISTEMA DEBE listar únicamente las búsquedas y notificaciones pertenecientes a su `user_id`  
SIN exponer información ni resultados de otros usuarios.

### R3 — Registro de token de dispositivo FCM
DONDE un usuario autenticado otorga permisos de notificaciones push en el navegador,  
CUANDO el cliente envía su token al endpoint `/fcm-token`,  
EL SISTEMA DEBE registrar o actualizar el token en `device_tokens` asociado a dicho usuario  
SIN duplicar registros para el mismo dispositivo.

### R4 — Protección de rutas privadas
CUANDO un usuario invitado (guest) intenta acceder a rutas protegidas (`/search`, `/results`, `/alerts`, `/searches`),  
EL SISTEMA DEBE redirigirlo automáticamente a la vista de login.

### Validación y Error

### R5 — Fallo o cancelación en Google OAuth
SI el usuario cancela la autorización en Google o el token de OAuth resulta inválido,  
EL SISTEMA DEBE capturar la excepción y redirigir al login con un mensaje de error informativo.
