# Xendit Payment Gateway API Documentation

## 🔗 **API Endpoints**

### **1. Create Payment**
**POST** `/api/payments/create`

**Description**: Membuat pembayaran untuk event berbayar menggunakan Xendit

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "event_id": 9,
  "payment_method": "invoice",
  "bank_code": "BCA",           // Optional: untuk virtual_account
  "ewallet_type": "OVO"         // Optional: untuk ewallet
}
```

**Payment Methods:**
- `invoice` - Credit Card (Snap Xendit)
- `virtual_account` - Virtual Account (BCA, BNI, BRI, MANDIRI)
- `ewallet` - E-Wallet (OVO, DANA, LINKAJA, SHOPEEPAY)

**Response Success (200):**
```json
{
  "success": true,
  "message": "Payment created successfully",
  "data": {
    "participant": {
      "id": 93,
      "user_id": 24,
      "event_id": 9,
      "status": "registered",
      "is_paid": false,
      "amount_paid": null,
      "payment_reference": "690251be8a9cf659daae6bcb",
      "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
      "payment_url": null,
      "payment_status": "pending",
      "payment_method": null,
      "attended_at": null,
      "created_at": "2025-10-29T17:05:28.000000Z",
      "updated_at": "2025-10-29T17:22:13.000000Z"
    },
    "payment_url": "https://checkout-staging.xendit.co/web/690251be8a9cf659daae6bcb",
    "payment_reference": "690251be8a9cf659daae6bcb",
    "payment_method": "invoice",
    "event": {
      "id": 9,
      "title": "Digital Art Exhibition",
      "start_date": "2025-11-24T16:25:39.000000Z",
      "location": "Jakarta Art Gallery"
    },
    "attendance_qr": {
      "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_url": "http://localhost:8000/storage/qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
      "message": "Use this QR code for attendance check-in at the event"
    }
  }
}
```

---

### **2. Get Payment Status**
**GET** `/api/payments/status/{participant_id}`

**Description**: Mendapatkan status pembayaran dari Xendit

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "participant": {
      "id": 93,
      "user_id": 24,
      "event_id": 9,
      "status": "registered",
      "is_paid": true,
      "amount_paid": 50000,
      "payment_status": "paid",
      "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
      "attended_at": null,
      "created_at": "2025-10-29T17:05:28.000000Z",
      "updated_at": "2025-10-29T17:22:13.000000Z"
    },
    "payment_status": "paid",
    "is_paid": true,
    "amount_paid": 50000,
    "paid_at": "2025-10-29T17:25:00.000000Z",
    "payment_url": "https://checkout-staging.xendit.co/web/690251be8a9cf659daae6bcb",
    "event": {
      "id": 9,
      "title": "Digital Art Exhibition",
      "start_date": "2025-11-24T16:25:39.000000Z",
      "location": "Jakarta Art Gallery"
    },
    "attendance_qr": {
      "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_url": "http://localhost:8000/storage/qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
      "message": "Use this QR code for attendance check-in at the event"
    }
  }
}
```

---

### **3. Cancel Payment**
**POST** `/api/payments/cancel/{participant_id}`

