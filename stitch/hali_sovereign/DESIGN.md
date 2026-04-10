# Design System Document

## 1. Overview & Creative North Star: "The Sovereign Lattice"

This design system is built to bridge the gap between high-level pan-African leadership and the precision of modern SaaS engineering. Our Creative North Star is **"The Sovereign Lattice"**—a visual philosophy that emphasizes structural connectivity, editorial breathing room, and a sense of "digital prestige."

Unlike standard enterprise portals that rely on rigid grids and heavy borders, this system uses **intentional asymmetry** and **tonal depth** to guide the eye. We treat the user interface not as a flat screen, but as an architectural space where information is layered, not just placed. By leveraging high-contrast typography scales and sophisticated "glass" surfaces, we move beyond a "template" look to create a custom, signature experience for HALI Access partners.

---

## 2. Colors & Surface Philosophy

The color palette is rooted in a deep, authoritative teal and a vibrant, leadership-gold. We move away from the "flat grey" era by injecting tonal depth into every surface.

### Tonal Hierarchy
*   **Primary (`#00606e`):** Used for primary actions and brand anchoring.
*   **Primary Container (`#1a7a8a`):** Used for hero backgrounds or large interactive regions to provide visual "soul."
*   **Secondary/Tertiary (`#835500` / `#745000`):** These represent the "Leadership Accent." Use these for badges, progress indicators, and high-value CTAs.

### The "No-Line" Rule
**Explicit Instruction:** Designers are prohibited from using 1px solid borders for sectioning or containment. 
*   Boundaries must be defined solely through background color shifts.
*   Example: A `surface_container_low` card sitting on a `surface` background provides all the definition needed. If you feel the need for a line, use whitespace (`spacing.8`) or a background transition instead.

### The "Glass & Gradient" Rule
To achieve a premium SaaS aesthetic, use **Glassmorphism** for floating elements (e.g., dropdowns, floating navigation, or modal overlays). 
*   **Implementation:** Use a semi-transparent `surface` color with a 20px-40px backdrop blur.
*   **Signature Textures:** Use subtle linear gradients (e.g., `primary` to `primary_container`) for main CTAs. This creates a tactile, "clickable" depth that flat colors lack.

---

## 3. Typography: Editorial Authority

We use a dual-font strategy to balance "High-Tech" with "Leadership Editorial."

*   **Display & Headline (Space Grotesk):** This geometric sans-serif provides the "innovative" vibe. Use `display-lg` (3.5rem) for impact metrics and `headline-md` (1.75rem) for section titles. The wider apertures and technical feel reflect the portal's SaaS capabilities.
*   **Title & Body (Inter):** Inter provides world-class legibility. Use `title-lg` (1.375rem) for card headings and `body-md` (0.875rem) for standard portal data.
*   **Hierarchy Note:** Always pair a `display` metric with a `label-sm` subtitle in all-caps to create a sophisticated, dashboard-as-a-magazine look.

---

## 4. Elevation & Depth: Tonal Layering

Standard drop shadows are often messy. This design system uses **Tonal Layering** and **Ambient Shadows** to convey hierarchy.

*   **The Layering Principle:** Stacks should follow a logical progression. 
    *   *Base:* `surface`
    *   *Section:* `surface_container_low`
    *   *Interactive Card:* `surface_container_lowest` (creates a "lift" effect via lightness).
*   **Ambient Shadows:** When a floating effect is required (e.g., a "Partner Spotlight" card), use a shadow with a blur of 32px-64px at 6% opacity. The shadow color should be tinted with the `on_surface` token (`#121c2c`) rather than pure black to keep the UI looking "airy."
*   **The "Ghost Border" Fallback:** If a border is required for accessibility, use the `outline_variant` token at **20% opacity**. Never use 100% opaque borders.

---

## 5. Components

### Navigation: The Fixed Sidebar
*   **Style:** A vertical monolith using `surface_container_low`.
*   **Active State:** Use a "pill" shape (`roundedness.full`) in `primary_container` with `on_primary_container` text. Avoid "icon-only" sidebars; show the labels to maintain an authoritative, professional tone.

### Cards & Data Containers
*   **Rule:** **Forbid divider lines.** 
*   **Styling:** Use `roundedness.xl` (0.75rem). Separate card content sections using vertical whitespace (e.g., `spacing.6` between header and body). 
*   **Interactive State:** On hover, a card should shift from `surface_container_lowest` to a subtle gradient-tinted background.

### Vibrant Accent Badges
*   **Style:** Small, high-contrast chips.
*   **Token Usage:** Use `secondary_container` for the background and `on_secondary_container` for text. 
*   **Shape:** `roundedness.full` with `label-sm` typography.

### Buttons
*   **Primary:** Linear gradient (`primary` to `primary_container`), `roundedness.md`.
*   **Secondary:** Ghost style using the "Ghost Border" rule (20% opacity `outline_variant`).
*   **Tertiary:** Text-only with an underline that appears only on hover.

### Input Fields
*   **Style:** Minimalist. Use `surface_container_highest` for the input background with no border. 
*   **Focus State:** A 2px "Ghost Border" of `primary` at 40% opacity.

---

## 6. Do’s and Don’ts

### Do:
*   **Do** use asymmetrical margins. If a container is 12 columns wide, try a 10-column centered content block with 1-column offsets to create an "editorial" feel.
*   **Do** use `display-lg` for single, powerful numbers (e.g., "Total Partners: 450").
*   **Do** embrace whitespace. If you think there is enough space, add `spacing.4` more.

### Don't:
*   **Don't** use pure black (`#000000`) for text. Use `on_surface` (`#121c2c`) to maintain a sophisticated color profile.
*   **Don't** use 1px dividers to separate list items. Use a 4px gap and a background shift (`surface_container_low` vs `surface_container_lowest`).
*   **Don't** use standard "Material Design" blue for links. Always use `primary` teal or `secondary` gold.

---

*Director's Note: Every pixel in this portal should feel like it was placed by a human hand, not a framework generator. Use the tonal shifts and the "No-Line" rule to create a workspace that feels like a premium lounge, not a spreadsheet.*