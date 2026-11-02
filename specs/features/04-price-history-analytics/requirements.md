# F04: Historial de Precios y Recomendación de Compra

> **Estado:** Lista para implementar  
> **ID:** F04 · **Slug:** `04-price-history-analytics`  
> **Prerequisitos:** F01 (`01-flight-search-and-cache`), F02 (`02-tracking-and-alerts`)

---

## 1. Historias de Usuario

- **US4.1:** Como viajero que sigue un vuelo, quiero ver una gráfica con la evolución del precio en el tiempo para saber si la tarifa actual es realmente una oferta o si suele estar más baja.
- **US4.2:** Como viajero, quiero ver estadísticas claras (precio mínimo histórico, precio promedio y precio máximo) en la pantalla de detalle de mi alerta.
- **US4.3:** Como usuario, quiero un indicador visual ("Buen momento para comprar" / "Esperar") que sintetice la recomendación basada en los datos históricos recopilados.

---

## 2. Criterios de Aceptación (EARS)

### Happy Path

### R1 — Visualización de métricas resumen de precio
DONDE un usuario autenticado accede a la vista de detalle de una alerta (`/alerts/{id}`),  
CUANDO existen registros históricos para esa búsqueda en `flight_results`,  
EL SISTEMA DEBE calcular y mostrar en pantalla:
- Precio mínimo alcanzado
- Precio promedio
- Precio actual más reciente  
SIN recalcular queries pesadas en cada renderizado reactivo.

### R2 — Gráfica de evolución temporal
DONDE el usuario consulta el detalle de la alerta,  
CUANDO existen 2 o más puntos temporales de precios históricos,  
EL SISTEMA DEBE renderizar una gráfica interactiva de línea con el eje X representando fechas/horas y el eje Y representando el precio en COP  
SIN alterar la responsividad móvil de la interfaz PWA.

### R3 — Badge de recomendación inteligente
CUANDO el precio actual está un 10% o más por debajo del promedio histórico registrado,  
EL SISTEMA DEBE mostrar un distintivo destacado: "🔥 ¡Excelente momento para comprar!".  
SI el precio actual está por encima del promedio,  
EL SISTEMA DEBE mostrar: "⏳ Te sugerimos esperar; el precio suele bajar".

### Validación y Error

### R4 — Estado inicial sin suficiente histórico
DONDE una búsqueda tiene únicamente un punto de datos histórico (recién creada),  
CUANDO el usuario entra a la pantalla de detalle,  
EL SISTEMA DEBE mostrar un estado informativo indicando que se están recopilando datos para generar la gráfica histórica  
SIN renderizar gráficas vacías ni disparar errores JavaScript en el cliente.
