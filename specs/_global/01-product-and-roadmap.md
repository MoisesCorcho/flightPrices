# Producto y Roadmap — FlightPrices

## Visión del Producto

**FlightPrices** es una plataforma web progresiva (PWA) de monitoreo inteligente de vuelos y alertas de baja de precios. Su propuesta de valor central es permitir a viajeros registrar rutas de interés con un precio objetivo y recibir notificaciones push en tiempo real cuando las aerolíneas bajen sus tarifas, optimizando al máximo las consultas a proveedores externos para garantizar viabilidad económica en tiers gratuitos y de pago.

---

## Core Loop de la Aplicación

```text
[1. Búsqueda]          Usuario busca origen, destino y fecha.
      │
      ▼
[2. Cache / Fetch]     El sistema consulta caché flexible o proveedor externo (SerpAPI).
      │
      ▼
[3. Seguimiento]       Se activa un rastreo automático con precio objetivo (Target Price).
      │
      ▼
[4. Scheduler]         `flights:check` agrupa rutas únicas y evalúa frecuencia dinámica.
      │
      ▼
[5. Alerta Push]       Si el precio baja del objetivo, se dispara notificación FCM al dispositivo.
```

---

## Matriz de Features y Roadmap

| ID | Slug | Estado | Prerequisitos | Descripción breve |
|:---|:---|:---:|:---|:---|
| **F01** | `01-flight-search-and-cache` | `Completa` | Ninguno | Búsqueda de vuelos, caché flexible Laravel 13 y desacoplamiento con `FlightProviderInterface`. |
| **F02** | `02-tracking-and-alerts` | `Completa` | F01 | Comando programado (`flights:check`), deduplicación por ruta, frecuencia adaptativa y push FCM. |
| **F03** | `03-auth-and-profile` | `Completa` | Ninguno | Aislamiento multiusuario con Fortify, Google OAuth (Socialite) y registro de `device_tokens`. |
| **F04** | `04-price-history-analytics` | `Lista para implementar` | F01, F02 | Gráficas de evolución histórica de precios por vuelo/ruta y recomendación de compra. |
| **F05** | `05-quota-and-tiers` | `No iniciada` | F03 | Límites de búsquedas activas concurrentes por usuario y planes freemium vs premium. |

---

## Dependencias entre Features

```text
       ┌───────────┐       ┌──────────────────────┐
       │ F03: Auth │       │ F01: Search & Cache  │
       └─────┬─────┘       └──────────┬───────────┘
             │                        │
             │                        ▼
             │             ┌──────────────────────┐
             └────────────►│ F02: Tracking & FCM  │
                           └──────────┬───────────┘
                                      │
                                      ▼
                           ┌──────────────────────┐
                           │ F04: Price Analytics │
                           └──────────────────────┘
```
