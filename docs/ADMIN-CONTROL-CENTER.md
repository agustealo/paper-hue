# Paper Hue Control Center

The Paper Hue administration experience follows a simple rule: **one source of truth, multiple useful views**.

## Appearance → Paper Hue

The dashboard is a read-only operational overview. It reports the current state of:

- Site Identity
- Primary Navigation
- Hero Slider
- Featured Story
- Recent Articles
- Fallback Image
- Widget Areas

Each area exposes a direct action into the canonical WordPress screen that owns the setting or content.

## Customizer

The Customizer remains the canonical presentation editor for Paper Hue. Existing theme-mod IDs are retained to preserve upgrades from older releases.

## Product direction

Upcoming feature slices may extend Slider, Featured Story, and Recent Articles, but must continue to use the centralized configuration layer rather than scattering `get_theme_mod()` calls throughout new templates or admin screens.

The dashboard must stay concise. It is not a parallel settings framework and must not become a promotional surface.
