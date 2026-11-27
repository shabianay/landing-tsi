# Design — "Grid Blok"

The visual system is derived from the logo: a stepped stack of square blocks, slate with one blue block. Everything on the site is built from that idea. Source of truth: `tailwind.config.js` and `resources/css/app.css`.

## Principles

1. **Blocks, not cards.** Content sits in square-cornered blocks and in cells that share 1px hairlines (`.cells`). There are no floating cards with soft shadows, no gradients, no glass.
2. **One blue block per view.** Brand blue `#3498DB` is a *fill* for the single most important thing on screen (primary CTA, the turnaround stat, the closing band). Text on blue is always ink, never white (white on `#3498DB` is 3:1 and fails contrast).
3. **Proof lives inside the blocks.** The home hero rebuilds the logo silhouette out of the four stats (from Settings). Process steps show a stack of squares filling up. Ratings are squares, not stars.
4. **The next step is one tap away.** On mobile a fixed bar shows WhatsApp + Konsultasi Gratis on every public page. On desktop there is a WhatsApp square bottom-right and a CTA in the nav.

## Tokens

| Role | Token | Value |
|---|---|---|
| Ink (headings, dark sections, admin sidebar) | `dark-300` | `#0F1720` |
| Body text strong | `dark-200` | `#1C2630` |
| Slate (logo slate, secondary text, stat blocks) | `dark-100` | `#415060` (8.6:1 on white) |
| Panel ground | `dark-50` | `#F1F3F5` |
| Hairline | `line` | `#DDE2E7` |
| Brand blue fill | `primary-500` | `#3498DB`: fills only, ink text on top |
| Blue text and links | `primary-600` / `primary-700` | `#1F78B4` / `#175E8F` (≥4.5:1) |
| WhatsApp | arbitrary | `#1FAF54`, hover `#178F44` |

- **Radius:** near-square. `rounded` is 2px, `xl` is 4px, `2xl` is 6px. Public blocks use none.
- **Shadow:** only for things that float (form card, popup, browser frame), always offset and soft.
- **Type:** Cabinet Grotesk 700/800 for display (`.display-1/2/3`, `font-display`) and Switzer 400–700 for text, both from Fontshare (allowed by the CSP in `SecurityHeaders`). Numbers use `.num` (tabular).
- **Spacing:** sections use `py-16 md:py-24`, and the container is `.wrap` (max-w-7xl, px-5/8).

## Components (`resources/css/app.css`, `resources/views/components`)

- **Buttons:**
  - `.btn-primary`: blue with ink text and a 3px inner base edge.
  - `.btn-ink`, `.btn-secondary` (outlined ink), `.btn-wa`, `.btn-white`.
  - All buttons are at least 48px tall.
- `.link-arrow` with `<x-icon-arrow dir="right|left|down|out" />`.
- `.cells`: hairline grid. `.tag`, `.tag-blue`.
- **Forms:** `.field`, `.field-label`, `.field-hint`, `.field-error`. Set `aria-invalid="true"` to get the red state.
- **Brand marks:**
  - `<x-blockmark size="1rem" />`: the logo mark at any size.
  - `<x-icon-whatsapp />`.
- `<x-page-header :title :lede :crumbs>`: the inner-page header, with an optional right-hand slot.
- `@include('partials.cta-band')`: the closing blue band, with optional `title` and `body`.
- **Motion:** `.block-drop` with `--i` is the one signature motion: blocks settle into the stack. It is disabled under `prefers-reduced-motion`.

## Admin

The admin uses the same tokens in Operate mode:
- An ink sidebar grouped into Ringkasan, Sales, Konten and Sistem. The active item has a blue edge.
- A white work area on `dark-50`, with hairline-bordered panels.
- The dashboard leads with what needs action (unread messages as the blue block, new applicants).

## Don'ts

- No eyebrow or kicker labels above headings.
- No gradient text or decorative blur.
- No icon-in-rounded-square card grids.
- No white text on `primary-500`.
- No skeleton loaders that delay content which is already rendered.
