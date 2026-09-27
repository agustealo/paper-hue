# Paper Hue admin architecture

Paper Hue keeps presentation settings in the WordPress Customizer and uses **Appearance → Paper Hue** as a read-only control center.

The admin dashboard may:

- summarize current theme state;
- surface configuration problems;
- link to the relevant WordPress management surface;
- provide direct links to edit content used by Paper Hue features.

The dashboard must not create a second settings database or silently fork Customizer state.

## Ownership

- `Paper_Hue_Config` is the canonical read layer for Paper Hue theme mods and derived status.
- `Paper_Hue_Admin` renders the Appearance dashboard and does not directly persist presentation settings.
- Existing theme-mod IDs remain authoritative for backward compatibility.

This separation keeps Paper Hue familiar to existing users while allowing the administration experience to become substantially more capable.
