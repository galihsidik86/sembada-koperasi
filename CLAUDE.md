# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this repo is

**Sembada** — an Indonesian koperasi simpan pinjam (cooperative) admin web app, built on **Laravel 11 + Inertia + Vue 3 + Tailwind + MariaDB**, evolved from an earlier static design system.

The original static design system has been folded into this Laravel app:

- `colors_and_type.css` (the design token source-of-truth) lives at **`resources/css/colors_and_type.css`** — it's `@import`-ed by `resources/css/app.css` so Vite ships it to the SPA.
- The static reference exhibit (`index.html`, `preview/`, `ui_kits/`, `print/`, `email/`, `assets/`) is preserved under **`public/design-system/`** — browsable at `/design-system/` for anyone who needs the raw HTML kits as reference.
- `README.md` (brand bible) and `SKILL.md` (Agent Skills manifest) still apply: voice rules, palette rules, batik usage rules, and koperasi vocabulary all bind every UI string and component you write here.

## Local environment

The repo is developed on Windows with XAMPP. Two relevant constraints:

- **Bash shell does not see Windows PATH.** Use `PowerShell` for tooling, or absolute paths: `C:\xampp\php\php.exe`, `C:\xampp\mysql\bin\mysql.exe`. PHP is also on user PATH so PowerShell finds `php` and `composer`.
- **Composer is a wrapper.** `composer.phar` lives at the repo root; `C:\xampp\php\composer.bat` is a thin shim that invokes `php` against it. Both `php composer.phar …` and `composer …` work.
- MariaDB runs via XAMPP Control Panel; the app uses database `sembada`, user `root`, no password (XAMPP default).

## Commands

```powershell
# Dev server (Laravel + Inertia, hot-reloads PHP automatically)
php artisan serve --host=127.0.0.1 --port=8000

# Vite (dev mode with HMR — separate terminal)
npm run dev

# Production assets
npm run build

# Run / rollback / status migrations
php artisan migrate
php artisan migrate:rollback --step=1
php artisan migrate:status

# Re-seed JenisSimpanan (pokok/wajib/sukarela rows)
php artisan db:seed

# Tinker REPL (e.g. to spot-check models)
php artisan tinker

# Single test (none yet — only Breeze defaults if/when added)
php artisan test --filter=SomeTest
```

When you change a Vue file with the dev server alone, run `npm run build` once to refresh production assets — `php artisan serve` won't auto-rebuild Vite.

## Architecture

- **Inertia bridge.** Controllers `return Inertia::render('Page/Name', [...])`; `resources/js/Pages/Page/Name.vue` receives those props. There is no JSON API — Inertia ships the same controller-rendered props to the SPA over the same HTTP request.
- **Layouts.** Two layouts, both at `resources/js/Layouts/`:
  - `GuestLayout.vue` — used by Login/Register/ForgotPassword. Cream bg + sogan-tinted Sembada lockup + kawung overlay. Centered card.
  - `AuthenticatedLayout.vue` — the **Sembada admin web shell**. Fixed 240px sidebar + 64px sticky topbar, all token-based. Used by Dashboard, Anggota, Simpanan, Pinjaman, Laporan, and Profile pages.
- **Domain modules.** Each module = migration + model + controller + Inertia pages under `resources/js/Pages/<Modul>/`:
  - `anggota` — member CRUD. NIK uniqueness, status badge (aktif/non-aktif/keluar), auto-generated `nomor_anggota` (`AGT-0001`). `AnggotaController::store` auto-creates the initial Simpanan Pokok transaction inside a DB transaction.
  - `simpanan` — Pokok/Wajib/Sukarela. Two tables: `jenis_simpanan` (seeded once with three rows) and `simpanan_transaksi` (setor/tarik). Saldo is computed on-the-fly via `SUM(setor) - SUM(tarik)`. Withdraw is rejected if `dapat_ditarik=false` or saldo insufficient.
  - `pinjaman` + `cicilan` — pengajuan → approve → cair → cicilan dibayar → lunas. `Pinjaman::generateJadwal()` creates the cicilan rows on approval; supports `flat` and `menurun` interest methods. `PinjamanController::bayarCicilan` updates a single cicilan and calls `Pinjaman::checkLunas()` to auto-mark lunas when all cicilan are paid.
  - `laporan` — SHU calculation. Jasa simpanan + jasa pinjaman, allocated 25%/25% of bunga terbayar in the period. Configurable percentages live as constants in `LaporanController::index` for now (no settings model yet).
