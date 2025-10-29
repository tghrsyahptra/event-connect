# Xendit Payment Gateway Configuration

## 🔑 Environment Variables yang Perlu Ditambahkan ke .env

Tambahkan konfigurasi berikut ke file `.env` Anda:

```env
# Xendit Payment Gateway Configuration
XENDIT_SECRET_KEY=xnd_development_eiSE3kYRS5UnfDPn5BAx8pFQRKvkeFJDLRpWC6O3CL9aYj4dAAXmdt1VD7N1ih
XENDIT_PUBLIC_KEY=xnd_public_development_1234567890abcdef
XENDIT_WEBHOOK_TOKEN=your_webhook_token_here
XENDIT_CALLBACK_URL=http://localhost:8000/api/payments/webhook
XENDIT_REDIRECT_URL=http://localhost:8000/payments/success
```

## 📝 Langkah-langkah Konfigurasi

### 1. Buka file .env
```bash
nano .env
# atau
code .env
```

### 2. Tambahkan konfigurasi Xendit
Copy dan paste konfigurasi di atas ke file .env

### 3. Update Public Key (Opsional)
Jika Anda memiliki public key yang berbeda, ganti `XENDIT_PUBLIC_KEY` dengan key yang benar.

### 4. Set Webhook Token
Ganti `your_webhook_token_here` dengan token webhook yang sebenarnya dari dashboard Xendit.

### 5. Update URLs untuk Production
Saat deploy ke production, ganti URL dengan domain yang sebenarnya:
```env
XENDIT_CALLBACK_URL=https://yourdomain.com/api/payments/webhook
XENDIT_REDIRECT_URL=https://yourdomain.com/payments/success
```

## 🔧 Verifikasi Konfigurasi

### 1. Clear Config Cache
```bash
php artisan config:clear
php artisan config:cache
```

### 2. Test Payment Gateway
```bash
# Test dengan API endpoint
curl -X POST http://localhost:8000/api/payments/create \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "event_id": 1,
    "payment_method": "invoice"
  }'
```

## 🚨 Troubleshooting

### Error: "Xendit secret key not configured"
- Pastikan `XENDIT_SECRET_KEY` sudah ditambahkan ke .env
- Jalankan `php artisan config:clear`

### Error: "Invalid Xendit credentials"
- Periksa apakah secret key sudah benar
- Pastikan tidak ada spasi atau karakter tambahan

### Error: "Webhook verification failed"
- Periksa `XENDIT_WEBHOOK_TOKEN` di .env
- Pastikan webhook URL sudah dikonfigurasi di dashboard Xendit

## 📋 Checklist Konfigurasi

- [ ] XENDIT_SECRET_KEY ditambahkan ke .env
- [ ] XENDIT_PUBLIC_KEY ditambahkan ke .env
- [ ] XENDIT_WEBHOOK_TOKEN ditambahkan ke .env
- [ ] XENDIT_CALLBACK_URL dikonfigurasi
- [ ] XENDIT_REDIRECT_URL dikonfigurasi
- [ ] Config cache di-clear
- [ ] Payment gateway di-test

## 🔗 Dashboard Xendit

1. Login ke [Xendit Dashboard](https://dashboard.xendit.co/)
2. Navigate ke Settings > Webhooks
3. Tambahkan webhook URL: `http://localhost:8000/api/payments/webhook`
4. Copy webhook token dan masukkan ke .env

## 📞 Support

Jika masih ada masalah dengan konfigurasi payment gateway, periksa:
1. Log Laravel: `storage/logs/laravel.log`
2. Network tab di browser untuk error API
3. Xendit dashboard untuk status webhook
