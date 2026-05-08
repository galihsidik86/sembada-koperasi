# Sembada Anggota — Member Mobile UI Kit

Aplikasi mobile untuk anggota koperasi. Audiens campur: dari ibu pedagang yang baru pakai aplikasi sampai karyawan urban — UI harus ramah pemula tapi tetap dewasa.

## Files
- `index.html` — interactive demo (3 phones side-by-side: Beranda+Pinjaman flow, Riwayat, Profil)
- `components.jsx` — atoms: `AppHeader`, `BottomNav`, `BalanceHero`, `QuickActions`, `LoanStatusCard`, `TxItem`, `SectionHeader`
- `screens.jsx` — `HomeScreen`, `ApplyLoanScreen`, `SuccessScreen`, `HistoryScreen`, `ProfileScreen`
- `ios-frame.jsx` — device frame (starter)

## Design notes
- **390×844** (iPhone 14 Pro). Test on smaller too.
- **Sticky 56px header** with cream-tinted blur. Title centered.
- **Fixed 64px bottom nav**, 4 tabs. Active = sogan-500 + bold + 2px stroke icon.
- **BalanceHero** is the signature element: sogan gradient + batik kawung overlay (8% opacity) + Fraunces money display. Reuse for SHU summary, savings detail.
- **Quick actions** 4×2 grid; ikon dalam square card (cream-on-white) untuk membedakan dari list item.
- Bottom-pinned primary CTA in flows, with safe-area-inset.

## Interactions in demo
- Klik **Pinjaman** dari Quick Actions → buka pengajuan flow → submit → success state → kembali ke beranda.
- Tab nav berfungsi (Home / Riwayat / Profil).
