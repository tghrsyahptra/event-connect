<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventParticipantController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

// Public event routes
Route::get('events', [EventController::class, 'index']);
Route::get('events/{event}', [EventController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::post('change-password', [ProfileController::class, 'changePassword']);
        Route::post('update-organizer-status', [ProfileController::class, 'updateOrganizerStatus']);
    });

    // Event routes
    Route::prefix('events')->group(function () {
        Route::post('/', [EventController::class, 'store']);
        Route::put('{event}', [EventController::class, 'update']);
        Route::delete('{event}', [EventController::class, 'destroy']);
        Route::get('my-events', [EventController::class, 'myEvents']);
        Route::get('participating', [EventController::class, 'participatingEvents']);
        Route::get('categories', [EventController::class, 'categories']);
    });

    // Event participation routes
    Route::prefix('participants')->group(function () {
        Route::post('join/{event}', [EventParticipantController::class, 'joinEvent']);
        Route::post('cancel/{event}', [EventParticipantController::class, 'cancelParticipation']);
        Route::post('attendance', [EventParticipantController::class, 'markAttendance']);
        Route::get('my-participations', [EventParticipantController::class, 'myParticipations']);
        Route::post('payment/{event}', [EventParticipantController::class, 'processPayment']);
        Route::get('event/{event}', [EventParticipantController::class, 'getEventParticipants']);
    });

    // Feedback routes
    Route::prefix('feedbacks')->group(function () {
        Route::post('{event}', [FeedbackController::class, 'store']);
        Route::get('my-feedbacks', [FeedbackController::class, 'myFeedbacks']);
        Route::get('event/{event}', [FeedbackController::class, 'getEventFeedbacks']);
        Route::get('certificate/{event}/download', [FeedbackController::class, 'downloadCertificate']);
        Route::get('certificate/{event}/url', [FeedbackController::class, 'getCertificateUrl']);
    });

    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::post('mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::get('unread-count', [NotificationController::class, 'unreadCount']);
        Route::delete('{notification}', [NotificationController::class, 'destroy']);
    });

    // Category management routes (Admin only)
    Route::prefix('categories')->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('{category}', [CategoryController::class, 'update']);
        Route::delete('{category}', [CategoryController::class, 'destroy']);
    });

    
});