**Description**: Membatalkan pembayaran yang pending

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Payment cancelled successfully",
  "data": {
    "participant": {
      "id": 93,
      "user_id": 24,
      "event_id": 9,
      "status": "registered",
      "is_paid": false,
      "amount_paid": null,
      "payment_status": "cancelled",
      "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
      "attended_at": null,
      "created_at": "2025-10-29T17:05:28.000000Z",
      "updated_at": "2025-10-29T17:22:13.000000Z"
    }
  }
}
```

---

### **4. Retry Payment**
**POST** `/api/payments/retry/{participant_id}`

**Description**: Mengulangi pembayaran yang gagal

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

**Request Body:**
```json
{
  "payment_method": "invoice",
  "bank_code": "BCA",           // Optional: untuk virtual_account
  "ewallet_type": "OVO"         // Optional: untuk ewallet
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Payment retry created successfully",
  "data": {
    "participant": {
      "id": 93,
      "user_id": 24,
      "event_id": 9,
      "status": "registered",
      "is_paid": false,
      "amount_paid": null,
      "payment_status": "pending",
      "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
      "attended_at": null,
      "created_at": "2025-10-29T17:05:28.000000Z",
      "updated_at": "2025-10-29T17:22:13.000000Z"
    },
    "payment_url": "https://checkout-staging.xendit.co/web/690251be8a9cf659daae6bcb",
    "payment_reference": "690251be8a9cf659daae6bcb",
    "payment_method": "invoice"
  }
}
```

---

### **5. Get Payment Methods**
**GET** `/api/payments/methods`

**Description**: Mendapatkan daftar metode pembayaran yang tersedia

**Headers:**
```
Authorization: Bearer {token}
Accept: application/json
```

**Response Success (200):**
```json
{
  "success": true,
  "data": {
    "invoice": {
      "name": "Credit Card",
      "description": "Pay with credit card via Xendit",
      "icon": "credit-card"
    },
    "virtual_account": {
      "name": "Virtual Account",
      "description": "Pay via bank transfer",
      "banks": ["BCA", "BNI", "BRI", "MANDIRI"]
    },
    "ewallet": {
      "name": "E-Wallet",
      "description": "Pay with digital wallet",
      "providers": ["OVO", "DANA", "LINKAJA", "SHOPEEPAY"]
    }
  }
}
```

---

### **6. Xendit Webhook**
**POST** `/api/payments/webhook`

**Description**: Webhook endpoint untuk menerima notifikasi dari Xendit

**Headers:**
```
X-Xendit-Signature: {webhook_signature}
Content-Type: application/json
```

**Webhook Payload (from Xendit):**
```json
{
  "id": "690251be8a9cf659daae6bcb",
  "external_id": "event_9_participant_93_1761757593_69024999af04a",
  "user_id": "5e8b4c4b4b4b4b4b4b4b4b4b",
  "status": "PAID",
  "merchant_name": "Event Connect",
  "merchant_profile_picture_url": "https://example.com/logo.png",
  "amount": 50000,
  "description": "Payment for event: Digital Art Exhibition",
  "invoice_url": "https://checkout-staging.xendit.co/web/690251be8a9cf659daae6bcb",
  "expiry_date": "2025-10-30T17:22:13.000Z",
  "created": "2025-10-29T17:22:13.000Z",
  "updated": "2025-10-29T17:25:00.000Z",
  "currency": "IDR",
  "paid_at": "2025-10-29T17:25:00.000Z",
  "payment_method": "CREDIT_CARD",
  "payment_channel": "CREDIT_CARD",
  "payment_destination": "CREDIT_CARD"
}
```

**Response Success (200):**
```json
{
  "success": true,
  "message": "Webhook processed successfully"
}
```

---

## 🔧 **Xendit Service Implementation**

### **PaymentService.php**
```php
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class PaymentService
{
    public function createInvoice(EventParticipant $participant, array $options = [])
    {
        // Configure Xendit SDK
        Configuration::setXenditKey($this->secretKey);
        
        $invoiceApi = new InvoiceApi();
        
        $createInvoiceRequest = new CreateInvoiceRequest([
            'external_id' => 'event_' . $event->id . '_participant_' . $participant->id . '_' . time(),
            'amount' => (float) $event->price,
            'description' => 'Payment for event: ' . $event->title,
            'invoice_duration' => 86400, // 24 hours
            'customer' => [
                'given_names' => $user->full_name ?? $user->name,
                'email' => $user->email,
            ],
            'success_redirect_url' => $this->redirectUrl . '?participant_id=' . $participant->id . '&status=success',
            'failure_redirect_url' => $this->redirectUrl . '?participant_id=' . $participant->id . '&status=failed',
            'currency' => 'IDR',
            'items' => [
                [
                    'name' => $event->title,
                    'quantity' => 1,
                    'price' => (float) $event->price,
                    'category' => 'Event Registration',
                ]
            ],
        ]);

        $invoice = $invoiceApi->createInvoice($createInvoiceRequest);
        
        return [
            'success' => true,
            'payment_url' => $invoice['invoice_url'],
            'invoice_id' => $invoice['id'],
        ];
    }
}
```

---

## ⚙️ **Configuration**

### **config/services.php**
```php
'xendit' => [
    'secret_key' => env('XENDIT_SECRET_KEY'),
    'public_key' => env('XENDIT_PUBLIC_KEY'),
    'webhook_token' => env('XENDIT_WEBHOOK_TOKEN'),
    'callback_url' => env('XENDIT_CALLBACK_URL'),
    'redirect_url' => env('XENDIT_REDIRECT_URL'),
],
```

### **.env**
```env
XENDIT_SECRET_KEY=xnd_development_eiSE3kYRS5UnfDPn5BAx8pFQRKvkeFJDLRpWC6O3CL9aYj4dAAXmdt1VD7N1ih
XENDIT_PUBLIC_KEY=xnd_public_development_1234567890abcdef
XENDIT_WEBHOOK_TOKEN=your_webhook_token_here
XENDIT_CALLBACK_URL=http://localhost:8000/api/payments/webhook
XENDIT_REDIRECT_URL=http://localhost:8000/payments/success
```

---

## 🧪 **Testing Examples**

### **Test Create Payment:**
```bash
curl -X POST http://localhost:8000/api/payments/create \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "event_id": 9,
    "payment_method": "invoice"
  }'
```

### **Test Payment Status:**
```bash
curl -X GET http://localhost:8000/api/payments/status/93 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### **Test Payment Methods:**
```bash
curl -X GET http://localhost:8000/api/payments/methods \
  -H "Authorization: Bearer YOUR_TOKEN"
```

---

## 🔄 **Payment Flow**

1. **User clicks "Join Event (Paid)"**
2. **Frontend calls** `POST /api/payments/create`
3. **Backend creates Xendit invoice** via PaymentService
4. **Response includes** `payment_url` (Xendit checkout page)
5. **Frontend redirects** to `payment_url`
6. **User completes payment** on Xendit page
7. **Xendit sends webhook** to `POST /api/payments/webhook`
8. **Backend updates** payment status to "paid"
9. **User can check status** via `GET /api/payments/status/{participant_id}`

---

**Last Updated**: January 2025
**Version**: 1.0.0
**Status**: ✅ Complete and Tested
