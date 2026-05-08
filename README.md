# Sembada — Design System

> **Sembada Koperasi** — aplikasi koperasi simpan pinjam digital. *Dari anggota, oleh anggota, untuk anggota.*

`Sembada` (bahasa Jawa: tercukupi / makmur / sejahtera) adalah brand fiktif untuk sebuah ekosistem koperasi simpan pinjam modern. Tujuannya membawa nilai-nilai gotong royong tradisional ke dalam pengalaman digital yang ringan, hangat, dan dapat dipercaya — tanpa terlihat seperti bank korporat yang dingin.

---

## Konteks produk

Sembada terdiri dari **lima permukaan produk**:

| Produk | Pengguna | Inti |
|---|---|---|
| **Sembada Anggota** (mobile) | Anggota koperasi (petani, UMKM, pegawai, ibu rumah tangga) | Lihat saldo simpanan, ajukan pinjaman, bayar cicilan, transfer antar anggota, cek SHU |
| **Sembada Pengurus** (mobile) | Pengurus koperasi di lapangan | Approval pinjaman cepat, kunjungan anggota, input simpanan harian |
| **Sembada Admin Web** | Pengurus & pengawas di kantor | Dashboard statistik, approval pinjaman, manajemen anggota, laporan SHU |
| **Sembada.id** (marketing) | Calon anggota / koperasi yang mau adopsi | Landing page, edukasi, daftar koperasi mitra |
| **Sembada Teller** (desktop) | Teller / kasir kantor cabang | Setor/tarik tunai, pembayaran cicilan, cetak slip |

## Audiens

Sangat campur. Dari ibu pedagang pasar yang baru pertama pakai aplikasi, sampai pengurus muda yang biasa pakai dashboard. Implikasinya:

- **Mobile harus sangat ramah pemula**: hit-target besar, copy jelas, ikon + label, jangan menyembunyikan fungsi di balik gesture.
- **Admin web boleh padat** tapi tetap ramah — bukan SAP, bukan Bloomberg.
- **Bahasa Indonesia santai** — pakai "kamu", bukan "Anda". Sapaan boleh hangat ("Halo, Bu Sari").

## Sumber & status aset

