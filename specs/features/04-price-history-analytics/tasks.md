# Tasks — F04: Historial de Precios y Recomendación de Compra

## Checklist de Tareas

### 1. Dominio y Cálculos
- [ ] Crear DTO `PriceAnalyticsDTO` para tipar las métricas de serie temporal. _(cubre R1)_
- [ ] Crear `CalculatePriceAnalyticsAction` que compute precio mínimo, promedio, actual y la recomendación de compra. _(cubre R1, R3)_

### 2. UI y Visualización (Livewire & Alpine)
- [ ] Integrar `CalculatePriceAnalyticsAction` en `AlertDetails.php`. _(cubre R1)_
- [ ] Diseñar tarjetas KPI de resumen en `alert-details.blade.php`. _(cubre R1, R3)_
- [ ] Diseñar el estado de recopilación de datos cuando hay menos de 2 puntos históricos. _(cubre R4)_
- [ ] Implementar la gráfica de línea responsiva (vía Alpine/SVG o Chart.js con `wire:ignore`). _(cubre R2)_

### 3. Tests de Verificación (Pest)
- [ ] Unit test: `tests/Unit/CalculatePriceAnalyticsActionTest.php` validando los cálculos matemáticos y las reglas del badge de recomendación. _(cubre R1, R3)_
- [ ] Feature test: `tests/Feature/AlertDetailsAnalyticsTest.php` comprobando que la vista muestra las estadísticas y renderiza el estado vacío si corresponde. _(cubre R1, R4)_

---

## Matriz de Trazabilidad

| Criterio | Tareas asociadas | Test de verificación |
|:---:|:---|:---|
| **R1** | 1.1, 1.2, 2.1, 2.2, 3.1, 3.2 | `tests/Unit/CalculatePriceAnalyticsActionTest.php` |
| **R2** | 2.4 | Browser / visual check en PWA |
| **R3** | 1.2, 2.2, 3.1 | `tests/Unit/CalculatePriceAnalyticsActionTest.php` |
| **R4** | 2.3, 3.2 | `tests/Feature/AlertDetailsAnalyticsTest.php` |

---

## Definition of Done (DoD)

- [ ] Todos los criterios R1 a R4 implementados y probados.
- [ ] Tests en Pest pasando en verde: `php artisan test --compact`.
- [ ] Código formateado con Pint: `vendor/bin/pint --dirty --format agent`.
- [ ] Sin regresiones en el flujo existente de `AlertDetails`.
