# Event Connect API Documentation

## Base URL
```
http://127.0.0.1:8003/api
```

## Authentication
This API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header for protected endpoints.

```
Authorization: Bearer {your-token-here}
```

---

## Authentication Endpoints

### Register User
**POST** `/auth/register`

Register a new user account.

**Request Body:**
```json
{
    "full_name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "full_name": "John Doe",
            "email": "john@example.com",
            "created_at": "2025-10-14T09:22:29.000000Z",
            "updated_at": "2025-10-14T09:22:29.000000Z"
        },
        "token": "1|hy0riaMP1xTEodrVb4a75xzutTKKMg2RitwI3eA97940a713",
        "token_type": "Bearer"
    }
}
```

### Login User
**POST** `/auth/login`

Login with email and password.

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "full_name": "John Doe",
            "email": "john@example.com",
            "phone": null,
            "bio": null,
            "avatar": null,
            "is_organizer": false,
            "email_verified_at": null,
            "created_at": "2025-10-14T09:22:29.000000Z",
            "updated_at": "2025-10-14T09:22:29.000000Z"
        },
        "token": "2|Yf53sZr53IWX4LL95axxguF8SuuhgnUNqaaYM8212f174190",
        "token_type": "Bearer"
    }
}
```

### Logout User
**POST** `/auth/logout` 🔒

Logout and invalidate current token.

### Get Current User
**GET** `/auth/me` 🔒

Get current authenticated user information.

---

## Profile Management

### Get Profile
**GET** `/profile` 🔒

Get user profile information.

### Update Profile
**PUT** `/profile` 🔒

Update user profile information.

**Request Body:**
```json
{
    "full_name": "John Updated",
    "phone": "+1234567890",
    "bio": "Event enthusiast and organizer",
    "avatar": "path/to/avatar.jpg"
}
```

### Change Password
**POST** `/profile/change-password` 🔒

Change user password.

**Request Body:**
```json
{
    "current_password": "oldpassword123",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

### Update Organizer Status
**POST** `/profile/update-organizer-status` 🔒

Update user's organizer status.

---

## Event Management

### Get Events (Homepage)
**GET** `/events`

Get all published events with optional filtering.

**Query Parameters:**
- `search` - Search by event title
- `category` - Filter by category ID
- `is_paid` - Filter by paid/free (true/false)
- `date` - Filter by date (YYYY-MM-DD)
- `page` - Page number for pagination

**Example:**
```
GET /events?search=tech&category=1&is_paid=false&date=2025-10-15&page=1
```

### Get Event Details
**GET** `/events/{id}`

Get specific event details.

### Create Event
**POST** `/events` 🔒 👤

Create a new event (organizer only).

**Request Body:**
```json
{
    "title": "Tech Conference 2025",
    "description": "Annual technology conference",
    "location": "Convention Center, Jakarta",
    "start_date": "2025-12-15 09:00:00",
    "end_date": "2025-12-15 17:00:00",
    "category_id": 1,
    "is_paid": true,
    "price": 150000,
    "quota": 100,
    "image": "path/to/image.jpg"
}
```

### Update Event
**PUT** `/events/{id}` 🔒 👤

Update event information (organizer only).

### Delete Event
**DELETE** `/events/{id}` 🔒 👤

Delete an event (organizer only).

### Get My Events
**GET** `/events/my-events` 🔒

Get events created by current user.

### Get Participating Events
**GET** `/events/participating` 🔒

Get events user is participating in.

---

## Event Participation

### Join Event
**POST** `/participants/join/{event_id}` 🔒

Join an event.

### Cancel Participation
**POST** `/participants/cancel/{event_id}` 🔒

Cancel event participation.

### Mark Attendance
**POST** `/participants/attendance` 🔒

Mark attendance via QR code scan.

**Request Body:**
```json
{
    "event_id": 1,
    "qr_data": "event_qr_code_data"
}
```

### Get My Participations
**GET** `/participants/my-participations` 🔒

Get user's event participations.

---

## Feedback & Certificates

### Submit Feedback
**POST** `/feedbacks/{event_id}` 🔒

Submit feedback for an event (required for certificate).

**Request Body:**
```json
{
    "rating": 5,
    "comment": "Great event! Very informative and well organized."
}
```

### Download Certificate
**GET** `/feedbacks/certificate/{event_id}/download` 🔒

Download event certificate (PDF).

### Get Certificate URL
**GET** `/feedbacks/certificate/{event_id}/url` 🔒

Get certificate download URL.

### Get My Feedbacks
**GET** `/feedbacks/my-feedbacks` 🔒

Get user's feedbacks.

---

## Notifications

### Get Notifications
**GET** `/notifications` 🔒

Get user notifications.

### Mark Notification as Read
**POST** `/notifications/{id}/read` 🔒

Mark specific notification as read.

### Mark All as Read
**POST** `/notifications/mark-all-read` 🔒

Mark all notifications as read.

### Get Unread Count
**GET** `/notifications/unread-count` 🔒

Get unread notifications count.

---

## Categories

### Get Categories
**GET** `/categories`

Get all active categories.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Technology",
            "description": "Tech conferences, workshops, and meetups",
            "color": "#3B82F6",
            "is_active": true,
            "created_at": "2025-10-14T09:13:18.000000Z",
            "updated_at": "2025-10-14T09:13:18.000000Z"
        }
    ]
}
```

### Create Category
**POST** `/categories` 🔒 👑

Create a new category (admin only).

### Update Category
**PUT** `/categories/{id}` 🔒 👑

Update category information (admin only).

### Delete Category
**DELETE** `/categories/{id}` 🔒 👑

Delete a category (admin only).

---

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password confirmation does not match."]
    }
}
```

### Unauthorized (401)
```json
{
    "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
    "message": "This action is unauthorized."
}
```

### Not Found (404)
```json
{
    "message": "The route api/events/999 could not be found."
}
```

---

## HTTP Status Codes

| Code | Description |
|------|-------------|
| 200  | Success |
| 401  | Unauthorized |
| 403  | Forbidden |
| 404  | Not Found |
| 422  | Validation Error |
| 500  | Internal Server Error |

---

## Legend

- 🔒 **Auth Required** - Requires valid authentication token
- 👤 **Organizer Only** - Requires organizer status
- 👑 **Admin Only** - Requires admin privileges

---

## Interactive Documentation

For a more interactive experience, visit the web-based API documentation at:
```
http://127.0.0.1:8003/api-docs
```

---

**Event Connect API** - Built with Laravel 12.33.0
