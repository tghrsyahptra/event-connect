# API Transaksi Event - Event Connect

## 📋 Overview
API untuk mendaftar dan mengelola transaksi event, termasuk pembayaran dan QR code attendance.

## 🔐 Authentication
Semua endpoint memerlukan Bearer Token:
```
Authorization: Bearer {token}
```

## 📚 Endpoints

### 1. **Join Event (Mendaftar Event)**

#### **POST** `/api/participants/join/{event_id}`

**Description**: Mendaftar ke event tertentu

**Parameters**:
- `event_id` (path): ID event yang akan diikuti

**Request Body**: Tidak ada

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Successfully joined the event",
  "data": {
    "participant": {
      "id": 93,
      "user_id": 24,
      "event_id": 9,
      "status": "registered",
      "is_paid": true,
      "amount_paid": 0,
      "payment_status": "paid",
      "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
      "attended_at": null,
      "created_at": "2025-10-29T17:05:28.000000Z",
      "updated_at": "2025-10-29T17:22:13.000000Z"
    },
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

**Response Error (400)**:
```json
{
  "success": false,
  "message": "Event is not available for registration"
}
```

**Possible Error Messages**:
- `"Event is not available for registration"` - Event tidak published atau tidak aktif
- `"Event is full"` - Event sudah penuh
- `"You are already registered for this event"` - User sudah terdaftar

---

### 2. **Create Payment (Membuat Pembayaran)**

#### **POST** `/api/payments/create`

**Description**: Membuat pembayaran untuk event berbayar

**Request Body**:
```json
{
  "event_id": 9,
  "payment_method": "invoice",
  "bank_code": "BCA",           // Optional: untuk virtual_account
  "ewallet_type": "OVO"         // Optional: untuk ewallet
}
```

**Payment Methods**:
- `invoice` - Credit Card (Snap Xendit)
- `virtual_account` - Virtual Account (BCA, BNI, BRI, MANDIRI)
- `ewallet` - E-Wallet (OVO, DANA, LINKAJA, SHOPEEPAY)

**Response Success (200)**:
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
      "payment_reference": "69024d448a9cf659daae6855",
      "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
      "payment_url": null,
      "payment_status": "pending",
      "payment_method": null,
      "attended_at": null,
      "created_at": "2025-10-29T17:05:28.000000Z",
      "updated_at": "2025-10-29T17:22:13.000000Z"
    },
    "payment_url": "https://checkout-staging.xendit.co/web/69024d448a9cf659daae6855",
    "payment_reference": "69024d448a9cf659daae6855",
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

**Response Error (422)**:
```json
{
  "success": false,
  "message": "Validation errors",
  "errors": {
    "event_id": ["The event id field is required."],
    "payment_method": ["The payment method field is required."]
  }
}
```

---

### 3. **Get Payment Status (Status Pembayaran)**

#### **GET** `/api/payments/status/{participant_id}`

**Description**: Mendapatkan status pembayaran

**Parameters**:
- `participant_id` (path): ID participant

**Response Success (200)**:
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
    "payment_url": "https://checkout-staging.xendit.co/web/69024d448a9cf659daae6855",
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

### 4. **Cancel Payment (Batalkan Pembayaran)**

#### **POST** `/api/payments/cancel/{participant_id}`

**Description**: Membatalkan pembayaran yang pending

**Parameters**:
- `participant_id` (path): ID participant

**Response Success (200)**:
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

### 5. **Retry Payment (Ulangi Pembayaran)**

#### **POST** `/api/payments/retry/{participant_id}`

**Description**: Mengulangi pembayaran yang gagal

**Request Body**:
```json
{
  "payment_method": "invoice",
  "bank_code": "BCA",           // Optional: untuk virtual_account
  "ewallet_type": "OVO"         // Optional: untuk ewallet
}
```