- **Auth & roles.** Laravel Breeze (Vue stack) provides login/register/password reset. Users have a `role` enum: `admin | pengurus | pengawas | teller`. The first registered user automatically becomes `admin` (`User::count() === 0` check in `RegisteredUserController::store`); subsequent registrations default to `pengurus`. The `EnsureRole` middleware (alias `role`) enables route-level role gating — registered in `bootstrap/app.php`, but not yet applied to any specific route. Use `->middleware('role:admin,pengurus')` when you want to gate something.
- **Inertia shared props.** `HandleInertiaRequests::share` exposes `auth.user` (with role) and `flash.success` / `flash.error` to every page. `FlashBanner.vue` renders these as a dismissible banner.
- **Tailwind theme is extended with Sembada tokens.** `tailwind.config.js` adds `sogan`, `cream`, `tanah`, `padi`, `emas`, `bata`, `wedel` color scales and `warm-{xs,sm,md,lg,xl}` shadows. Use these classes (`bg-sogan-500`, `text-tanah-700`, `shadow-warm-md`) in Vue templates — do **not** introduce hardcoded hex.

## Token discipline (still load-bearing)

- `resources/css/app.css` is the entry: it imports `colors_and_type.css` (which itself loads Plus Jakarta Sans + Fraunces + JetBrains Mono via Google Fonts) and then Tailwind's three layers.
- The static `public/design-system/colors_and_type.css` is a separate copy — keep them in sync if you change tokens. The static file uses relative `./assets/...` paths; the resources copy uses absolute `/design-system/assets/...` paths.
- Money formatting: PHP-side use `number_format($n, 0, ',', '.')`; JS-side use the `rupiah(n)` helper inlined in pages (`'Rp ' + Math.round(n).toLocaleString('id-ID')`). Both produce `Rp 1.250.000`.

## Voice rules (still apply to every UI string)

From `README.md` → Content Fundamentals:

- **Bahasa Indonesia santai.** "kamu", not "Anda". Sapaan: "Halo, Bu Sari".
- **Sentence case** for buttons, headings, labels. No SHOUTY uppercase except eyebrow labels and short status badges.
- **Koperasi terms stay Indonesian and capitalized:** Simpanan Pokok/Wajib/Sukarela, SHU, RAT, pengurus, pengawas, anggota.
- **No emoji in product UI.**
- **Cards on cream background**, not pure-white pages.

## Repository map

```
app/                      Laravel app code (Models, Http\Controllers, Http\Middleware)
bootstrap/                Laravel bootstrap; middleware aliases registered here
config/                   Laravel config files
database/migrations/      Schema migrations (anggota, simpanan_*, pinjaman, cicilan, role-on-users)
database/seeders/         JenisSimpananSeeder seeds pokok/wajib/sukarela
public/                   Web root; Laravel serves from here
public/design-system/     The original static design system, browsable at /design-system/
resources/css/            app.css + colors_and_type.css (token source for the SPA)
resources/js/Components/  Reusable Vue atoms (PrimaryButton, TextInput, FlashBanner, …)
resources/js/Layouts/     GuestLayout, AuthenticatedLayout
resources/js/Pages/       Inertia pages — one folder per module (Anggota, Simpanan, Pinjaman, Laporan, Auth, Profile)
routes/web.php            Web routes, all admin routes wrapped in auth middleware
routes/auth.php           Breeze auth routes
README.md                 Sembada brand bible (voice + visual rules)
SKILL.md                  Agent Skills manifest for the design system surface
composer.phar             Project-local Composer
```

## When editing UI

- Lift components from `public/design-system/ui_kits/<surface>/index.html` when you need a reference for how a pattern should look — they are read-only references now, not the source of truth.
- Auth pages and admin shell are already styled — match their structure when adding new pages (sidebar entry in `AuthenticatedLayout.vue` `nav` array → controller method → Inertia page in `resources/js/Pages/<Modul>/`).
- Validation messages should be in Bahasa Indonesia santai. Standard pattern: pass a custom messages array as the second argument to `$request->validate([...], [...])`.
