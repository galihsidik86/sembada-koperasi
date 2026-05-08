# Print Templates

Material cetak fisik untuk koperasi.

## Files
- `kartu-anggota.html` — Kartu anggota CR80 (front + back). Magstripe placeholder, QR placeholder, parang batik overlay, tumpal accent.
- `formulir-pinjaman.html` — Formulir A4 pengajuan pinjaman dengan section ber-nomor (gaya formulir Indonesia: "1. Data Anggota", "2. Detail Pinjaman", dll), 3 kolom tanda tangan.

## Notes
- Use `@page { size: A4; margin: 0 }` for browser print → PDF. The viewer just shows on screen with a tan backdrop.
- Tumpal triangular border at top of formulir & bottom of kartu front — ornamental nod to batik kain panjang.
- Kartu uses parang pattern overlay; formulir uses solid sogan-500 strip headers untuk efek formal.
