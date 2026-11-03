# Arquitectura y Control de Costos de API — FlightPrices

## El Desafío Económico

El proveedor principal de datos de vuelos es **SerpAPI (Google Flights)**. 
- **Plan Free Tier:** 250 búsquedas por mes.
- **Costo de desborde:** $0.01 por búsqueda adicional o upgrade a plan pago ($50+/mes).

Para que la aplicación sea financieramente viable en su etapa de crecimiento, el software **no puede realizar consultas 1:1 a la API externa** por cada búsqueda o usuario.

---

## Estrategias Arquitectónicas de Optimización

### 1. Desacoplamiento por Strategy Pattern
El dominio nunca interactúa directamente con `Http::get('serpapi...')`.
- Interfaz: [`app/Contracts/FlightProviderInterface.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Contracts/FlightProviderInterface.php)
- Implementación: [`app/Integrations/SerpApi/SerpApiProvider.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Integrations/SerpApi/SerpApiProvider.php)
- Permite cambiar de proveedor (Amadeus, Skyscanner, RapidAPI) o usar fakes en testing sin tocar la lógica de negocio ni consumir créditos.

### 2. Caché Flexible Global (Laravel 13)
[`app/Actions/FetchFlightsAction.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Actions/FetchFlightsAction.php) utiliza `Cache::flexible()`:
- **Clave:** `flights:search:{origin}:{destination}:{date}`
- **TTL Fresco:** 6 horas (21,600 segundos).
- **TTL Stale (Gracia):** 12 horas (43,200 segundos).
Si 5 usuarios buscan Bogotá a Medellín para el próximo viernes en una ventana de 6 horas, solo se consume **1 crédito de SerpAPI**.

### 3. Agrupamiento y Deduplicación en el Scheduler
En [`app/Console/Commands/CheckFlightsCommand.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Console/Commands/CheckFlightsCommand.php):
- Se obtienen todas las búsquedas activas de todos los usuarios.
- Se agrupan por clave de ruta única: `"{origin}-{destination}-{date}"`.
- Se realiza **una sola llamada a la API por grupo de ruta**, y los resultados se distribuyen a cada usuario del grupo.

### 4. Frecuencia de Rastreo Adaptativa (`shouldUpdate`)
No todos los vuelos cambian de precio al mismo ritmo. La frecuencia se ajusta según la proximidad del vuelo:

| Días hasta el vuelo | Frecuencia de chequeo | Consumo mensual por ruta |
|---|---|:---:|
| **< 7 días** (Crítico) | Cada 6 horas (4 veces/día) | ~120 reqs/mes |
| **7 a 30 días** (Medio) | Cada 12 horas (2 veces/día) | ~60 reqs/mes |
| **> 30 días** (Largo plazo) | Cada 24 horas (1 vez/día) | ~30 reqs/mes |

---

## Modelo de Proyección de Consumo

| Escenario | Búsquedas Activas | Rutas Únicas | Estrategia de Ahorro | Req/Mes Estimadas | Estado |
|---|:---:|:---:|---|:---:|:---:|
| **1 Usuario (MVP)** | 1 | 1 | Frecuencia adaptativa | ~60 - 120 | ✅ Cubierto (Free Tier) |
| **5 Usuarios** | 5 | 3 (coincidentes) | Agrupamiento + Caché | ~150 - 200 | ✅ Cubierto (Free Tier) |
| **10 Usuarios** | 10 | 7 | Agrupamiento + Caché | ~350 - 450 | ⚠️ Requiere plan pago o segundo proveedor |
