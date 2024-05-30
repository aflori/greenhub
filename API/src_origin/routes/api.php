<?php

use App\Http\Controllers\Api\AdressController;
use App\Http\Controllers\Api\BlogArticleController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\OrderController;
// use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Route::apiResource('products', ProductController::class);
Route::get('/products', [ProductController::class, 'index']);
Route::apiResource('product', ProductController::class)->except('index');
Route::post('/product/{product}/comment', [ProductController::class, 'comment'])->middleware(['auth:sanctum', 'XSS']);

Route::apiResource('orders', OrderController::class)->only(['store'])->middleware(['auth:sanctum']);

Route::apiResource('users', UserController::class);
Route::apiResource('articles', BlogArticleController::class);
Route::apiResource('companies', CompanyController::class);

Route::middleware('isAdmin')->group(function () {
    Route::apiResource('adresses', AdressController::class);
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('comments', CommentController::class);
    Route::apiResource('discounts', DiscountController::class);
    Route::apiResource('labels', LabelController::class);
});
