# Tasks — F02: Monitoreo Periódico y Alertas Push (FCM)

## Checklist de Tareas

### 1. Scheduler y Agrupamiento
- [x] Crear comando `flights:check` con agrupación por ruta única. _(cubre R2)_
- [x] Implementar método `shouldUpdate()` con frecuencia adaptativa (<7d: 6h, <30d: 12h, >30d: 24h). _(cubre R3)_
- [x] Programar la invocación en `routes/console.php` con `Schedule::command('flights:check')->everySixHours()`. _(cubre R2)_

### 2. Procesamiento de Vuelos y Notificaciones
- [x] Implementar `ProcessFlightsAction` guardando resultados históricos e identificando bajas de precio. _(cubre R1, R4)_
- [x] Prevenir notificaciones duplicadas mediante verificación de existencia previa. _(cubre R5)_
- [x] Configurar `PriceDropNotification` utilizando `FcmChannel` para entrega push. _(cubre R4)_
- [x] Crear comando de prueba `flights:test-notify`. _(cubre R6)_

### 3. Tests de Verificación (Pest)
- [x] Feature test: `tests/Feature/PriceNotificationTest.php` probando que se envía notificación ante caída de precio. _(cubre R4, R5)_

---

## Matriz de Trazabilidad

| Criterio | Tareas asociadas | Test de verificación |
|:---:|:---|:---|
| **R1** | 2.1 | `tests/Feature/PriceNotificationTest.php` |
| **R2** | 1.1, 1.3 | Ejecución de `flights:check` en test suite |
| **R3** | 1.2 | Unit tests de `shouldUpdate()` |
| **R4** | 2.1, 2.3, 3.1 | `tests/Feature/PriceNotificationTest.php` |
| **R5** | 2.2, 3.1 | `tests/Feature/PriceNotificationTest.php` |
| **R6** | 2.4 | `php artisan flights:test-notify` |

---

## Definition of Done (DoD)

- [x] Criterios R1 a R6 implementados y comprobados.
- [x] Notificaciones push verificadas con FCM token en base de datos.
- [x] Cobertura de tests en verde sin errores en el pipeline.
