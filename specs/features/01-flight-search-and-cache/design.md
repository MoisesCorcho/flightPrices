# Diseño Técnico — F01: Búsqueda de Vuelos y Caché Inteligente

## Arquitectura de Componentes

```text
[Livewire: FlightSearch] ───► [Action: FetchFlightsAction]
                                      │
                         ┌────────────┴────────────┐
                         ▼                         ▼
               [Cache::flexible]        [FlightProviderInterface]
                                                   │
                                                   ▼
                                         [SerpApiProvider] (Saloon v4)
```

---

## 1. Contratos y DTOs

### `SearchCriteriaDTO`
[`app/DTOs/SearchCriteriaDTO.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/DTOs/SearchCriteriaDTO.php)
- `origin`: `string` (IATA de 3 caracteres)
- `destination`: `string` (IATA de 3 caracteres)
- `date`: `string` (`Y-m-d`)

### `FlightDTO`
[`app/DTOs/FlightDTO.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/DTOs/FlightDTO.php)
- `airline_name`: `string`
- `airline_logo`: `?string`
- `flight_number`: `string`
- `origin_name`: `string`
- `destination_name`: `string`
- `departure_time`: `string`
- `arrival_time`: `string`
- `duration`: `string`
- `price`: `int` (Moneda en COP)
- `stops`: `int`

### `FlightProviderInterface`
[`app/Contracts/FlightProviderInterface.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Contracts/FlightProviderInterface.php)
```php
namespace App\Contracts;

use App\DTOs\SearchCriteriaDTO;
use Illuminate\Support\Collection;

interface FlightProviderInterface
{
    /**
     * @return Collection<int, \App\DTOs\FlightDTO>
     */
    public function search(SearchCriteriaDTO $criteria): Collection;
}
```

---

## 2. Estrategia de Caché Flexible

En [`app/Actions/FetchFlightsAction.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Actions/FetchFlightsAction.php):
- Clave de caché: `"flights:search:{$criteria->origin}:{$criteria->destination}:{$criteria->date}"`
- TTL Fresco: `6 * 3600` (6 horas).
- TTL Gracia: `12 * 3600` (12 horas).
- Evento disparado: [`app/Events/FlightSearched.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Events/FlightSearched.php) indicando si los datos vinieron o no de caché (`fromCache: bool`), registrado por [`app/Listeners/LogFlightSearch.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Listeners/LogFlightSearch.php).

---

## 3. Componentes de UI (Livewire)

- [`app/Livewire/FlightSearch.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Livewire/FlightSearch.php): Formulario de búsqueda con validación, swap y ordenamiento de aeropuertos por ciudad.
- [`resources/views/livewire/flight-search.blade.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/resources/views/livewire/flight-search.blade.php): Plantilla Blade PWA con Flux UI y swap animado.
- [`app/Livewire/FlightResults.php`](file:///D:/MCP2_DISCO_2/PROGRAMACION/LaravelHerd/flightprices/app/Livewire/FlightResults.php): Listado de vuelos ordenados por menor precio con opción de fijar precio objetivo.
