# Diseño Técnico — F02: Monitoreo Periódico y Alertas Push (FCM)

## Flujo del Scheduler

```text
[Cron: schedule:run]
       │
       ▼ (everySixHours)
[Command: flights:check]
       │
       ├─► Filtra búsquedas activas vía shouldUpdate()
       │
       ├─► Agrupa por ruta: "{origin}-{destination}-{date}"
       │
       ├─► FetchFlightsAction (1 sola llamada API por grupo)
       │
       ▼
[Action: ProcessFlightsAction]
       │
       ├─► Guarda histórico en flight_results
       │
       ▼ ¿price <= target_price && !notified?
[Notification: PriceDropNotification] ───► [Channel: FcmChannel] ───► FCM Web Push
```

---

## 1. Comandos Console

- [`app/Console/Commands/CheckFlightsCommand.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Console/Commands/CheckFlightsCommand.php):
  - Signature: `flights:check`
  - Inyecta `FetchFlightsAction` y `ProcessFlightsAction`.
  - Agrupa por clave `{$search->origin}-{$search->destination}-{$search->date->format('Y-m-d')}`.
  - Implementa lógica de ventana en `shouldUpdate(FlightSearch $search): bool`.
- [`app/Console/Commands/TestNotificationCommand.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Console/Commands/TestNotificationCommand.php):
  - Signature: `flights:test-notify`
  - Dispara un push dummy hacia el primer usuario para verificación rápida de credenciales.

---

## 2. Acciones y Notificaciones

- [`app/Actions/ProcessFlightsAction.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Actions/ProcessFlightsAction.php):
  - Persiste cada opción en `FlightResult`.
  - Si `target_price` coincide y `FlightNotification` no existe previamente, persiste la alerta y dispara `$user->notify(new PriceDropNotification($notification))`.
- [`app/Notifications/PriceDropNotification.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Notifications/PriceDropNotification.php):
  - Canal: `FcmChannel::class`.
  - Carga útil: Título, cuerpo con ruta y precio, y URL de redirección a la alerta.

---

## 3. Modelo de Datos Involucrado

- `FlightSearch`: `user_id`, `origin`, `destination`, `date`, `target_price`, `is_active`.
- `FlightResult`: `flight_search_id`, `airline`, `price`, `departure_time`, `duration`.
- `FlightNotification`: `user_id`, `flight_result_id`, `origin`, `destination`, `price`, etc.
- `DeviceToken`: `user_id`, `token`.
