<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\InteractionController;
use App\Http\Controllers\Api\V1\LeadController;
use App\Http\Controllers\Api\V1\OpportunityController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProductPriceController;
use App\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for version 1 of the API.
| All routes are prefixed with /api/v1 and use Sanctum authentication.
|
*/

// ==========================================
// Public Routes (No Authentication)
// ==========================================

Route::prefix('v1')->group(function () {
    // Authentication Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// ==========================================
// Protected Routes (Requires Sanctum Auth)
// ==========================================

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Accounts
    Route::apiResource('accounts', AccountController::class);

    // Contacts
    Route::apiResource('contacts', ContactController::class);

    // Leads
    Route::apiResource('leads', LeadController::class);

    // Opportunities
    Route::apiResource('opportunities', OpportunityController::class);

    // Interactions
    Route::apiResource('interactions', InteractionController::class);

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Products
    Route::apiResource('products', ProductController::class);

    // Product Prices
    Route::apiResource('product-prices', ProductPriceController::class);
});