**Response Success (200)**:
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
    "payment_url": "https://checkout-staging.xendit.co/web/69024d448a9cf659daae6855",
    "payment_reference": "69024d448a9cf659daae6855",
    "payment_method": "invoice"
  }
}
```

---

### 6. **Get Payment Methods (Metode Pembayaran)**

#### **GET** `/api/payments/methods`

**Description**: Mendapatkan daftar metode pembayaran yang tersedia

**Response Success (200)**:
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

### 7. **Mark Attendance (Absensi)**

#### **POST** `/api/participants/attendance`

**Description**: Mark attendance menggunakan QR code

**Request Body**:
```json
{
  "qr_code": "user_24_event_9_1761757593_69024999af04a"
}
```

**Response Success (200)**:
```json
{
  "success": true,
  "message": "Attendance marked successfully",
  "data": {
    "participant": {
      "id": 93,
      "user_id": 24,
      "event_id": 9,
      "status": "attended",
      "is_paid": true,
      "amount_paid": 50000,
      "payment_status": "paid",
      "qr_code": "qr_codes/participants/user_24_event_9_1761757593_69024999af04a.svg",
      "qr_code_string": "user_24_event_9_1761757593_69024999af04a",
      "attended_at": "2025-10-29T17:30:00.000000Z",
      "created_at": "2025-10-29T17:05:28.000000Z",
      "updated_at": "2025-10-29T17:30:00.000000Z"
    },
    "event": {
      "id": 9,
      "title": "Digital Art Exhibition",
      "start_date": "2025-11-24T16:25:39.000000Z",
      "location": "Jakarta Art Gallery"
    }
  }
}
```

---

### 8. **Get My Participations (Event Saya)**

#### **GET** `/api/participants/my-participations`

**Description**: Mendapatkan daftar event yang diikuti user

**Query Parameters**:
- `status` (optional): `registered`, `attended`, `cancelled`
- `page` (optional): Halaman (default: 1)
- `per_page` (optional): Item per halaman (default: 10)

**Response Success (200)**:
```json
{
  "success": true,
  "data": {
    "current_page": 1,
    "data": [
      {
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
        "updated_at": "2025-10-29T17:22:13.000000Z",
        "event": {
          "id": 9,
          "title": "Digital Art Exhibition",
          "start_date": "2025-11-24T16:25:39.000000Z",
          "location": "Jakarta Art Gallery",
          "price": 50000,
          "organizer": {
            "id": 1,
            "name": "Sarah Johnson",
            "email": "sarah@workshop.com"
          }
        }
      }
    ],
    "first_page_url": "http://localhost:8000/api/participants/my-participations?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/participants/my-participations?page=1",
    "links": [...],
    "next_page_url": null,
    "path": "http://localhost:8000/api/participants/my-participations",
    "per_page": 10,
    "prev_page_url": null,
    "to": 1,
    "total": 1
  }
}
```

---

## 🔄 Complete Transaction Flow

### **Free Event Flow:**
1. **POST** `/api/participants/join/{event_id}` → User langsung terdaftar
2. Response includes QR code untuk attendance
3. User bisa langsung attend event

### **Paid Event Flow:**
1. **POST** `/api/participants/join/{event_id}` → User terdaftar dengan status pending
2. **POST** `/api/payments/create` → Create payment dengan Xendit
3. User redirect ke Xendit payment page
4. **GET** `/api/payments/status/{participant_id}` → Check payment status
5. Setelah payment success, user bisa attend event
6. **POST** `/api/participants/attendance` → Mark attendance dengan QR code

## 🧪 Testing Examples

### **Test Join Free Event:**
```bash
curl -X POST http://localhost:8000/api/participants/join/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

### **Test Join Paid Event:**
```bash
curl -X POST http://localhost:8000/api/participants/join/9 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json"
```

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

## 🔐 Security Features

- **Authentication Required**: Semua endpoint memerlukan Bearer Token
- **User Authorization**: User hanya bisa akses data miliknya
- **QR Code Security**: Setiap user memiliki QR code unik per event
- **Payment Security**: Xendit integration dengan webhook verification
- **Input Validation**: Semua input divalidasi dengan Laravel validation

## 📱 Frontend Integration

### **JavaScript Example:**
```javascript
// Join event
async function joinEvent(eventId) {
  const response = await fetch(`/api/participants/join/${eventId}`, {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + token,
      'Content-Type': 'application/json'
    }
  });
  
  const data = await response.json();
  
  if (data.success) {
    if (data.data.attendance_qr) {
      // Show QR code for attendance
      console.log('QR Code:', data.data.attendance_qr.qr_code_url);
    }
  }
}

// Create payment
async function createPayment(eventId, paymentMethod) {
  const response = await fetch('/api/payments/create', {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + token,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      event_id: eventId,
      payment_method: paymentMethod
    })
  });
  
  const data = await response.json();
  
  if (data.success && data.data.payment_url) {
    // Redirect to Xendit payment page
    window.location.href = data.data.payment_url;
  }
}
```

---

**Last Updated**: January 2025
**Version**: 1.0.0
**Status**: ✅ Complete and Tested
