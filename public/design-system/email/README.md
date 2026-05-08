# Email & Notification Templates

Transactional emails untuk anggota koperasi. Gunakan inline styles saat kirim production — file ini pakai `<style>` block agar mudah di-preview.

## Templates
1. **Setoran berhasil** — konfirmasi saldo + nomor referensi.
2. **Pinjaman disetujui** — kabar baik dengan tanggal pencairan dan jadwal cicilan.
3. **Pengingat cicilan** — tone supportive, bukan ancaman ("kalau sudah dibayar, abaikan email ini ya").

## Email-safe rules
- Pakai `<table>` untuk layout — bukan flexbox/grid (Outlook/Gmail tidak support).
- Inline-kan styles sebelum kirim. Tools: Premailer, MJML, atau Mailchimp inliner.
- Font fallback: `-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif` untuk body. Display headlines pakai Georgia (mirip Fraunces) sebagai fallback.
- Tumpal accent strip pakai `repeating-linear-gradient` — bukan SVG, karena banyak email client tidak render SVG.

## Voice
- Sapa dengan nama depan ("Halo, Sari").
- Pengingat = lembut, dengan opt-out kalau sudah lunas.
- Closing: "Terima kasih sudah jadi bagian dari Sembada" untuk milestone, plain footer untuk transaksi rutin.
