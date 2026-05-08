# Sembada Admin — Web Dashboard UI Kit

Single-page demo for the pengurus (officer) dashboard. Pure HTML/CSS — components are inlined for simplicity (this kit is mostly cosmetic recreation, not interactive flow).

## Files
- `index.html` — full dashboard layout: sidebar + topbar + KPI row + chart panel + activity panel + approval queue table.

## Layout
- Sidebar 240px fixed, top bar 64px sticky, content max-width follows viewport.
- 4-column KPI grid, then 2:1 chart-to-side-panel split, then full-width approval table.

## Patterns to lift
- `.kpi`, `.panel`, `.table`, `.badge.b-warn|.b-succ|.b-info|.b-err`, `.btn-sm`, `.btn-sm.primary`.
- Sidebar item active state: `bg sogan-50 + color sogan-500 + bold + 2px stroke icon`.
- Search input, icon button (round), avatar-mini.
- Bar chart with sogan→tanah gradient + emas highlight on current month.
