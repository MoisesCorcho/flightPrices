# Cómo usar estas specs (SDD) — FlightPrices

## Qué es esto

Esta carpeta define **Spec-Driven Development (SDD)** para la plataforma de seguimiento y alertas de precios de vuelos **FlightPrices**, sin duplicar lo que ya es canónico en el repositorio (guidelines de Laravel Boost, convenciones de Pest y esquema de base de datos).

Cada feature del producto se especifica en:

| Archivo | Propósito | Regla de oro |
|---------|-----------|--------------|
| `requirements.md` | **QUÉ** — Historias de usuario + criterios EARS observables. | Prohibido incluir clases PHP, nombres de librerías o detalles de implementación. |
| `design.md` | **CÓMO** — Actions, DTOs, interfaces de proveedores, componentes Livewire/Flux y flujos. | Debe respetar las convenciones de Laravel 13, tipado estricto y desacoplamiento. |
| `tasks.md` | **ORDEN** — Checklist ejecutable con trazabilidad `_(cubre Rn)_`, tests explícitos y Definition of Done. | Máximo 2 niveles de jerarquía; cada tarea mapea a un criterio de aceptación. |

Ubicación: `specs/features/<NN-slug>/`.

---

## Fuentes de verdad canónicas (No duplicar aquí)

| Tema | Fuente canónica | Notas |
|------|-----------------|-------|
| Stack, Pest, Pint, Laravel 13 idioms | Laravel Boost + `GEMINI.md` | Consultar vía `search-docs` / Boost |
| Modelo de datos y migraciones | `database/migrations/`, `app/Models/` | Si una feature cambia el esquema, se versiona en migraciones |
| Arquitectura de proveedores externos | `app/Contracts/FlightProviderInterface.php` | El dominio depende de interfaces, no de proveedores concretos |
| Producto, roadmap y dependencias | [`specs/_global/01-product-and-roadmap.md`](01-product-and-roadmap.md) | Única fuente de verdad de prioridades y dependencias |
| Costos y arquitectura de cuotas API | [`specs/_global/02-architecture-and-costs.md`](02-architecture-and-costs.md) | Estrategia de SerpAPI, caché y deduplicación |
| Estándar de calidad y criterios EARS | [`specs/_global/03-feature-quality.md`](03-feature-quality.md) | Contrato de robustez para specs |

**Regla anti-duplicación:** Si la información ya vive en el código o en las fuentes canónicas de la tabla, en `specs/` solo se referencia.

---

## Ciclo de vida de una Feature

1. **Estado inicial:** `No iniciada` o `Specs en progreso`.
2. **Definir el QUÉ:** Redactar `requirements.md` con sintaxis EARS (`CUANDO`, `MIENTRAS`, `DONDE`, `SI... ENTONCES`).
3. **Definir el CÓMO:** Redactar `design.md` alineado con Clean Architecture y Laravel Boost (Actions, DTOs, Provider Strategy).
4. **Planificar el ORDEN:** Crear `tasks.md` desglosando tareas específicas con trazabilidad `_(cubre Rn)_` y tests Pest asociados.
5. **Implementación:** Cambiar estado a `En implementación`. Ejecutar tareas respetando TDD y formateo con Pint (`vendor/bin/pint --dirty`).
6. **Cierre:** Completar el Definition of Done de `tasks.md` con tests verdes (`php artisan test --compact`). Estado: `Completa`.

---

## Convención de estado

Al inicio de cada `requirements.md` y en la tabla del roadmap:

```markdown
> **Estado:** Completa | Lista para implementar | En implementación | No iniciada  
> **ID:** F01 · **Slug:** `01-flight-search-and-cache`  
> **Prerequisitos:** Ninguno
```
