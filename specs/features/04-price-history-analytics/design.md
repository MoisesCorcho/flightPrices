# Diseño Técnico — F04: Historial de Precios y Recomendación de Compra

## Arquitectura de Datos y Métricas

```text
[FlightSearch]
      │
      └───► hasMany [FlightResult] (ordenados por created_at ASC)
                 │
                 ▼
     [Action: CalculatePriceAnalyticsAction]
                 │
                 ├─► min_price: int
                 ├─► max_price: int
                 ├─► avg_price: int
                 ├─► points: array<['timestamp' => string, 'price' => int]>
                 └─► recommendation: RecommendationEnum
```

---

## 1. DTO y Action de Dominio

### `PriceAnalyticsDTO`
- `min_price`: `int`
- `max_price`: `int`
- `avg_price`: `int`
- `current_price`: `int`
- `recommendation`: `string` (`buy_now`, `wait`, `neutral`)
- `history_points`: `array<int, array{date: string, price: int}>`

### `CalculatePriceAnalyticsAction`
- Ubicación: `app/Actions/CalculatePriceAnalyticsAction.php`
- Responsabilidad: Procesar la colección de `FlightResult` de un `FlightSearch`, agrupar por punto de muestreo y computar métricas y recomendaciones sin alterar datos.

---

## 2. Componentes de UI (Livewire & Frontend)

- [`app/Livewire/AlertDetails.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Livewire/AlertDetails.php):
  - Inyecta `CalculatePriceAnalyticsAction`.
  - Expone `$analytics` a la vista Blade.
- [`resources/views/livewire/alert-details.blade.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/resources/views/livewire/alert-details.blade.php):
  - Renderiza tarjetas KPI con estadísticas.
  - Integra componente de gráfica (SVG ligero o Chart.js vía Alpine/Flux) encapsulado con `wire:ignore` para evitar colisiones con el ciclo de vida de morphdom de Livewire.

---

## 3. Consideraciones de Performance
- Índices en base de datos: Asegurar que `flight_results` tenga índice compuesto `(flight_search_id, created_at)` para queries rápidas de serie temporal.
