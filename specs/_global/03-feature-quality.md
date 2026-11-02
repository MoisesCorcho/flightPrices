# Calidad de Features SDD — FlightPrices

**Alcance:** `specs/features/<slug>/` en este repositorio.

---

## Los Tres Artefactos Obligatorios

| Archivo | Propósito | Prohibiciones |
|---------|-----------|---------------|
| `requirements.md` | **QUÉ** observable desde la perspectiva del usuario y del sistema. | Prohibido incluir clases PHP, nombres de paquetes, migraciones o queries. |
| `design.md` | **CÓMO** técnico: Actions, contratos, modelos, Livewire y providers. | No contradecir las convenciones de Laravel Boost ni el esquema de BD. |
| `tasks.md` | **ORDEN** de ejecución con trazabilidad y Definition of Done. | Prohibido incluir tareas genéricas ("hacer tests") sin referenciar criterios `_(cubre Rn)_`. |

---

## Criterios EARS e Identificadores

Cada criterio de aceptación debe seguir una sintaxis EARS formal:

```markdown
### R{N} — [Título descriptivo]

CUANDO [evento o condición de inicio]
Y [precondición opcional]
EL SISTEMA DEBE [resultado observable]
SIN [efecto secundario indeseado].
```

- **DONDE** es obligatorio para interacciones de UI (pantallas Livewire, Flux UI, formularios).
- **CUANDO** define eventos del usuario o ejecuciones del scheduler.
- **SI... ENTONCES** define reglas de validación o fallos de red/proveedor.

---

## Granularidad y Trazabilidad en Tasks

- Máximo **2 niveles** de jerarquía.
- Toda tarea debe referenciar al menos un criterio de aceptación: `- [ ] Implementar X acción. _(cubre R1, R2)_`.
- Los tests en `tasks.md` deben ser explícitos y usar Pest (`tests/Feature/...`).
- Todo `tasks.md` debe cerrar con la **Matriz de Trazabilidad** y el **Definition of Done**.

### Formato de Matriz de Trazabilidad

```markdown
| Criterio | Tareas asociadas | Test de verificación |
|---|---|---|
| R1 | 1.1, 2.1 | `tests/Feature/FlightSearchTest.php` |
| R2 | 1.2, 3.1 | `tests/Feature/FlightCacheTest.php` |
```

---

## Definition of Done (DoD) Estándar

- [ ] Todos los criterios de aceptación R1...Rn cubiertos por tests automáticos en Pest.
- [ ] Ejecución de tests limpia: `php artisan test --compact` en verde sin fallos.
- [ ] Estilo de código verificado con Laravel Pint: `vendor/bin/pint --dirty --format agent`.
- [ ] Migraciones reversibles (`down()`) si se modificó la base de datos.
- [ ] Documentación de API o configuración reflejada en `.env.example`.
