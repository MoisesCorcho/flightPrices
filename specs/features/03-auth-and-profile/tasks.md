# Tasks — F03: Autenticación Multiusuario y Aislamiento de Alertas

## Checklist de Tareas

### 1. Base de Datos y Modelo
- [x] Migración: agregar `google_id` y `avatar` a la tabla `users`. _(cubre R1)_
- [x] Configurar relaciones `searches`, `notifications` y `deviceTokens` en `User.php`. _(cubre R2, R3)_

### 2. Socialite y Controladores
- [x] Instalar y configurar credenciales de Google OAuth en `config/services.php`. _(cubre R1)_
- [x] Implementar `GoogleAuthController` con `redirect()` y `callback()`. _(cubre R1, R5)_
- [x] Crear componente `<x-google-button />` e insertarlo en vistas de login y registro. _(cubre R1)_

### 3. Aislamiento y Rutas
- [x] Proteger rutas privadas con middleware `auth` en `routes/web.php`. _(cubre R4)_
- [x] Ajustar `FlightSearch`, `MySearches` y `MyAlerts` para filtrar por `$user->id`. _(cubre R2)_
- [x] Implementar endpoint `/fcm-token` para registrar `DeviceToken` por usuario. _(cubre R3)_

---

## Matriz de Trazabilidad

| Criterio | Tareas asociadas | Test de verificación |
|:---:|:---|:---|
| **R1** | 1.1, 2.1, 2.2, 2.3 | Test de integración de callback Google |
| **R2** | 1.2, 3.2 | Pest tests de vistas aisladas |
| **R3** | 1.2, 3.3 | Endpoint `/fcm-token` |
| **R4** | 3.1 | Test de redirección guest a login |
| **R5** | 2.2 | Manejo de excepción en `GoogleAuthController` |

---

## Definition of Done (DoD)

- [x] Criterios R1 a R5 implementados y funcionando en producción.
- [x] Pruebas de autenticación y aislamiento pasando en verde.
- [x] Formateo Pint verificado sin inconsistencias.