- **Logo**: dibuat dari nol di proyek ini (SVG di `assets/`)
- **Tipografi**: Plus Jakarta Sans (sans, body & UI) + Fraunces (serif, display) — keduanya open-source dari Google Fonts. Plus Jakarta Sans dipilih karena dirancang oleh tim Indonesia (Tokotype/Bonjour Type) — terasa lokal tanpa harus eksotis. Fraunces memberi sentuhan hangat-klasik untuk hero & SHU display.
- **Ikon**: [Lucide](https://lucide.dev) via CDN untuk outline; **Phosphor Fill** untuk active states. Dimuat dari CDN; tidak ada icon font yang di-bundle.
- **Ilustrasi**: motif batik kawung dan parang disederhanakan jadi pattern SVG (`assets/patterns/`). Untuk ilustrasi karakter, saat ini placeholder — perlu di-commission ilustrator.
- **Foto**: tidak ada foto stok di-bundle. Kalau dibutuhkan, gunakan foto warm-toned: pasar tradisional, UMKM, keluarga, gotong royong.

> ⚠️ **Catatan substitusi**: brand ini dibuat dari nol tanpa codebase/Figma. Semua keputusan visual ada di file ini & dapat diubah. Tidak ada lisensi atau brand asli yang ditiru.

---

## Content Fundamentals

### Bahasa & sapaan

- **Bahasa Indonesia santai**, bukan formal. "Kamu", bukan "Anda". "Halo", bukan "Selamat datang, Tuan/Nyonya".
- Sapaan personal di mobile member app: **"Halo, {nama panggilan}"** atau **"Hai, Bu Sari"** / **"Hai, Pak Bambang"**. Pakai panggilan yang anggota tulis sendiri saat KYC.
- Hindari jargon perbankan: **"setor"** > "deposit", **"tarik"** > "withdrawal", **"pinjaman"** > "kredit"/"loan", **"cicilan"** > "installment", **"saldo simpanan"** > "balance simpanan".
- Istilah koperasi yang tetap dipakai apa adanya karena ini bagian dari identitas: *Simpanan Pokok, Simpanan Wajib, Simpanan Sukarela, SHU (Sisa Hasil Usaha), RAT (Rapat Anggota Tahunan), pengurus, pengawas, anggota*.

### Casing

- **Sentence case** untuk semua tombol, judul, label form. *"Ajukan pinjaman"*, bukan *"AJUKAN PINJAMAN"* atau *"Ajukan Pinjaman"*.
- **Title Case hanya untuk nama produk**: Sembada Anggota, Simpanan Sukarela, Sisa Hasil Usaha.
- **UPPERCASE** dipakai sangat hemat — hanya untuk eyebrow label kecil di marketing site (mis. `KOPERASI MITRA`) dan badge status singkat (`AKTIF`, `JATUH TEMPO`).

### Angka & uang

- Mata uang selalu **Rupiah** dengan format `Rp 1.250.000` (spasi setelah Rp, titik sebagai pemisah ribuan).
- Untuk display besar (saldo di hero), boleh disingkat: `Rp 1,25 jt`, `Rp 12,5 jt`, `Rp 1,2 M`. Singkatan: `rb`, `jt`, `M` (huruf kecil kecuali M untuk milyar agar jelas).
- Persen pakai koma desimal: `7,5% per tahun`, bukan `7.5%`.
- Tanggal: **`12 Mei 2026`** untuk display, `12/05/2026` hanya di tabel padat.

### Tone — contoh nyata

| Skenario | ❌ Hindari | ✅ Tulis seperti ini |
|---|---|---|
| Hero saldo | "Total Saldo Anda" | "Simpanan kamu" |
| Tombol utama | "AJUKAN PINJAMAN SEKARANG" | "Ajukan pinjaman" |
| Empty state riwayat | "No transactions found." | "Belum ada transaksi. Mulai dengan setor simpanan, yuk." |
| Error saldo kurang | "Insufficient balance." | "Saldo kamu belum cukup untuk transfer ini. Top up dulu, ya." |
| Sukses pengajuan | "Your loan application has been submitted." | "Pengajuan pinjaman kamu sudah masuk. Pengurus akan menghubungi dalam 1×24 jam." |
| Push notif cicilan | "Reminder: payment due." | "Cicilan bulan ini Rp 250.000 jatuh tempo 3 hari lagi. Bayar sekarang?" |
| Onboarding | "Welcome to our app!" | "Selamat datang di Sembada. Yuk, kenalan dulu." |
| Konfirmasi destruktif | "Are you sure?" | "Yakin mau batalkan pengajuan? Data nggak bisa dikembalikan." |

### Emoji & ikon dalam copy

- **Emoji TIDAK dipakai** dalam UI produk. Boleh dipakai di push notification (sangat hemat, max 1 per pesan, dan hanya yang netral: 🎉 untuk SHU cair, 📅 untuk pengingat). Tidak pernah dipakai di tombol, header, atau body utama.
- Karakter unicode khusus (✓, →, •) boleh dipakai sebagai bullet/decoration di marketing copy — tidak menggantikan ikon proper.

### Prinsip menulis

1. **Pendek di mobile, lebih panjang oke di onboarding & marketing.** Tombol mobile max 2 kata kalau bisa.
2. **Sebut "kamu" dan "kami"** — Sembada adalah "kami", anggota adalah "kamu". Ini koperasi: kepemilikan bersama, bukan vendor-klien.
3. **Jangan menakuti.** Bahasa pinjaman & cicilan sering terdengar mengintimidasi. Kita bilang "yuk bayar cicilan" bukan "tagihan jatuh tempo".
4. **Selalu kasih tahu langkah berikutnya.** Setiap empty state, error, success punya CTA atau ekspektasi.
5. **Hormati istilah koperasi.** Jangan ganti "Simpanan Wajib" jadi "Monthly Saving" — ini identitas hukum & budaya koperasi Indonesia.

---

## Visual Foundations

### Palet warna

Inspirasi dari **batik klasik Jawa**: warna tanah (sogan), merah maroon (kain pesisir), dan cream gading. Ini bukan palet techie biru-ungu; ini palet yang terasa hangat dan dewasa.

- **Primary — Sogan Merah** `#8B2E2E`: warna utama brand. Untuk header, tombol primer, link.
- **Cream Gading** `#F5F1E8`: background utama (bukan putih murni). Memberi kesan kertas/dokumen koperasi tradisional.
- **Sogan Coklat** `#5C3A1E`: warna teks utama (bukan hitam). Mengikat dengan palet hangat.
- **Hijau Padi** `#4A7C3A`: secondary, untuk indikator positif (saldo naik, pinjaman approved).
- **Kuning Tua / Emas Kunyit** `#D4A437`: aksen, untuk highlight, badge SHU, illustrative warmth.
- **Merah Bata** `#C44536`: warning ringan / status "perlu perhatian".
- **Hitam Wedel** `#1F1B16`: hanya untuk teks display besar atau kontras maksimum.

Detail token & semantik di `colors_and_type.css`.

### Tipografi

- **Plus Jakarta Sans** (400/500/600/700/800) — UI, body, label, angka tabel.
- **Fraunces** (400/500/600/700, opsz 9-144, soft variant) — display, hero, angka saldo besar, judul section di marketing.
- **JetBrains Mono** — angka tabular dalam tabel transaksi padat dan kode referensi.

Skala: 12 / 14 / 16 / 18 / 20 / 24 / 32 / 40 / 56 / 72 px. Body 16px. Mobile minimum 14px untuk label kecil. Lihat `colors_and_type.css`.

### Spacing

Skala 4px: `4 / 8 / 12 / 16 / 20 / 24 / 32 / 40 / 56 / 72 / 96`. Container mobile padding 20px, desktop 24-32px. Card padding 20-24px.

### Backgrounds

- **Default**: cream gading `#F5F1E8` di hampir semua surface. Memberi kesan kertas/koperasi yang ramah, bukan tech.
- **Hero & section starter**: full-bleed sogan merah `#8B2E2E` dengan motif **batik kawung** sangat tipis (opacity 4-6%) sebagai pattern overlay. Lihat `assets/patterns/kawung.svg`.
- **Marketing hero**: cream + foto warm-toned + pattern parang di sudut.
- **Tidak pakai gradient bluish-purple**, tidak pakai mesh gradient. Boleh pakai gradient **sogan→sogan-tua** sangat halus untuk depth di card hero.

### Animasi & easing

- Easing default: `cubic-bezier(0.32, 0.72, 0, 1)` (similar ke iOS spring). Halus, tidak mechanical.
- Durasi: `120ms` untuk hover/press feedback, `240ms` untuk panel/modal entry, `400ms` untuk transisi halaman.
- Gerakan utama: **fade + slight slide** (translate Y 8px). Tidak ada bounce berlebihan, tidak ada parallax.
- Loading: **skeleton shimmer** dengan tone cream lebih gelap, bukan spinner.

### Hover states

- **Tombol primary**: darken 8% (mis. `#8B2E2E` → `#7A2828`).
- **Tombol secondary / ghost**: background isi `rgba(139, 46, 46, 0.06)`.
- **Link**: underline appear (default no underline), warna tetap.
- **Card clickable**: shadow lift dari `sm` ke `md`, tidak scale.

### Press / active states

- **Tombol**: scale `0.98` + darken tambahan 4%, durasi 80ms.
- **List item mobile**: background flash `rgba(139, 46, 46, 0.08)` selama 120ms.

### Borders

- Default border: `1px solid #E5DDD0` (cream lebih gelap, bukan abu-abu netral).
- Focus ring: `2px solid #8B2E2E` dengan `outline-offset: 2px`.
- Divider tabel: `1px solid #ECE5D8`.

### Shadows / elevation

5 level, semuanya hangat (tinted dengan sogan), bukan abu-abu murni:

```
xs: 0 1px 2px rgba(92, 58, 30, 0.06)
sm: 0 2px 6px rgba(92, 58, 30, 0.08)
md: 0 6px 16px rgba(92, 58, 30, 0.10)
lg: 0 16px 32px rgba(92, 58, 30, 0.12)
xl: 0 32px 64px rgba(92, 58, 30, 0.16)
```

### Corner radii

- `xs: 4px` — badge, tag kecil
- `sm: 8px` — input, button, small card
- `md: 12px` — card default
- `lg: 16px` — card hero, modal
- `xl: 24px` — sheet bottom mobile, hero image
- `full: 9999px` — pill, avatar, FAB

### Cards

- Background: `#FFFFFF` (putih murni — kontras lembut dengan cream background).
- Border: `1px solid #ECE5D8` (subtle).
- Radius: `12px` default, `16px` untuk hero.
- Shadow: `sm` default, `md` saat hover.
- Padding: `20px` mobile, `24px` desktop.

### Transparansi & blur

- Transparansi dipakai untuk **press states** (`rgba(139, 46, 46, 0.08)`) dan **batik pattern overlay** (`opacity: 0.05`).
- Blur dipakai untuk **mobile bottom sheet backdrop**: `backdrop-filter: blur(8px)` + `rgba(31, 27, 22, 0.32)`.
- Tidak ada glassmorphism / heavy frosted card.

### Layout rules

- Mobile bottom nav: **fixed**, height 64px, safe-area aware.
- Mobile header: **sticky**, height 56px, blur backdrop saat scroll.
- Web admin: sidebar 240px fixed kiri, top bar 56px sticky, content max-width 1280px.
- Marketing: container max-width 1200px, gutter 80px desktop.

### Iconography (ringkasan — detail di section bawah)

- Default: **Lucide** outline 1.5px stroke.
- Active state: **Phosphor Fill**.
- Ukuran: 16 / 20 / 24 px. Inline dengan teks: 1em + vertical-align.

---

## Iconography

Lihat juga `colors_and_type.css` untuk ukuran ikon.

### Sistem yang dipakai

1. **Lucide** ([lucide.dev](https://lucide.dev)) — sistem default, outline, stroke 1.5px.
   - CDN: `<script src="https://unpkg.com/lucide@latest"></script>`
   - Dipakai untuk: navigasi, action button, list item.
2. **Phosphor Icons** Fill variant ([phosphoricons.com](https://phosphoricons.com)) — hanya untuk active state ikon yang sudah ada di Lucide.
   - CDN: `<script src="https://unpkg.com/@phosphor-icons/web"></script>`
   - Dipakai untuk: bottom nav active, tab active, tombol toggle aktif.
3. **Custom illustrative icons** — disimpan di `assets/icons/` untuk konsep koperasi yang tidak ada padanan di Lucide:
   - `simpanan.svg` (tabungan/celengan)
   - `shu.svg` (panen/sisa hasil usaha)
   - `gotong-royong.svg`
   - `kartu-anggota.svg`

### Aturan pakai

- **Selalu pasangkan ikon dengan label** di mobile member app, kecuali tombol yang sudah universal (back arrow, close X). Anggota koperasi awam belum tentu kenal ikon.
- **Ukuran ikon**: 16px (inline kecil), 20px (default UI), 24px (header, FAB), 32px (empty state hero).
- **Stroke**: konsisten 1.5px di Lucide. Jangan campur stroke 1px dan 2px.
- **Warna**: ikuti warna teks parent. Hanya gunakan warna khusus untuk status (hijau success, merah error).

### Emoji

Tidak dipakai di UI produk (lihat Content Fundamentals). Boleh hemat di push notification.

### Pattern & ornamen (bukan ikon)

- `assets/patterns/kawung.svg` — motif batik kawung untuk hero overlay
- `assets/patterns/parang.svg` — motif parang miring untuk section divider
- `assets/patterns/tumpal.svg` — segitiga tumpal untuk border print template

---

## Index — manifest folder

```
index.html                 ← landing/index page (start here visually)
README.md                  ← kamu di sini
SKILL.md                   ← skill manifest (Agent Skills compatible)
colors_and_type.css        ← CSS vars: warna, tipografi, spacing, shadow, radius
fonts/                     ← (kosong; pakai Google Fonts CDN)
assets/
  logo/                    ← logo Sembada (mark, lockup, monochrome)
  patterns/                ← motif batik (kawung, parang, tumpal)
  icons/                   ← custom illustrative icons koperasi
preview/                   ← preview cards untuk Design System tab
ui_kits/
  member_mobile/           ← Sembada Anggota (mobile member app)
  admin_mobile/            ← Sembada Pengurus (mobile admin app)
  admin_web/               ← Sembada Admin (web dashboard)
  marketing_web/           ← Sembada.id (landing page)
  teller_desktop/          ← Sembada Teller (desktop kasir)
print/                     ← Kartu anggota, formulir
email/                     ← Email & notifikasi templates
```

---

## Caveat

Brand & sistem ini dibuat **dari nol** dalam satu sesi. Semua hal di sini bersifat *proposal awal* — silakan iterasi:

- Logo Sembada saat ini adalah mark sederhana berbentuk *padi-melingkar*. Bisa diganti dengan logo asli koperasi target.
- Ilustrasi karakter (untuk onboarding, empty state) belum ada — perlu di-commission. Saat ini placeholder geometris.
- Foto stok tidak di-bundle.
- Tidak ada validasi pengguna nyata; copy & flow harus diuji ke anggota koperasi sungguhan.
