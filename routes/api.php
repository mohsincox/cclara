<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Post CRUD routes
// Route::get('/posts', [PostController::class, 'index']);
// Route::get('/posts/{id}', [PostController::class, 'show']);
// Route::post('/posts', [PostController::class, 'store']);
// Route::put('/posts/{id}', [PostController::class, 'update']);
// Route::delete('/posts/{id}', [PostController::class, 'destroy']);


// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
// Protected routes
// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
// });
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', fn (Request $request) => $request->user());
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);
    Route::get('/posts/{post}', [PostController::class, 'show']);

    Route::get('/contents', [ContentController::class, 'index']);
    Route::post('/contents', [ContentController::class, 'store']);
    Route::put('/contents/{content}', [ContentController::class, 'update']);
    Route::delete('/contents/{content}', [ContentController::class, 'destroy']);
    Route::get('/contents/{content}', [ContentController::class, 'show']);

    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{book}', [BookController::class, 'update']);
    Route::delete('/books/{book}', [BookController::class, 'destroy']);
    Route::get('/books/{book}', [BookController::class, 'show']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    // Product CRUD routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});

Route::get('/todos',           [TodoController::class, 'index']);
Route::post('/todos',          [TodoController::class, 'store']);
Route::put('/todos/reorder',   [TodoController::class, 'reorder']);
Route::put('/todos/{id}',      [TodoController::class, 'update']);
Route::delete('/todos/{id}',   [TodoController::class, 'destroy']);

Route::get('/token-info', function () {
    // Extract the secret part if your token includes the ID prefix (e.g., "3|secret...")
    $plainTextToken = '39|bYHeJ8QbmpM8ExhtwdfUJnKycKsnyakr1qoyJKzy078bdbd0';

    if (str_contains($plainTextToken, '|')) {
        [$id, $plainTextToken] = explode('|', $plainTextToken, 2);
    }

    // Compute the SHA-256 hash just like Sanctum does
    $hashedToken = hash('sha256', $plainTextToken);

    // Find the token row and its assigned user
    $tokenModel = Laravel\Sanctum\PersonalAccessToken::findToken($plainTextToken);

    if ($tokenModel) {
        echo "User ID: " . $tokenModel->tokenable_id . "\n";
        echo "<pre>";
        print_r($tokenModel->tokenable->toArray());
        echo "</pre>";
    } else {
        echo "Invalid or expired token.";
    }
});

Route::get('/personal-access-tokens-table', function () {
    $accessToken = Laravel\Sanctum\PersonalAccessToken::findToken("bYHeJ8QbmpM8ExhtwdfUJnKycKsnyakr1qoyJKzy078bdbd0");
    echo "<pre>";
    print_r($accessToken->tokenable); 
    echo "<pre>";
});