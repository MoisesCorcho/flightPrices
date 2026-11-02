# Tasks — F01: Búsqueda de Vuelos y Caché Inteligente

## Checklist de Tareas

### 1. Dominio y Abstracción de Proveedor
- [x] Crear DTOs de dominio `SearchCriteriaDTO` y `FlightDTO`. _(cubre R1)_
- [x] Crear interfaz `FlightProviderInterface` e implementar `SerpApiProvider` con Saloon v4. _(cubre R1, R5)_
- [x] Registrar el binding en `AppServiceProvider`. _(cubre R1)_

### 2. Caché y Lógica de Búsqueda
- [x] Implementar `FetchFlightsAction` con `Cache::flexible()` a 6 horas. _(cubre R3)_
- [x] Crear evento `FlightSearched` y listener `LogFlightSearch` para auditoría de hits de caché. _(cubre R3)_

### 3. Interfaz de Usuario (Livewire)
- [x] Implementar componente `FlightSearch` con swap reactivo de origen y destino. _(cubre R1, R2)_
- [x] Agregar validación en tiempo de ejecución para parámetros de fecha y aeropuertos. _(cubre R4)_
- [x] Implementar vista de resultados `FlightResults` con renderizado de tarjetas de vuelo. _(cubre R1)_

### 4. Tests Automatizados (Pest)
- [x] Feature test: `tests/Feature/FlightCacheTest.php` probando que peticiones idénticas usan caché sin llamar al provider. _(cubre R3)_
- [x] Feature test: `tests/Feature/Integrations/SerpApiFlightProcessingTest.php` probando normalización de respuesta de vuelos. _(cubre R1, R5)_

---

## Matriz de Trazabilidad

| Criterio | Tareas asociadas | Test de verificación |
|:---:|:---|:---|
| **R1** | 1.1, 1.2, 1.3, 3.1, 3.3, 4.2 | `tests/Feature/Integrations/SerpApiFlightProcessingTest.php` |
| **R2** | 3.1 | Test manual en componente Livewire |
| **R3** | 2.1, 2.2, 4.1 | `tests/Feature/FlightCacheTest.php` |
| **R4** | 3.2 | Validación Livewire en `FlightSearch` |
| **R5** | 1.2, 4.2 | `tests/Feature/Integrations/SerpApiFlightProcessingTest.php` |

---

## Definition of Done (DoD)

- [x] Criterios R1 a R5 implementados y verificados.
- [x] Tests unitarios y de integración pasando al 100%.
- [x] Pint sin observaciones de estilo.
