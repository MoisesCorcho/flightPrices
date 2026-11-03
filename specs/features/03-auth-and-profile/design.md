# Diseño Técnico — F03: Autenticación Multiusuario y Aislamiento de Alertas

## Arquitectura de Autenticación

```text
[Browser / PWA]
       │
       ├─► [Fortify: Login/Register standard]
       │
       └─► [GoogleAuthController] ───► [Socialite Google Provider]
                    │
                    ▼
         UpdateOrCreate User by email / google_id
                    │
                    ▼
            Auth::login($user)
```

---

## 1. Controladores y Rutas

- [`app/Http/Controllers/Auth/GoogleAuthController.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Http/Controllers/Auth/GoogleAuthController.php):
  - `redirect()`: Redirige a Google usando Socialite.
  - `callback()`: Recupera el usuario OAuth, crea o actualiza en la tabla `users` asociando `google_id` y `avatar`, e inicia sesión con `Auth::login($user)`.
- Rutas en [`routes/web.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/routes/web.php):
  - `GET /auth/google` (`auth.google`)
  - `GET /auth/google/callback`
  - `POST /fcm-token` (asocia tokens a `$request->user()`)
  - Grupo `middleware(['auth'])` protegiendo `/search`, `/results`, `/searches`, `/alerts`.

---

## 2. Componentes UI

- [`resources/views/components/google-button.blade.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/resources/views/components/google-button.blade.php): Botón estilizado reutilizable en vistas de autenticación.
- [`resources/views/livewire/auth/login.blade.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/resources/views/livewire/auth/login.blade.php) y [`resources/views/livewire/auth/register.blade.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/resources/views/livewire/auth/register.blade.php).

---

## 3. Modelo de Datos

- Tabla `users`: se agregaron columnas `google_id` y `avatar` vía migración [`database/migrations/2026_05_19_222745_add_google_id_to_users_table.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/database/migrations/2026_05_19_222745_add_google_id_to_users_table.php).
- Relaciones en [`app/Models/User.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Models/User.php):
  - `searches()`: `hasMany(FlightSearch::class)`
  - `notifications()`: `hasMany(FlightNotification::class)`
  - `deviceTokens()`: `hasMany(DeviceToken::class)`
  - `routeNotificationForFcm()`: retorna tokens del usuario autenticado.
