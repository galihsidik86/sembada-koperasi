---
name: sembada-design-system
description: Sembada Koperasi — design system for an Indonesian digital cooperative (koperasi simpan pinjam). Use when designing any surface that needs to feel warm, trustworthy, and rooted in Indonesian cooperative culture — mobile apps for members or pengurus, admin dashboards, teller terminals, marketing pages, print forms, or transactional emails. Palette is warm earth (sogan, cream, padi green); typography pairs Plus Jakarta Sans with Fraunces; copy is Bahasa Indonesia santai (uses "kamu", not "Anda"); ornament uses simplified batik motifs (kawung, parang, tumpal). Skip this system for anything that needs to feel like a slick fintech, a Western SaaS, or a corporate bank.
---

# Sembada — Design System Skill

Sembada is a fictional Indonesian digital cooperative brand. This skill packages the full visual + voice + component system as a reusable starting point for any koperasi-flavored product or document.

## When to use this skill
Reach for it whenever the user asks for:
- A koperasi / cooperative / Indonesian community-finance product
- An Indonesian-language UI that should feel warm and personal, not corporate
- A design that uses traditional Indonesian motifs without being kitschy
- A multi-surface system (mobile + web + print + email) that should feel like one brand

Do NOT use this skill for:
- Generic SaaS, B2B SaaS, or Silicon-Valley-style fintech (the warmth will read as off-brand)
- Anything in English-first contexts (copy here is bilingual-leaning Indonesian)
- Brands that need a cool/clinical/medical tone

## How to use it

### 1. Always read the foundations first
Before producing anything, read these files in order:
1. `README.md` — full brand context: product surfaces, voice, content rules, visual rules, iconography
2. `colors_and_type.css` — every CSS variable (color, type, spacing, shadow, radius)

The CSS file is the source of truth. Always link it and reference variables (e.g. `var(--sogan-500)`) instead of hardcoding hex.

### 2. Pick the closest UI kit, then adapt
Each kit under `ui_kits/<surface>/index.html` is a working, copy-pasteable single-file demo. Treat them as **patterns + componentry**, not finished apps:

| Surface | Kit | When to start here |
|---|---|---|
| Mobile member app | `ui_kits/member_mobile/index.html` | Any anggota-facing mobile flow (saldo, pinjaman, cicilan, profil) |
| Mobile pengurus app | `ui_kits/admin_mobile/index.html` | Field-staff mobile (approval queue, kunjungan) |
| Web admin dashboard | `ui_kits/admin_web/index.html` | Office desktop (statistik cabang, manajemen anggota, laporan) |
| Marketing site | `ui_kits/marketing_web/index.html` | Landing page, koperasi mitra, edukasi |
| Teller desktop | `ui_kits/teller_desktop/index.html` | Kasir / cabang terminal |

Don't copy the WHOLE kit — open it, find the components you need (header, card, button, list item, table row), and lift only those. Each kit's README explains what's inside.

### 3. Honor the voice
Bahasa Indonesia santai. "kamu" not "Anda". Sentence case. No emoji in product UI. See README → Content Fundamentals for the full rules and example tone table.

### 4. Use the ornament sparingly
Batik patterns (`assets/patterns/kawung.svg`, `parang.svg`, `tumpal.svg`) are accents — not background wallpaper. Typical usage:
- `kawung` overlay at 4–8% opacity behind hero/section dark panels
- `tumpal` strip as a section divider or print border
- `parang` for marketing decoration in corners

Never tile a pattern at full opacity across a whole screen.

### 5. Output checklist
Before delivering, verify:
- [ ] `<link rel="stylesheet" href="colors_and_type.css">` (or relative path) is on every HTML file
- [ ] Plus Jakarta Sans + Fraunces are loaded (the CSS file imports them)
- [ ] Money is `Rp 1.250.000` format; percent uses `,` decimal
- [ ] No "Anda", no `Are you sure?`, no shouty UPPERCASE on buttons
- [ ] Copy is Indonesian unless the user asked for English
- [ ] Cards on cream background `var(--bg)`, not pure white pages
- [ ] Shadows are warm-tinted (sogan-tinted), never cool gray

## File map
```
README.md                  ← brand bible (read first)
SKILL.md                   ← this file
colors_and_type.css        ← all design tokens
preview/                   ← design system review cards
assets/logo/               ← Sembada wordmark + mark
assets/patterns/           ← batik motifs (kawung, parang, tumpal)
assets/icons/              ← koperasi-specific icons
ui_kits/member_mobile/     ← Sembada Anggota
ui_kits/admin_mobile/      ← Sembada Pengurus
ui_kits/admin_web/         ← Sembada Admin Web
ui_kits/marketing_web/     ← Sembada.id
ui_kits/teller_desktop/    ← Sembada Teller
print/                     ← Kartu anggota, formulir pinjaman
email/                     ← Transactional email templates
```

## Caveats
- This is a fictional brand built from scratch — no real customer-research validation.
- Logo and illustration are placeholder-quality; commission proper illustration before production.
- No photos are bundled. When you need photography, source warm-toned, unposed images of UMKM, pasar, keluarga, gotong royong contexts.
