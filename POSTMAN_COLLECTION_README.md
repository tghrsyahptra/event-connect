# Event Connect API - Postman Collection & Environment

## 📦 Files Included

1. **Event_Connect_API_Collection.postman_collection.json** - Complete Postman collection with all API endpoints
2. **Event_Connect_Environment.postman_environment.json** - Postman environment file with variables

## 🚀 Quick Start

### 1. Import Collection & Environment

1. Open Postman
2. Click **Import** button
3. Select both files:
   - `Event_Connect_API_Collection.postman_collection.json`
   - `Event_Connect_Environment.postman_environment.json`
4. Click **Import**

### 2. Set Up Environment

1. Click on **Environments** tab in Postman
2. Select **Event Connect Environment**
3. Set `base_url` to your API endpoint:
   - Local: `http://127.0.0.1:8000`
   - Staging: `https://your-staging-url.com`
   - Production: `https://your-production-url.com`

### 3. Authentication Flow

1. **Register** a new user:
   - Go to `Authentication > Register User`
   - Update the request body with your details
   - Click **Send**

2. **Login** to get access token:
   - Go to `Authentication > Login User`
   - Enter your email and password
   - Click **Send**
   - Copy the `access_token` from response

3. **Set Access Token**:
   - Go to **Environments** tab
   - Paste the `access_token` value
   - Save the environment

Now all authenticated requests will automatically use this token!

## 📋 Collection Structure

### 🔐 Authentication
- ✅ Register User
- ✅ Login User
- ✅ Logout User
- ✅ Forgot Password
- ✅ Reset Password
- ✅ Get Current User (Me)
- ✅ Get User Profile
- ✅ Update User Profile
- ✅ Change Password
- ✅ Update Organizer Status

### 📅 Events
- ✅ Advanced Event Search
- ✅ Get Filter Options
- ✅ Get Popular Searches
- ✅ Get All Events
- ✅ Get Event by ID
- ✅ Create Event (Admin/Organizer)
- ✅ Update Event (Admin/Organizer)
- ✅ Delete Event (Admin/Organizer)
- ✅ Get My Events (Organizer)
- ✅ Get Participating Events

### 👥 Event Participation
- ✅ Join Event
- ✅ Cancel Participation
- ✅ Mark Attendance (QR Code) ⭐ NEW
- ✅ Get My Participations
- ✅ Get Event Participants

### 🏷️ Categories
- ✅ Get All Categories
- ✅ Get Category by ID
- ✅ Create Category (Admin)
- ✅ Update Category (Admin)
- ✅ Delete Category (Admin)

### 💬 Feedback
- ✅ Submit Feedback
- ✅ Get Event Feedbacks
- ✅ Get My Feedbacks
- ✅ Download Certificate
- ✅ Get Certificate URL

### 🔔 Notifications
- ✅ Get User Notifications
- ✅ Mark Notification as Read
- ✅ Mark All Notifications as Read
- ✅ Get Unread Count
- ✅ Delete Notification

### 💳 Payments (Xendit)
- ✅ Get Payment Methods
- ✅ Create Payment (Invoice)
- ✅ Create Payment (Virtual Account)
- ✅ Create Payment (E-Wallet)
- ✅ Get Payment Status
- ✅ Cancel Payment
- ✅ Retry Payment
- ✅ Payment Webhook

### 👨‍💼 Admin Dashboard
- ✅ Get Dashboard Statistics
- ✅ Get Recent Activities
- ✅ Get Top Events
- ✅ Get Analytics Data

### 🔧 User Management (Admin)
- ✅ Get All Users
- ✅ Get User by ID
- ✅ Update User Status
- ✅ Delete User

## 🌐 Environment Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `base_url` | API base URL | `http://127.0.0.1:8000` |
| `access_token` | Bearer token for auth | Auto-filled after login |
| `event_id` | Default event ID | `1` |
| `participant_id` | Default participant ID | `1` |
| `category_id` | Default category ID | `1` |
| `user_id` | Default user ID | `1` |
| `xendit_secret_key` | Xendit secret key | Stored securely |

## 📝 Usage Examples

### Example 1: Search Events

