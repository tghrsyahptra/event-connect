<?php

namespace App\Http\Controllers\DocumentationApi;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Event Management API Documentation",
 *     description="Dokumentasi lengkap API untuk sistem manajemen event. Mencakup fitur feedback, notifikasi, dan sertifikat.",
 *     @OA\Contact(
 *         email="support@eventmanagement.com",
 *         name="API Support Team"
 *     ),
 *     @OA\License(
 *         name="MIT License",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local Development Server"
 * )
 * 
 * @OA\Server(
 *     url="https://staging-api.eventmanagement.com",
 *     description="Staging Server"
 * )
 * 
 * @OA\Server(
 *     url="https://api.eventmanagement.com",
 *     description="Production Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Masukkan JWT token yang didapat dari login. Format: Bearer {your-token}"
 * )
 * 
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Operation successful"),
 *     @OA\Property(property="data", type="object")
 * )
 * 
 * @OA\Schema(
 *     schema="ValidationError",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Validation errors"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={
 *             "rating": {"The rating field is required."},
 *             "comment": {"The comment must not exceed 1000 characters."}
 *         }
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="UnauthorizedError",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Unauthenticated")
 * )
 * 
 * @OA\Schema(
 *     schema="ForbiddenError",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Unauthorized to access this resource")
 * )
 * 
 * @OA\Schema(
 *     schema="NotFoundError",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Resource not found")
 * )
 * 
 * @OA\Schema(
 *     schema="BadRequestError",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Bad request")
 * )
 * 
 * @OA\Schema(
 *     schema="ServerError",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Internal server error")
 * )
 * 
 * @OA\Schema(
 *     schema="PaginationMeta",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="per_page", type="integer", example=20),
 *     @OA\Property(property="total", type="integer", example=100),
 *     @OA\Property(property="last_page", type="integer", example=5),
 *     @OA\Property(property="from", type="integer", example=1),
 *     @OA\Property(property="to", type="integer", example=20)
 * )
 * 
 * @OA\Schema(
 *     schema="Event",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Tech Conference 2024"),
 *     @OA\Property(property="description", type="string", example="Annual technology conference"),
 *     @OA\Property(property="start_date", type="string", format="date-time", example="2024-12-15 09:00:00"),
 *     @OA\Property(property="end_date", type="string", format="date-time", example="2024-12-15 17:00:00"),
 *     @OA\Property(property="location", type="string", example="Jakarta Convention Center"),
 *     @OA\Property(property="event_type", type="string", enum={"online", "offline", "hybrid"}, example="offline"),
 *     @OA\Property(property="status", type="string", enum={"draft", "published", "cancelled"}, example="published"),
 *     @OA\Property(property="is_active", type="boolean", example=true)
 * )
 * 
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com")
 * )
 * 
 * @OA\Schema(
 *     schema="Feedback",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="event_id", type="integer", example=1),
 *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *     @OA\Property(property="comment", type="string", example="Event sangat bermanfaat dan terorganisir dengan baik"),
 *     @OA\Property(property="certificate_generated", type="boolean", example=true),
 *     @OA\Property(property="certificate_path", type="string", example="certificates/certificate_1_1_1234567890.pdf"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="Notification",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="event_id", type="integer", example=1),
 *     @OA\Property(property="type", type="string", example="event_reminder"),
 *     @OA\Property(property="title", type="string", example="Event Reminder"),
 *     @OA\Property(property="message", type="string", example="Reminder: Tech Conference 2024 on 15 Dec 2024"),
 *     @OA\Property(property="is_read", type="boolean", example=false),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         @OA\Property(property="event_title", type="string", example="Tech Conference 2024"),
 *         @OA\Property(property="start_date", type="string", format="date-time"),
 *         @OA\Property(property="location", type="string", example="Jakarta Convention Center")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class SwaggerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/",
     *     tags={"Health Check"},
     *     summary="API Health Check",
     *     description="Endpoint untuk mengecek status API",
     *     @OA\Response(
     *         response=200,
     *         description="API is running",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="API is running"),
     *             @OA\Property(property="version", type="string", example="1.0.0"),
     *             @OA\Property(property="timestamp", type="string", format="date-time")
     *         )
     *     )
     * )
     */
    public function healthCheck()
    {
        return response()->json([
            'success' => true,
            'message' => 'API is running',
            'version' => '1.0.0',
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}