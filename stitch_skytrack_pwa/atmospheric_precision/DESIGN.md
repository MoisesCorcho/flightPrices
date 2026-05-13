---
name: Atmospheric Precision
colors:
  surface: '#f8f9fa'
  surface-dim: '#d9dadb'
  surface-bright: '#f8f9fa'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f3f4f5'
  surface-container: '#edeeef'
  surface-container-high: '#e7e8e9'
  surface-container-highest: '#e1e3e4'
  on-surface: '#191c1d'
  on-surface-variant: '#464555'
  inverse-surface: '#2e3132'
  inverse-on-surface: '#f0f1f2'
  outline: '#777587'
  outline-variant: '#c7c4d8'
  surface-tint: '#4d44e3'
  primary: '#3525cd'
  on-primary: '#ffffff'
  primary-container: '#4f46e5'
  on-primary-container: '#dad7ff'
  inverse-primary: '#c3c0ff'
  secondary: '#006591'
  on-secondary: '#ffffff'
  secondary-container: '#39b8fd'
  on-secondary-container: '#004666'
  tertiary: '#005338'
  on-tertiary: '#ffffff'
  tertiary-container: '#006e4b'
  on-tertiary-container: '#67f4b7'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#e2dfff'
  primary-fixed-dim: '#c3c0ff'
  on-primary-fixed: '#0f0069'
  on-primary-fixed-variant: '#3323cc'
  secondary-fixed: '#c9e6ff'
  secondary-fixed-dim: '#89ceff'
  on-secondary-fixed: '#001e2f'
  on-secondary-fixed-variant: '#004c6e'
  tertiary-fixed: '#6ffbbe'
  tertiary-fixed-dim: '#4edea3'
  on-tertiary-fixed: '#002113'
  on-tertiary-fixed-variant: '#005236'
  background: '#f8f9fa'
  on-background: '#191c1d'
  surface-variant: '#e1e3e4'
typography:
  display-price:
    fontFamily: Inter
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 30px
    fontWeight: '600'
    lineHeight: 36px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  container-padding-mobile: 16px
  container-padding-desktop: 32px
  gutter: 16px
  max-width: 1200px
---

## Brand & Style

The design system is anchored in the concepts of clarity, velocity, and calm. As a utility-focused PWA for flight tracking, the UI must eliminate cognitive load, allowing users to monitor volatile price data without stress. 

The aesthetic is **Corporate Modern** with a **Minimalist** execution. It prioritizes functional white space and high-quality typography over decorative elements. The emotional response should be one of reliability and "smart" assistance—moving away from the cluttered, ad-heavy nature of traditional travel agencies toward a streamlined, tool-like experience. Expect breathable layouts, subtle motion for state changes, and a focus on data density that feels organized rather than overwhelming.

## Colors

The color palette is led by **Indigo (#4F46E5)**, providing a sense of trust and institutional stability. 

- **Primary (Indigo):** Used for primary actions, active tracking states, and key brand moments.
- **Secondary (Sky Blue):** Used for decorative accents and secondary information like "non-stop" indicators.
- **Tertiary (Emerald):** Reserved strictly for "price drop" indicators and positive financial trends.
- **Neutral (Gray-50 Base):** The background is kept incredibly light to maximize the "app-like" feel of the PWA, with higher-order neutrals (Gray-100 to Gray-900) used for borders and text hierarchy.

Gradients should be used sparingly, restricted to a very subtle 15-degree linear shift from Primary to Secondary on major call-to-action buttons.

## Typography

This design system utilizes **Inter** for all roles to ensure maximum legibility and a systematic, utilitarian feel. 

The hierarchy is built around "Display" levels for pricing information, which use tighter letter spacing and heavier weights to command attention. For airport codes (e.g., JFK, LHR), use `label-sm` with slightly increased letter spacing to enhance scannability. 

Mobile responsiveness is handled by scaling down large headlines while maintaining generous line heights for body copy to ensure comfortable reading during travel.

## Layout & Spacing

The layout follows a **fluid grid** model optimized for PWA performance. On mobile devices, the system uses a single-column layout with 16px side margins. As the screen scales to tablet and desktop, the content is contained within a 1200px max-width container, transitioning to a multi-column layout for dashboard views.

Spacing is based on an **8px linear scale**. Use 16px (base * 2) for standard component padding and 24px (base * 3) for vertical section spacing. Large touch targets are mandatory; all interactive elements must have a minimum hit area of 48x48px.

## Elevation & Depth

Visual hierarchy is achieved through **tonal layers** and **ambient shadows**. 

- **Level 0 (Background):** Gray-50.
- **Level 1 (Cards/Surface):** White (#FFFFFF) with a very soft, diffused shadow (0px 4px 20px rgba(0, 0, 0, 0.05)).
- **Level 2 (Active/Floating):** White (#FFFFFF) with a more pronounced shadow (0px 10px 30px rgba(79, 70, 229, 0.08)) to indicate interactivity or modal states.

Avoid using heavy borders. Instead, use thin, 1px Gray-100 strokes to define boundaries only when tonal contrast is insufficient. Semi-transparent backdrop blurs (20px) should be used for fixed navigation headers to maintain context while scrolling.

## Shapes

The shape language is friendly and modern, utilizing generous corner radii to soften the data-heavy interface. 

- **Standard Components:** Buttons, input fields, and small cards use a **0.5rem (8px)** radius.
- **Large Containers:** Main price cards and modal sheets use **1.5rem (24px)** to create a distinct, premium "container" feel.
- **Interactive Chips:** These utilize a fully rounded (pill) shape to distinguish them from actionable buttons.

## Components

- **Buttons:** Primary buttons use the Indigo gradient with white text. Secondary buttons use a Gray-100 background with Indigo text. All buttons feature a minimum height of 52px for touch-friendliness.
- **Input Fields:** Large, 56px high fields with 16px horizontal padding. On focus, the border shifts to Indigo with a 2px stroke. Placeholders should be Gray-400.
- **Price Trend Chips:** Small, pill-shaped indicators. Use Emerald for price drops and Gray-200 for neutral trends.
- **Flight Cards:** White background, Level 1 shadow, 24px corner radius. The price should be positioned at the top right in `display-price` style.
- **Price Alerts (Toggle):** Use a custom-styled switch that turns from Gray-200 to Indigo when active, providing haptic-like visual feedback.
- **Bottom Sheet:** For mobile filtering, use a slide-up panel with a 24px top-corner radius and a centered "grabber" handle.