1. Go to `Events > Advanced Event Search`
2. Update query parameters:
   - `q`: "tech"
   - `category_ids`: "1,2"
   - `price_min`: "0"
   - `price_max`: "500000"
   - `date_filter`: "this_month"
3. Click **Send**

### Example 2: Join Event & Pay

1. **Join Event**:
   - Go to `Event Participation > Join Event`
   - Update URL: Replace `1` with your `event_id`
   - Click **Send**

2. **Create Payment**:
   - Go to `💳 Payments > Create Payment (Invoice)`
   - Update `event_id` in body
   - Click **Send**
   - Copy `payment_url` from response

3. **Check Payment Status**:
   - Go to `💳 Payments > Get Payment Status`
   - Update URL: Replace `85` with `participant_id`
   - Click **Send**

### Example 3: Mark Attendance via QR Code

1. Scan QR code at event venue
2. Go to `Event Participation > Mark Attendance (QR Code)`
3. Update body with scanned QR code:
   ```json
   {
       "qr_code": "event_1234567890_abc123"
   }
   ```
4. Click **Send**

### Example 4: Submit Feedback & Get Certificate

1. **Submit Feedback**:
   - Go to `Feedback > Submit Feedback`
   - Update URL: Replace `1` with your `event_id`
   - Update body with rating and comment
   - Click **Send**

2. **Download Certificate**:
   - Go to `Feedback > Download Certificate`
   - Update URL: Replace `1` with your `event_id`
   - Click **Send**

## 🔧 Troubleshooting

### Issue: 401 Unauthorized
**Solution**: Make sure you've:
1. Logged in successfully
2. Copied the `access_token` from login response
3. Set `access_token` in environment variables
4. Selected the correct environment in Postman

### Issue: 404 Not Found
**Solution**: 
1. Check `base_url` is correct
2. Verify the endpoint path is correct
3. Make sure the server is running

### Issue: 422 Validation Error
**Solution**: 
1. Check request body format
2. Verify all required fields are included
3. Check data types match the API requirements

### Issue: 403 Forbidden
**Solution**: 
1. Verify your user role (Admin/Organizer required)
2. Make sure you have permission for the action
3. Check if token is still valid

## 🎫 QR Code Integration

### QR Code for Event Attendance

When users successfully register for an event (free or paid), the API response includes QR code information:

```json
{
  "success": true,
  "message": "Successfully joined the event",
  "data": {
    "participant": { ... },
    "event": {
      "id": 1,
      "title": "Sample Event",
      "start_date": "2024-01-15T10:00:00.000000Z",
      "location": "Jakarta Convention Center",
      "qr_code": "qr_codes/event_1234567890_abc123.png",
      "qr_code_url": "http://localhost:8000/storage/qr_codes/event_1234567890_abc123.png",
      "qr_code_string": "event_1234567890_abc123"
    }
  }
}
```

### QR Code Fields Explained

- **`qr_code`**: Path to QR code image file
- **`qr_code_url`**: Full URL to access QR code image
- **`qr_code_string`**: Unique QR code string for scanning

### Using QR Code for Attendance

1. **For Participants**: Use the QR code to scan and mark attendance
2. **For Organizers**: Display QR code at event venue for participants to scan
3. **API Endpoint**: `POST /api/participants/attendance` with `qr_code` parameter

## 📚 API Documentation

For complete API documentation, visit:
- Local: `http://127.0.0.1:8000/api-docs`
- Or check `API_DOCUMENTATION.md` file

## 🔄 Auto Token Management

To automatically save access token after login:

1. Go to `Authentication > Login User`
2. Click on **Tests** tab
3. Add this script:
```javascript
if (pm.response.code === 200) {
    var jsonData = pm.response.json();
    if (jsonData.data && jsonData.data.access_token) {
        pm.environment.set("access_token", jsonData.data.access_token);
    }
}
```

Now every time you login, the token will be automatically saved!

## 📞 Support

If you encounter any issues:
1. Check the API documentation
2. Verify your environment variables
3. Check server logs
4. Ensure all dependencies are installed

---

**Last Updated**: January 2025
**Version**: 2.0.0
**Total Endpoints**: 50+
